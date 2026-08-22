/**
 * Synchronisation localStorage ↔ serveur (partage téléphone / PC)
 */
(function (window) {
    'use strict';

    var API = '/api/store';

    var KEYS = [
        'libautoent_catalogue_produits',
        'libautoent_stock_meta',
        'libautoent_stock_categories',
        'libautoent_utilisateurs',
        'libautoent_bons_achat',
        'libautoent_bons_vente',
        'libautoent_reglements_achat',
        'libautoent_reglements_vente'
    ];

    var ARRAY_KEYS = {
        libautoent_catalogue_produits: true,
        libautoent_utilisateurs: true,
        libautoent_bons_achat: true,
        libautoent_bons_vente: true,
        libautoent_reglements_achat: true,
        libautoent_reglements_vente: true
    };

    function getSyncHeaders() {
        var headers = {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (meta && meta.content) {
            headers['X-CSRF-TOKEN'] = meta.content;
        }
        var match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
        if (match) {
            headers['X-XSRF-TOKEN'] = decodeURIComponent(match[1]);
        }
        return headers;
    }

    function normalizePulled(key, data) {
        if (data === null || data === undefined) return null;
        if (ARRAY_KEYS[key]) {
            return Array.isArray(data) ? data : [];
        }
        if (typeof data !== 'object' || Array.isArray(data)) {
            return {};
        }
        return data;
    }

    function pullKey(key) {
        return fetch(API + '/' + encodeURIComponent(key), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) return null;
                return r.json();
            })
            .then(function (data) {
                if (data === null || data === undefined) {
                    try {
                        var raw = localStorage.getItem(key);
                        if (raw && raw !== '[]' && raw !== '{}') {
                            var local = JSON.parse(raw);
                            local = normalizePulled(key, local);
                            if (local == null) return null;
                            return pushKey(key, local).then(function () {
                                return local;
                            });
                        }
                    } catch (e) { /* ignore */ }
                    return null;
                }
                var normalized = normalizePulled(key, data);
                localStorage.setItem(key, JSON.stringify(normalized));
                return normalized;
            })
            .catch(function () {
                return null;
            });
    }

    function pushKey(key, data) {
        return fetch(API + '/' + encodeURIComponent(key), {
            method: 'PUT',
            headers: getSyncHeaders(),
            credentials: 'same-origin',
            body: JSON.stringify(data)
        }).catch(function () {});
    }

    function pullAll(keys) {
        var list = keys || KEYS;
        return Promise.all(list.map(pullKey));
    }

    function pushKeyFromLocal(key) {
        try {
            var raw = localStorage.getItem(key);
            if (!raw) return Promise.resolve();
            return pushKey(key, JSON.parse(raw));
        } catch (e) {
            return Promise.resolve();
        }
    }

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState !== 'visible') return;
        pullKey('libautoent_catalogue_produits').then(function () {
            if (typeof window.onCatalogueSynced === 'function') {
                window.onCatalogueSynced();
            }
        });
        pullKey('libautoent_bons_vente').then(function () {
            if (typeof window.onVentesSynced === 'function') {
                window.onVentesSynced();
            }
        });
    });

    window.DataSync = {
        KEYS: KEYS,
        pullKey: pullKey,
        pushKey: pushKey,
        pullAll: pullAll,
        pushKeyFromLocal: pushKeyFromLocal
    };
})(window);
