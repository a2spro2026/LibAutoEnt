/**
 * Stockage partagé Bons / Règlements Vente (localStorage)
 */
(function (window) {
    'use strict';

    var KEY_REGLEMENTS = 'libautoent_reglements_vente';
    var KEY_BONS = 'libautoent_bons_vente';

    function read(key) {
        try {
            var raw = localStorage.getItem(key);
            var data = raw ? JSON.parse(raw) : [];
            return Array.isArray(data) ? data : [];
        } catch (e) {
            return [];
        }
    }

    function write(key, data, options) {
        var safe = Array.isArray(data) ? data : [];
        localStorage.setItem(key, JSON.stringify(safe));
        if (window.DataSync) {
            return DataSync.pushKey(key, safe, options);
        }
        return Promise.resolve({ ok: true });
    }

    function initFromServer() {
        if (!window.DataSync) return Promise.resolve();
        return Promise.all([
            DataSync.pullKey(KEY_BONS),
            DataSync.pullKey(KEY_REGLEMENTS)
        ]);
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
                id: 'regv_' + Date.now() + '_' + index + '_' + Math.random().toString(36).slice(2, 7),
                date: formatDateFR(payload.date),
                numero: 'RV-' + y + m + String(base).padStart(4, '0'),
                client: payload.client || '',
                montant: Number(ligne.montant),
                type: ligne.type || 'Esp',
                bnq: ligne.bnq || '—',
                tire: ligne.tire || '—',
                dateEncaiss: ligne.dateEncaiss || ligne.dateDecaiss || formatDateFR(payload.date),
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

    function nextBonNumero() {
        var list = read(KEY_BONS);
        var max = 0;
        list.forEach(function (b) {
            var m = String(b.numero || '').trim().match(/^BL0*(\d+)$/i);
            if (m) max = Math.max(max, parseInt(m[1], 10) || 0);
        });
        return 'BL' + String(max + 1).padStart(4, '0');
    }

    function addBon(bon) {
        var list = read(KEY_BONS);
        var item = {
            id: bon.id || ('bonv_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7)),
            date: bon.date || formatDateFR(),
            numero: String(bon.numero || '').trim() || nextBonNumero(),
            client: bon.client || '',
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

    function updateBon(id, payload) {
        var list = read(KEY_BONS);
        var updated = null;
        list = list.map(function (b) {
            if (String(b.id) !== String(id)) return b;
            updated = Object.assign({}, b, {
                date: payload.date != null ? payload.date : b.date,
                numero: payload.numero != null ? payload.numero : b.numero,
                client: payload.client != null ? payload.client : b.client,
                montant: payload.montant != null ? Number(payload.montant) : b.montant,
                typePaie: payload.typePaie != null ? payload.typePaie : b.typePaie,
                montantPaye: payload.montantPaye != null ? Number(payload.montantPaye) : b.montantPaye,
                solde: payload.solde != null ? Number(payload.solde) : b.solde,
                lignes: payload.lignes != null ? payload.lignes : b.lignes
            });
            return updated;
        });
        write(KEY_BONS, list);
        return updated;
    }

    function getBon(id) {
        var sid = String(id || '');
        var list = read(KEY_BONS);
        for (var i = 0; i < list.length; i++) {
            if (String(list[i].id) === sid) return list[i];
        }
        return null;
    }

    function deleteBon(id) {
        var sid = String(id || '');
        var list = read(KEY_BONS).filter(function (b) {
            return String(b.id) !== sid;
        });
        return write(KEY_BONS, list, { force: true }).then(function (res) {
            return { ok: !res || res.ok !== false, list: list, sync: res };
        });
    }

    function getBonsNonSoldes() {
        return read(KEY_BONS).filter(function (b) {
            return Number(b.solde) > 0.0001;
        });
    }

    function getClientsNonSoldes() {
        var names = {};
        getBonsNonSoldes().forEach(function (b) {
            if (b.client) names[b.client] = true;
        });
        return Object.keys(names).sort();
    }

    function getBonsNonSoldesByClient(client) {
        return getBonsNonSoldes().filter(function (b) {
            return b.client === client;
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
            id: reg.id || ('regv_' + Date.now() + '_' + Math.random().toString(36).slice(2, 7)),
            date: reg.date || formatDateFR(),
            numero: reg.numero || ('RV-' + y + m + String(list.length + 1).padStart(4, '0')),
            client: reg.client || '',
            montant: Number(reg.montant) || 0,
            type: reg.type || 'Esp',
            bnq: reg.bnq || '—',
            tire: reg.tire || '—',
            dateEncaiss: reg.dateEncaiss || reg.dateDecaiss || formatDateFR(),
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

    function getBonsByClient(client) {
        return read(KEY_BONS).filter(function (b) {
            return b.client === client;
        }).sort(function (a, b) {
            return parseDateFR(b.date) - parseDateFR(a.date);
        });
    }

    function getBalanceClients() {
        var map = {};
        read(KEY_BONS).forEach(function (b) {
            var name = b.client || '—';
            if (!map[name]) {
                map[name] = {
                    client: name,
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
            return (a.client || '').localeCompare(b.client || '', 'fr');
        });
    }

    function getBalanceTotals() {
        var rows = getBalanceClients();
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

    function getDashboardStats() {
        var bons = getBons();
        var totalVentes = 0;
        bons.forEach(function (b) {
            totalVentes += Number(b.montant) || 0;
        });
        return {
            nbrBonsLivres: bons.length,
            totalVentes: totalVentes,
            totalSolde: getTotalSolde()
        };
    }

    window.VenteStore = {
        getReglements: getReglements,
        saveReglements: saveReglements,
        addReglementsFromPaiement: addReglementsFromPaiement,
        addReglement: addReglement,
        deleteReglement: deleteReglement,
        updateReglementStatut: updateReglementStatut,
        getBons: getBons,
        saveBons: saveBons,
        addBon: addBon,
        updateBon: updateBon,
        getBon: getBon,
        deleteBon: deleteBon,
        nextBonNumero: nextBonNumero,
        getBonsNonSoldes: getBonsNonSoldes,
        getClientsNonSoldes: getClientsNonSoldes,
        getBonsNonSoldesByClient: getBonsNonSoldesByClient,
        getBonsByClient: getBonsByClient,
        getBalanceClients: getBalanceClients,
        getBalanceTotals: getBalanceTotals,
        getTotalSolde: getTotalSolde,
        getDashboardStats: getDashboardStats,
        applyPaiementToBon: applyPaiementToBon,
        formatDateFR: formatDateFR,
        fmtMoney: fmtMoney,
        initFromServer: initFromServer
    };
})(window);
