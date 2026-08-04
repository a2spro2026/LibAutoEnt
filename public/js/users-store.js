/**
 * Utilisateurs — stockage localStorage
 */
(function (window) {
    'use strict';

    var KEY = '7ssabhani_utilisateurs';
    var LOGIN_SUFFIX = '@7ssabHani.com';

    function read() {
        try {
            var raw = localStorage.getItem(KEY);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function write(list) {
        localStorage.setItem(KEY, JSON.stringify(list));
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
        // strip accidental @domain then append official suffix
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

    function getUsers() {
        return read();
    }

    function addUser(data) {
        var list = read();
        var loginCheck = validateLogin(data.login);
        if (!loginCheck.ok) throw new Error(loginCheck.message);
        var pwdCheck = validatePassword(data.password);
        if (!pwdCheck.ok) throw new Error(pwdCheck.message);

        var dup = list.some(function (u) {
            return String(u.login).toLowerCase() === loginCheck.login.toLowerCase();
        });
        if (dup) throw new Error('Ce login existe déjà.');

        var item = {
            id: 'usr_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7),
            idCode: data.idCode || nextId(),
            date: data.date || formatDateFR(),
            nomComplet: String(data.nomComplet || '').trim(),
            statut: data.statut || 'Actif',
            contact: String(data.contact || '').trim(),
            login: loginCheck.login,
            password: String(data.password)
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

            var dup = list.some(function (o) {
                return o.id !== id && String(o.login).toLowerCase() === loginCheck.login.toLowerCase();
            });
            if (dup) throw new Error('Ce login existe déjà.');

            found = Object.assign({}, u, {
                date: data.date || u.date,
                idCode: data.idCode || u.idCode,
                nomComplet: String(data.nomComplet != null ? data.nomComplet : u.nomComplet).trim(),
                statut: data.statut || u.statut,
                contact: String(data.contact != null ? data.contact : u.contact).trim(),
                login: loginCheck.login,
                password: String(pwd)
            });
            return found;
        });
        if (!found) throw new Error('Utilisateur introuvable.');
        write(list);
        return found;
    }

    function deleteUser(id) {
        write(read().filter(function (u) { return u.id !== id; }));
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
        nextId: nextId,
        formatDateFR: formatDateFR,
        normalizeLogin: normalizeLogin,
        LOGIN_SUFFIX: LOGIN_SUFFIX
    };
})(window);
