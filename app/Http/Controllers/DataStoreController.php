<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DataStoreController extends Controller
{
    private const PREFIX = 'libautoent_';

    private const ARRAY_KEYS = [
        'libautoent_catalogue_produits',
        'libautoent_utilisateurs',
        'libautoent_bons_achat',
        'libautoent_bons_vente',
        'libautoent_reglements_achat',
        'libautoent_reglements_vente',
    ];

    /** Clés où on fusionne toujours par id (jamais d'écrasement destructif). */
    private const UNION_KEYS = [
        'libautoent_catalogue_produits',
        'libautoent_utilisateurs',
        'libautoent_bons_achat',
        'libautoent_bons_vente',
        'libautoent_reglements_achat',
        'libautoent_reglements_vente',
    ];

    private function safeKey(string $key): ?string
    {
        if (! str_starts_with($key, self::PREFIX)) {
            return null;
        }
        if (! preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
            return null;
        }

        return $key;
    }

    private function filePath(string $key): string
    {
        return storage_path('app/libautoent/'.$key.'.json');
    }

    private function normalizePayload(string $key, mixed $data): mixed
    {
        if (in_array($key, self::ARRAY_KEYS, true)) {
            return is_array($data) && array_is_list($data) ? $data : [];
        }

        return is_array($data) && ! array_is_list($data) ? $data : (object) [];
    }

    /**
     * Fusion par id : le serveur ne perd jamais une ligne existante.
     * Les lignes entrantes mettent à jour les ids connus ; le reste est ajouté.
     */
    private function mergeById(array $existing, array $incoming): array
    {
        $byId = [];
        foreach ($existing as $row) {
            if (! is_array($row)) {
                continue;
            }
            if (! empty($row['id'])) {
                $byId[(string) $row['id']] = $row;
            } else {
                $byId['anon_e_'.count($byId)] = $row;
            }
        }
        foreach ($incoming as $row) {
            if (! is_array($row)) {
                continue;
            }
            if (! empty($row['id'])) {
                $byId[(string) $row['id']] = $row;
            } else {
                $byId['anon_i_'.count($byId)] = $row;
            }
        }

        return array_values($byId);
    }

    private function sortBonsNewestFirst(array $rows): array
    {
        usort($rows, function ($a, $b) {
            $da = $this->bonDateTs(is_array($a) ? ($a['date'] ?? '') : '');
            $db = $this->bonDateTs(is_array($b) ? ($b['date'] ?? '') : '');
            if ($db !== $da) {
                return $db <=> $da;
            }
            $na = $this->bonNumeroRank(is_array($a) ? ($a['numero'] ?? '') : '');
            $nb = $this->bonNumeroRank(is_array($b) ? ($b['numero'] ?? '') : '');

            return $nb <=> $na;
        });

        return array_values($rows);
    }

    private function bonDateTs(string $s): int
    {
        $s = trim($s);
        if ($s === '') {
            return 0;
        }
        if (str_contains($s, '-')) {
            $p = explode('-', $s);

            return count($p) >= 3 ? (int) strtotime(sprintf('%04d-%02d-%02d', (int) $p[0], (int) $p[1], (int) $p[2])) : 0;
        }
        $p = explode('/', $s);
        if (count($p) < 3) {
            return 0;
        }

        return (int) strtotime(sprintf('%04d-%02d-%02d', (int) $p[2], (int) $p[1], (int) $p[0]));
    }

    private function bonNumeroRank(string $numero): int
    {
        return preg_match('/^BL0*(\d+)$/i', trim($numero), $m) ? (int) $m[1] : 0;
    }

    public function show(string $key)
    {
        $safe = $this->safeKey($key);
        if (! $safe) {
            return response()->json(['message' => 'Clé invalide'], Response::HTTP_NOT_FOUND);
        }

        $path = $this->filePath($safe);
        if (! is_file($path)) {
            return response()->json(null);
        }

        $raw = file_get_contents($path);
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(null);
        }

        $payload = $this->normalizePayload($safe, $data);
        if ($safe === 'libautoent_bons_vente' && is_array($payload)) {
            $payload = $this->sortBonsNewestFirst($payload);
        }

        return response()->json($payload, 200, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function update(Request $request, string $key)
    {
        $safe = $this->safeKey($key);
        if (! $safe) {
            return response()->json(['message' => 'Clé invalide'], Response::HTTP_NOT_FOUND);
        }

        $raw = $request->getContent();
        if ($raw === '' || $raw === false) {
            return response()->json(['message' => 'Corps vide'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'JSON invalide'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $payload = $this->normalizePayload($safe, $decoded);
        $force = $request->headers->get('X-Libautoent-Force') === '1';
        $path = $this->filePath($safe);
        $existingCount = 0;
        $existing = [];

        if (is_file($path)) {
            $existing = json_decode((string) file_get_contents($path), true);
            if (! is_array($existing)) {
                $existing = [];
            }
            $existingCount = count($existing);
        }

        if (! $force && in_array($safe, self::UNION_KEYS, true) && $existingCount > 0) {
            if ($payload === []) {
                return response()->json([
                    'message' => 'Refus d’écraser les données par une liste vide',
                    'kept' => $existingCount,
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if (is_array($payload)) {
                $incomingCount = count($payload);
                $payload = $this->mergeById($existing, $payload);

                // Garde-fou : après fusion, on ne doit jamais descendre sous l’existant
                if (count($payload) < $existingCount) {
                    Log::warning('libautoent sync: refus baisse de volume', [
                        'key' => $safe,
                        'existing' => $existingCount,
                        'incoming' => $incomingCount,
                        'merged' => count($payload),
                    ]);
                    $payload = $existing;
                }
            }
        }

        if ($safe === 'libautoent_bons_vente' && is_array($payload)) {
            $payload = $this->sortBonsNewestFirst($payload);
        }

        $dir = storage_path('app/libautoent');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Snapshot avant écriture + copie journalière durable
        if (in_array($safe, self::UNION_KEYS, true) && is_file($path)) {
            $this->snapshotKey($safe, $path);
            $this->dailyBackup($safe, $path);
        }

        file_put_contents(
            $path,
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return response()->json([
            'ok' => true,
            'count' => is_array($payload) ? count($payload) : null,
            'previous' => $existingCount,
        ]);
    }

    private function snapshotKey(string $key, string $path): void
    {
        $snapDir = storage_path('app/backups/libautoent-snapshots/'.$key);
        if (! is_dir($snapDir)) {
            mkdir($snapDir, 0755, true);
        }
        $dest = $snapDir.'/'.$key.'-'.date('YmdHis').'.json';
        @copy($path, $dest);

        $files = glob($snapDir.'/'.$key.'-*.json') ?: [];
        rsort($files);
        // Garder plus d’historique pour pouvoir restaurer une journée
        foreach (array_slice($files, 120) as $old) {
            @unlink($old);
        }
    }

    /** Une copie par jour, non écrasée dans la journée (premier + dernier). */
    private function dailyBackup(string $key, string $path): void
    {
        $dayDir = storage_path('app/backups/libautoent-daily/'.date('Y-m-d'));
        if (! is_dir($dayDir)) {
            mkdir($dayDir, 0755, true);
        }
        $first = $dayDir.'/'.$key.'-first.json';
        $last = $dayDir.'/'.$key.'-last.json';
        if (! is_file($first)) {
            @copy($path, $first);
        }
        @copy($path, $last);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|file|image|max:5120',
        ]);

        $dir = 'libautoent/photos';
        Storage::disk('public')->makeDirectory($dir);

        $file = $request->file('photo');
        $ext = strtolower((string) $file->getClientOriginalExtension());
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            $ext = 'jpg';
        }
        $name = 'p_'.date('YmdHis').'_'.bin2hex(random_bytes(4)).'.'.$ext;
        $path = $file->storeAs($dir, $name, 'public');

        return response()->json([
            'ok' => true,
            'url' => '/storage/'.$path,
        ]);
    }
}
