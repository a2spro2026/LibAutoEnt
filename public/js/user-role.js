/**
 * Rôle connecté + autorisations
 */
(function (window) {
    'use strict';

    function getStatut() {
        if (window.__LIBAUTOENT_STATUT__) {
            return String(window.__LIBAUTOENT_STATUT__).toLowerCase();
        }
        try {
            var stored = sessionStorage.getItem('libautoent_statut');
            if (stored) return String(stored).toLowerCase();
        } catch (e) { /* ignore */ }
        return 'gerant';
    }

    function getPermissions() {
        if (window.__LIBAUTOENT_PERMISSIONS__ && typeof window.__LIBAUTOENT_PERMISSIONS__ === 'object') {
            return window.__LIBAUTOENT_PERMISSIONS__;
        }
        try {
            var raw = sessionStorage.getItem('libautoent_permissions');
            if (raw) return JSON.parse(raw);
        } catch (e) { /* ignore */ }
        return null;
    }

    function can(key) {
        var perms = getPermissions();
        if (perms && Object.prototype.hasOwnProperty.call(perms, key)) {
            return !!perms[key];
        }
        // Fallback historiques par statue
        var s = getStatut();
        if (s === 'gerant') return true;
        if (s === 'assis') {
            return key !== 'config.manage' && key !== 'dashboard.delete' && key !== 'stock.delete';
        }
        // vendeur
        return (
            key === 'dashboard.view' ||
            key === 'dashboard.create' ||
            key === 'dashboard.edit' ||
            key === 'dashboard.print' ||
            key === 'stock.view'
        );
    }

    window.UserRole = {
        getStatut: getStatut,
        getPermissions: getPermissions,
        can: can,
        isVendeur: function () {
            return getStatut() === 'vendeur';
        },
        canDelete: function () {
            return can('dashboard.delete') || can('stock.delete');
        }
    };
})(window);
