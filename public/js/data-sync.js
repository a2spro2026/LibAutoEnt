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

    function getCsrf() {
        var match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]*)/);
        if (match) {
            return decodeURIComponent(match[1]);
        }
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : '';
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
                            return pushKey(key, local).then(function () {
                                return local;
                            });
                        }
                    } catch (e) { /* ignore */ }
                    return null;
                }
                localStorage.setItem(key, JSON.stringify(data));
                return data;
            })
            .catch(function () {
                return null;
            });
    }

    function pushKey(key, data) {
        return fetch(API + '/' + encodeURIComponent(key), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
                'X-Requested-With': 'XMLHttpRequest'
            },
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
    });

    window.DataSync = {
        KEYS: KEYS,
        pullKey: pullKey,
        pushKey: pushKey,
        pullAll: pullAll,
        pushKeyFromLocal: pushKeyFromLocal
    };
})(window);
