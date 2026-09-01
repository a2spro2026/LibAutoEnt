/**
 * Rôle connecté (gérant, assis, vendeur)
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

    window.UserRole = {
        getStatut: getStatut,
        isVendeur: function () {
            return getStatut() === 'vendeur';
        },
        canDelete: function () {
            return getStatut() !== 'vendeur';
        }
    };
})(window);
