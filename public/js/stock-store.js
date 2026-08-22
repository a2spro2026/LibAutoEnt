/**
 * Stock — catégories & produits issus des bons d'achat / vente
 */
(function (window) {
    'use strict';

    var KEY_META = 'libautoent_stock_meta';
    var KEY_CAT = 'libautoent_stock_categories';
    var KEY_CATALOG = 'libautoent_catalogue_produits';
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
        var year = new Date().getFullYear();

        function ensure(nom) {
            var name = normName(nom);
            if (!name) return null;
            var k = keyOf(name);
            if (!map[k]) {
                map[k] = {
                    nom: name,
                    achat: 0,
                    vente: 0,
                    venteMois: 0,
                    ventesParMois: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                };
            }
            return k;
        }

        function bump(nom, field, qte) {
            var k = ensure(nom);
            if (!k) return;
            map[k][field] += Number(qte) || 0;
        }

        read(KEY_BONS_ACHAT).forEach(function (bon) {
            (bon.lignes || []).forEach(function (l) {
                bump(l.produit, 'achat', l.qte);
            });
        });

        read(KEY_BONS_VENTE).forEach(function (bon) {
            var d = parseDateFR(bon.date);
            var moisCourant = isCurrentMonth(bon.date);
            var moisIdx = (d && !isNaN(d.getTime()) && d.getFullYear() === year) ? d.getMonth() : -1;
            (bon.lignes || []).forEach(function (l) {
                var k = ensure(l.produit);
                if (!k) return;
                var q = Number(l.qte) || 0;
                map[k].vente += q;
                if (moisCourant) map[k].venteMois += q;
                if (moisIdx >= 0) map[k].ventesParMois[moisIdx] += q;
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

    function asArray(data) {
        return Array.isArray(data) ? data : [];
    }

    function readCatalog() {
        try {
            var raw = localStorage.getItem(KEY_CATALOG);
            var data = raw ? JSON.parse(raw) : [];
            if (!Array.isArray(data)) {
                localStorage.setItem(KEY_CATALOG, '[]');
                return [];
            }
            return data;
        } catch (e) {
            return [];
        }
    }

    function writeCatalog(list) {
        var safe = asArray(list);
        try {
            localStorage.setItem(KEY_CATALOG, JSON.stringify(safe));
        } catch (e) {
            console.error('Catalogue localStorage', e);
            throw new Error('Espace de stockage insuffisant (photo trop lourde). Réessayez avec une image plus légère.');
        }
        if (window.DataSync) {
            DataSync.pushKey(KEY_CATALOG, safe);
        }
    }

    function initCatalogFromServer() {
        if (!window.DataSync) {
            return Promise.resolve();
        }
        return DataSync.pullKey(KEY_CATALOG).then(function (data) {
            if (data != null && !Array.isArray(data)) {
                writeCatalog([]);
            }
            return readCatalog();
        });
    }

    function uid() {
        return 'p_' + Date.now().toString(36) + '_' + Math.random().toString(36).slice(2, 8);
    }

    function numOrZero(v) {
        var n = parseFloat(String(v == null ? '' : v).replace(',', '.'));
        return isNaN(n) ? 0 : n;
    }

    function fmtMoney(n) {
        return (Math.round((Number(n) || 0) * 100) / 100).toFixed(2);
    }

    function nextCatalogRef() {
        var list = readCatalog();
        var max = 0;
        list.forEach(function (p) {
            var ref = p && p.ref;
            if (!ref) return;
            var m = String(ref).match(/(\d+)$/);
            if (m) max = Math.max(max, parseInt(m[1], 10) || 0);
        });
        return 'PR-' + String(max + 1).padStart(4, '0');
    }

    function normalizeProduit(payload, existing) {
        var base = existing || {};
        var ref = String(payload.ref || base.ref || '').trim();
        if (!ref) ref = nextCatalogRef();
        var photo = payload.photo;
        if (photo === undefined) photo = base.photo || '';
        else photo = String(photo || '');
        return {
            id: base.id || uid(),
            ref: ref,
            codeBarre: String(payload.codeBarre || '').trim().toUpperCase(),
            designation: normName(payload.designation || payload.nom || ''),
            categorie: normName(payload.categorie || ''),
            famille: normName(payload.famille || ''),
            quantite: Math.max(0, numOrZero(payload.quantite)),
            pa: Math.max(0, numOrZero(payload.pa)),
            pv: Math.max(0, numOrZero(payload.pv)),
            photo: photo
        };
    }

    function getCatalogue() {
        return readCatalog().slice().sort(function (a, b) {
            return (a.ref || '').localeCompare(b.ref || '', 'fr');
        });
    }

    function getProduit(id) {
        var list = readCatalog();
        for (var i = 0; i < list.length; i++) {
            if (list[i].id === id) return list[i];
        }
        return null;
    }

    function saveProduit(payload) {
        var list = readCatalog();
        var id = payload && payload.id ? String(payload.id) : '';
        var idx = -1;
        if (id) {
            for (var i = 0; i < list.length; i++) {
                if (list[i].id === id) { idx = i; break; }
            }
        }
        var item = normalizeProduit(payload || {}, idx >= 0 ? list[idx] : null);
        if (!item.designation) return null;
        if (idx >= 0) list[idx] = item;
        else list.push(item);
        writeCatalog(list);
        return item;
    }

    function deleteProduit(id) {
        var list = readCatalog();
        var next = list.filter(function (p) { return p.id !== id; });
        if (next.length === list.length) return false;
        writeCatalog(next);
        return true;
    }

    /** État Produit : Réf, Désignation, Stock Initial, ventes Jan–Déc, Qte Actuel */
    function getEtatResume() {
        var emptyMois = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        var moves = collectMovements();
        var catalog = getCatalogue();
        var seen = {};
        var list = [];

        function rowFrom(id, ref, designation, stockInitial, mv) {
            mv = mv || {};
            var ventesParMois = (mv.ventesParMois || emptyMois).slice();
            var achat = Number(mv.achat) || 0;
            var vente = Number(mv.vente) || 0;
            var stock = Number(stockInitial) || 0;
            return {
                id: id,
                ref: ref || '',
                designation: designation || '',
                stockInitial: stock,
                ventesParMois: ventesParMois,
                venteTousMois: vente,
                qteActuel: stock + achat - vente
            };
        }

        catalog.forEach(function (p) {
            var k = keyOf(p.designation);
            seen[k] = true;
            list.push(rowFrom(p.id, p.ref, p.designation, p.quantite, moves[k]));
        });

        syncProducts();
        var meta = getMeta();
        Object.keys(meta).forEach(function (k) {
            if (seen[k]) return;
            var m = meta[k];
            list.push(rowFrom(m.ref || k, m.ref, m.nom, m.qteInitial, moves[k]));
        });

        return list.sort(function (a, b) {
            return (a.ref || '').localeCompare(b.ref || '', 'fr');
        });
    }

    window.StockStore = {
        getCategories: getCategories,
        getEtatProduits: getEtatProduits,
        getEtatResume: getEtatResume,
        setQteInitial: setQteInitial,
        setStatut: setStatut,
        syncProducts: syncProducts,
        syncCategories: syncCategories,
        initCatalogFromServer: initCatalogFromServer,
        getCatalogue: getCatalogue,
        getProduit: getProduit,
        saveProduit: saveProduit,
        deleteProduit: deleteProduit,
        nextCatalogRef: nextCatalogRef,
        fmtMoney: fmtMoney,
        FAIBLE_SEUIL: FAIBLE_SEUIL,
        MOIS_LABELS: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc']
    };
})(window);
