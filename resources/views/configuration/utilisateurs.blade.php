<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utilisateurs — LibAutoEnt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}?v=2">
    <style>
        .menu-link--quiet { background: transparent; box-shadow: none; color: rgba(255,255,255,0.82); }
        .menu-link--quiet:hover { background: rgba(252,163,17,0.12); color: #fff; transform: none; }
        .menu-link--quiet .menu-ico { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.08); box-shadow: none; }
        .submenu a.is-active { color: #fff; background: rgba(252,163,17,0.16); }
        .submenu a.is-active::before { background: var(--green-bright); }

        .page-wrap { flex: 1; padding: 0 1.5rem 1.5rem; min-width: 0; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 1rem; justify-content: flex-end; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.7rem 1.15rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s, filter 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 18px rgba(252,163,17,0.35), 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright));
            box-shadow: 0 0 16px rgba(252,163,17,0.35);
        }
        .btn-ghost {
            color: var(--ink-soft); background: #eef2f7;
            border: 1px solid rgba(13,27,42,0.1);
            box-shadow: none; padding: 0.45rem 0.8rem; font-size: 0.78rem;
        }
        .btn-ghost:hover { background: #e4e9f0; transform: none; }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(252,163,17,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 1080px; }
        .data-table th {
            padding: 0.85rem 0.75rem; font-family: 'Outfit', sans-serif;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.03em;
            text-transform: uppercase; color: var(--muted); background: #f4f7fb;
            text-align: center; border-bottom: 1px solid rgba(13,27,42,0.08);
        }
        .data-table td {
            padding: 0.8rem 0.75rem; font-size: 0.88rem;
            border-bottom: 1px solid rgba(21,32,20,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252,163,17,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .nom-cell { font-weight: 600; text-align: left !important; padding-left: 1rem !important; }
        .user-cell { display: flex; flex-direction: column; align-items: flex-start; gap: 0.15rem; }
        .user-cell small { font-weight: 500; color: var(--muted); font-size: 0.72rem; }

        .actions { display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 2; }
        .icon-btn {
            width: 32px; height: 32px; border-radius: 9px;
            border: 1px solid rgba(21,32,20,0.1); background: #f4f7f0; color: var(--ink-soft);
            display: inline-grid; place-items: center; cursor: pointer;
            pointer-events: auto !important; position: relative; z-index: 10;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
        }
        .icon-btn svg, .icon-btn svg * {
            width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8;
            pointer-events: none !important;
        }
        .icon-btn:hover { box-shadow: 0 4px 12px rgba(21,32,20,0.1); }
        .icon-btn.icon-view:hover { background: rgba(61,126,166,0.12); color: #3d7ea6; }
        .icon-btn.icon-edit:hover { background: rgba(201,162,39,0.15); color: #a8861a; }
        .icon-btn.icon-delete:hover { background: rgba(184,92,56,0.12); color: #b85c38; }
        .icon-btn.icon-suspend:hover { background: rgba(245,127,23,0.14); color: #e65100; }

        .statut-badge {
            display: inline-block; min-width: 96px; padding: 0.35rem 0.7rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.03em; text-transform: uppercase;
        }
        .statue-gerant { color: #8a6d0a; background: rgba(252,163,17,0.2); box-shadow: inset 0 0 0 1px rgba(252,163,17,0.45); }
        .statue-assis { color: #1565c0; background: rgba(33,150,243,0.16); box-shadow: inset 0 0 0 1px rgba(33,150,243,0.35); }
        .statue-vendeur { color: #1b5e20; background: rgba(76,175,80,0.18); box-shadow: inset 0 0 0 1px rgba(76,175,80,0.35); }
        .pwd-mask { font-family: monospace; letter-spacing: 0.08em; color: var(--muted); }

        .perm-chip {
            display: inline-flex; align-items: center; gap: 0.35rem;
            padding: 0.32rem 0.65rem; border-radius: 999px;
            background: rgba(20,33,61,0.06); color: var(--ink-soft);
            font-size: 0.72rem; font-weight: 700;
        }
        .perm-chip strong { color: var(--ink); }

        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(10,16,8,0.55); backdrop-filter: blur(4px);
            z-index: 80; display: none; pointer-events: none;
            align-items: flex-start; justify-content: center; padding: 1rem; overflow-y: auto;
        }
        .modal-backdrop.show { display: flex; pointer-events: auto; }
        .modal {
            width: min(100%, 920px); margin: 1.1rem auto; background: var(--white);
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(16,24,14,0.35), 0 0 0 1px rgba(252,163,17,0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.1rem 1.35rem;
            background: linear-gradient(125deg, #14213d, #0d1b2a 55%, #1a2a18); color: #fff;
        }
        .modal-head h2 { font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 700; }
        .modal-head h2 span { color: var(--gold); }
        .modal-body { padding: 1.15rem 1.35rem 1.35rem; background:
            radial-gradient(1200px 280px at 10% -10%, rgba(252,163,17,0.08), transparent 55%),
            #fff;
        }

        .form-section {
            margin-bottom: 1.15rem;
            border: 1px solid rgba(13,27,42,0.08);
            border-radius: 16px;
            background: rgba(244,247,251,0.65);
            overflow: hidden;
        }
        .form-section-head {
            display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem;
            padding: 0.9rem 1.05rem;
            border-bottom: 1px solid rgba(13,27,42,0.07);
            background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(244,247,251,0.4));
        }
        .form-section-head h3 {
            margin: 0; font-family: 'Outfit', sans-serif; font-size: 0.95rem; font-weight: 700;
            color: var(--ink); display: flex; align-items: center; gap: 0.55rem;
        }
        .form-section-head h3 .sec-num {
            width: 24px; height: 24px; border-radius: 8px; display: inline-grid; place-items: center;
            font-size: 0.72rem; font-weight: 800; color: #0d1b2a;
            background: linear-gradient(135deg, #ffb83a, #fca311);
            box-shadow: 0 4px 10px rgba(252,163,17,0.28);
        }
        .form-section-head p {
            margin: 0.25rem 0 0; font-size: 0.78rem; color: var(--muted); font-weight: 500;
        }
        .form-section-body { padding: 1rem 1.05rem 1.1rem; }

        .form-grid {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.85rem;
        }
        .field label {
            display: block; margin-bottom: 0.35rem; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .field input, .field select {
            width: 100%; padding: 0.7rem 0.85rem; border-radius: 10px;
            border: 1px solid rgba(21,32,20,0.12); background: #fff;
            font-family: inherit; font-size: 0.92rem; color: var(--ink); outline: none; text-align: center;
        }
        .field input:focus, .field select:focus {
            border-color: rgba(252,163,17,0.65); box-shadow: 0 0 0 3px rgba(252,163,17,0.15);
        }
        .field input.readonly { background: #eef2f7; font-weight: 600; cursor: default; }
        .field-span { grid-column: 1 / -1; }

        .password-wrap { position: relative; display: flex; align-items: center; }
        .password-wrap input { width: 100%; padding-right: 2.75rem; text-align: left; }
        .toggle-password {
            position: absolute; right: 0.4rem; top: 50%; transform: translateY(-50%);
            width: 34px; height: 34px; border: none; border-radius: 8px;
            background: transparent; color: var(--muted); cursor: pointer;
            display: grid; place-items: center; padding: 0;
        }
        .toggle-password:hover { color: var(--ink); background: rgba(21,32,20,0.06); }
        .toggle-password svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; }
        .toggle-password .icon-hide { display: none; }
        .toggle-password.is-visible .icon-show { display: none; }
        .toggle-password.is-visible .icon-hide { display: block; }
        .hint-field { margin-top: 0.3rem; font-size: 0.75rem; color: var(--muted); text-align: left; }

        .role-presets {
            display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0.65rem;
            margin-bottom: 1rem;
        }
        .role-card {
            border: 1px solid rgba(13,27,42,0.1);
            border-radius: 14px; padding: 0.75rem 0.8rem;
            background: #fff; cursor: pointer; text-align: left;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        }
        .role-card:hover { transform: translateY(-1px); border-color: rgba(252,163,17,0.45); }
        .role-card.is-active {
            border-color: rgba(252,163,17,0.7);
            box-shadow: 0 0 0 3px rgba(252,163,17,0.15), 0 8px 18px rgba(13,27,42,0.08);
            background: linear-gradient(180deg, #fff8eb, #fff);
        }
        .role-card strong {
            display: block; font-family: 'Outfit', sans-serif; font-size: 0.88rem;
            color: var(--ink); margin-bottom: 0.2rem;
        }
        .role-card span { font-size: 0.72rem; color: var(--muted); line-height: 1.35; display: block; }

        .perm-toolbar {
            display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;
            justify-content: space-between; margin-bottom: 0.85rem;
        }
        .perm-toolbar .left { display: flex; flex-wrap: wrap; gap: 0.45rem; }
        .perm-count {
            font-size: 0.78rem; font-weight: 700; color: var(--ink-soft);
            padding: 0.35rem 0.7rem; border-radius: 999px; background: rgba(13,27,42,0.06);
        }
        .perm-count em { font-style: normal; color: #c47e00; }

        .perm-grid {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.75rem;
        }
        .perm-module {
            border: 1px solid rgba(13,27,42,0.09);
            border-radius: 14px; background: #fff; overflow: hidden;
            box-shadow: 0 4px 14px rgba(13,27,42,0.04);
        }
        .perm-module-head {
            display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem;
            padding: 0.75rem 0.85rem; background: linear-gradient(135deg, #14213d, #1f3358);
            color: #fff;
        }
        .perm-module-head h4 {
            margin: 0; font-family: 'Outfit', sans-serif; font-size: 0.86rem; font-weight: 700;
        }
        .perm-module-head p {
            margin: 0.2rem 0 0; font-size: 0.7rem; color: rgba(255,255,255,0.72); line-height: 1.35;
        }
        .perm-module-toggle {
            flex-shrink: 0; border: 1px solid rgba(255,255,255,0.2);
            background: rgba(252,163,17,0.18); color: #ffd27a;
            border-radius: 8px; padding: 0.28rem 0.55rem; font-size: 0.68rem;
            font-weight: 700; cursor: pointer; font-family: 'Outfit', sans-serif;
        }
        .perm-module-toggle:hover { background: rgba(252,163,17,0.32); }
        .perm-module-body {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.35rem 0.55rem;
            padding: 0.75rem 0.85rem 0.9rem;
        }
        .perm-check {
            display: flex; align-items: center; gap: 0.45rem;
            padding: 0.4rem 0.45rem; border-radius: 9px;
            cursor: pointer; user-select: none;
            transition: background 0.12s;
        }
        .perm-check:hover { background: rgba(252,163,17,0.08); }
        .perm-check input {
            width: 16px; height: 16px; accent-color: #fca311; cursor: pointer; flex-shrink: 0;
        }
        .perm-check span {
            font-size: 0.8rem; font-weight: 600; color: var(--ink-soft); text-align: left;
        }
        .perm-check input:checked + span { color: var(--ink); }

        .modal-actions {
            display: flex; flex-wrap: wrap; gap: 0.65rem; justify-content: flex-end;
            padding-top: 0.35rem;
        }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .form-grid, .perm-grid, .role-presets { grid-template-columns: 1fr; }
            .perm-module-body { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>
    <div class="app">
        @include('partials.sidebar', ['activePage' => 'utilisateurs', 'openMenu' => 'configuration'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Utilisateurs</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="toolbar">
                    <button type="button" class="btn btn-add" id="btnAjouter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                        Ajouter
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                        Fermer
                    </a>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Nom Complet</th>
                                    <th>Statue</th>
                                    <th>Autorisations</th>
                                    <th>Contact</th>
                                    <th>Login</th>
                                    <th>Mot de Passe</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="usersBody">
                                <tr class="empty-row">
                                    <td colspan="7" class="empty">Aucun utilisateur — cliquez sur Ajouter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="modalUser" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalUserTitle">Nouvel <span>Utilisateur</span></h2>
                <button type="button" class="btn btn-close" id="userModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formUser" autocomplete="off" onsubmit="return false;">
                    <input type="hidden" id="userEditId" value="">
                    <input type="text" name="fakeuser" style="display:none" tabindex="-1" autocomplete="username">
                    <input type="password" name="fakepass" style="display:none" tabindex="-1" autocomplete="current-password">

                    <section class="form-section">
                        <div class="form-section-head">
                            <div>
                                <h3><span class="sec-num">1</span> Identité</h3>
                                <p>Informations de base du compte collaborateur</p>
                            </div>
                        </div>
                        <div class="form-section-body">
                            <div class="form-grid">
                                <div class="field">
                                    <label for="userDate">Date</label>
                                    <input type="date" id="userDate" required>
                                </div>
                                <div class="field">
                                    <label for="userId">ID</label>
                                    <input type="text" id="userId" class="readonly" readonly tabindex="-1">
                                </div>
                                <div class="field">
                                    <label for="userNom">Nom Complet</label>
                                    <input type="text" id="userNom" required autocomplete="off">
                                </div>
                                <div class="field">
                                    <label for="userContact">Contact</label>
                                    <input type="text" id="userContact" autocomplete="off" placeholder="Tél. / e-mail">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <div class="form-section-head">
                            <div>
                                <h3><span class="sec-num">2</span> Accès &amp; authentification</h3>
                                <p>Statue, identifiants de connexion et mot de passe</p>
                            </div>
                        </div>
                        <div class="form-section-body">
                            <div class="role-presets" id="rolePresets">
                                <button type="button" class="role-card" data-statue="Gérant">
                                    <strong>Gérant</strong>
                                    <span>Accès complet : ventes, stock, rapports et configuration.</span>
                                </button>
                                <button type="button" class="role-card" data-statue="Assis">
                                    <strong>Assis</strong>
                                    <span>Exploitation quotidienne sans gestion avancée des comptes.</span>
                                </button>
                                <button type="button" class="role-card" data-statue="Vendeur">
                                    <strong>Vendeur</strong>
                                    <span>Tableau de bord + stock. Suppression désactivée.</span>
                                </button>
                            </div>
                            <div class="form-grid">
                                <div class="field">
                                    <label for="userStatue">Statue</label>
                                    <select id="userStatue" required autocomplete="off">
                                        <option value=""></option>
                                        <option value="Gérant">Gérant</option>
                                        <option value="Assis">Assis</option>
                                        <option value="Vendeur">Vendeur</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="userLogin">Login</label>
                                    <input type="text" id="userLogin" name="user_login_field" required autocomplete="off" placeholder="ex. ahmed">
                                    <div class="hint-field">Suffixe automatique : @LibAutoEnt.com</div>
                                </div>
                                <div class="field field-span">
                                    <label for="userPassword">Mot de Passe</label>
                                    <div class="password-wrap">
                                        <input type="password" id="userPassword" name="user_password_field" minlength="8" required autocomplete="new-password">
                                        <button type="button" class="toggle-password" id="toggleUserPassword" aria-label="Afficher le mot de passe" title="Afficher / masquer">
                                            <svg class="icon-show" viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg class="icon-hide" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-6 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c6 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>
                                        </button>
                                    </div>
                                    <div class="hint-field">Minimum 8 caractères</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <div class="form-section-head">
                            <div>
                                <h3><span class="sec-num">3</span> Autorisations</h3>
                                <p>Cochez les modules et actions autorisés pour cet utilisateur</p>
                            </div>
                        </div>
                        <div class="form-section-body">
                            <div class="perm-toolbar">
                                <div class="left">
                                    <button type="button" class="btn btn-ghost" id="btnPermAll">Tout cocher</button>
                                    <button type="button" class="btn btn-ghost" id="btnPermNone">Tout décocher</button>
                                    <button type="button" class="btn btn-ghost" id="btnPermPreset">Réappliquer le profil statue</button>
                                </div>
                                <div class="perm-count" id="permCount">0 / 0 actives</div>
                            </div>
                            <div class="perm-grid" id="permGrid"></div>
                        </div>
                    </section>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnUserValider">Valider</button>
                        <button type="button" class="btn btn-close" id="btnUserFermer">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=9"></script>
    <script src="{{ asset('js/table-actions.js') }}?v=8"></script>
    <script src="{{ asset('js/users-store.js') }}?v=6"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; }
        menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        const modalUser = document.getElementById('modalUser');
        const modalTitle = document.getElementById('modalUserTitle');
        const permGrid = document.getElementById('permGrid');
        let editMode = false;
        let skipStatuePreset = false;

        function statueClass(s) {
            var v = String(s || '').toLowerCase();
            if (v === 'assis') return 'statue-assis';
            if (v === 'vendeur') return 'statue-vendeur';
            return 'statue-gerant';
        }

        function maskPwd(pwd) {
            var n = Math.max(8, String(pwd || '').length);
            return '•'.repeat(Math.min(n, 12));
        }

        function toIsoDate(fr) {
            if (!fr) return new Date().toISOString().slice(0, 10);
            if (fr.indexOf('-') !== -1) return fr;
            var p = fr.split('/');
            if (p.length === 3) return p[2] + '-' + p[1] + '-' + p[0];
            return new Date().toISOString().slice(0, 10);
        }

        function totalPermKeys() {
            var n = 0;
            (UsersStore.PERMISSION_GROUPS || []).forEach(function (g) {
                n += (g.items || []).length;
            });
            return n;
        }

        function updatePermCount() {
            var boxes = permGrid.querySelectorAll('input[type="checkbox"][data-perm]');
            var on = 0;
            boxes.forEach(function (b) { if (b.checked) on += 1; });
            document.getElementById('permCount').innerHTML =
                '<em>' + on + '</em> / ' + boxes.length + ' actives';
        }

        function setRoleCardActive(statue) {
            document.querySelectorAll('#rolePresets .role-card').forEach(function (card) {
                card.classList.toggle('is-active', card.getAttribute('data-statue') === statue);
            });
        }

        function applyPermissionsMap(map) {
            permGrid.querySelectorAll('input[type="checkbox"][data-perm]').forEach(function (box) {
                var key = box.getAttribute('data-perm');
                box.checked = !!(map && map[key]);
            });
            updatePermCount();
        }

        function collectPermissions() {
            var out = {};
            permGrid.querySelectorAll('input[type="checkbox"][data-perm]').forEach(function (box) {
                out[box.getAttribute('data-perm')] = !!box.checked;
            });
            return out;
        }

        function buildPermGrid() {
            var groups = UsersStore.PERMISSION_GROUPS || [];
            permGrid.innerHTML = groups.map(function (g) {
                var checks = (g.items || []).map(function (it) {
                    return '' +
                        '<label class="perm-check">' +
                        '<input type="checkbox" data-perm="' + it.key + '">' +
                        '<span>' + it.label + '</span>' +
                        '</label>';
                }).join('');
                return '' +
                    '<article class="perm-module" data-group="' + g.id + '">' +
                    '<div class="perm-module-head">' +
                    '<div><h4>' + g.title + '</h4><p>' + (g.hint || '') + '</p></div>' +
                    '<button type="button" class="perm-module-toggle" data-group-toggle="' + g.id + '">Groupe</button>' +
                    '</div>' +
                    '<div class="perm-module-body">' + checks + '</div>' +
                    '</article>';
            }).join('');

            permGrid.querySelectorAll('input[type="checkbox"][data-perm]').forEach(function (box) {
                box.addEventListener('change', updatePermCount);
            });
            permGrid.querySelectorAll('[data-group-toggle]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var gid = btn.getAttribute('data-group-toggle');
                    var module = permGrid.querySelector('.perm-module[data-group="' + gid + '"]');
                    if (!module) return;
                    var boxes = module.querySelectorAll('input[type="checkbox"][data-perm]');
                    var allOn = Array.prototype.every.call(boxes, function (b) { return b.checked; });
                    boxes.forEach(function (b) { b.checked = !allOn; });
                    updatePermCount();
                });
            });
        }

        function renderUsers() {
            var body = document.getElementById('usersBody');
            if (!body || !window.UsersStore) return;
            var list = UsersStore.getUsers();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="7" class="empty">Aucun utilisateur — cliquez sur Ajouter</td></tr>';
                return;
            }
            var total = totalPermKeys();
            body.innerHTML = list.map(function (u) {
                var perms = UsersStore.normalizePermissions(u.permissions, u.statue);
                var n = UsersStore.countEnabled(perms);
                return '' +
                    '<tr data-id="' + u.id + '">' +
                    '<td class="nom-cell"><div class="user-cell"><span>' + (u.nomComplet || '') + '</span><small>' + (u.idCode || '') + ' · ' + (u.date || '') + '</small></div></td>' +
                    '<td><span class="statut-badge ' + statueClass(u.statue) + '">' + (u.statue || '—') + '</span></td>' +
                    '<td><span class="perm-chip"><strong>' + n + '</strong> / ' + total + '</span></td>' +
                    '<td>' + (u.contact || '—') + '</td>' +
                    '<td>' + (u.login || '') + '</td>' +
                    '<td><span class="pwd-mask" title="Mot de passe masqué">' + maskPwd(u.password) + '</span></td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');

            if (window.TableActions) {
                TableActions.fillCells('#usersBody .actions-cell', ['view', 'edit', 'delete', 'suspend']);
            }
        }

        function openModal(user) {
            editMode = !!(user && user.id);
            document.getElementById('userEditId').value = editMode ? user.id : '';
            modalTitle.innerHTML = editMode ? 'Modifier <span>Utilisateur</span>' : 'Nouvel <span>Utilisateur</span>';

            document.getElementById('formUser').reset();
            document.getElementById('userEditId').value = editMode ? user.id : '';

            document.getElementById('userDate').value = editMode ? toIsoDate(user.date) : new Date().toISOString().slice(0, 10);
            document.getElementById('userId').value = editMode ? (user.idCode || '') : UsersStore.nextId();
            document.getElementById('userNom').value = editMode ? (user.nomComplet || '') : '';
            document.getElementById('userContact').value = editMode ? (user.contact || '') : '';

            skipStatuePreset = true;
            document.getElementById('userStatue').value = editMode ? (user.statue || '') : '';
            setRoleCardActive(editMode ? (user.statue || '') : '');
            skipStatuePreset = false;

            document.getElementById('userLogin').value = editMode ? String(user.login || '').replace(/@LibAutoEnt\.com$/i, '') : '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = !editMode;
            document.getElementById('userPassword').placeholder = editMode
                ? 'Laisser vide pour conserver'
                : '';

            if (editMode) {
                applyPermissionsMap(UsersStore.normalizePermissions(user.permissions, user.statue));
            } else {
                applyPermissionsMap(UsersStore.defaultPermissions('Vendeur'));
            }

            modalUser.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modalUser.classList.remove('show');
            document.body.style.overflow = '';
            editMode = false;
        }

        function applyStatuePreset(statue) {
            if (!statue) return;
            skipStatuePreset = true;
            document.getElementById('userStatue').value = statue;
            skipStatuePreset = false;
            setRoleCardActive(statue);
            applyPermissionsMap(UsersStore.defaultPermissions(statue));
        }

        buildPermGrid();

        document.getElementById('btnAjouter').addEventListener('click', function () { openModal(null); });
        document.getElementById('userModalX').addEventListener('click', closeModal);
        document.getElementById('btnUserFermer').addEventListener('click', closeModal);
        modalUser.addEventListener('click', function (e) { if (e.target === modalUser) closeModal(); });

        document.getElementById('rolePresets').addEventListener('click', function (e) {
            var card = e.target.closest('.role-card');
            if (!card) return;
            applyStatuePreset(card.getAttribute('data-statue'));
        });

        document.getElementById('userStatue').addEventListener('change', function () {
            if (skipStatuePreset) return;
            var statue = this.value;
            setRoleCardActive(statue);
            if (statue) applyPermissionsMap(UsersStore.defaultPermissions(statue));
        });

        document.getElementById('btnPermAll').addEventListener('click', function () {
            permGrid.querySelectorAll('input[type="checkbox"][data-perm]').forEach(function (b) { b.checked = true; });
            updatePermCount();
        });
        document.getElementById('btnPermNone').addEventListener('click', function () {
            permGrid.querySelectorAll('input[type="checkbox"][data-perm]').forEach(function (b) { b.checked = false; });
            updatePermCount();
        });
        document.getElementById('btnPermPreset').addEventListener('click', function () {
            var statue = document.getElementById('userStatue').value;
            if (!statue) {
                alert('Sélectionnez d’abord une statue.');
                return;
            }
            applyPermissionsMap(UsersStore.defaultPermissions(statue));
        });

        document.getElementById('btnUserValider').addEventListener('click', function () {
            try {
                var payload = {
                    date: UsersStore.formatDateFR(document.getElementById('userDate').value),
                    idCode: document.getElementById('userId').value.trim(),
                    nomComplet: document.getElementById('userNom').value.trim(),
                    statue: document.getElementById('userStatue').value,
                    contact: document.getElementById('userContact').value.trim(),
                    login: document.getElementById('userLogin').value.trim(),
                    password: document.getElementById('userPassword').value,
                    permissions: collectPermissions()
                };

                if (!payload.statue) {
                    alert('Sélectionnez une statue.');
                    return;
                }
                if (!payload.login) {
                    alert('Saisissez un login.');
                    return;
                }
                if (!editMode && (!payload.password || payload.password.length < 8)) {
                    alert('Saisissez un mot de passe (8 caractères minimum).');
                    return;
                }
                if (editMode && payload.password && payload.password.length < 8) {
                    alert('Le nouveau mot de passe doit contenir au moins 8 caractères.');
                    return;
                }

                if (editMode) {
                    var id = document.getElementById('userEditId').value;
                    if (!payload.password) delete payload.password;
                    UsersStore.updateUser(id, payload);
                } else {
                    UsersStore.addUser(payload);
                }
                renderUsers();
                closeModal();
            } catch (err) {
                alert(err.message || 'Erreur de validation');
            }
        });

        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.getUser(id);
                    if (!u) return;
                    var perms = UsersStore.normalizePermissions(u.permissions, u.statue);
                    var lines = [];
                    (UsersStore.PERMISSION_GROUPS || []).forEach(function (g) {
                        var enabled = (g.items || []).filter(function (it) { return perms[it.key]; })
                            .map(function (it) { return it.label; });
                        if (enabled.length) {
                            lines.push(g.title + ' : ' + enabled.join(', '));
                        }
                    });
                    alert(
                        'ID : ' + (u.idCode || '') + '\n' +
                        'Date : ' + (u.date || '') + '\n' +
                        'Nom : ' + (u.nomComplet || '') + '\n' +
                        'Statue : ' + (u.statue || '') + '\n' +
                        'Statut compte : ' + (u.statut || 'Actif') + '\n' +
                        'Contact : ' + (u.contact || '') + '\n' +
                        'Login : ' + (u.login || '') + '\n\n' +
                        'Autorisations (' + UsersStore.countEnabled(perms) + ') :\n' +
                        (lines.length ? lines.join('\n') : 'Aucune')
                    );
                },
                edit: function (tr) {
                    var u = UsersStore.getUser(tr.getAttribute('data-id'));
                    if (u) openModal(u);
                },
                delete: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.getUser(id);
                    if (!confirm('Supprimer l’utilisateur ' + (u ? u.nomComplet : '') + ' ?')) return;
                    UsersStore.deleteUser(id);
                    renderUsers();
                },
                suspend: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.suspendUser(id);
                    if (u) {
                        alert(u.statut === 'Suspendu'
                            ? 'Utilisateur suspendu.'
                            : 'Utilisateur réactivé.');
                    }
                    renderUsers();
                }
            });
            TableActions.bind(document.getElementById('usersBody'));
        }

        window.onUsersSynced = renderUsers;
        renderUsers();
        if (window.UsersStore && UsersStore.initFromServer) {
            UsersStore.initFromServer().then(renderUsers);
        }

        (function () {
            var input = document.getElementById('userPassword');
            var btn = document.getElementById('toggleUserPassword');
            if (!input || !btn) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('is-visible', show);
                btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            });
        })();
    </script>
</body>
</html>
