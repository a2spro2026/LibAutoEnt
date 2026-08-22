/**
 * Données démo LOCAL uniquement — activer avec ?seedDemo=1
 * Exemple : http://127.0.0.1:8000/dashboard?seedDemo=1
 * Ne s’exécute jamais hors localhost (protège la prod).
 */
(function (window) {
    'use strict';

    function isLocalHost() {
        var h = (window.location && window.location.hostname) || '';
        return h === 'localhost' || h === '127.0.0.1' || h === '::1';
    }

    function wantSeed() {
        try {
            return /(?:\?|&)seedDemo=1(?:&|$)/.test(window.location.search || '');
        } catch (e) {
            return false;
        }
    }

    if (!isLocalHost() || !wantSeed()) {
        return;
    }

    var KEY_CATALOG = 'libautoent_catalogue_produits';
    var KEY_BONS = 'libautoent_bons_vente';

    var products = [
        { ref: 'PR-0001', codeBarre: '610000000001', designation: 'Filtre à huile premium', categorie: 'Filtres', famille: 'Entretien', quantite: 40, pa: 35, pv: 55 },
        { ref: 'PR-0002', codeBarre: '610000000002', designation: 'Filtre à air moteur', categorie: 'Filtres', famille: 'Entretien', quantite: 35, pa: 45, pv: 70 },
        { ref: 'PR-0003', codeBarre: '610000000003', designation: 'Plaquettes frein avant', categorie: 'Freinage', famille: 'Sécurité', quantite: 28, pa: 120, pv: 190 },
        { ref: 'PR-0004', codeBarre: '610000000004', designation: 'Plaquettes frein arrière', categorie: 'Freinage', famille: 'Sécurité', quantite: 30, pa: 95, pv: 150 },
        { ref: 'PR-0005', codeBarre: '610000000005', designation: 'Disque de frein ventilé', categorie: 'Freinage', famille: 'Sécurité', quantite: 16, pa: 210, pv: 320 },
        { ref: 'PR-0006', codeBarre: '610000000006', designation: 'Bougie d’allumage iridium', categorie: 'Allumage', famille: 'Moteur', quantite: 80, pa: 28, pv: 45 },
        { ref: 'PR-0007', codeBarre: '610000000007', designation: 'Courroie de distribution', categorie: 'Transmission', famille: 'Moteur', quantite: 12, pa: 180, pv: 280 },
        { ref: 'PR-0008', codeBarre: '610000000008', designation: 'Kit d’embrayage complet', categorie: 'Transmission', famille: 'Boîte', quantite: 8, pa: 650, pv: 980 },
        { ref: 'PR-0009', codeBarre: '610000000009', designation: 'Amortisseur avant gauche', categorie: 'Suspension', famille: 'Train', quantite: 10, pa: 320, pv: 480 },
        { ref: 'PR-0010', codeBarre: '610000000010', designation: 'Amortisseur avant droit', categorie: 'Suspension', famille: 'Train', quantite: 10, pa: 320, pv: 480 },
        { ref: 'PR-0011', codeBarre: '610000000011', designation: 'Batterie 12V 70Ah', categorie: 'Électricité', famille: 'Énergie', quantite: 14, pa: 520, pv: 780 },
        { ref: 'PR-0012', codeBarre: '610000000012', designation: 'Alternateur 120A', categorie: 'Électricité', famille: 'Énergie', quantite: 6, pa: 890, pv: 1350 },
        { ref: 'PR-0013', codeBarre: '610000000013', designation: 'Pompe à eau', categorie: 'Refroidissement', famille: 'Moteur', quantite: 11, pa: 240, pv: 370 },
        { ref: 'PR-0014', codeBarre: '610000000014', designation: 'Radiateur aluminium', categorie: 'Refroidissement', famille: 'Moteur', quantite: 5, pa: 780, pv: 1150 },
        { ref: 'PR-0015', codeBarre: '610000000015', designation: 'Huile moteur 5W40 5L', categorie: 'Lubrifiants', famille: 'Entretien', quantite: 50, pa: 95, pv: 145 },
        { ref: 'PR-0016', codeBarre: '610000000016', designation: 'Liquide frein DOT4 1L', categorie: 'Lubrifiants', famille: 'Sécurité', quantite: 40, pa: 25, pv: 42 },
        { ref: 'PR-0017', codeBarre: '610000000017', designation: 'Essuie-glace avant 24"', categorie: 'Accessoires', famille: 'Visibilité', quantite: 25, pa: 40, pv: 65 },
        { ref: 'PR-0018', codeBarre: '610000000018', designation: 'Phare avant halogène', categorie: 'Éclairage', famille: 'Visibilité', quantite: 18, pa: 160, pv: 250 },
        { ref: 'PR-0019', codeBarre: '610000000019', designation: 'Capteur ABS roue', categorie: 'Électricité', famille: 'Sécurité', quantite: 22, pa: 110, pv: 175 },
        { ref: 'PR-0020', codeBarre: '610000000020', designation: 'Joint de culasse', categorie: 'Moteur', famille: 'Joint', quantite: 9, pa: 145, pv: 230 }
    ].map(function (p, i) {
        return {
            id: 'p_demo_' + String(i + 1).padStart(2, '0'),
            ref: p.ref,
            codeBarre: p.codeBarre,
            designation: p.designation,
            categorie: p.categorie,
            famille: p.famille,
            quantite: p.quantite,
            pa: p.pa,
            pv: p.pv,
            photo: ''
        };
    });

    var clients = [
        'Garage Atlas', 'Auto Plus Casa', 'Société Najah', 'Garage El Amal',
        'Transport Rapid', 'Client Comptoir', 'Fleet Maroc', 'Atelier Pro'
    ];
    var modes = ['Espèce', 'Crédit', 'Chèque', 'Virement'];

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function dateFr(dayOffset) {
        var d = new Date();
        d.setDate(d.getDate() - (dayOffset || 0));
        return pad(d.getDate()) + '/' + pad(d.getMonth() + 1) + '/' + d.getFullYear();
    }

    var bons = [];
    for (var i = 0; i < 20; i++) {
        var p1 = products[i % products.length];
        var p2 = products[(i + 3) % products.length];
        var q1 = 1 + (i % 4);
        var q2 = 1 + ((i + 1) % 3);
        var lignes = [
            {
                produitId: p1.id,
                ref: p1.ref,
                codeBarre: p1.codeBarre,
                designation: p1.designation,
                qte: q1,
                pu: p1.pv,
                sousTotal: q1 * p1.pv
            },
            {
                produitId: p2.id,
                ref: p2.ref,
                codeBarre: p2.codeBarre,
                designation: p2.designation,
                qte: q2,
                pu: p2.pv,
                sousTotal: q2 * p2.pv
            }
        ];
        var montant = lignes[0].sousTotal + lignes[1].sousTotal;
        var typePaie = modes[i % modes.length];
        var montantPaye = typePaie === 'Crédit' ? Math.round(montant * 0.4 * 100) / 100 : montant;
        var solde = Math.max(0, Math.round((montant - montantPaye) * 100) / 100);
        bons.push({
            id: 'bonv_demo_' + String(i + 1).padStart(2, '0'),
            date: dateFr(i),
            numero: 'BL' + String(i + 1).padStart(4, '0'),
            client: clients[i % clients.length],
            montant: montant,
            typePaie: typePaie,
            montantPaye: montantPaye,
            solde: solde,
            lignes: lignes
        });
    }

    try {
        window.localStorage.setItem(KEY_CATALOG, JSON.stringify(products));
        window.localStorage.setItem(KEY_BONS, JSON.stringify(bons));
        window.sessionStorage.setItem('libautoent_seed_demo_ok', '1');
    } catch (e) {
        window.alert('Impossible d’écrire les données démo (stockage local).');
        return;
    }

    // Pousser vers l’API locale si dispo (sans toucher la prod : localhost only)
    function push(key, data) {
        if (!window.DataSync || !DataSync.pushKey) return Promise.resolve();
        return DataSync.pushKey(key, data);
    }

    function cleanupUrl() {
        try {
            var u = new URL(window.location.href);
            u.searchParams.delete('seedDemo');
            window.history.replaceState(null, '', u.pathname + (u.search || '') + (u.hash || ''));
        } catch (e) { /* ignore */ }
    }

    Promise.all([
        push(KEY_CATALOG, products),
        push(KEY_BONS, bons)
    ]).then(function () {
        cleanupUrl();
        window.alert('Démo locale chargée : 20 produits + 20 bons de vente.');
        window.location.reload();
    });
})(window);
