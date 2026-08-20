/**
 * Stockage partagé Bons / Règlements (localStorage)
 */
(function (window) {
    'use strict';

    var KEY_REGLEMENTS = 'libautoent_reglements_achat';
    var KEY_BONS = 'libautoent_bons_achat';

    function read(key) {
        try {
            var raw = localStorage.getItem(key);
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return [];
        }
    }

    function write(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    function nextRegNumber() {
        var list = read(KEY_REGLEMENTS);
        var n = list.length + 1;
        var d = new Date();
        var y = String(d.getFullYear()).slice(-2);
        var m = String(d.getMonth() + 1).padStart(2, '0');
        return 'RA-' + y + m + String(n).padStart(4, '0');
    }

    function formatDateFR(isoOrDate) {
        if (!isoOrDate) {
            var d = new Date();
            return String(d.getDate()).padStart(2, '0') + '/' +
                String(d.getMonth() + 1).padStart(2, '0') + '/' +
                d.getFullYear();
        }
        if (isoOrDate.indexOf('-') !== -1) {
            var p = isoOrDate.split('-');
            return p[2] + '/' + p[1] + '/' + p[0];
        }
        return isoOrDate;
    }

    function addReglementsFromPaiement(payload) {
        var list = read(KEY_REGLEMENTS);
        var added = [];
        var d = new Date();
        var y = String(d.getFullYear()).slice(-2);
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var base = list.length;

        (payload.lignes || []).forEach(function (ligne, index) {
            if (!ligne.montant || Number(ligne.montant) <= 0) return;
            base += 1;
            var reg = {
                id: 'reg_' + Date.now() + '_' + index + '_' + Math.random().toString(36).slice(2, 7),
                date: formatDateFR(payload.date),
                numero: 'RA-' + y + m + String(base).padStart(4, '0'),
                fournisseur: payload.fournisseur || '',
                montant: Number(ligne.montant),
                type: ligne.type || 'Esp',
                bnq: ligne.bnq || '—',
                tire: ligne.tire || '—',
                dateDecaiss: ligne.dateDecaiss || formatDateFR(payload.date),
                photo: ligne.photo || '',
                statut: ligne.statut || 'paye',
                numBon: payload.numBon || ''
            };
            list.unshift(reg);
            added.push(reg);
        });
        write(KEY_REGLEMENTS, list);
        return added;
    }

    function getReglements() {
        return read(KEY_REGLEMENTS);
    }

    function saveReglements(list) {
        write(KEY_REGLEMENTS, list);
    }

    function deleteReglement(id) {
        var list = read(KEY_REGLEMENTS).filter(function (r) { return r.id !== id; });
        write(KEY_REGLEMENTS, list);
        return list;
    }

    function updateReglementStatut(id, statut) {
        var list = read(KEY_REGLEMENTS).map(function (r) {
            if (r.id === id) r.statut = statut;
            return r;
        });
        write(KEY_REGLEMENTS, list);
        return list;
    }

    function fmtMoney(n) {
        return Number(n || 0).toFixed(2) + ' DH';
    }

    function getBons() {
        return read(KEY_BONS);
    }

    function saveBons(list) {
        write(KEY_BONS, list);
    }

    function addBon(bon) {
        var list = read(KEY_BONS);
        var item = {
            id: bon.id || ('bon_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7)),
            date: bon.date || formatDateFR(),
            numero: bon.numero || '',
            fournisseur: bon.fournisseur || '',
            montant: Number(bon.montant) || 0,
            typePaie: bon.typePaie || 'Crédit',
            montantPaye: Number(bon.montantPaye) || 0,
            solde: Number(bon.solde) || 0,
            lignes: bon.lignes || []
        };
        list.unshift(item);
        write(KEY_BONS, list);
        return item;
    }

    function deleteBon(id) {
        var list = read(KEY_BONS).filter(function (b) { return b.id !== id; });
        write(KEY_BONS, list);
        return list;
    }

    function getBonsNonSoldes() {
        return read(KEY_BONS).filter(function (b) {
            return Number(b.solde) > 0.0001;
        });
    }

    function getFournisseursNonSoldes() {
        var names = {};
        getBonsNonSoldes().forEach(function (b) {
            if (b.fournisseur) names[b.fournisseur] = true;
        });
        return Object.keys(names).sort();
    }

    function getBonsNonSoldesByFournisseur(fournisseur) {
        return getBonsNonSoldes().filter(function (b) {
            return b.fournisseur === fournisseur;
        });
    }

    function applyPaiementToBon(bonId, montantPayeAjout) {
        var pay = Number(montantPayeAjout) || 0;
        var list = read(KEY_BONS);
        var updated = null;
        list = list.map(function (b) {
            if (b.id !== bonId) return b;
            var nouveauPaye = Number(b.montantPaye || 0) + pay;
            var solde = Math.max(0, Number(b.montant || 0) - nouveauPaye);
            updated = Object.assign({}, b, {
                montantPaye: nouveauPaye,
                solde: solde,
                typePaie: solde <= 0.0001 ? (b.typePaie || 'Esp') : b.typePaie
            });
            return updated;
        });
        write(KEY_BONS, list);
        return updated;
    }

    function addReglement(reg) {
        var list = read(KEY_REGLEMENTS);
        var d = new Date();
        var y = String(d.getFullYear()).slice(-2);
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var item = {
            id: reg.id || ('reg_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7)),
            date: reg.date || formatDateFR(),
            numero: reg.numero || ('RA-' + y + m + String(list.length + 1).padStart(4, '0')),
            fournisseur: reg.fournisseur || '',
            montant: Number(reg.montant) || 0,
            type: reg.type || 'Esp',
            bnq: reg.bnq || '—',
            tire: reg.tire || '—',
            dateDecaiss: reg.dateDecaiss || formatDateFR(),
            photo: reg.photo || '',
            statut: reg.statut || 'paye',
            numBon: reg.numBon || '',
            bonId: reg.bonId || ''
        };
        list.unshift(item);
        write(KEY_REGLEMENTS, list);
        return item;
    }

    function parseDateFR(s) {
        if (!s || typeof s !== 'string') return 0;
        var p = s.split('/');
        if (p.length !== 3) return 0;
        return new Date(Number(p[2]), Number(p[1]) - 1, Number(p[0])).getTime() || 0;
    }

    function getBonsByFournisseur(fournisseur) {
        return read(KEY_BONS).filter(function (b) {
            return b.fournisseur === fournisseur;
        }).sort(function (a, b) {
            return parseDateFR(b.date) - parseDateFR(a.date);
        });
    }

    /** Agrégat balance par fournisseur (tous les bons) */
    function getBalanceFournisseurs() {
        var map = {};
        read(KEY_BONS).forEach(function (b) {
            var name = b.fournisseur || '—';
            if (!map[name]) {
                map[name] = {
                    fournisseur: name,
                    date: b.date || '',
                    montant: 0,
                    montantPaye: 0,
                    solde: 0,
                    _dateTs: 0
                };
            }
            map[name].montant += Number(b.montant) || 0;
            map[name].montantPaye += Number(b.montantPaye) || 0;
            map[name].solde += Number(b.solde) || 0;
            var ts = parseDateFR(b.date);
            if (ts >= map[name]._dateTs) {
                map[name]._dateTs = ts;
                map[name].date = b.date || map[name].date;
            }
        });
        return Object.keys(map).map(function (k) {
            var row = map[k];
            delete row._dateTs;
            return row;
        }).sort(function (a, b) {
            return (a.fournisseur || '').localeCompare(b.fournisseur || '', 'fr');
        });
    }

    function getBalanceTotals() {
        var rows = getBalanceFournisseurs();
        var t = { montant: 0, montantPaye: 0, solde: 0 };
        rows.forEach(function (r) {
            t.montant += Number(r.montant) || 0;
            t.montantPaye += Number(r.montantPaye) || 0;
            t.solde += Number(r.solde) || 0;
        });
        return t;
    }

    function getTotalSolde() {
        return getBalanceTotals().solde;
    }

    window.AchatStore = {
        getReglements: getReglements,
        saveReglements: saveReglements,
        addReglementsFromPaiement: addReglementsFromPaiement,
        addReglement: addReglement,
        deleteReglement: deleteReglement,
        updateReglementStatut: updateReglementStatut,
        getBons: getBons,
        saveBons: saveBons,
        addBon: addBon,
        deleteBon: deleteBon,
        getBonsNonSoldes: getBonsNonSoldes,
        getFournisseursNonSoldes: getFournisseursNonSoldes,
        getBonsNonSoldesByFournisseur: getBonsNonSoldesByFournisseur,
        getBonsByFournisseur: getBonsByFournisseur,
        getBalanceFournisseurs: getBalanceFournisseurs,
        getBalanceTotals: getBalanceTotals,
        getTotalSolde: getTotalSolde,
        applyPaiementToBon: applyPaiementToBon,
        formatDateFR: formatDateFR,
        fmtMoney: fmtMoney
    };
})(window);
