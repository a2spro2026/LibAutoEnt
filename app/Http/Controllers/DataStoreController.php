<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        return response()->json($this->normalizePayload($safe, $data));
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

        // Ne jamais écraser le catalogue par une liste vide accidentelle
        // (les bons / règlements peuvent être volontairement vidés après suppression)
        $force = $request->headers->get('X-Libautoent-Force') === '1';
        $path = $this->filePath($safe);
        $protected = [
            'libautoent_catalogue_produits',
            'libautoent_utilisateurs',
            'libautoent_bons_vente',
            'libautoent_bons_achat',
        ];
        if (! $force && in_array($safe, $protected, true) && is_file($path)) {
            $existing = json_decode((string) file_get_contents($path), true);
            if (is_array($existing) && count($existing) > 0) {
                if ($payload === []) {
                    return response()->json(['message' => 'Refus d’écraser les données par une liste vide'], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
                // Toujours fusionner par id (évite de perdre des ventes saisies sur un autre appareil)
                if (is_array($payload)) {
                    $byId = [];
                    foreach ($existing as $row) {
                        if (is_array($row) && ! empty($row['id'])) {
                            $byId[(string) $row['id']] = $row;
                        }
                    }
                    foreach ($payload as $row) {
                        if (is_array($row) && ! empty($row['id'])) {
                            $byId[(string) $row['id']] = $row;
                        } elseif (is_array($row)) {
                            $byId['anon_'.count($byId)] = $row;
                        }
                    }
                    $payload = array_values($byId);
                }
            }
        }

        $dir = storage_path('app/libautoent');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Snapshot avant chaque écriture des données critiques
        if (in_array($safe, $protected, true) && is_file($path)) {
            $this->snapshotKey($safe, $path);
        }

        file_put_contents(
            $path,
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return response()->json(['ok' => true, 'count' => is_array($payload) ? count($payload) : null]);
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
        foreach (array_slice($files, 40) as $old) {
            @unlink($old);
        }
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
