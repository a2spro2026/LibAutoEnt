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

    var UNION_KEYS = {
        libautoent_utilisateurs: true,
        libautoent_bons_achat: true,
        libautoent_bons_vente: true,
        libautoent_reglements_achat: true,
        libautoent_reglements_vente: true
    };

    var PROTECTED_KEYS = {
        libautoent_catalogue_produits: true,
        libautoent_utilisateurs: true,
        libautoent_bons_achat: true,
        libautoent_bons_vente: true
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

    function mergeById(remote, local) {
        if (!Array.isArray(remote)) return Array.isArray(local) ? local : [];
        if (!Array.isArray(local) || !local.length) return remote;
        var byId = {};
        local.forEach(function (b) {
            if (b && b.id) byId[String(b.id)] = b;
        });
        var merged = remote.map(function (b) {
            if (!b || !b.id) return b;
            var loc = byId[String(b.id)];
            return loc ? Object.assign({}, b, loc) : b;
        });
        var remoteIds = {};
        remote.forEach(function (b) {
            if (b && b.id) remoteIds[String(b.id)] = true;
        });
        local.forEach(function (b) {
            if (b && b.id && !remoteIds[String(b.id)]) {
                merged.unshift(b);
            }
        });
        return merged;
    }

    function mergeBons(remote, local) {
        return mergeById(remote, local);
    }

    function fetchServerKeyOnly(key) {
        return fetch(API + '/' + encodeURIComponent(key), {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) return null;
                return r.json();
            })
            .then(function (data) {
                return normalizePulled(key, data);
            })
            .catch(function () {
                return null;
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
                        if (PROTECTED_KEYS[key]) {
                            return pushKeyRaw(key, local).then(function () {
                                return local;
                            });
                        }
                        var normalizedLocal = normalizePulled(key, local);
                        if (normalizedLocal == null) return null;
                        return pushKeyRaw(key, normalizedLocal).then(function () {
                            return normalizedLocal;
                        });
                    }
                    return null;
                }

                var normalized = normalizePulled(key, data);

                // Ne pas écraser une liste locale non vide par une liste serveur vide
                if (PROTECTED_KEYS[key] &&
                    Array.isArray(normalized) && normalized.length === 0 &&
                    Array.isArray(local) && local.length > 0) {
                    return pushKeyRaw(key, local).then(function () {
                        return local;
                    });
                }

                if (key === 'libautoent_catalogue_produits' && Array.isArray(normalized)) {
                    normalized = mergeCatalogue(normalized, local);
                }

                if (UNION_KEYS[key] && Array.isArray(normalized) && Array.isArray(local) && local.length > 0) {
                    var mergedUnion = mergeById(normalized, local);
                    if (mergedUnion.length > normalized.length) {
                        localStorage.setItem(key, JSON.stringify(mergedUnion));
                        return pushKeyRaw(key, mergedUnion).then(function () {
                            return mergedUnion;
                        });
                    }
                    // Toujours garder le plus grand ensemble (serveur plus riche que local)
                    if (mergedUnion.length >= (Array.isArray(normalized) ? normalized.length : 0)) {
                        normalized = mergedUnion;
                    }
                }

                // Si le serveur a nettement plus d'éléments, forcer le local
                if (PROTECTED_KEYS[key] && Array.isArray(normalized) && Array.isArray(local)
                    && normalized.length > local.length) {
                    localStorage.setItem(key, JSON.stringify(normalized));
                    return normalized;
                }

                localStorage.setItem(key, JSON.stringify(normalized));
                return normalized;
            })
            .catch(function () {
                return null;
            });
    }

    function sanitizeForPush(key, data) {
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

    function refreshCsrfToken() {
        return fetch('/login', {
            method: 'GET',
            credentials: 'same-origin',
            headers: { Accept: 'text/html', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (r) {
            return r.text();
        }).then(function (html) {
            var m = html.match(/name="csrf-token"\s+content="([^"]+)"/);
            if (!m) {
                m = html.match(/name="_token"\s+value="([^"]+)"/);
            }
            if (m && m[1]) {
                var meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    meta.content = m[1];
                }
            }
            return true;
        }).catch(function () {
            return false;
        });
    }

    function putJson(key, data, options, retried) {
        options = options || {};
        var headers = getSyncHeaders(true);
        if (options.force) {
            headers['X-Libautoent-Force'] = '1';
        }
        var body = JSON.stringify(sanitizeForPush(key, data));
        return fetch(API + '/' + encodeURIComponent(key), {
            method: 'PUT',
            headers: headers,
            credentials: 'same-origin',
            body: body
        }).then(function (r) {
            // Session expirée : rafraîchir le token CSRF et réessayer une fois
            if (r.status === 419 && !retried) {
                return refreshCsrfToken().then(function () {
                    return putJson(key, data, options, true);
                });
            }
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

    function pushKeyRaw(key, data, options) {
        options = options || {};
        if (key === 'libautoent_catalogue_produits' && Array.isArray(data)) {
            return fetchServerKeyOnly(key).then(function (remote) {
                if (Array.isArray(remote) && remote.length > data.length) {
                    console.warn('Sync catalogue: refus d\'écraser le serveur (' + remote.length + ' produits) avec une copie locale plus petite (' + data.length + ').');
                    var merged = mergeCatalogue(remote, data);
                    try {
                        localStorage.setItem(key, JSON.stringify(merged));
                    } catch (e) { /* ignore */ }
                    if (typeof window.onCatalogueSynced === 'function') {
                        window.onCatalogueSynced();
                    }
                    return { ok: true, skipped: true };
                }
                return putJson(key, data, options);
            });
        }

        if (UNION_KEYS[key] && Array.isArray(data) && !options.force) {
            return fetchServerKeyOnly(key).then(function (remote) {
                // Toujours relire le local au moment de l'envoi : un push plus ancien
                // ne doit jamais écraser un bon ajouté pendant l'attente (ventes rapides).
                var localNow = readLocal(key);
                var toSend = Array.isArray(data) ? data.slice() : [];
                if (Array.isArray(localNow) && localNow.length) {
                    toSend = mergeById(toSend, localNow);
                }
                if (Array.isArray(remote) && remote.length) {
                    toSend = mergeById(remote, toSend);
                }
                try {
                    var prevLen = Array.isArray(localNow) ? localNow.length : 0;
                    if (toSend.length >= prevLen) {
                        localStorage.setItem(key, JSON.stringify(toSend));
                    }
                } catch (e) { /* ignore */ }
                if (Array.isArray(localNow) && toSend.length > localNow.length) {
                    console.warn('Sync ' + key + ': fusion avant envoi (' + localNow.length + ' → ' + toSend.length + ').');
                    if (key === 'libautoent_bons_vente' && typeof window.onVentesSynced === 'function') {
                        window.onVentesSynced();
                    }
                    if (key === 'libautoent_utilisateurs' && typeof window.onUsersSynced === 'function') {
                        window.onUsersSynced();
                    }
                }
                return putJson(key, toSend, options).then(function (res) {
                    if (res && res.ok && Array.isArray(toSend)) {
                        try {
                            var latest = readLocal(key);
                            var finalList = toSend;
                            if (Array.isArray(latest) && latest.length) {
                                finalList = mergeById(toSend, latest);
                            }
                            localStorage.setItem(key, JSON.stringify(finalList));
                        } catch (e) { /* ignore */ }
                    }
                    return res;
                });
            });
        }

        return putJson(key, data, options);
    }

    function pullKey(key) {
        return enqueue(function () {
            return pullKeyRaw(key);
        });
    }

    function pushKey(key, data, options) {
        return enqueue(function () {
            return pushKeyRaw(key, data, options);
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
