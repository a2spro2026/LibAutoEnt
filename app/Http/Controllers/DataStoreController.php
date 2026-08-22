<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataStoreController extends Controller
{
    private const PREFIX = 'libautoent_';

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

        return response()->json($data);
    }

    public function update(Request $request, string $key)
    {
        $safe = $this->safeKey($key);
        if (! $safe) {
            return response()->json(['message' => 'Clé invalide'], Response::HTTP_NOT_FOUND);
        }

        $dir = storage_path('app/libautoent');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $payload = $request->json()->all();
        file_put_contents(
            $this->filePath($safe),
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        return response()->json(['ok' => true]);
    }
}
