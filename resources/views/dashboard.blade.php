<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Tableau de bord — 7ssabHani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #4f9228;
            --green-bright: #8bc34a;
            --green-deep: #2e5c18;
            --green-soft: rgba(139, 195, 74, 0.16);
            --green-glow: rgba(139, 195, 74, 0.45);
            --gold: #c9a227;
            --gold-soft: rgba(201, 162, 39, 0.28);
            --ink: #152014;
            --ink-soft: #3d4a38;
            --muted: #6b7a66;
            --paper: #f3f6ef;
            --paper-2: #e8eee2;
            --white: #ffffff;
            --sidebar-w: 280px;
            --shadow-card: 0 10px 28px rgba(21, 32, 20, 0.12), 0 2px 8px rgba(21, 32, 20, 0.06);
            --shadow-glow: 0 0 0 1px rgba(139, 195, 74, 0.18), 0 12px 32px rgba(79, 146, 40, 0.18), 0 0 40px rgba(139, 195, 74, 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 50% at 100% -10%, rgba(139, 195, 74, 0.18), transparent 55%),
                radial-gradient(ellipse 60% 40% at 0% 100%, rgba(201, 162, 39, 0.12), transparent 50%),
                linear-gradient(165deg, #eef3e8 0%, var(--paper) 45%, #e4ebdc 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.35;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(139, 195, 74, 0.08) 0 1px, transparent 1.5px),
                radial-gradient(circle at 80% 70%, rgba(201, 162, 39, 0.07) 0 1px, transparent 1.5px);
            background-size: 28px 28px, 36px 36px;
        }

        .app {
            display: flex;
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* ——— Sidebar ——— */
        .sidebar {
            width: var(--sidebar-w);
            flex-shrink: 0;
            background:
                linear-gradient(185deg, #1c2a18 0%, #152014 55%, #10180e 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow:
                8px 0 32px rgba(16, 24, 14, 0.28),
                inset -1px 0 0 rgba(139, 195, 74, 0.12);
            z-index: 50;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 195, 74, 0.22), transparent 70%);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1.35rem 1.25rem 1.15rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            z-index: 1;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(139, 195, 74, 0.25), rgba(201, 162, 39, 0.15));
            border: 1.5px solid rgba(139, 195, 74, 0.45);
            box-shadow: 0 0 18px rgba(139, 195, 74, 0.25);
            flex-shrink: 0;
        }

        .brand-icon svg { width: 24px; height: 24px; }

        .brand-text strong {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .brand-text strong .gold { color: var(--gold); }

        .brand-text span {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 0.02em;
        }

        .side-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem 1.25rem;
            position: relative;
            z-index: 1;
        }

        .side-nav::-webkit-scrollbar { width: 5px; }
        .side-nav::-webkit-scrollbar-thumb {
            background: rgba(139, 195, 74, 0.35);
            border-radius: 99px;
        }

        .menu-group { margin-bottom: 0.35rem; }

        .menu-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.88rem 1rem;
            margin-bottom: 0.7rem;
            border-radius: 14px;
            text-decoration: none;
            color: #102008;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background:
                linear-gradient(135deg, #b6e85f 0%, #8bc34a 42%, #6fad35 78%, #c9a227 160%);
            box-shadow:
                0 0 0 1px rgba(201, 162, 39, 0.35),
                0 8px 22px rgba(79, 146, 40, 0.35),
                0 0 28px rgba(139, 195, 74, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.45);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(180deg, #f0d56a, #c9a227);
            box-shadow: 0 0 12px rgba(201, 162, 39, 0.65);
            z-index: 1;
        }

        .menu-link::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(115deg, transparent 35%, rgba(255,255,255,0.28) 50%, transparent 65%);
            transform: translateX(-120%);
            transition: transform 0.55s ease;
            pointer-events: none;
            z-index: 0;
        }

        .menu-link:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow:
                0 0 0 1px rgba(201, 162, 39, 0.5),
                0 12px 28px rgba(79, 146, 40, 0.42),
                0 0 36px rgba(139, 195, 74, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.55);
        }

        .menu-link:hover::after {
            transform: translateX(120%);
        }

        .menu-link .menu-ico {
            background: rgba(16, 32, 8, 0.18);
            border-color: rgba(16, 32, 8, 0.12);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.2),
                0 4px 12px rgba(16, 32, 8, 0.15);
            color: #102008;
            position: relative;
            z-index: 1;
        }

        .menu-link .menu-label {
            position: relative;
            z-index: 1;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .menu-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.78rem 0.9rem;
            border: none;
            border-radius: 12px;
            background: transparent;
            color: rgba(255, 255, 255, 0.82);
            font-family: inherit;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            text-align: left;
            transition: background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-btn:hover {
            background: rgba(139, 195, 74, 0.12);
            color: #fff;
        }

        .menu-group.open .menu-btn {
            background: linear-gradient(135deg, rgba(139, 195, 74, 0.22), rgba(201, 162, 39, 0.1));
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(139, 195, 74, 0.28);
        }

        .menu-ico {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        .menu-group.open .menu-ico {
            background: rgba(139, 195, 74, 0.2);
            border-color: rgba(139, 195, 74, 0.35);
            box-shadow: 0 0 12px rgba(139, 195, 74, 0.25);
        }

        .menu-ico svg { width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8; }

        .menu-label { flex: 1; }

        .chevron {
            width: 16px;
            height: 16px;
            opacity: 0.55;
            transition: transform 0.25s ease;
        }

        .menu-group.open .chevron { transform: rotate(180deg); opacity: 0.9; }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.25s ease;
            opacity: 0;
            padding-left: 0.35rem;
        }

        .menu-group.open .submenu {
            max-height: 320px;
            opacity: 1;
            margin-top: 0.2rem;
            margin-bottom: 0.35rem;
        }

        .submenu a {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.55rem 0.85rem 0.55rem 2.9rem;
            color: rgba(255, 255, 255, 0.62);
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 500;
            border-radius: 9px;
            position: relative;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .submenu a::before {
            content: '';
            position: absolute;
            left: 1.55rem;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(139, 195, 74, 0.45);
            box-shadow: 0 0 8px rgba(139, 195, 74, 0.35);
        }

        .submenu a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .submenu a:hover::before {
            background: var(--green-bright);
        }

        .sidebar-foot {
            padding: 1rem 1.15rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright));
            box-shadow: 0 0 14px rgba(139, 195, 74, 0.4);
        }

        .user-meta { flex: 1; min-width: 0; }
        .user-meta strong {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-meta span {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .logout-btn {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: background 0.2s, color 0.2s;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(220, 80, 60, 0.2);
            color: #ffb4a8;
            border-color: rgba(220, 80, 60, 0.35);
        }

        .logout-btn svg { width: 16px; height: 16px; }

        /* ——— Main ——— */
        .main {
            flex: 1;
            min-width: 0;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 0.85rem 1.25rem 0;
            padding: 1rem 1.35rem;
            flex-shrink: 0;
            border-radius: 18px;
            background:
                linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(243,246,239,0.88) 100%);
            border: 1px solid rgba(139, 195, 74, 0.22);
            box-shadow:
                0 10px 28px rgba(21, 32, 20, 0.08),
                inset 0 1px 0 rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .topbar::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(180deg, var(--green-bright), var(--gold));
            border-radius: 18px 0 0 18px;
        }

        .topbar-cluster {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }

        .topbar-left h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.2rem;
        }

        .topbar-left .welcome-line {
            margin-top: 0;
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.55rem, 3.2vw, 2.15rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.15;
            color: var(--ink);
        }

        .topbar-left .welcome-line span {
            background: linear-gradient(120deg, #6fad35 0%, #8bc34a 40%, #c9a227 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .menu-toggle {
            display: none;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid rgba(21, 32, 20, 0.1);
            background: var(--white);
            box-shadow: var(--shadow-card);
            cursor: pointer;
            place-items: center;
            color: var(--ink);
            flex-shrink: 0;
        }

        .menu-toggle svg { width: 20px; height: 20px; }

        .topbar-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(139, 195, 74, 0.16), rgba(201, 162, 39, 0.1));
            border: 1px solid rgba(139, 195, 74, 0.35);
            box-shadow: 0 6px 16px rgba(79, 146, 40, 0.12);
            font-family: 'Outfit', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--green-deep);
            white-space: nowrap;
        }

        .topbar-badge i {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--green-bright);
            box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.25), 0 0 10px var(--green-glow);
            display: block;
            animation: pulse-dot 1.8s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(0.85); opacity: 0.75; }
        }

        /* ——— KPI Cards ——— */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 0.85rem;
            margin: 0.85rem 1.25rem 0.85rem;
            padding: 0.85rem;
            flex-shrink: 0;
            border-radius: 20px;
            background:
                linear-gradient(145deg, rgba(28, 42, 24, 0.96), rgba(16, 24, 14, 0.94));
            border: 1px solid rgba(139, 195, 74, 0.28);
            box-shadow:
                0 16px 40px rgba(16, 24, 14, 0.22),
                inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .kpi-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 16px;
            padding: 1.05rem 1rem 1.1rem;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
            color: #fff;
            background:
                linear-gradient(160deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.03) 100%);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,0.08),
                0 8px 20px rgba(0,0,0,0.15);
            transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
            isolation: isolate;
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 12%;
            bottom: 12%;
            width: 3px;
            border-radius: 0 4px 4px 0;
            background: var(--accent, var(--green-bright));
            box-shadow: 0 0 12px var(--accent-glow);
            pointer-events: none;
        }

        .kpi-card::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -25%;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--accent-glow, rgba(139, 195, 74, 0.35)), transparent 68%);
            z-index: -1;
            transition: transform 0.35s ease;
            opacity: 0.85;
        }

        .kpi-card:hover {
            transform: translateY(-3px) scale(1.01);
            background:
                linear-gradient(160deg, rgba(255,255,255,0.16) 0%, rgba(255,255,255,0.05) 100%);
            box-shadow:
                inset 0 0 0 1px rgba(255,255,255,0.14),
                0 14px 28px rgba(0,0,0,0.22),
                0 0 24px var(--accent-glow);
        }

        .kpi-card:hover::after { transform: scale(1.3); }

        .kpi-card:focus-visible {
            outline: 2px solid var(--green-bright);
            outline-offset: 3px;
        }

        .kpi-card[data-tone="suppliers"] { --accent: #8bc34a; --accent-glow: rgba(139, 195, 74, 0.4); }
        .kpi-card[data-tone="clients"]   { --accent: #4db6ac; --accent-glow: rgba(77, 182, 172, 0.4); }
        .kpi-card[data-tone="stock"]     { --accent: #c9a227; --accent-glow: rgba(201, 162, 39, 0.45); }
        .kpi-card[data-tone="caisse"]    { --accent: #64b5f6; --accent-glow: rgba(100, 181, 246, 0.4); }
        .kpi-card[data-tone="charges"]   { --accent: #e07a4f; --accent-glow: rgba(224, 122, 79, 0.4); }

        .kpi-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .kpi-ico {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: color-mix(in srgb, var(--accent) 22%, transparent);
            color: var(--accent);
            box-shadow:
                0 0 0 1px color-mix(in srgb, var(--accent) 35%, transparent),
                0 0 18px color-mix(in srgb, var(--accent) 25%, transparent);
        }

        .kpi-ico svg { width: 19px; height: 19px; stroke: currentColor; fill: none; stroke-width: 1.8; }

        .kpi-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            text-transform: none;
            color: rgba(255,255,255,0.78);
            margin-bottom: 0.35rem;
            line-height: 1.25;
        }

        .kpi-value {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.15rem, 1.7vw, 1.4rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #fff;
            line-height: 1.15;
        }

        .kpi-value small {
            font-size: 0.7rem;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            margin-left: 0.2rem;
        }

        .kpi-foot {
            display: none;
        }

        .kpi-trend {
            display: none;
        }

        .dash-hero {
            flex: 1 1 auto;
            min-height: 0;
            margin: 0;
            border-radius: 0;
            overflow: hidden;
            border: none;
            box-shadow: none;
            background: #e8eee2;
            position: relative;
        }

        .dash-hero img {
            position: absolute;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
        }

        .dash-hero::after {
            display: none;
        }

        /* ——— Content area ——— */
        .content {
            display: none;
        }

        .welcome-panel {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            padding: clamp(1.5rem, 3vw, 2.25rem);
            background:
                linear-gradient(125deg, rgba(28, 42, 24, 0.94) 0%, rgba(21, 32, 20, 0.9) 50%, rgba(36, 48, 22, 0.92) 100%);
            color: #fff;
            box-shadow:
                0 16px 40px rgba(16, 24, 14, 0.22),
                0 0 0 1px rgba(139, 195, 74, 0.2),
                0 0 48px rgba(139, 195, 74, 0.12);
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-panel::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            right: -40px;
            top: -80px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139, 195, 74, 0.28), transparent 68%);
            pointer-events: none;
        }

        .welcome-panel::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            left: 35%;
            bottom: -90px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.18), transparent 70%);
            pointer-events: none;
        }

        .welcome-panel h2 {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.4rem, 2.5vw, 1.85rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 1;
            max-width: 28ch;
        }

        .welcome-panel h2 span { color: var(--gold); }

        .welcome-panel p {
            margin-top: 0.65rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            max-width: 42ch;
            position: relative;
            z-index: 1;
            line-height: 1.5;
        }

        .welcome-actions {
            margin-top: 1.35rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            position: relative;
            z-index: 1;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.7rem 1.15rem;
            border-radius: 11px;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright) 50%, #6fad35);
            box-shadow: 0 0 20px rgba(139, 195, 74, 0.4), 0 8px 18px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            transition: transform 0.2s, filter 0.2s;
        }

        .btn-primary:hover { transform: translateY(-1px); filter: brightness(1.05); }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            padding: 0.7rem 1.15rem;
            border-radius: 11px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            font-family: inherit;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-ghost:hover { background: rgba(255, 255, 255, 0.12); }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 16, 8, 0.45);
            backdrop-filter: blur(3px);
            z-index: 45;
        }

        .overlay.show { display: block; }

        .sidebar-close {
            display: none;
        }

        @media (max-width: 1200px) {
            .kpi-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100dvh;
                height: 100svh;
                width: min(86vw, 300px);
                max-width: 300px;
                transform: translateX(-110%);
                transition: transform 0.3s ease;
                z-index: 50;
                visibility: visible;
                pointer-events: auto;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .menu-toggle {
                display: grid;
                flex-shrink: 0;
            }

            .sidebar-close {
                display: grid;
                place-items: center;
                width: 36px;
                height: 36px;
                margin-left: auto;
                border: 1px solid rgba(255, 255, 255, 0.14);
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.06);
                color: #fff;
                cursor: pointer;
            }

            .sidebar-close svg { width: 18px; height: 18px; }

            .brand { padding-right: 0.85rem; }

            .topbar {
                margin-left: 0.75rem;
                margin-right: 0.75rem;
                padding: 0.9rem 1rem;
            }

            .kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                margin-left: 0.75rem;
                margin-right: 0.75rem;
            }

            .content { padding-left: 1rem; padding-right: 1rem; }
            .dash-hero { margin: 0; }
        }

        @media (max-width: 560px) {
            .kpi-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>

    <div class="app">
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M3 5h2l1.2 9.2a2 2 0 0 0 2 1.8h8.5a2 2 0 0 0 2-1.6L20 8H7" stroke="#c9a227" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="10" cy="19" r="1.2" fill="#c9a227"/>
                        <circle cx="17" cy="19" r="1.2" fill="#c9a227"/>
                        <path d="M16 3c-1.2 0-2 .9-2.2 2.1C15.2 5.4 16.4 6 17.5 5.6 17.2 4 16.6 3 16 3Z" fill="#8bc34a"/>
                        <path d="M18.2 4.2c-.7-.2-1.4.3-1.6 1.1.9.4 1.9.3 2.5-.2-.2-.5-.5-.8-.9-.9Z" fill="#6fad35"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <strong>7ssab<span class="gold">Hani</span></strong>
                    <span>La Solution qui Gère</span>
                </div>
                <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Fermer le menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                </button>
            </div>

            <nav class="side-nav" aria-label="Menu principal">
                <a href="{{ route('dashboard') }}" class="menu-link" aria-current="page">
                    <span class="menu-ico">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                    </span>
                    <span class="menu-label">Tableau de Bord</span>
                </a>

                <div class="menu-group" data-menu="fournisseur">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M3 10.5 12 4l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="menu-label">Fournisseur</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('bon-achat') }}">Bon Achat</a>
                        <a href="{{ route('reglement-achat') }}">Règlement Achat</a>
                        <a href="{{ route('balance-achat') }}">Balance Achat</a>
                    </div>
                </div>

                <div class="menu-group" data-menu="client">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M16 19a4 4 0 0 0-8 0"/><circle cx="12" cy="9" r="3.5"/><path d="M19 19a3.5 3.5 0 0 0-2.2-3.2M5 19a3.5 3.5 0 0 1 2.2-3.2M17.5 8.2a3 3 0 1 1-1.2-4"/></svg>
                        </span>
                        <span class="menu-label">Client</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('bon-vente') }}">Bon Vente</a>
                        <a href="{{ route('reglement-vente') }}">Règlement Vente</a>
                        <a href="{{ route('balance-vente') }}">Balance Vente</a>
                    </div>
                </div>

                <div class="menu-group" data-menu="stock">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M3 8.5 12 4l9 4.5-9 4.5L3 8.5Z"/><path d="M3 12.5 12 17l9-4.5"/><path d="M3 16.5 12 21l9-4.5"/></svg>
                        </span>
                        <span class="menu-label">Stock</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('categorie-produit') }}">Catégorie Produit</a>
                        <a href="{{ route('etat-produit') }}">État Produit</a>
                    </div>
                </div>

                <div class="menu-group" data-menu="charges">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M4 7h16v12H4z"/><path d="M8 7V5.5A1.5 1.5 0 0 1 9.5 4h5A1.5 1.5 0 0 1 16 5.5V7"/><path d="M8 12h8"/></svg>
                        </span>
                        <span class="menu-label">Charges</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="#">État Charge</a>
                        <a href="#">Balance Charges</a>
                    </div>
                </div>

                <div class="menu-group" data-menu="rapports">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-3"/></svg>
                        </span>
                        <span class="menu-label">Rapports</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="#">Relevé Compte Frns</a>
                        <a href="#">Relevé Compte Client</a>
                        <a href="#">Relevé Compte Stock</a>
                    </div>
                </div>

                <div class="menu-group" data-menu="configuration">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 3.5v2.2M12 18.3v2.2M4.9 6.5l1.6 1.5M17.5 16l1.6 1.5M3.5 12h2.2M18.3 12h2.2M4.9 17.5l1.6-1.5M17.5 8l1.6-1.5"/></svg>
                        </span>
                        <span class="menu-label">Configuration</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="#">Utilisateurs</a>
                        <a href="#">Paramètres Système</a>
                    </div>
                </div>
            </nav>

            <div class="sidebar-foot">
                <div class="avatar">AD</div>
                <div class="user-meta">
                    <strong>Administrateur</strong>
                    <span>admin@7ssabhani.com</span>
                </div>
                <a href="{{ route('login') }}" class="logout-btn" title="Déconnexion" aria-label="Déconnexion">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 7V5a2 2 0 0 1 2-2h7v18h-7a2 2 0 0 1-2-2v-2"/><path d="M15 12H3m0 0 3-3m-3 3 3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </aside>

        <div class="main">
            <header class="topbar">
                <div class="topbar-cluster">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Tableau de bord</h1>
                        <p class="welcome-line">Bienvenue sur <span>7ssabHani</span></p>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <nav class="kpi-grid notranslate" aria-label="Indicateurs rapides" translate="no">
                <button type="button" class="kpi-card" data-tone="suppliers">
                    <div class="kpi-head">
                        <span class="kpi-ico">
                            <svg viewBox="0 0 24 24"><path d="M3 10.5 12 4l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                    <div class="kpi-label" translate="no">Solde Fournisseur</div>
                    <div class="kpi-value" id="kpiSoldeFournisseur">0.00 <small>DH</small></div>
                </button>

                <button type="button" class="kpi-card" data-tone="clients">
                    <div class="kpi-head">
                        <span class="kpi-ico">
                            <svg viewBox="0 0 24 24"><path d="M16 19a4 4 0 0 0-8 0"/><circle cx="12" cy="9" r="3.5"/><path d="M19 19a3.5 3.5 0 0 0-2.2-3.2M5 19a3.5 3.5 0 0 1 2.2-3.2"/></svg>
                        </span>
                    </div>
                    <div class="kpi-label" translate="no">Solde Clients</div>
                    <div class="kpi-value" id="kpiSoldeClients">0.00 <small>DH</small></div>
                </button>

                <button type="button" class="kpi-card" data-tone="stock">
                    <div class="kpi-head">
                        <span class="kpi-ico">
                            <svg viewBox="0 0 24 24"><path d="M3 8.5 12 4l9 4.5-9 4.5L3 8.5Z"/><path d="M3 12.5 12 17l9-4.5"/><path d="M3 16.5 12 21l9-4.5"/></svg>
                        </span>
                    </div>
                    <div class="kpi-label" translate="no">Valeur Stock</div>
                    <div class="kpi-value">0.00 <small>DH</small></div>
                </button>

                <button type="button" class="kpi-card" data-tone="caisse">
                    <div class="kpi-head">
                        <span class="kpi-ico">
                            <svg viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><path d="M8 14h3"/></svg>
                        </span>
                    </div>
                    <div class="kpi-label" translate="no">Caisse</div>
                    <div class="kpi-value">0.00 <small>DH</small></div>
                </button>

                <button type="button" class="kpi-card" data-tone="charges">
                    <div class="kpi-head">
                        <span class="kpi-ico">
                            <svg viewBox="0 0 24 24"><path d="M12 3v18"/><path d="M7 7.5c0-1.7 2.2-3 5-3s5 1.3 5 3-2.2 3-5 3-5 1.3-5 3 2.2 3 5 3 5-1.3 5-3"/></svg>
                        </span>
                    </div>
                    <div class="kpi-label" translate="no">Charges</div>
                    <div class="kpi-value">0.00 <small>DH</small></div>
                </button>
            </nav>

            <figure class="dash-hero">
                <img src="{{ asset('images/dashboard-hero.png') }}" alt="Rayon fruits et légumes — 7ssabHani" width="1600" height="900">
            </figure>

            <section class="content">
            </section>
        </div>
    </div>

    <script src="{{ asset('js/sidebar-menu.js') }}?v=1"></script>
    <script src="{{ asset('js/achat-store.js') }}?v=7"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=7"></script>
    <script>

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const sidebarClose = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        menuToggle?.addEventListener('click', () => {
            if (sidebar.classList.contains('open')) closeSidebar();
            else openSidebar();
        });

        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        function fillKpiMoney(el, amount) {
            if (!el) return;
            var n = Number(amount) || 0;
            var txt = n.toFixed(2);
            el.innerHTML = txt + ' <small>DH</small>';
        }

        function refreshDashboardKpis() {
            var soldeFrns = window.AchatStore ? AchatStore.getTotalSolde() : 0;
            var soldeClients = window.VenteStore ? VenteStore.getTotalSolde() : 0;
            fillKpiMoney(document.getElementById('kpiSoldeFournisseur'), soldeFrns);
            fillKpiMoney(document.getElementById('kpiSoldeClients'), soldeClients);
        }

        refreshDashboardKpis();
        window.addEventListener('storage', refreshDashboardKpis);
        window.addEventListener('focus', refreshDashboardKpis);
    </script>
</body>
</html>
