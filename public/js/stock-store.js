/**
 * Stock — catégories & produits issus des bons d'achat / vente
 */
(function (window) {
    'use strict';

    var KEY_META = 'libautoent_stock_meta';
    var KEY_CAT = 'libautoent_stock_categories';
    var KEY_BONS_ACHAT = 'libautoent_bons_achat';
    var KEY_BONS_VENTE = 'libautoent_bons_vente';
    var FAIBLE_SEUIL = 5;

    function read(key) {
        try {
            var raw = localStorage.getItem(key);
            if (key === KEY_META || key === KEY_CAT) {
                return raw ? JSON.parse(raw) : {};
            }
            return raw ? JSON.parse(raw) : [];
        } catch (e) {
            return (key === KEY_META || key === KEY_CAT) ? {} : [];
        }
    }

    function write(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }

    function normName(name) {
        return String(name || '').trim().replace(/\s+/g, ' ');
    }

    function keyOf(name) {
        return normName(name).toLowerCase();
    }

    function parseDateFR(s) {
        if (!s || typeof s !== 'string') return null;
        var p = s.split('/');
        if (p.length !== 3) return null;
        return new Date(Number(p[2]), Number(p[1]) - 1, Number(p[0]));
    }

    function isCurrentMonth(dateFr) {
        var d = parseDateFR(dateFr);
        if (!d || isNaN(d.getTime())) return false;
        var now = new Date();
        return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear();
    }

    function getMeta() {
        return read(KEY_META);
    }

    function saveMeta(meta) {
        write(KEY_META, meta);
    }

    function getCatMeta() {
        return read(KEY_CAT);
    }

    function saveCatMeta(meta) {
        write(KEY_CAT, meta);
    }

    function nextRef(meta, prefix) {
        var max = 0;
        Object.keys(meta).forEach(function (k) {
            var ref = meta[k] && meta[k].ref;
            if (!ref) return;
            var m = String(ref).match(/(\d+)$/);
            if (m) max = Math.max(max, parseInt(m[1], 10) || 0);
        });
        return prefix + String(max + 1).padStart(4, '0');
    }

    function ensureProduct(meta, nom) {
        var name = normName(nom);
        if (!name) return null;
        var k = keyOf(name);
        if (!meta[k]) {
            meta[k] = {
                ref: nextRef(meta, 'PR-'),
                nom: name,
                qteInitial: 0,
                statut: 'actif'
            };
        } else if (!meta[k].nom) {
            meta[k].nom = name;
        }
        return meta[k];
    }

    function ensureCategory(meta, nom) {
        var name = normName(nom);
        if (!name) return null;
        var k = keyOf(name);
        if (!meta[k]) {
            meta[k] = {
                ref: nextRef(meta, 'CAT-'),
                nom: name
            };
        } else if (!meta[k].nom) {
            meta[k].nom = name;
        }
        return meta[k];
    }

    /** Collecte les noms de catégorie saisis sur les lignes des bons */
    function collectCategoryNames() {
        var names = {};
        function add(cat) {
            var name = normName(cat);
            if (!name) return;
            names[keyOf(name)] = name;
        }
        read(KEY_BONS_ACHAT).forEach(function (bon) {
            (bon.lignes || []).forEach(function (l) {
                add(l.categorie);
            });
        });
        read(KEY_BONS_VENTE).forEach(function (bon) {
            (bon.lignes || []).forEach(function (l) {
                add(l.categorie);
            });
        });
        return names;
    }

    /** Agrège qté achat / vente depuis les bons (par produit) */
    function collectMovements() {
        var map = {};

        function bump(nom, field, qte) {
            var name = normName(nom);
            if (!name) return;
            var k = keyOf(name);
            if (!map[k]) {
                map[k] = { nom: name, achat: 0, vente: 0, venteMois: 0 };
            }
            map[k][field] += Number(qte) || 0;
        }

        read(KEY_BONS_ACHAT).forEach(function (bon) {
            (bon.lignes || []).forEach(function (l) {
                bump(l.produit, 'achat', l.qte);
            });
        });

        read(KEY_BONS_VENTE).forEach(function (bon) {
            var mois = isCurrentMonth(bon.date);
            (bon.lignes || []).forEach(function (l) {
                bump(l.produit, 'vente', l.qte);
                if (mois) bump(l.produit, 'venteMois', l.qte);
            });
        });

        return map;
    }

    function syncProducts() {
        var meta = getMeta();
        var moves = collectMovements();
        Object.keys(moves).forEach(function (k) {
            ensureProduct(meta, moves[k].nom);
        });
        saveMeta(meta);
        return meta;
    }

    function syncCategories() {
        var meta = getCatMeta();
        var names = collectCategoryNames();
        Object.keys(names).forEach(function (k) {
            ensureCategory(meta, names[k]);
        });
        saveCatMeta(meta);
        return meta;
    }

    /** Liste Catégorie Produit : Réf + Nom (champ Catégorie des bons) */
    function getCategories() {
        syncCategories();
        var meta = getCatMeta();
        return Object.keys(meta).map(function (k) {
            return {
                key: k,
                ref: meta[k].ref,
                nom: meta[k].nom
            };
        }).sort(function (a, b) {
            return (a.ref || '').localeCompare(b.ref || '', 'fr');
        });
    }

    function computeEtat(stockFinal) {
        var s = Number(stockFinal) || 0;
        if (s <= 0) return { value: 'rupture', label: 'Rupture' };
        if (s <= FAIBLE_SEUIL) return { value: 'faible', label: 'Faible' };
        return { value: 'dispo', label: 'Dispo' };
    }

    /** Liste État Produit */
    function getEtatProduits() {
        syncProducts();
        var meta = getMeta();
        var moves = collectMovements();
        var list = [];

        Object.keys(meta).forEach(function (k) {
            var m = meta[k];
            var mv = moves[k] || { achat: 0, vente: 0, venteMois: 0 };
            var qteInitial = Number(m.qteInitial) || 0;
            var achat = Number(mv.achat) || 0;
            var venteMois = Number(mv.venteMois) || 0;
            var vente = Number(mv.vente) || 0;
            var stockFinal = qteInitial + achat - vente;
            var etat = computeEtat(stockFinal);
            list.push({
                key: k,
                ref: m.ref,
                nom: m.nom,
                qteInitial: qteInitial,
                achat: achat,
                venteMois: venteMois,
                stockFinal: stockFinal,
                etat: etat.value,
                etatLabel: etat.label,
                statut: m.statut || 'actif'
            });
        });

        return list.sort(function (a, b) {
            return (a.ref || '').localeCompare(b.ref || '', 'fr');
        });
    }

    function setQteInitial(key, qte) {
        var meta = getMeta();
        if (!meta[key]) return null;
        meta[key].qteInitial = Math.max(0, Number(qte) || 0);
        saveMeta(meta);
        return meta[key];
    }

    function setStatut(key, statut) {
        var meta = getMeta();
        if (!meta[key]) return null;
        meta[key].statut = (statut === 'inactif') ? 'inactif' : 'actif';
        saveMeta(meta);
        return meta[key];
    }

    window.StockStore = {
        getCategories: getCategories,
        getEtatProduits: getEtatProduits,
        setQteInitial: setQteInitial,
        setStatut: setStatut,
        syncProducts: syncProducts,
        syncCategories: syncCategories,
        FAIBLE_SEUIL: FAIBLE_SEUIL
    };
})(window);
