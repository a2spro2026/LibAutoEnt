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

    // File d'attente : un pull ne doit jamais écraser un push de suppression en cours
    var syncChain = Promise.resolve();

    function enqueue(task) {
        var run = function () {
            return Promise.resolve().then(task).catch(function () {
                return null;
            });
        };
        syncChain = syncChain.then(run, run);
        return syncChain;
    }

    function getSyncHeaders(isJson) {
        var headers = {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        if (isJson !== false) {
            headers['Content-Type'] = 'application/json';
        }
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

    function readLocal(key) {
        try {
            var raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function mergeCatalogue(remote, local) {
        if (!Array.isArray(remote)) return Array.isArray(local) ? local : [];
        if (!Array.isArray(local) || !local.length) return remote;
        var byId = {};
        local.forEach(function (p) {
            if (p && p.id) byId[p.id] = p;
        });
        return remote.map(function (p) {
            if (!p || !p.id) return p;
            var loc = byId[p.id];
            if (!loc) return p;
            var remotePhoto = String(p.photo || '');
            var localPhoto = String(loc.photo || '');
            if (!remotePhoto && localPhoto) {
                return Object.assign({}, p, { photo: localPhoto });
            }
            return p;
        });
    }

    function pullKeyRaw(key) {
        var localAtStart = readLocal(key);
        return fetch(API + '/' + encodeURIComponent(key), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) return null;
                return r.json();
            })
            .then(function (data) {
                // Si le local a changé pendant le fetch (ex: suppression), ne pas l’écraser
                var localNow = readLocal(key);
                try {
                    if (JSON.stringify(localNow) !== JSON.stringify(localAtStart) && localNow != null) {
                        return pushKeyRaw(key, localNow).then(function () {
                            return localNow;
                        });
                    }
                } catch (e) { /* ignore */ }

                var local = localNow;

                if (data === null || data === undefined) {
                    if (local != null && JSON.stringify(local) !== '[]' && JSON.stringify(local) !== '{}') {
                        var normalizedLocal = normalizePulled(key, local);
                        if (normalizedLocal == null) return null;
                        return pushKeyRaw(key, normalizedLocal).then(function () {
                            return normalizedLocal;
                        });
                    }
                    return null;
                }

                var normalized = normalizePulled(key, data);

                // Ne pas écraser un catalogue local non vide par une liste serveur vide
                if (key === 'libautoent_catalogue_produits' &&
                    Array.isArray(normalized) && normalized.length === 0 &&
                    Array.isArray(local) && local.length > 0) {
                    return pushKeyRaw(key, local).then(function () {
                        return local;
                    });
                }

                // Suppressions locales non encore sur le serveur
                if ((key === 'libautoent_bons_vente' || key === 'libautoent_bons_achat') &&
                    Array.isArray(normalized) && Array.isArray(local) &&
                    local.length > 0 && local.length < normalized.length) {
                    var remoteIds = {};
                    normalized.forEach(function (b) {
                        if (b && b.id) remoteIds[String(b.id)] = true;
                    });
                    var localAllOnRemote = local.every(function (b) {
                        return b && b.id && remoteIds[String(b.id)];
                    });
                    if (localAllOnRemote) {
                        return pushKeyRaw(key, local).then(function () {
                            return local;
                        });
                    }
                }

                if (key === 'libautoent_catalogue_produits' && Array.isArray(normalized)) {
                    normalized = mergeCatalogue(normalized, local);
                }

                localStorage.setItem(key, JSON.stringify(normalized));
                return normalized;
            })
            .catch(function () {
                return null;
            });
    }

    function sanitizeForPush(key, data) {
        // Ne jamais pousser les photos base64 (trop lourdes → sync échoue → photos perdues)
        if (key === 'libautoent_catalogue_produits' && Array.isArray(data)) {
            return data.map(function (p) {
                if (!p || typeof p !== 'object') return p;
                var photo = String(p.photo || '');
                if (photo.indexOf('data:') === 0) {
                    return Object.assign({}, p, { photo: '' });
                }
                return p;
            });
        }
        return data;
    }

    function pushKeyRaw(key, data) {
        var body = sanitizeForPush(key, data);
        return fetch(API + '/' + encodeURIComponent(key), {
            method: 'PUT',
            headers: getSyncHeaders(true),
            credentials: 'same-origin',
            body: JSON.stringify(body)
        }).then(function (r) {
            if (!r.ok) {
                return r.text().then(function () {
                    return { ok: false, status: r.status };
                });
            }
            return { ok: true };
        }).catch(function () {
            return { ok: false };
        });
    }

    function pullKey(key) {
        return enqueue(function () {
            return pullKeyRaw(key);
        });
    }

    function pushKey(key, data) {
        return enqueue(function () {
            return pushKeyRaw(key, data);
        });
    }

    function uploadPhoto(blobOrFile, filename) {
        var fd = new FormData();
        fd.append('photo', blobOrFile, filename || 'photo.jpg');
        return fetch('/api/photo', {
            method: 'POST',
            headers: getSyncHeaders(false),
            credentials: 'same-origin',
            body: fd
        }).then(function (r) {
            if (!r.ok) throw new Error('Upload photo échoué (' + r.status + ')');
            return r.json();
        }).then(function (json) {
            if (!json || !json.url) throw new Error('Réponse photo invalide');
            return json.url;
        });
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
        pushKeyFromLocal: pushKeyFromLocal,
        uploadPhoto: uploadPhoto
    };
})(window);
