<?php
/** Page de récupération d'urgence des bons locaux (localStorage → serveur). */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Récupération ventes — LibAutoEnt</title>
    <style>
        body { font-family: Segoe UI, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; color: #1a1a1a; }
        h1 { font-size: 1.35rem; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; background: #fafafa; }
        .ok { color: #0a7a2f; font-weight: 600; }
        .bad { color: #b00020; font-weight: 600; }
        button { font-size: 1rem; padding: 0.6rem 1rem; margin: 0.35rem 0.35rem 0.35rem 0; cursor: pointer; }
        #log { white-space: pre-wrap; font-size: 0.9rem; background: #111; color: #d7ffd7; padding: 1rem; min-height: 8rem; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { border-bottom: 1px solid #ddd; padding: 0.35rem; text-align: left; }
    </style>
</head>
<body>
    <h1>Récupération des ventes (local → serveur)</h1>
    <p>À ouvrir <strong>uniquement sur le PC / téléphone de caisse</strong> où les ventes du jour ont été saisies.
        Ne pas vider le cache avant.</p>

    <div class="box" id="summary">Analyse en cours…</div>

    <div>
        <button type="button" id="btnPush">1. Renvoyer toutes les ventes locales au serveur</button>
        <button type="button" id="btnDl">2. Télécharger une copie de secours (JSON)</button>
        <a href="{{ route('dashboard') }}"><button type="button">Retour tableau de bord</button></a>
    </div>

    <h2>Bons locaux du 03/09/2026</h2>
    <div class="box" style="overflow:auto;max-height:280px">
        <table>
            <thead><tr><th>N°</th><th>Client</th><th>Montant</th><th>Lignes</th></tr></thead>
            <tbody id="rows"></tbody>
        </table>
    </div>

    <h2>Journal</h2>
    <div id="log"></div>

    <script src="{{ asset('js/data-sync.js') }}?v=11"></script>
    <script>
        var KEY = 'libautoent_bons_vente';
        var logEl = document.getElementById('log');
        function log(msg) {
            logEl.textContent += msg + '\n';
        }

        function readBons() {
            try {
                var raw = localStorage.getItem(KEY);
                var data = raw ? JSON.parse(raw) : [];
                return Array.isArray(data) ? data : [];
            } catch (e) {
                return [];
            }
        }

        function isSep3(b) {
            var d = String((b && b.date) || '');
            return d.indexOf('03/09/2026') === 0 || d.indexOf('2026-09-03') === 0;
        }

        function byDate(bons) {
            var map = {};
            bons.forEach(function (b) {
                var d = String((b && b.date) || '?');
                map[d] = (map[d] || 0) + 1;
            });
            return map;
        }

        function refresh() {
            var bons = readBons();
            var d03 = bons.filter(isSep3);
            var map = byDate(bons);
            var dates = Object.keys(map).sort();
            var html = '<p>Total local : <strong>' + bons.length + '</strong> bons</p>';
            html += '<p>Dont <strong class="' + (d03.length >= 20 ? 'ok' : 'bad') + '">' + d03.length + '</strong> datés du 03/09/2026</p>';
            html += '<p>Répartition : ' + dates.map(function (d) { return d + '=' + map[d]; }).join(' · ') + '</p>';
            if (d03.length < 20) {
                html += '<p class="bad">Si ce chiffre est bas, ce navigateur n’a plus les ventes de la journée (déjà écrasé). Essayez l’autre PC / Chrome / téléphone utilisé ce matin.</p>';
            } else {
                html += '<p class="ok">Ce navigateur contient encore beaucoup de ventes du 03/09 — cliquez « Renvoyer ».</p>';
            }
            document.getElementById('summary').innerHTML = html;

            var tb = document.getElementById('rows');
            tb.innerHTML = d03.map(function (b) {
                var n = (b.lignes && b.lignes.length) || 0;
                return '<tr><td>' + (b.numero || '') + '</td><td>' + (b.client || '') + '</td><td>' + (b.montant != null ? b.montant : '') + '</td><td>' + n + '</td></tr>';
            }).join('') || '<tr><td colspan="4">Aucun bon local du 03/09</td></tr>';
        }

        document.getElementById('btnDl').addEventListener('click', function () {
            var bons = readBons();
            var blob = new Blob([JSON.stringify(bons, null, 2)], { type: 'application/json' });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'libautoent_bons_vente_local_' + Date.now() + '.json';
            a.click();
            log('Téléchargement de ' + bons.length + ' bons.');
        });

        document.getElementById('btnPush').addEventListener('click', function () {
            var bons = readBons();
            if (!bons.length) {
                log('Rien à envoyer : localStorage vide.');
                return;
            }
            if (!window.DataSync || !DataSync.pushKeyFromLocal) {
                log('DataSync indisponible.');
                return;
            }
            log('Envoi de ' + bons.length + ' bons (fusion serveur)…');
            DataSync.pushKeyFromLocal(KEY).then(function (res) {
                log('Résultat push : ' + JSON.stringify(res || {}));
                return fetch('/api/store/' + encodeURIComponent(KEY), {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                }).then(function (r) { return r.json(); });
            }).then(function (remote) {
                var list = Array.isArray(remote) ? remote : [];
                var n03 = list.filter(isSep3).length;
                log('Serveur maintenant : ' + list.length + ' bons, dont ' + n03 + ' du 03/09.');
                refresh();
            }).catch(function (e) {
                log('Erreur : ' + (e && e.message ? e.message : e));
            });
        });

        refresh();
        log('Page prête. Navigateur : ' + navigator.userAgent);
    </script>
</body>
</html>
