<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Connexion — 7ssabHani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #5a9e2f;
            --green-bright: #8bc34a;
            --green-glow: rgba(139, 195, 74, 0.55);
            --gold: #c9a227;
            --gold-soft: rgba(201, 162, 39, 0.45);
            --ink: #1a1f16;
            --muted: rgba(255, 255, 255, 0.72);
            --glass: rgba(18, 28, 14, 0.52);
            --glass-border: rgba(255, 255, 255, 0.18);
            --field: rgba(255, 255, 255, 0.08);
            --field-focus: rgba(139, 195, 74, 0.18);
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
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
            background: #0d120a;
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
            transform: scale(1.02);
            animation: bg-breathe 18s ease-in-out infinite alternate;
        }

        .login-page::after {
            content: '';
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                linear-gradient(
                    105deg,
                    rgba(8, 14, 6, 0.15) 0%,
                    rgba(8, 14, 6, 0.28) 42%,
                    rgba(6, 12, 5, 0.55) 70%,
                    rgba(5, 10, 4, 0.68) 100%
                ),
                radial-gradient(
                    ellipse 80% 60% at 75% 50%,
                    rgba(0, 0, 0, 0.25) 0%,
                    transparent 70%
                );
            pointer-events: none;
        }

        @keyframes bg-breathe {
            from { transform: scale(1.02); }
            to { transform: scale(1.06); }
        }

        .login-panel {
            width: min(100%, 420px);
            margin-right: clamp(0rem, 6vw, 5rem);
            padding: clamp(1.75rem, 3.5vw, 2.5rem);
            border-radius: 22px;
            background: var(--glass);
            backdrop-filter: blur(22px) saturate(1.25);
            -webkit-backdrop-filter: blur(22px) saturate(1.25);
            border: 1px solid var(--glass-border);
            box-shadow:
                0 0 0 1px rgba(139, 195, 74, 0.12),
                0 0 28px var(--green-glow),
                0 0 64px rgba(139, 195, 74, 0.22),
                0 0 100px var(--gold-soft),
                0 24px 48px rgba(0, 0, 0, 0.45),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
            animation: panel-in 0.7s cubic-bezier(0.22, 1, 0.36, 1) both,
                       glow-pulse 4.5s ease-in-out infinite;
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
                    0 0 0 1px rgba(139, 195, 74, 0.12),
                    0 0 28px var(--green-glow),
                    0 0 64px rgba(139, 195, 74, 0.22),
                    0 0 100px var(--gold-soft),
                    0 24px 48px rgba(0, 0, 0, 0.45),
                    inset 0 1px 0 rgba(255, 255, 255, 0.12);
            }
            50% {
                box-shadow:
                    0 0 0 1px rgba(139, 195, 74, 0.22),
                    0 0 36px rgba(139, 195, 74, 0.7),
                    0 0 80px rgba(139, 195, 74, 0.32),
                    0 0 120px rgba(201, 162, 39, 0.35),
                    0 24px 48px rgba(0, 0, 0, 0.45),
                    inset 0 1px 0 rgba(255, 255, 255, 0.16);
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
            color: rgba(255, 255, 255, 0.78);
        }

        .field input,
        .field select {
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: var(--field);
            color: #fff;
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-family: inherit;
            font-size: 0.98rem;
            outline: none;
            transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }

        .field select {
            background-image:
                linear-gradient(45deg, transparent 50%, rgba(255,255,255,0.7) 50%),
                linear-gradient(135deg, rgba(255,255,255,0.7) 50%, transparent 50%);
            background-position:
                calc(100% - 18px) calc(50% - 3px),
                calc(100% - 12px) calc(50% - 3px);
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
            padding-right: 2.4rem;
            cursor: pointer;
        }

        .field select option {
            color: #1a1f16;
            background: #f5f7f2;
        }

        .field input::placeholder {
            color: rgba(255, 255, 255, 0.38);
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(139, 195, 74, 0.65);
            background: var(--field-focus);
            box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.18), 0 0 18px rgba(139, 195, 74, 0.2);
        }

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
            color: #102008;
            cursor: pointer;
            background: linear-gradient(135deg, #a8d85a 0%, var(--green-bright) 45%, #6fad35 100%);
            box-shadow:
                0 0 20px rgba(139, 195, 74, 0.45),
                0 8px 20px rgba(0, 0, 0, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow:
                0 0 28px rgba(139, 195, 74, 0.6),
                0 10px 24px rgba(0, 0, 0, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .panel-footer {
            margin-top: 1.35rem;
            text-align: center;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.45);
        }

        .brand-mark {
            display: none;
        }

        @media (max-width: 768px) {
            .login-page {
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                min-height: 100dvh;
                min-height: 100svh;
                padding: max(0.65rem, env(safe-area-inset-top)) 1.15rem max(1.35rem, env(safe-area-inset-bottom));
                gap: clamp(1.75rem, 7vh, 3.25rem);
            }

            .brand-mark {
                display: flex;
                flex-direction: column;
                align-items: center;
                flex: 0 0 auto;
                width: min(100%, 300px);
                margin-top: 0.35rem;
                z-index: 1;
            }

            .brand-mark img {
                width: 100%;
                height: auto;
                display: block;
                object-fit: contain;
                filter: drop-shadow(0 8px 24px rgba(0, 0, 0, 0.45));
            }

            .login-page::before {
                /* Magasin visible derrière, logo géré par .brand-mark */
                background-position: 72% center;
                background-size: cover;
                transform: none;
                animation: none;
            }

            .login-page::after {
                background: linear-gradient(
                    180deg,
                    rgba(8, 14, 6, 0.35) 0%,
                    rgba(6, 12, 5, 0.4) 40%,
                    rgba(5, 10, 4, 0.55) 100%
                );
            }

            .login-panel {
                flex-shrink: 0;
                margin-right: 0;
                width: min(100%, 380px);
                padding: 1.25rem 1.2rem 1.4rem;
                background: rgba(18, 28, 14, 0.58);
            }

            .panel-header {
                margin-bottom: 1.05rem;
            }

            .panel-header h1 {
                font-size: 1.28rem;
            }

            .panel-header h1 span {
                display: none;
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
            <img src="{{ asset('images/brand-logo.png') }}" alt="7ssabHani — La Solution qui Gère" width="520" height="420">
        </div>
        <form class="login-panel" method="POST" action="{{ url('/login') }}" autocomplete="on">
            @csrf
            <header class="panel-header">
                <h1>Connexion <span>7ssabHani</span></h1>
                <p>Accédez à votre espace de gestion</p>
            </header>

            <div class="field">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" required>
                    <option value="" disabled>Sélectionner un statut</option>
                    <option value="admin" selected>Administrateur</option>
                    <option value="gerant">Gérant</option>
                    <option value="vendeur">Vendeur</option>
                    <option value="caissier">Caissier</option>
                </select>
            </div>

            <div class="field">
                <label for="login">Login</label>
                <input
                    type="text"
                    id="login"
                    name="login"
                    value="admin@7ssabhani.com"
                    placeholder="Votre identifiant"
                    required
                    autocomplete="username"
                >
            </div>

            <div class="field">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    value="password"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="submit-btn">Se connecter</button>

            <p class="panel-footer">La solution qui gère votre commerce</p>
        </form>
    </main>
</body>
</html>
