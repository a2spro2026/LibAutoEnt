/**
 * Utilisateurs — stockage localStorage + autorisations
 */
(function (window) {
    'use strict';

    var KEY = 'libautoent_utilisateurs';
    var LOGIN_SUFFIX = '@LibAutoEnt.com';
    var STATUES = ['Gérant', 'Assis', 'Vendeur'];

    var PERMISSION_GROUPS = [
        {
            id: 'dashboard',
            title: 'Tableau de bord',
            hint: 'Bons de vente du jour et actions rapides',
            items: [
                { key: 'dashboard.view', label: 'Consulter' },
                { key: 'dashboard.create', label: 'Ajouter un bon' },
                { key: 'dashboard.edit', label: 'Modifier' },
                { key: 'dashboard.print', label: 'Imprimer' },
                { key: 'dashboard.delete', label: 'Supprimer' }
            ]
        },
        {
            id: 'stock',
            title: 'Stock',
            hint: 'Catégorie produit et état du stock',
            items: [
                { key: 'stock.view', label: 'Consulter' },
                { key: 'stock.create', label: 'Ajouter' },
                { key: 'stock.edit', label: 'Modifier' },
                { key: 'stock.delete', label: 'Supprimer' }
            ]
        },
        {
            id: 'ventes',
            title: 'État Vente',
            hint: 'Balance des ventes et rapport revenue',
            items: [
                { key: 'ventes.view', label: 'Consulter' },
                { key: 'ventes.print', label: 'Imprimer / exporter' }
            ]
        },
        {
            id: 'config',
            title: 'Configuration',
            hint: 'Utilisateurs et paramètres système',
            items: [
                { key: 'config.view', label: 'Consulter' },
                { key: 'config.manage', label: 'Gérer les comptes' }
            ]
        }
    ];

    var ALL_KEYS = [];
    PERMISSION_GROUPS.forEach(function (g) {
        g.items.forEach(function (it) { ALL_KEYS.push(it.key); });
    });

    var ROLE_PRESETS = {
        'Gérant': ALL_KEYS.slice(),
        'Assis': [
            'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.print',
            'stock.view', 'stock.create', 'stock.edit',
            'ventes.view', 'ventes.print',
            'config.view'
        ],
        'Vendeur': [
            'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.print',
            'stock.view'
        ]
    };

    function emptyPermissions() {
        var out = {};
        ALL_KEYS.forEach(function (k) { out[k] = false; });
        return out;
    }

    function permissionsFromList(list) {
        var out = emptyPermissions();
        (list || []).forEach(function (k) {
            if (Object.prototype.hasOwnProperty.call(out, k)) out[k] = true;
        });
        return out;
    }

    function defaultPermissions(statue) {
        return permissionsFromList(ROLE_PRESETS[statue] || ROLE_PRESETS['Vendeur']);
    }

    function normalizePermissions(raw, statue) {
        var base = defaultPermissions(statue || 'Vendeur');
        if (!raw || typeof raw !== 'object') return base;
        var out = emptyPermissions();
        ALL_KEYS.forEach(function (k) {
            if (Object.prototype.hasOwnProperty.call(raw, k)) {
                out[k] = !!raw[k];
            } else {
                out[k] = !!base[k];
            }
        });
        return out;
    }

    function countEnabled(perms) {
        var n = 0;
        ALL_KEYS.forEach(function (k) { if (perms && perms[k]) n += 1; });
        return n;
    }

    function read() {
        try {
            var raw = localStorage.getItem(KEY);
            var data = raw ? JSON.parse(raw) : [];
            if (!Array.isArray(data)) return [];
            return data.map(function (u) {
                if (!u || typeof u !== 'object') return u;
                return Object.assign({}, u, {
                    permissions: normalizePermissions(u.permissions, u.statue)
                });
            });
        } catch (e) {
            return [];
        }
    }

    function write(list, options) {
        var safe = Array.isArray(list) ? list : [];
        localStorage.setItem(KEY, JSON.stringify(safe));
        if (window.DataSync) {
            return DataSync.pushKey(KEY, safe, options);
        }
        return Promise.resolve({ ok: true });
    }

    function initFromServer() {
        if (!window.DataSync) return Promise.resolve();
        return DataSync.pullKey(KEY);
    }

    function formatDateFR(isoOrDate) {
        if (!isoOrDate) {
            var d = new Date();
            return String(d.getDate()).padStart(2, '0') + '/' +
                String(d.getMonth() + 1).padStart(2, '0') + '/' +
                d.getFullYear();
        }
        if (String(isoOrDate).indexOf('-') !== -1) {
            var p = String(isoOrDate).split('-');
            return p[2] + '/' + p[1] + '/' + p[0];
        }
        return isoOrDate;
    }

    function nextId() {
        var list = read();
        var max = 0;
        list.forEach(function (u) {
            var m = String(u.idCode || '').match(/(\d+)$/);
            if (m) max = Math.max(max, parseInt(m[1], 10) || 0);
        });
        return 'USR-' + String(max + 1).padStart(4, '0');
    }

    function normalizeLogin(login) {
        var raw = String(login || '').trim();
        if (!raw) return '';
        if (raw.toLowerCase().endsWith(LOGIN_SUFFIX.toLowerCase())) return raw;
        var local = raw.split('@')[0].trim();
        if (!local) return '';
        return local + LOGIN_SUFFIX;
    }

    function validateLogin(login) {
        var n = normalizeLogin(login);
        if (!n) return { ok: false, message: 'Saisissez un login.' };
        if (!n.toLowerCase().endsWith(LOGIN_SUFFIX.toLowerCase())) {
            return { ok: false, message: 'Le login doit se terminer par ' + LOGIN_SUFFIX };
        }
        return { ok: true, login: n };
    }

    function validatePassword(pwd) {
        if (!pwd || String(pwd).length < 8) {
            return { ok: false, message: 'Le mot de passe doit contenir au moins 8 caractères.' };
        }
        return { ok: true };
    }

    function validateStatue(statue) {
        var value = String(statue || '').trim();
        if (!value) return { ok: false, message: 'Sélectionnez une statue.' };
        if (STATUES.indexOf(value) === -1) {
            return { ok: false, message: 'Statue invalide.' };
        }
        return { ok: true, statue: value };
    }

    function getUsers() {
        return read();
    }

    function addUser(data) {
        var list = read();
        var loginCheck = validateLogin(data.login);
        if (!loginCheck.ok) throw new Error(loginCheck.message);
        var pwdCheck = validatePassword(data.password);
        if (!pwdCheck.ok) throw new Error(pwdCheck.message);
        var statueCheck = validateStatue(data.statue);
        if (!statueCheck.ok) throw new Error(statueCheck.message);

        var dup = list.some(function (u) {
            return String(u.login).toLowerCase() === loginCheck.login.toLowerCase();
        });
        if (dup) throw new Error('Ce login existe déjà.');

        var item = {
            id: 'usr_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7),
            idCode: data.idCode || nextId(),
            date: data.date || formatDateFR(),
            nomComplet: String(data.nomComplet || '').trim(),
            statue: statueCheck.statue,
            statut: 'Actif',
            contact: String(data.contact || '').trim(),
            login: loginCheck.login,
            password: String(data.password),
            permissions: normalizePermissions(data.permissions, statueCheck.statue)
        };
        if (!item.nomComplet) throw new Error('Saisissez le nom complet.');

        list.unshift(item);
        write(list);
        return item;
    }

    function updateUser(id, data) {
        var list = read();
        var found = null;
        list = list.map(function (u) {
            if (u.id !== id) return u;
            var loginCheck = validateLogin(data.login != null ? data.login : u.login);
            if (!loginCheck.ok) throw new Error(loginCheck.message);
            var pwd = data.password != null && data.password !== '' ? data.password : u.password;
            var pwdCheck = validatePassword(pwd);
            if (!pwdCheck.ok) throw new Error(pwdCheck.message);
            var statueCheck = validateStatue(data.statue != null ? data.statue : u.statue);
            if (!statueCheck.ok) throw new Error(statueCheck.message);

            var dup = list.some(function (o) {
                return o.id !== id && String(o.login).toLowerCase() === loginCheck.login.toLowerCase();
            });
            if (dup) throw new Error('Ce login existe déjà.');

            found = Object.assign({}, u, {
                date: data.date || u.date,
                idCode: data.idCode || u.idCode,
                nomComplet: String(data.nomComplet != null ? data.nomComplet : u.nomComplet).trim(),
                statue: statueCheck.statue,
                statut: u.statut || 'Actif',
                contact: String(data.contact != null ? data.contact : u.contact).trim(),
                login: loginCheck.login,
                password: String(pwd),
                permissions: normalizePermissions(
                    data.permissions != null ? data.permissions : u.permissions,
                    statueCheck.statue
                )
            });
            return found;
        });
        if (!found) throw new Error('Utilisateur introuvable.');
        write(list);
        return found;
    }

    function deleteUser(id) {
        write(read().filter(function (u) { return u.id !== id; }), { force: true });
    }

    function suspendUser(id) {
        var list = read().map(function (u) {
            if (u.id !== id) return u;
            var next = (u.statut === 'Suspendu') ? 'Actif' : 'Suspendu';
            return Object.assign({}, u, { statut: next });
        });
        write(list);
        return list.find(function (u) { return u.id === id; });
    }

    function getUser(id) {
        return read().find(function (u) { return u.id === id; }) || null;
    }

    window.UsersStore = {
        getUsers: getUsers,
        getUser: getUser,
        addUser: addUser,
        updateUser: updateUser,
        deleteUser: deleteUser,
        suspendUser: suspendUser,
        initFromServer: initFromServer,
        nextId: nextId,
        formatDateFR: formatDateFR,
        normalizeLogin: normalizeLogin,
        defaultPermissions: defaultPermissions,
        normalizePermissions: normalizePermissions,
        countEnabled: countEnabled,
        PERMISSION_GROUPS: PERMISSION_GROUPS,
        ROLE_PRESETS: ROLE_PRESETS,
        LOGIN_SUFFIX: LOGIN_SUFFIX,
        STATUES: STATUES
    };
})(window);
