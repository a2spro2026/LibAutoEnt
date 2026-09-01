<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Connexion — LibAutoEnt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0d1b2a;
            --navy-deep: #07111c;
            --navy-soft: #14213d;
            --gold: #fca311;
            --gold-bright: #ffb83a;
            --gold-deep: #e8920a;
            --gold-soft: rgba(252, 163, 17, 0.35);
            --ink: #0d1b2a;
            --muted: rgba(13, 27, 42, 0.62);
            --panel: #ffffff;
            --panel-border: rgba(13, 27, 42, 0.08);
            --field: #f4f6f9;
            --field-focus: #fffaf0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            min-height: 100vh;
            overflow-x: hidden;
            background: var(--navy-deep);
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: clamp(1.25rem, 4vw, 3rem);
            position: relative;
            isolation: isolate;
        }

        .login-page::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                url('{{ asset('images/login-bg.png') }}') center center / cover no-repeat;
            animation: bg-breathe 20s ease-in-out infinite alternate;
        }

        .login-page::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(
                    105deg,
                    rgba(7, 17, 28, 0.12) 0%,
                    rgba(7, 17, 28, 0.05) 48%,
                    rgba(255, 255, 255, 0.08) 72%,
                    transparent 100%
                );
            pointer-events: none;
        }

        @keyframes bg-breathe {
            from { transform: scale(1); }
            to { transform: scale(1.03); }
        }

        .login-panel {
            width: min(100%, 400px);
            margin-right: clamp(0.5rem, 5vw, 4.5rem);
            padding: clamp(1.75rem, 3.5vw, 2.4rem);
            border-radius: 28px 28px 36px 28px;
            background: var(--panel);
            border: 1px solid var(--panel-border);
            box-shadow:
                0 0 0 1px rgba(252, 163, 17, 0.12),
                0 18px 40px rgba(7, 17, 28, 0.28),
                0 0 48px var(--gold-soft);
            animation: panel-in 0.7s cubic-bezier(0.22, 1, 0.36, 1) both,
                       glow-pulse 5s ease-in-out infinite;
            position: relative;
            overflow: hidden;
        }

        .login-panel::before {
            content: '';
            position: absolute;
            left: 1.25rem;
            right: 1.25rem;
            top: 0;
            height: 3px;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(90deg, var(--navy) 0%, var(--gold) 55%, var(--gold-bright) 100%);
        }

        .login-panel::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--navy-soft), var(--navy));
        }

        @keyframes panel-in {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes glow-pulse {
            0%, 100% {
                box-shadow:
                    0 0 0 1px rgba(252, 163, 17, 0.12),
                    0 18px 40px rgba(7, 17, 28, 0.28),
                    0 0 40px rgba(252, 163, 17, 0.22);
            }
            50% {
                box-shadow:
                    0 0 0 1px rgba(252, 163, 17, 0.28),
                    0 20px 48px rgba(7, 17, 28, 0.32),
                    0 0 64px rgba(252, 163, 17, 0.38);
            }
        }

        .panel-header {
            margin-bottom: 1.75rem;
        }

        .panel-header h1 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.55rem, 3vw, 1.85rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.15;
            color: var(--navy);
        }

        .panel-header h1 span {
            color: var(--gold);
        }

        .panel-header p {
            margin-top: 0.45rem;
            color: var(--muted);
            font-size: 0.95rem;
            font-weight: 400;
        }

        .field {
            margin-bottom: 1.15rem;
        }

        .field label {
            display: block;
            margin-bottom: 0.45rem;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: rgba(13, 27, 42, 0.72);
        }

        .field input,
        .field select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            border: 1px solid rgba(13, 27, 42, 0.14);
            background: var(--field);
            color: var(--navy);
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-family: inherit;
            font-size: 0.98rem;
            outline: none;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .field select {
            background-image:
                linear-gradient(45deg, transparent 50%, var(--navy) 50%),
                linear-gradient(135deg, var(--navy) 50%, transparent 50%);
            background-position:
                calc(100% - 18px) calc(50% - 3px),
                calc(100% - 12px) calc(50% - 3px);
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
            padding-right: 2.4rem;
            cursor: pointer;
        }

        .field select option {
            color: var(--navy);
            background: #fff;
        }

        .field input::placeholder {
            color: rgba(13, 27, 42, 0.35);
        }

        .field input:focus,
        .field select:focus {
            border-color: var(--gold);
            background: var(--field-focus);
            box-shadow: 0 0 0 3px rgba(252, 163, 17, 0.22), 0 0 16px rgba(252, 163, 17, 0.18);
        }

        .password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrap input {
            width: 100%;
            padding-right: 2.75rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.55rem;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: rgba(13, 27, 42, 0.55);
            cursor: pointer;
            display: grid;
            place-items: center;
            padding: 0;
        }
        .toggle-password:hover {
            color: var(--navy);
            background: rgba(13, 27, 42, 0.06);
        }
        .toggle-password svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }
        .toggle-password .icon-hide { display: none; }
        .toggle-password.is-visible .icon-show { display: none; }
        .toggle-password.is-visible .icon-hide { display: block; }

        .submit-btn {
            width: 100%;
            margin-top: 0.5rem;
            border: none;
            border-radius: 12px;
            padding: 0.95rem 1.25rem;
            font-family: 'Outfit', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--navy-deep);
            cursor: pointer;
            background: linear-gradient(135deg, var(--gold-bright) 0%, var(--gold) 48%, var(--gold-deep) 100%);
            box-shadow:
                0 0 20px rgba(252, 163, 17, 0.4),
                0 8px 20px rgba(7, 17, 28, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow:
                0 0 28px rgba(252, 163, 17, 0.55),
                0 10px 24px rgba(7, 17, 28, 0.22);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .panel-footer {
            margin-top: 1.35rem;
            margin-bottom: 0.35rem;
            text-align: center;
            font-size: 0.82rem;
            color: rgba(13, 27, 42, 0.45);
        }

        .brand-mark {
            display: none;
        }

        @media (max-width: 768px) {
            .login-page {
                flex-direction: column;
                justify-content: flex-end;
                align-items: center;
                min-height: 100dvh;
                min-height: 100svh;
                padding: max(0.65rem, env(safe-area-inset-top)) 1.15rem max(1.35rem, env(safe-area-inset-bottom));
                gap: 0;
            }

            .brand-mark {
                display: none;
            }

            .login-page::before {
                background-position: left center;
                background-size: cover;
                transform: none;
                animation: none;
            }

            .login-page::after {
                background: linear-gradient(
                    180deg,
                    rgba(7, 17, 28, 0.15) 0%,
                    rgba(7, 17, 28, 0.35) 45%,
                    rgba(7, 17, 28, 0.72) 100%
                );
            }

            .login-panel {
                flex-shrink: 0;
                margin-right: 0;
                width: min(100%, 380px);
                padding: 1.35rem 1.25rem 1.5rem;
                background: rgba(255, 255, 255, 0.96);
            }

            .panel-header {
                margin-bottom: 1.05rem;
            }

            .panel-header h1 {
                font-size: 1.28rem;
            }

            .panel-header h1 span {
                display: inline;
            }

            .panel-header p {
                font-size: 0.88rem;
            }

            .field {
                margin-bottom: 0.85rem;
            }

            .field input,
            .field select {
                padding: 0.75rem 0.9rem;
            }

            .panel-footer {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-page::before,
            .login-panel {
                animation: none;
            }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <main class="login-page">
        <div class="brand-mark">
            <img src="{{ asset('images/brand-logo.png') }}" alt="LibAutoEnt — La Solution qui Gère" width="520" height="420">
        </div>
        <form class="login-panel" method="POST" action="{{ url('/login') }}" autocomplete="off" novalidate>
            @csrf
            <input type="text" name="decoy_user" value="" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;" tabindex="-1" autocomplete="username" aria-hidden="true">
            <input type="password" name="decoy_pass" value="" style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;" tabindex="-1" autocomplete="current-password" aria-hidden="true">
            <header class="panel-header">
                <h1>Connexion <span>LibAutoEnt</span></h1>
                <p>Accédez à votre espace de gestion</p>
            </header>

            <div class="field">
                <label for="statut">Statue</label>
                <select id="statut" name="statut" required autocomplete="off">
                    <option value="" selected disabled>Sélectionner…</option>
                    <option value="gerant">Gérant</option>
                    <option value="assis">Assis</option>
                    <option value="vendeur">Vendeur</option>
                </select>
            </div>

            <div class="field">
                <label for="login">Login</label>
                <input
                    type="text"
                    id="login"
                    name="login"
                    required
                    autocomplete="off"
                    autocapitalize="off"
                    autocorrect="off"
                    spellcheck="false"
                    placeholder="Votre identifiant"
                    readonly
                >
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Mot de passe"
                        readonly
                    >
                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Afficher le mot de passe" title="Afficher / masquer">
                        <svg class="icon-show" viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="icon-hide" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-6 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c6 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-btn">Se connecter</button>

            <p class="panel-footer">Gestion du stock et des ventes</p>
        </form>
    </main>
    <script>
        (function () {
            var login = document.getElementById('login');
            var password = document.getElementById('password');
            var statut = document.getElementById('statut');
            var btn = document.getElementById('togglePassword');

            function unlockField(el) {
                if (el && el.hasAttribute('readonly')) {
                    el.removeAttribute('readonly');
                }
            }

            [login, password].forEach(function (el) {
                if (!el) return;
                el.addEventListener('focus', function () { unlockField(el); });
                el.value = '';
            });
            if (statut) statut.selectedIndex = 0;

            var form = document.querySelector('.login-panel');
            if (form) {
                form.addEventListener('submit', function () {
                    if (!statut || !statut.value) return;
                    try {
                        sessionStorage.setItem('libautoent_statut', String(statut.value).toLowerCase());
                    } catch (e) { /* ignore */ }
                });
            }

            if (!password || !btn) return;
            btn.addEventListener('click', function () {
                unlockField(password);
                var show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                btn.classList.toggle('is-visible', show);
                btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            });
        })();
    </script>
</body>
</html>
