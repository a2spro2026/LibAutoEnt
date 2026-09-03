<?php

if (! function_exists('libautoent_default_permissions')) {
    function libautoent_default_permissions(string $statue): array
    {
        $all = [
            'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.print', 'dashboard.delete',
            'stock.view', 'stock.create', 'stock.edit', 'stock.delete',
            'ventes.view', 'ventes.print',
            'config.view', 'config.manage',
        ];
        $presets = [
            'gerant' => $all,
            'assis' => [
                'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.print',
                'stock.view', 'stock.create', 'stock.edit',
                'ventes.view', 'ventes.print',
                'config.view',
            ],
            'vendeur' => [
                'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.print',
                'stock.view',
            ],
        ];
        $keys = $presets[$statue] ?? $presets['vendeur'];
        $out = [];
        foreach ($all as $key) {
            $out[$key] = in_array($key, $keys, true);
        }

        return $out;
    }
}

if (! function_exists('libautoent_find_user_permissions')) {
    function libautoent_find_user_permissions(string $login, string $statue): array
    {
        $path = storage_path('app/libautoent/libautoent_utilisateurs.json');
        if (is_file($path)) {
            $raw = json_decode((string) file_get_contents($path), true);
            if (is_array($raw)) {
                $needle = strtolower(trim($login));
                foreach ($raw as $user) {
                    if (! is_array($user)) {
                        continue;
                    }
                    $userLogin = strtolower(trim((string) ($user['login'] ?? '')));
                    if ($needle !== '' && $userLogin === $needle) {
                        $perms = $user['permissions'] ?? null;
                        if (is_array($perms)) {
                            return array_merge(libautoent_default_permissions($statue), array_map('boolval', $perms));
                        }
                        $userStatue = strtolower(trim((string) ($user['statue'] ?? $statue)));
                        $map = ['gérant' => 'gerant', 'gerant' => 'gerant', 'assis' => 'assis', 'vendeur' => 'vendeur'];

                        return libautoent_default_permissions($map[$userStatue] ?? $statue);
                    }
                }
            }
        }

        return libautoent_default_permissions($statue);
    }
}
