<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de bord — LibAutoEnt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --green: #14213d;
            --green-bright: #fca311;
            --green-deep: #07111c;
            --green-soft: rgba(252, 163, 17, 0.16);
            --green-glow: rgba(252, 163, 17, 0.45);
            --gold: #fca311;
            --gold-soft: rgba(252, 163, 17, 0.28);
            --ink: #0d1b2a;
            --ink-soft: #3d4f63;
            --muted: #6b7c8f;
            --paper: #f0f3f7;
            --paper-2: #e4e9f0;
            --white: #ffffff;
            --sidebar-w: 280px;
            --shadow-card: 0 10px 28px rgba(13, 27, 42, 0.12), 0 2px 8px rgba(13, 27, 42, 0.06);
            --shadow-glow: 0 0 0 1px rgba(252, 163, 17, 0.18), 0 12px 32px rgba(13, 27, 42, 0.18), 0 0 40px rgba(252, 163, 17, 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 50% at 100% -10%, rgba(252, 163, 17, 0.18), transparent 55%),
                radial-gradient(ellipse 60% 40% at 0% 100%, rgba(252, 163, 17, 0.12), transparent 50%),
                linear-gradient(165deg, #eef2f7 0%, var(--paper) 45%, #e0e6ef 100%);
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
                radial-gradient(circle at 20% 30%, rgba(252, 163, 17, 0.08) 0 1px, transparent 1.5px),
                radial-gradient(circle at 80% 70%, rgba(252, 163, 17, 0.07) 0 1px, transparent 1.5px);
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
                linear-gradient(185deg, #14213d 0%, #0d1b2a 55%, #07111c 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            box-shadow:
                8px 0 32px rgba(7, 17, 28, 0.28),
                inset -1px 0 0 rgba(252, 163, 17, 0.12);
            z-index: 50;
            transition: width 0.28s ease, opacity 0.2s ease, transform 0.28s ease;
            overflow: hidden;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(252, 163, 17, 0.22), transparent 70%);
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
            background: linear-gradient(145deg, rgba(252, 163, 17, 0.25), rgba(252, 163, 17, 0.15));
            border: 1.5px solid rgba(252, 163, 17, 0.45);
            box-shadow: 0 0 18px rgba(252, 163, 17, 0.25);
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
            background: rgba(252, 163, 17, 0.35);
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
            color: #0d1b2a;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: -0.01em;
            position: relative;
            overflow: hidden;
            isolation: isolate;
            background:
                linear-gradient(135deg, #ffc857 0%, #fca311 42%, #e8920a 78%, #fca311 160%);
            box-shadow:
                0 0 0 1px rgba(252, 163, 17, 0.35),
                0 8px 22px rgba(13, 27, 42, 0.35),
                0 0 28px rgba(252, 163, 17, 0.25),
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
            background: linear-gradient(180deg, #ffb83a, #fca311);
            box-shadow: 0 0 12px rgba(252, 163, 17, 0.65);
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
                0 0 0 1px rgba(252, 163, 17, 0.5),
                0 12px 28px rgba(13, 27, 42, 0.42),
                0 0 36px rgba(252, 163, 17, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.55);
        }

        .menu-link:hover::after {
            transform: translateX(120%);
        }

        .menu-link .menu-ico {
            background: rgba(13, 27, 42, 0.18);
            border-color: rgba(13, 27, 42, 0.12);
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.2),
                0 4px 12px rgba(13, 27, 42, 0.15);
            color: #0d1b2a;
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
            background: rgba(252, 163, 17, 0.12);
            color: #fff;
        }

        .menu-group.open .menu-btn {
            background: linear-gradient(135deg, rgba(252, 163, 17, 0.22), rgba(252, 163, 17, 0.1));
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(252, 163, 17, 0.28);
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
            background: rgba(252, 163, 17, 0.2);
            border-color: rgba(252, 163, 17, 0.35);
            box-shadow: 0 0 12px rgba(252, 163, 17, 0.25);
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
            background: rgba(252, 163, 17, 0.45);
            box-shadow: 0 0 8px rgba(252, 163, 17, 0.35);
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
            background: linear-gradient(135deg, #ffb83a, var(--green-bright));
            box-shadow: 0 0 14px rgba(252, 163, 17, 0.4);
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

        .page-wrap {
            flex: 1;
            min-height: 0;
            padding: 0.85rem 1.25rem 1.25rem;
            overflow: auto;
        }

        .dash-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.7rem;
            margin-bottom: 0.85rem;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 14px;
            padding: 0.75rem 0.9rem 0.8rem;
            text-align: left;
            color: #fff;
            isolation: isolate;
            box-shadow: 0 8px 20px rgba(7, 17, 28, 0.14);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(7, 17, 28, 0.18);
        }
        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 100% 0%, rgba(255,255,255,0.18), transparent 42%),
                radial-gradient(circle at 0% 100%, rgba(0,0,0,0.1), transparent 45%);
            pointer-events: none;
            z-index: 0;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            right: -14px;
            bottom: -22px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            pointer-events: none;
            z-index: 0;
        }

        .stat-card[data-tone="livres"] {
            background: linear-gradient(145deg, #1a3a5c 0%, #0d1b2a 55%, #07111c 100%);
            box-shadow: 0 8px 20px rgba(7, 17, 28, 0.16), 0 0 0 1px rgba(100, 181, 246, 0.2);
        }
        .stat-card[data-tone="ventes"] {
            background: linear-gradient(145deg, #fca311 0%, #e8920a 48%, #c47e00 100%);
            color: #0d1b2a;
            box-shadow: 0 8px 20px rgba(252, 163, 17, 0.22), 0 0 0 1px rgba(252, 163, 17, 0.3);
        }
        .stat-card[data-tone="solde"] {
            background: linear-gradient(145deg, #1b4332 0%, #0f2f24 50%, #07111c 100%);
            box-shadow: 0 8px 20px rgba(7, 17, 28, 0.16), 0 0 0 1px rgba(77, 182, 172, 0.24);
        }

        .stat-top {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.45rem;
        }
        .stat-ico {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .stat-card[data-tone="ventes"] .stat-ico {
            background: rgba(13, 27, 42, 0.12);
            border-color: rgba(13, 27, 42, 0.12);
        }
        .stat-ico svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }
        .stat-label {
            position: relative;
            z-index: 1;
            font-family: 'Outfit', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            opacity: 0.88;
            margin-bottom: 0.2rem;
        }
        .stat-value {
            position: relative;
            z-index: 1;
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.15rem, 1.8vw, 1.35rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }
        .stat-value small {
            font-size: 0.55em;
            font-weight: 700;
            margin-left: 0.15rem;
            opacity: 0.8;
        }
        .stat-hint {
            display: none;
        }

        @media (max-width: 900px) {
            .dash-stats { grid-template-columns: 1fr; }
        }

        .table-card {
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(252, 163, 17, 0.14);
            overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }
        .data-table th {
            padding: 0.75rem 0.55rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--muted);
            background: #f4f7fb;
            border-bottom: 1px solid rgba(13, 27, 42, 0.08);
            text-align: center;
            white-space: nowrap;
        }
        .data-table td {
            padding: 0.7rem 0.55rem;
            font-size: 0.86rem;
            border-bottom: 1px solid rgba(13, 27, 42, 0.06);
            color: var(--ink);
            vertical-align: middle;
            text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252, 163, 17, 0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .nom-cell { font-weight: 600; text-align: left !important; padding-left: 0.85rem !important; }
        .money { font-variant-numeric: tabular-nums; font-weight: 600; }
        .col-solde { color: #c47e00; font-weight: 700; }

        .toolbar {
            display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 0.85rem;
            align-items: flex-end; justify-content: space-between;
        }
        .filters {
            display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: flex-end; flex: 1;
        }
        .filter-field label {
            display: block; margin-bottom: 0.28rem; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .filter-field input, .filter-field select {
            padding: 0.6rem 0.75rem; border-radius: 10px;
            border: 1px solid rgba(13,27,42,0.12); background: #fff;
            font-family: inherit; font-size: 0.9rem; color: var(--ink); outline: none;
            min-width: 140px;
        }
        .filter-field input:focus, .filter-field select:focus {
            border-color: rgba(252,163,17,0.65); box-shadow: 0 0 0 3px rgba(252,163,17,0.15);
        }
        .filter-range { display: flex; align-items: center; gap: 0.4rem; }
        .filter-range span { color: var(--muted); font-size: 0.8rem; font-weight: 600; }
        .toolbar-actions { display: flex; flex-wrap: wrap; gap: 0.55rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.65rem 1.05rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.88rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s, filter 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 15px; height: 15px; }
        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 16px rgba(252,163,17,0.3), 0 6px 12px rgba(0,0,0,0.1);
        }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright));
            box-shadow: 0 0 14px rgba(252,163,17,0.3);
        }
        .btn-print-soft {
            color: #fff;
            background: linear-gradient(135deg, #3d7ea6, #2f6284);
        }
        .btn-view-soft {
            color: #fff;
            background: linear-gradient(135deg, #5a6570, #3d4650);
        }

        .preview-backdrop {
            position: fixed; inset: 0; z-index: 120;
            background: rgba(10,16,8,0.55); backdrop-filter: blur(4px);
            display: none; align-items: flex-start; justify-content: center;
            padding: 1.25rem; overflow-y: auto;
        }
        .preview-backdrop.show { display: flex; }
        .preview-panel {
            width: min(100%, 820px); margin: 1rem auto; background: #fff;
            border-radius: 18px; overflow: hidden;
            box-shadow: 0 24px 60px rgba(16,24,14,0.35), 0 0 0 1px rgba(252,163,17,0.2);
        }
        .preview-head {
            display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
            padding: 0.9rem 1.1rem;
            background: linear-gradient(125deg, #14213d, #0d1b2a 60%, #243016); color: #fff;
        }
        .preview-head h2 { font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 700; margin: 0; }
        .preview-head h2 span { color: var(--gold); }
        .preview-actions { display: flex; gap: 0.45rem; flex-wrap: wrap; }
        .preview-body { background: #f4f7fb; padding: 0.85rem; }
        #previewFrame {
            width: 100%; min-height: 62vh; border: 0; border-radius: 12px;
            background: #fff; box-shadow: 0 4px 16px rgba(13,27,42,0.08);
        }

        .mode-badge {
            display: inline-block; min-width: 54px; padding: 0.28rem 0.55rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.02em;
            background: rgba(13,27,42,0.08); color: var(--ink);
        }

        .actions { display: flex; gap: 0.3rem; justify-content: center; flex-wrap: wrap; }
        .icon-btn {
            width: 30px; height: 30px; border-radius: 8px;
            border: 1px solid rgba(13,27,42,0.1); background: #f4f7fb; color: var(--ink-soft);
            display: inline-grid; place-items: center; cursor: pointer;
        }
        .icon-btn svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8; pointer-events: none; }
        .icon-btn.icon-view:hover { background: rgba(61,126,166,0.12); color: #3d7ea6; }
        .icon-btn.icon-edit:hover { background: rgba(252,163,17,0.15); color: #c47e00; }
        .icon-btn.icon-delete:hover { background: rgba(184,92,56,0.12); color: #b85c38; }

        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(7,17,28,0.55); backdrop-filter: blur(4px);
            z-index: 80; display: none; pointer-events: none;
            align-items: flex-start; justify-content: center; padding: 1rem; overflow-y: auto;
        }
        .modal-backdrop.show { display: flex; pointer-events: auto; }
        .modal {
            width: min(100%, 920px); margin: 1.25rem auto; background: var(--white);
            border-radius: 18px;
            box-shadow: 0 24px 60px rgba(7,17,28,0.35), 0 0 0 1px rgba(252,163,17,0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1rem 1.2rem;
            background: linear-gradient(125deg, #14213d, #0d1b2a 60%, #07111c); color: #fff;
        }
        .modal-head h2 { font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 700; }
        .modal-head h2 span { color: var(--gold); }
        .modal-body { padding: 1.1rem 1.2rem 1.25rem; }
        .form-grid {
            display: grid;
            grid-template-columns: minmax(120px, 1.1fr) minmax(90px, 0.7fr) minmax(160px, 1.6fr) minmax(90px, 0.75fr);
            gap: 0.55rem;
            margin-bottom: 0.9rem;
            align-items: end;
        }
        .field-narrow input,
        .field-narrow select {
            padding-left: 0.45rem;
            padding-right: 0.45rem;
            font-size: 0.84rem;
        }
        .field label {
            display: block; margin-bottom: 0.3rem; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .field input, .field select {
            width: 100%; padding: 0.65rem 0.75rem; border-radius: 10px;
            border: 1px solid rgba(13,27,42,0.12); background: #f4f7fb;
            font-family: inherit; font-size: 0.9rem; color: var(--ink); outline: none; text-align: center;
        }
        .field input:focus, .field select:focus {
            border-color: rgba(252,163,17,0.65); box-shadow: 0 0 0 3px rgba(252,163,17,0.15); background: #fff;
        }
        .field input.readonly { background: #eef2f7; font-weight: 600; cursor: default; }

        .lines-wrap {
            border: 1px solid rgba(13,27,42,0.08); border-radius: 14px; overflow: hidden; margin-bottom: 0.85rem;
        }
        .lines-table { width: 100%; border-collapse: collapse; min-width: 760px; }
        .lines-table th {
            padding: 0.4rem 0.3rem; font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.03em;
            background: #f4f7fb; color: var(--muted); text-align: center;
        }
        .lines-table td { padding: 0.15rem 0.25rem; border-top: 1px solid rgba(13,27,42,0.06); }
        .lines-table input, .lines-table select {
            width: 100%; padding: 0.35rem 0.35rem; border-radius: 7px;
            border: 1px solid rgba(13,27,42,0.12); background: #fff;
            font-family: inherit; font-size: 0.84rem; text-align: center; outline: none;
        }
        .lines-table input:focus, .lines-table select:focus { border-color: rgba(252,163,17,0.65); }
        .lines-table input.readonly { background: #eef2f7; font-weight: 600; }
        .lines-table .ln-cb { text-transform: uppercase; letter-spacing: 0.04em; }
        .lines-table .ln-ref,
        .lines-table .ln-cb { font-weight: 600; }
        .lines-table .ln-ref:not([readonly]),
        .lines-table .ln-cb:not([readonly]) { background: #fffaf0; }
        .lines-scroll-wrap {
            display: flex; align-items: stretch; gap: 0.35rem;
        }
        .lines-scroll-wrap .table-scroll {
            flex: 1; min-width: 0; max-height: 260px; overflow: auto;
        }
        .lines-scroll-btns {
            display: flex; flex-direction: column; justify-content: center; gap: 0.35rem;
            flex-shrink: 0; padding: 0.15rem 0;
        }
        .btn-scroll {
            width: 34px; height: 34px; border-radius: 9px;
            border: 1px solid rgba(13,27,42,0.12); background: #f4f7fb; color: var(--ink);
            cursor: pointer; display: grid; place-items: center; padding: 0;
            transition: background 0.15s, border-color 0.15s;
        }
        .btn-scroll:hover { background: rgba(252,163,17,0.2); border-color: rgba(252,163,17,0.45); }
        .btn-scroll:disabled { opacity: 0.35; cursor: default; }
        .btn-scroll svg { width: 16px; height: 16px; }
        .btn-add-line {
            display: inline-flex; align-items: center; gap: 0.4rem;
            margin: 0.55rem 0 0.15rem; padding: 0.45rem 0.85rem;
            border: none; border-radius: 9px; cursor: pointer;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.85rem;
            color: #0d1b2a;
            background: linear-gradient(135deg, #ffb83a, #fca311);
            box-shadow: 0 4px 10px rgba(252,163,17,0.28);
        }
        .btn-add-line svg { width: 15px; height: 15px; }
        .btn-add-line:disabled { opacity: 0.45; cursor: default; }
        .btn-rm {
            width: 28px; height: 28px; border-radius: 8px; border: 1px solid rgba(184,92,56,0.25);
            background: rgba(184,92,56,0.08); color: #b85c38; cursor: pointer; display: grid; place-items: center;
        }
        .total-bar {
            display: flex; justify-content: flex-end; align-items: center; gap: 0.75rem;
            margin-bottom: 0.9rem; font-family: 'Outfit', sans-serif;
        }
        .total-bar strong { font-size: 1.15rem; color: var(--ink); }
        .modal-actions { display: flex; flex-wrap: wrap; gap: 0.55rem; justify-content: flex-end; }

        @media (max-width: 900px) {
            .form-grid { grid-template-columns: 1fr 1fr; }
            .filters { width: 100%; }
            .toolbar { flex-direction: column; align-items: stretch; }
            .toolbar-actions { justify-content: flex-end; }
        }
        @media (max-width: 560px) {
            .form-grid { grid-template-columns: 1fr; }
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 16, 8, 0.45);
            backdrop-filter: blur(3px);
            z-index: 45;
        }

        .overlay.show { display: block; }

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
            border: 1px solid rgba(252, 163, 17, 0.22);
            box-shadow:
                0 10px 28px rgba(13, 27, 42, 0.08),
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
            background: linear-gradient(120deg, #e8920a 0%, #fca311 40%, #fca311 100%);
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
            border: 1px solid rgba(13, 27, 42, 0.1);
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
            background: linear-gradient(135deg, rgba(252, 163, 17, 0.16), rgba(252, 163, 17, 0.1));
            border: 1px solid rgba(252, 163, 17, 0.35);
            box-shadow: 0 6px 16px rgba(13, 27, 42, 0.12);
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
            box-shadow: 0 0 0 3px rgba(252, 163, 17, 0.25), 0 0 10px var(--green-glow);
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
                linear-gradient(145deg, rgba(28, 42, 24, 0.96), rgba(7, 17, 28, 0.94));
            border: 1px solid rgba(252, 163, 17, 0.28);
            box-shadow:
                0 16px 40px rgba(7, 17, 28, 0.22),
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
            background: radial-gradient(circle, var(--accent-glow, rgba(252, 163, 17, 0.35)), transparent 68%);
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

        .kpi-card[data-tone="suppliers"] { --accent: #3b82c4; --accent-glow: rgba(59, 130, 196, 0.4); }
        .kpi-card[data-tone="clients"]   { --accent: #4db6ac; --accent-glow: rgba(77, 182, 172, 0.4); }
        .kpi-card[data-tone="stock"]     { --accent: #fca311; --accent-glow: rgba(252, 163, 17, 0.45); }
        .kpi-card[data-tone="caisse"]    { --accent: #64b5f6; --accent-glow: rgba(100, 181, 246, 0.4); }
        .kpi-card[data-tone="charges"]   { --accent: #e07a4f; --accent-glow: rgba(224, 122, 79, 0.4); }

        .kpi-card.is-muted {
            --accent: #8a94a0;
            --accent-glow: transparent;
            filter: grayscale(1);
            opacity: 0.48;
            cursor: default;
            pointer-events: none;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05);
        }
        .kpi-card.is-muted:hover {
            transform: none;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.05);
        }

        .menu-group.is-muted {
            opacity: 0.42;
            filter: grayscale(1);
            pointer-events: none;
            cursor: default;
        }
        .menu-group.is-muted .menu-btn {
            color: rgba(255, 255, 255, 0.45);
        }
        .menu-group.is-muted .menu-ico {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.06);
            box-shadow: none;
        }

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
            background: #e4e9f0;
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
                linear-gradient(125deg, rgba(28, 42, 24, 0.94) 0%, rgba(13, 27, 42, 0.9) 50%, rgba(36, 48, 22, 0.92) 100%);
            color: #fff;
            box-shadow:
                0 16px 40px rgba(7, 17, 28, 0.22),
                0 0 0 1px rgba(252, 163, 17, 0.2),
                0 0 48px rgba(252, 163, 17, 0.12);
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
            background: radial-gradient(circle, rgba(252, 163, 17, 0.28), transparent 68%);
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
            background: radial-gradient(circle, rgba(252, 163, 17, 0.18), transparent 70%);
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
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 20px rgba(252, 163, 17, 0.4), 0 8px 18px rgba(0, 0, 0, 0.2);
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

        .sidebar-vis-btn {
            margin-left: auto;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.85);
            cursor: pointer;
            transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
        .sidebar-vis-btn:hover {
            background: rgba(252, 163, 17, 0.18);
            border-color: rgba(252, 163, 17, 0.4);
            color: #fff;
        }
        .sidebar-vis-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
        }

        .sidebar-show-btn {
            position: fixed;
            left: 0.85rem;
            top: 1rem;
            z-index: 60;
            width: 44px;
            height: 44px;
            display: none;
            place-items: center;
            border-radius: 12px;
            border: 1px solid rgba(252, 163, 17, 0.35);
            background: linear-gradient(145deg, #14213d, #0d1b2a);
            color: #fca311;
            box-shadow: 0 10px 28px rgba(7, 17, 28, 0.35), 0 0 18px rgba(252, 163, 17, 0.2);
            cursor: pointer;
        }
        .sidebar-show-btn.is-visible { display: grid; }
        .sidebar-show-btn:hover { filter: brightness(1.08); }
        .sidebar-show-btn svg { width: 20px; height: 20px; }

        .app.sidebar-hidden .sidebar {
            width: 0 !important;
            min-width: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            border: none !important;
            box-shadow: none !important;
            opacity: 0;
            pointer-events: none;
            transform: translateX(-100%);
            transition: transform 0.28s ease, opacity 0.2s ease, width 0.28s ease;
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

            .app.sidebar-hidden .sidebar {
                width: min(86vw, 300px) !important;
                opacity: 1;
                pointer-events: auto;
                transform: translateX(-110%);
            }
            .app.sidebar-hidden .sidebar.open {
                transform: translateX(0);
            }

            .menu-toggle {
                display: grid;
                flex-shrink: 0;
            }

            .sidebar-vis-btn {
                display: none;
            }

            .sidebar-show-btn {
                display: none !important;
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
                        <path d="M4 5.2A2.2 2.2 0 0 1 6.2 3H12v18H6.2A2.2 2.2 0 0 1 4 18.8V5.2Z" stroke="#fca311" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="M20 5.2A2.2 2.2 0 0 0 17.8 3H12v18h5.8A2.2 2.2 0 0 0 20 18.8V5.2Z" stroke="#fca311" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="M12 3v18" stroke="#ffb83a" stroke-width="1.7"/>
                        <path d="M7.2 8h2.8M7.2 11h2.8" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="brand-text">
                    <strong>LibAuto<span class="gold">Ent</span></strong>
                    <span>La Solution qui Gère</span>
                </div>
                <button type="button" class="sidebar-vis-btn" id="sidebarHide" aria-label="Masquer le panneau" title="Masquer le panneau">
                    <svg class="icon-hide-panel" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-6 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94"/>
                        <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c6 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"/>
                        <path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/>
                        <path d="M1 1l22 22" stroke-linecap="round"/>
                    </svg>
                </button>
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

                <div class="menu-group" data-menu="client">
                    <button type="button" class="menu-btn" aria-expanded="false">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M16 19a4 4 0 0 0-8 0"/><circle cx="12" cy="9" r="3.5"/><path d="M19 19a3.5 3.5 0 0 0-2.2-3.2M5 19a3.5 3.5 0 0 1 2.2-3.2M17.5 8.2a3 3 0 1 1-1.2-4"/></svg>
                        </span>
                        <span class="menu-label">État Vente</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('reglement-vente') }}">Balance des Ventes</a>
                        <a href="{{ route('balance-vente') }}">Rapport Revenue</a>
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
                        <a href="{{ route('utilisateurs') }}">Utilisateurs</a>
                        <a href="#">Paramètres Système</a>
                    </div>
                </div>

                <div class="menu-group is-muted" data-menu="fournisseur" aria-disabled="true">
                    <button type="button" class="menu-btn" aria-expanded="false" disabled tabindex="-1">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M3 10.5 12 4l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z" stroke-linejoin="round"/></svg>
                        </span>
                        <span class="menu-label">Fournisseur</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="{{ route('bon-achat') }}" tabindex="-1">Bon Achat</a>
                        <a href="{{ route('reglement-achat') }}" tabindex="-1">Règlement Achat</a>
                        <a href="{{ route('balance-achat') }}" tabindex="-1">Balance Achat</a>
                    </div>
                </div>

                <div class="menu-group is-muted" data-menu="charges" aria-disabled="true">
                    <button type="button" class="menu-btn" aria-expanded="false" disabled tabindex="-1">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M4 7h16v12H4z"/><path d="M8 7V5.5A1.5 1.5 0 0 1 9.5 4h5A1.5 1.5 0 0 1 16 5.5V7"/><path d="M8 12h8"/></svg>
                        </span>
                        <span class="menu-label">Charges</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="#" tabindex="-1">État Charge</a>
                        <a href="#" tabindex="-1">Balance Charges</a>
                    </div>
                </div>

                <div class="menu-group is-muted" data-menu="rapports" aria-disabled="true">
                    <button type="button" class="menu-btn" aria-expanded="false" disabled tabindex="-1">
                        <span class="menu-ico">
                            <svg viewBox="0 0 24 24"><path d="M4 19V5"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-3"/><path d="M4 19h16"/></svg>
                        </span>
                        <span class="menu-label">Rapports</span>
                        <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </button>
                    <div class="submenu">
                        <a href="#" tabindex="-1">Relevé Compte Frns</a>
                        <a href="#" tabindex="-1">Relevé Compte Client</a>
                        <a href="#" tabindex="-1">Relevé Compte Stock</a>
                    </div>
                </div>
            </nav>

            <div class="sidebar-foot">
                <div class="avatar">AD</div>
                <div class="user-meta">
                    <strong>Administrateur</strong>
                    <span>admin@libautoent.com</span>
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
                        <p class="welcome-line">Bienvenue sur <span>LibAutoEnt</span></p>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="dash-stats" aria-label="Indicateurs ventes">
                    <article class="stat-card" data-tone="livres">
                        <div class="stat-top">
                            <span class="stat-ico" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                            </span>
                        </div>
                        <div class="stat-label">Nbrs Bon Livrés</div>
                        <div class="stat-value" id="statBonsLivres">0</div>
                        <div class="stat-hint">Bons de vente enregistrés</div>
                    </article>

                    <article class="stat-card" data-tone="ventes">
                        <div class="stat-top">
                            <span class="stat-ico" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M12 3v18"/><path d="M7 7.5c0-1.7 2.2-3 5-3s5 1.3 5 3-2.2 3-5 3-5 1.3-5 3 2.2 3 5 3 5-1.3 5-3"/></svg>
                            </span>
                        </div>
                        <div class="stat-label">Total Ventes</div>
                        <div class="stat-value" id="statTotalVentes">0.00 <small>DH</small></div>
                        <div class="stat-hint">Chiffre d’affaires cumulé</div>
                    </article>

                    <article class="stat-card" data-tone="solde">
                        <div class="stat-top">
                            <span class="stat-ico" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18"/><path d="M8 14h4"/></svg>
                            </span>
                        </div>
                        <div class="stat-label">Total Solde</div>
                        <div class="stat-value" id="statTotalSolde">0.00 <small>DH</small></div>
                        <div class="stat-hint">Reste à encaisser</div>
                    </article>
                </div>

                <div class="toolbar">
                    <div class="filters">
                        <div class="filter-field">
                            <label for="filterMois">Mois</label>
                            <select id="filterMois">
                                <option value="">Tous</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label>De — À</label>
                            <div class="filter-range">
                                <input type="date" id="filterDe" aria-label="Date début">
                                <span>à</span>
                                <input type="date" id="filterA" aria-label="Date fin">
                            </div>
                        </div>
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn btn-add" id="btnAjouter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                            Ajouter
                        </button>
                        <button type="button" class="btn btn-close" id="btnFermerFiltres">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                            Fermer
                        </button>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="bonsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>N° Bn</th>
                                    <th>Nom Client</th>
                                    <th>Montant Bn</th>
                                    <th>Montant Payé</th>
                                    <th>Solde</th>
                                    <th>Mode</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bonsBody">
                                <tr class="empty-row">
                                    <td colspan="8" class="empty">Aucun bon — cliquez sur Ajouter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="modalBon" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalBonTitle">Nouveau <span>Bon</span></h2>
                <button type="button" class="btn btn-close" id="bonModalX" style="padding:0.4rem 0.65rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formBon" autocomplete="off" onsubmit="return false;">
                    <input type="hidden" id="bonEditId" value="">
                    <div class="form-grid">
                        <div class="field">
                            <label for="bonDate">Date</label>
                            <input type="date" id="bonDate" class="readonly" readonly>
                        </div>
                        <div class="field field-narrow">
                            <label for="bonNumero">N° Bn</label>
                            <input type="text" id="bonNumero" placeholder="N°">
                        </div>
                        <div class="field">
                            <label for="bonClient">Nom Client</label>
                            <input type="text" id="bonClient" required>
                        </div>
                        <div class="field field-narrow">
                            <label for="bonMode">Mode</label>
                            <select id="bonMode">
                                <option value="Esp">Esp</option>
                                <option value="Chq">Chq</option>
                                <option value="Vir">Vir</option>
                                <option value="Vers">Vers</option>
                                <option value="Crédit">Crédit</option>
                            </select>
                        </div>
                    </div>

                    <div class="lines-wrap">
                        <div class="lines-scroll-wrap">
                            <div class="table-scroll" id="linesScroll">
                                <table class="lines-table">
                                    <thead>
                                        <tr>
                                            <th style="width:90px">Réf</th>
                                            <th style="width:110px">Code Barre</th>
                                            <th>Article (Stock)</th>
                                            <th style="width:80px">Quantité</th>
                                            <th style="width:95px">Prix/U</th>
                                            <th style="width:105px">Sous-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="linesBody"></tbody>
                                </table>
                            </div>
                            <div class="lines-scroll-btns">
                                <button type="button" class="btn-scroll" id="btnScrollUp" title="Défiler vers le haut" aria-label="Défiler vers le haut">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 14l6-6 6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                                <button type="button" class="btn-scroll" id="btnScrollDown" title="Défiler vers le bas" aria-label="Défiler vers le bas">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 10l6 6 6-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </div>
                        </div>
                        <div style="padding:0 0.65rem 0.55rem;display:flex;justify-content:flex-start;">
                            <button type="button" class="btn-add-line" id="btnAddArticle">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                                Ajouter un article
                            </button>
                        </div>
                    </div>

                    <div class="total-bar">
                        <span>Total</span>
                        <strong id="bonGrandTotal">0.00 DH</strong>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnBonValider">Valider</button>
                        <button type="button" class="btn btn-view-soft" id="btnBonVisualiser">Visualiser</button>
                        <button type="button" class="btn btn-print-soft" id="btnBonImprimer">Imprimer</button>
                        <button type="button" class="btn btn-close" id="btnBonFermer">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="preview-backdrop" id="previewBon" role="dialog" aria-modal="true" aria-labelledby="previewBonTitle">
        <div class="preview-panel">
            <div class="preview-head">
                <h2 id="previewBonTitle">Visualiser <span>Bon</span></h2>
                <div class="preview-actions">
                    <button type="button" class="btn btn-print-soft" id="btnPreviewImprimer" style="padding:0.45rem 0.85rem;">Imprimer</button>
                    <button type="button" class="btn btn-close" id="btnPreviewFermer" style="padding:0.45rem 0.85rem;">Fermer</button>
                </div>
            </div>
            <div class="preview-body">
                <iframe id="previewFrame" title="Aperçu du bon"></iframe>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=3"></script>
    <script src="{{ asset('js/sidebar-menu.js') }}?v=3"></script>
    <script src="{{ asset('js/table-actions.js') }}?v=7"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=10"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=10"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const modalBon = document.getElementById('modalBon');
        const modalTitle = document.getElementById('modalBonTitle');
        const linesBody = document.getElementById('linesBody');

        let editMode = false;
        let viewMode = false;

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            if (!modalBon.classList.contains('show')) document.body.style.overflow = '';
        }
        menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        function todayISO() {
            return new Date().toISOString().slice(0, 10);
        }
        function toIsoFromFr(fr) {
            if (!fr) return '';
            if (fr.indexOf('-') !== -1) return fr;
            var p = fr.split('/');
            if (p.length === 3) return p[2] + '-' + p[1] + '-' + p[0];
            return '';
        }
        function parseDateTs(s) {
            if (!s) return 0;
            if (s.indexOf('-') !== -1) {
                var a = s.split('-');
                return new Date(Number(a[0]), Number(a[1]) - 1, Number(a[2])).getTime() || 0;
            }
            var p = s.split('/');
            if (p.length !== 3) return 0;
            return new Date(Number(p[2]), Number(p[1]) - 1, Number(p[0])).getTime() || 0;
        }
        function monthOf(s) {
            var ts = parseDateTs(s);
            if (!ts) return 0;
            return new Date(ts).getMonth() + 1;
        }
        function fmt(n) { return (Number(n) || 0).toFixed(2); }
        function money(n) { return fmt(n) + ' DH'; }

        function fmtMoneyHtml(n) {
            return fmt(n) + ' <small>DH</small>';
        }

        function refreshDashStats() {
            var stats = window.VenteStore && VenteStore.getDashboardStats
                ? VenteStore.getDashboardStats()
                : { nbrBonsLivres: 0, totalVentes: 0, totalSolde: 0 };
            document.getElementById('statBonsLivres').textContent = String(stats.nbrBonsLivres || 0);
            document.getElementById('statTotalVentes').innerHTML = fmtMoneyHtml(stats.totalVentes);
            document.getElementById('statTotalSolde').innerHTML = fmtMoneyHtml(stats.totalSolde);
        }

        function filteredBons() {
            if (!window.VenteStore) return [];
            var mois = document.getElementById('filterMois').value;
            var de = document.getElementById('filterDe').value;
            var a = document.getElementById('filterA').value;
            var deTs = de ? parseDateTs(de) : 0;
            var aTs = a ? parseDateTs(a) : 0;
            return VenteStore.getBons().filter(function (b) {
                var ts = parseDateTs(b.date);
                if (mois && String(monthOf(b.date)) !== String(mois)) return false;
                if (deTs && ts < deTs) return false;
                if (aTs && ts > aTs) return false;
                return true;
            });
        }

        function renderBons() {
            var body = document.getElementById('bonsBody');
            if (!body) return;
            var list = filteredBons();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="8" class="empty">Aucun bon — cliquez sur Ajouter</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (b) {
                return '' +
                    '<tr data-id="' + b.id + '">' +
                    '<td>' + (b.date || '—') + '</td>' +
                    '<td>' + (b.numero || '—') + '</td>' +
                    '<td class="nom-cell">' + (b.client || '—') + '</td>' +
                    '<td class="money">' + money(b.montant) + '</td>' +
                    '<td class="money">' + money(b.montantPaye) + '</td>' +
                    '<td class="money col-solde">' + money(b.solde) + '</td>' +
                    '<td><span class="mode-badge">' + (b.typePaie || 'Crédit') + '</span></td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');
            if (window.TableActions) {
                TableActions.fillCells('#bonsBody .actions-cell', ['view', 'edit', 'delete']);
            }
        }

        function catalog() {
            return (window.StockStore && StockStore.getCatalogue) ? StockStore.getCatalogue() : [];
        }

        function findProductById(id) {
            if (!id) return null;
            var list = catalog();
            for (var i = 0; i < list.length; i++) {
                if (list[i].id === id) return list[i];
            }
            return null;
        }

        function normSearch(s) {
            return String(s || '').trim().toLowerCase();
        }

        function findProductsByRef(q) {
            q = normSearch(q);
            if (!q) return [];
            return catalog().filter(function (p) {
                return normSearch(p.ref).indexOf(q) !== -1;
            }).sort(function (a, b) {
                var ar = normSearch(a.ref);
                var br = normSearch(b.ref);
                if (ar === q && br !== q) return -1;
                if (br === q && ar !== q) return 1;
                if (ar.indexOf(q) === 0 && br.indexOf(q) !== 0) return -1;
                if (br.indexOf(q) === 0 && ar.indexOf(q) !== 0) return 1;
                return ar.localeCompare(br, 'fr');
            });
        }

        function findProductsByCodeBarre(q) {
            q = String(q || '').trim().toUpperCase();
            if (!q) return [];
            return catalog().filter(function (p) {
                return String(p.codeBarre || '').toUpperCase().indexOf(q) !== -1;
            }).sort(function (a, b) {
                var ac = String(a.codeBarre || '').toUpperCase();
                var bc = String(b.codeBarre || '').toUpperCase();
                if (ac === q && bc !== q) return -1;
                if (bc === q && ac !== q) return 1;
                return ac.localeCompare(bc);
            });
        }

        function selectProductOnLine(tr, p, opts) {
            opts = opts || {};
            var prod = tr.querySelector('.ln-prod');
            if (p) {
                if (prod) prod.value = p.id;
                fillFromProduct(tr, p);
                if (opts.focusQty) {
                    var qte = tr.querySelector('.ln-qte');
                    if (qte) { qte.focus(); qte.select(); }
                }
            } else {
                if (prod) prod.value = '';
                fillFromProduct(tr, null);
            }
            return p;
        }

        function applySearchOnLine(tr, field) {
            var refInput = tr.querySelector('.ln-ref');
            var cbInput = tr.querySelector('.ln-cb');
            var matches = [];
            if (field === 'ref') {
                matches = findProductsByRef(refInput.value);
            } else {
                matches = findProductsByCodeBarre(cbInput.value);
            }
            tr._matchList = matches;
            tr._matchIndex = matches.length ? 0 : -1;
            if (!matches.length) {
                if (String((field === 'ref' ? refInput : cbInput).value || '').trim()) {
                    (field === 'ref' ? refInput : cbInput).style.borderColor = 'rgba(184,92,56,0.65)';
                }
                return null;
            }
            (field === 'ref' ? refInput : cbInput).style.borderColor = '';
            return selectProductOnLine(tr, matches[0], { focusQty: field === 'cb' });
        }

        function cycleMatchOnLine(tr, field, dir) {
            var matches = tr._matchList;
            if (!matches || !matches.length) {
                applySearchOnLine(tr, field);
                matches = tr._matchList || [];
            }
            if (!matches.length) return;
            var idx = typeof tr._matchIndex === 'number' ? tr._matchIndex : 0;
            idx = (idx + dir + matches.length) % matches.length;
            tr._matchIndex = idx;
            selectProductOnLine(tr, matches[idx], { focusQty: false });
            var refInput = tr.querySelector('.ln-ref');
            var cbInput = tr.querySelector('.ln-cb');
            if (field === 'ref' && refInput) {
                refInput.focus();
                refInput.setSelectionRange(refInput.value.length, refInput.value.length);
            } else if (cbInput) {
                cbInput.focus();
                cbInput.setSelectionRange(cbInput.value.length, cbInput.value.length);
            }
        }

        function productOptionsHtml(selectedId) {
            var list = catalog();
            var html = '<option value="">— Choisir / rechercher —</option>';
            list.forEach(function (p) {
                var id = p.id || '';
                var label = (p.ref ? p.ref + ' — ' : '') + (p.designation || 'Sans nom') +
                    (p.codeBarre ? ' [' + String(p.codeBarre).toUpperCase() + ']' : '');
                var sel = selectedId && selectedId === id ? ' selected' : '';
                html += '<option value="' + String(id).replace(/"/g, '') + '"' + sel +
                    ' data-ref="' + String(p.ref || '').replace(/"/g, '&quot;') + '"' +
                    ' data-cb="' + String(p.codeBarre || '').replace(/"/g, '&quot;') + '">' +
                    String(label).replace(/</g, '&lt;') + '</option>';
            });
            return html;
        }

        function scrollLines(dir) {
            var box = document.getElementById('linesScroll');
            if (!box) return;
            box.scrollBy({ top: dir * 72, behavior: 'smooth' });
            updateScrollButtons();
        }

        function updateScrollButtons() {
            var box = document.getElementById('linesScroll');
            var up = document.getElementById('btnScrollUp');
            var down = document.getElementById('btnScrollDown');
            if (!box || !up || !down) return;
            var max = Math.max(0, box.scrollHeight - box.clientHeight);
            up.disabled = box.scrollTop <= 2;
            down.disabled = box.scrollTop >= max - 2;
        }

        function recalcLine(tr) {
            var qte = parseFloat(tr.querySelector('.ln-qte').value) || 0;
            var pu = parseFloat(tr.querySelector('.ln-pu').value) || 0;
            tr.querySelector('.ln-st').value = fmt(qte * pu);
            recalcTotal();
        }

        function recalcTotal() {
            var total = 0;
            linesBody.querySelectorAll('tr').forEach(function (tr) {
                total += parseFloat(tr.querySelector('.ln-st').value) || 0;
            });
            document.getElementById('bonGrandTotal').textContent = money(total);
            return total;
        }

        function fillFromProduct(tr, p) {
            if (!p) {
                tr.querySelector('.ln-ref').value = '';
                tr.querySelector('.ln-cb').value = '';
                tr.querySelector('.ln-pu').value = fmt(0);
                recalcLine(tr);
                return;
            }
            tr.querySelector('.ln-ref').value = p.ref || '';
            tr.querySelector('.ln-cb').value = String(p.codeBarre || '').toUpperCase();
            if (p.pv != null) tr.querySelector('.ln-pu').value = fmt(p.pv);
            recalcLine(tr);
        }

        function addLine(data) {
            data = data || {};
            var list = catalog();
            if (!list.length && !data.produitId) {
                alert('Aucun article dans Catégorie Produit (Stock).\nAjoutez d’abord des produits au catalogue.');
            }
            var selectedId = data.produitId || '';
            if (!selectedId && data.ref) {
                var hit = findProductsByRef(data.ref)[0];
                if (hit) selectedId = hit.id;
            }
            if (!selectedId && data.codeBarre) {
                var hitCb = findProductsByCodeBarre(data.codeBarre)[0];
                if (hitCb) selectedId = hitCb.id;
            }
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><input type="text" class="ln-ref" autocomplete="off" spellcheck="false" placeholder="Réf…" value=""></td>' +
                '<td><input type="text" class="ln-cb" autocomplete="off" spellcheck="false" placeholder="Code barre…" value=""></td>' +
                '<td><select class="ln-prod">' + productOptionsHtml(selectedId) + '</select></td>' +
                '<td><input type="number" class="ln-qte" min="0" step="1" value="' + (data.qte != null ? data.qte : 1) + '"></td>' +
                '<td><input type="number" class="ln-pu" min="0" step="0.01" value="' + fmt(data.pu || data.prix || 0) + '"></td>' +
                '<td><input type="text" class="ln-st readonly" readonly value="0.00"></td>';
            linesBody.appendChild(tr);
            bindLine(tr);
            var p = findProductById(selectedId);
            if (p) fillFromProduct(tr, p);
            else if (data.pu != null || data.prix != null) recalcLine(tr);
            else recalcLine(tr);
            setTimeout(updateScrollButtons, 0);
            return tr;
        }

        function bindLine(tr) {
            var prod = tr.querySelector('.ln-prod');
            var ref = tr.querySelector('.ln-ref');
            var cb = tr.querySelector('.ln-cb');
            var qte = tr.querySelector('.ln-qte');
            var pu = tr.querySelector('.ln-pu');

            prod.addEventListener('change', function () {
                fillFromProduct(tr, findProductById(prod.value));
                tr._matchList = null;
                tr._matchIndex = -1;
                ref.style.borderColor = '';
                cb.style.borderColor = '';
            });

            function bindSearchField(input, field) {
                input.addEventListener('input', function () {
                    if (field === 'cb') input.value = input.value.toUpperCase();
                    input.style.borderColor = '';
                    tr._matchList = null;
                });
                input.addEventListener('keydown', function (e) {
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        cycleMatchOnLine(tr, field, 1);
                        return;
                    }
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        cycleMatchOnLine(tr, field, -1);
                        return;
                    }
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        var found = applySearchOnLine(tr, field);
                        if (found && field === 'cb') {
                            var row = addLine();
                            var nextCb = row && row.querySelector('.ln-cb');
                            if (nextCb) nextCb.focus();
                            var box = document.getElementById('linesScroll');
                            if (box) box.scrollTop = box.scrollHeight;
                            updateScrollButtons();
                        } else if (found) {
                            qte.focus();
                            qte.select();
                        }
                    }
                });
                input.addEventListener('blur', function () {
                    if (!String(input.value || '').trim()) return;
                    if (!prod.value) applySearchOnLine(tr, field);
                });
            }

            bindSearchField(ref, 'ref');
            bindSearchField(cb, 'cb');

            qte.addEventListener('input', function () { recalcLine(tr); });
            pu.addEventListener('input', function () { recalcLine(tr); });
        }

        function collectLignes() {
            var rows = [];
            linesBody.querySelectorAll('tr').forEach(function (tr) {
                var produitId = tr.querySelector('.ln-prod').value;
                var p = findProductById(produitId);
                if (!p) return;
                var qte = parseFloat(tr.querySelector('.ln-qte').value) || 0;
                var pu = parseFloat(tr.querySelector('.ln-pu').value) || 0;
                rows.push({
                    produitId: p.id,
                    ref: p.ref || '',
                    codeBarre: String(p.codeBarre || '').toUpperCase(),
                    designation: p.designation || '',
                    produit: p.designation || '',
                    categorie: p.categorie || '',
                    qte: qte,
                    pu: pu,
                    sousTotal: qte * pu
                });
            });
            return rows;
        }

        function setFormReadonly(ro) {
            document.getElementById('bonClient').readOnly = ro;
            document.getElementById('bonMode').disabled = ro;
            document.getElementById('bonNumero').readOnly = ro;
            linesBody.querySelectorAll('input, select').forEach(function (inp) {
                if (inp.classList.contains('ln-st')) return;
                if (inp.tagName === 'SELECT') {
                    inp.disabled = ro;
                } else if (inp.classList.contains('ln-ref') || inp.classList.contains('ln-cb')) {
                    inp.readOnly = ro;
                    inp.classList.toggle('readonly', ro);
                    if (ro) inp.setAttribute('tabindex', '-1');
                    else inp.removeAttribute('tabindex');
                } else {
                    inp.readOnly = ro;
                }
            });
            var btnAdd = document.getElementById('btnAddArticle');
            if (btnAdd) {
                btnAdd.style.display = ro ? 'none' : '';
                btnAdd.disabled = !!ro;
            }
            document.getElementById('btnBonValider').style.display = ro ? 'none' : '';
            setTimeout(updateScrollButtons, 0);
        }

        function openModal(bon, mode) {
            editMode = mode === 'edit';
            viewMode = mode === 'view';
            document.getElementById('formBon').reset();
            document.getElementById('bonEditId').value = bon && bon.id ? bon.id : '';
            linesBody.innerHTML = '';

            if (viewMode) modalTitle.innerHTML = 'Voir <span>Bon</span>';
            else if (editMode) modalTitle.innerHTML = 'Modifier <span>Bon</span>';
            else modalTitle.innerHTML = 'Nouveau <span>Bon</span>';

            document.getElementById('bonDate').value = bon ? (toIsoFromFr(bon.date) || todayISO()) : todayISO();
            document.getElementById('bonClient').value = bon ? (bon.client || '') : '';
            document.getElementById('bonMode').value = bon ? (bon.typePaie || 'Crédit') : 'Crédit';
            document.getElementById('bonNumero').value = bon ? (bon.numero || '') : '';

            var lignes = (bon && bon.lignes && bon.lignes.length) ? bon.lignes : [{}];
            lignes.forEach(function (l) { addLine(l); });
            setFormReadonly(viewMode);
            modalBon.classList.add('show');
            document.body.style.overflow = 'hidden';
            if (!viewMode) {
                var firstCb = linesBody.querySelector('.ln-cb');
                if (firstCb) setTimeout(function () { firstCb.focus(); }, 50);
            }
        }

        function closeModal() {
            modalBon.classList.remove('show');
            document.body.style.overflow = '';
            editMode = false;
            viewMode = false;
            setFormReadonly(false);
        }

        document.getElementById('btnAjouter').addEventListener('click', function () { openModal(null, 'add'); });
        document.getElementById('btnAddArticle').addEventListener('click', function () {
            if (viewMode) return;
            var row = addLine();
            var focusEl = row && row.querySelector('.ln-cb');
            if (focusEl) focusEl.focus();
            var box = document.getElementById('linesScroll');
            if (box) box.scrollTop = box.scrollHeight;
            updateScrollButtons();
        });
        document.getElementById('btnScrollUp').addEventListener('click', function () { scrollLines(-1); });
        document.getElementById('btnScrollDown').addEventListener('click', function () { scrollLines(1); });
        document.getElementById('linesScroll').addEventListener('scroll', updateScrollButtons);
        document.getElementById('bonModalX').addEventListener('click', closeModal);
        document.getElementById('btnBonFermer').addEventListener('click', closeModal);
        modalBon.addEventListener('click', function (e) { if (e.target === modalBon) closeModal(); });

        document.getElementById('btnFermerFiltres').addEventListener('click', function () {
            document.getElementById('filterMois').value = '';
            document.getElementById('filterDe').value = '';
            document.getElementById('filterA').value = '';
            renderBons();
        });

        document.getElementById('filterMois').addEventListener('change', renderBons);
        document.getElementById('filterDe').addEventListener('change', renderBons);
        document.getElementById('filterA').addEventListener('change', renderBons);

        document.getElementById('btnBonValider').addEventListener('click', function () {
            if (viewMode || !window.VenteStore) return;
            var client = document.getElementById('bonClient').value.trim();
            if (!client) {
                alert('Veuillez renseigner le Nom Client.');
                return;
            }
            var lignes = collectLignes();
            if (!lignes.length) {
                if (!catalog().length) {
                    alert('Catalogue stock vide. Ajoutez des articles dans Stock → Catégorie Produit.');
                } else {
                    alert('Choisissez un article du catalogue stock pour chaque ligne.');
                }
                return;
            }
            var total = lignes.reduce(function (s, l) { return s + (l.sousTotal || 0); }, 0);
            var mode = document.getElementById('bonMode').value || 'Crédit';
            var paye = (mode === 'Crédit') ? 0 : total;
            var solde = Math.max(0, total - paye);
            var payload = {
                date: VenteStore.formatDateFR(document.getElementById('bonDate').value),
                numero: document.getElementById('bonNumero').value.trim(),
                client: client,
                montant: total,
                typePaie: mode,
                montantPaye: paye,
                solde: solde,
                lignes: lignes
            };
            var id = document.getElementById('bonEditId').value;
            if (id) VenteStore.updateBon(id, payload);
            else VenteStore.addBon(payload);
            closeModal();
            refreshDashboard();
        });

        function escapeHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function buildBonDoc() {
            var client = document.getElementById('bonClient').value.trim() || '—';
            var date = document.getElementById('bonDate').value || '';
            var num = document.getElementById('bonNumero').value.trim() || '—';
            var mode = document.getElementById('bonMode').value || '';
            var lignes = collectLignes();
            var rows = lignes.length
                ? lignes.map(function (l) {
                    return '<tr><td>' + escapeHtml(l.ref || '') + '</td><td>' +
                        escapeHtml(String(l.codeBarre || '').toUpperCase()) + '</td><td>' +
                        escapeHtml(l.designation || '') + '</td><td>' + escapeHtml(l.qte) + '</td><td>' +
                        escapeHtml(fmt(l.pu)) + '</td><td>' + escapeHtml(fmt(l.sousTotal)) + '</td></tr>';
                }).join('')
                : '<tr><td colspan="6">Aucun article</td></tr>';
            var total = document.getElementById('bonGrandTotal').textContent || '0.00 DH';
            var body =
                '<h1>Bon de vente</h1>' +
                '<p>Date : ' + escapeHtml(date) + ' | N° : ' + escapeHtml(num) +
                ' | Client : ' + escapeHtml(client) + ' | Mode : ' + escapeHtml(mode) + '</p>' +
                '<table><thead><tr><th>Réf</th><th>Code Barre</th><th>Désignation</th><th>Qté</th><th>Prix/U</th><th>Sous-Total</th></tr></thead>' +
                '<tbody>' + rows + '</tbody></table>' +
                '<p style="text-align:right;font-weight:bold;margin-top:16px">Total : ' + escapeHtml(total) + '</p>';
            var title = 'Bon ' + num;
            var html = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>' +
                escapeHtml(title) + '</title>' +
                '<style>body{font-family:Arial,sans-serif;padding:24px;color:#0d1b2a;margin:0}' +
                'table{width:100%;border-collapse:collapse;margin-top:16px}' +
                'th,td{border:1px solid #ccc;padding:8px;text-align:center;font-size:13px}' +
                'th{background:#14213d;color:#fff}h1{margin:0 0 8px;font-size:20px}' +
                '@media print{body{padding:12px}}</style></head><body>' + body + '</body></html>';
            return { title: title, body: body, html: html };
        }

        function writeFrame(frame, html) {
            var win = frame && frame.contentWindow;
            var doc = win && win.document;
            if (!doc) return null;
            doc.open();
            doc.write(html);
            doc.close();
            return win;
        }

        function printHtml(title, bodyHtml) {
            var html = '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>' +
                escapeHtml(title) + '</title>' +
                '<style>body{font-family:Arial,sans-serif;padding:24px;color:#0d1b2a}' +
                'table{width:100%;border-collapse:collapse;margin-top:16px}' +
                'th,td{border:1px solid #ccc;padding:8px;text-align:center;font-size:13px}' +
                'th{background:#14213d;color:#fff}h1{margin:0 0 8px;font-size:20px}' +
                '@media print{body{padding:0}}</style></head><body>' + bodyHtml + '</body></html>';

            var frame = document.getElementById('printFrame');
            if (!frame) {
                frame = document.createElement('iframe');
                frame.id = 'printFrame';
                frame.setAttribute('aria-hidden', 'true');
                frame.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;opacity:0;pointer-events:none;';
                document.body.appendChild(frame);
            }

            var win = writeFrame(frame, html);
            if (!win) {
                alert('Impression impossible sur ce navigateur.');
                return;
            }

            setTimeout(function () {
                try {
                    win.focus();
                    win.print();
                } catch (err) {
                    alert('Impossible d’ouvrir la feuille d’impression.');
                }
            }, 200);
        }

        function openPreview() {
            var doc = buildBonDoc();
            var preview = document.getElementById('previewBon');
            var frame = document.getElementById('previewFrame');
            writeFrame(frame, doc.html);
            preview.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            document.getElementById('previewBon').classList.remove('show');
            if (!modalBon.classList.contains('show')) document.body.style.overflow = '';
        }

        document.getElementById('btnBonVisualiser').addEventListener('click', openPreview);
        document.getElementById('btnPreviewFermer').addEventListener('click', closePreview);
        document.getElementById('previewBon').addEventListener('click', function (e) {
            if (e.target === document.getElementById('previewBon')) closePreview();
        });
        document.getElementById('btnPreviewImprimer').addEventListener('click', function () {
            var frame = document.getElementById('previewFrame');
            var win = frame && frame.contentWindow;
            if (!win) return;
            try {
                win.focus();
                win.print();
            } catch (err) {
                var doc = buildBonDoc();
                printHtml(doc.title, doc.body);
            }
        });

        document.getElementById('btnBonImprimer').addEventListener('click', function () {
            var doc = buildBonDoc();
            printHtml(doc.title, doc.body);
        });

        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    var b = VenteStore.getBon(tr.getAttribute('data-id'));
                    if (b) openModal(b, 'view');
                },
                edit: function (tr) {
                    var b = VenteStore.getBon(tr.getAttribute('data-id'));
                    if (b) openModal(b, 'edit');
                },
                delete: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var b = VenteStore.getBon(id);
                    if (!confirm('Supprimer le bon ' + ((b && b.numero) || '') + ' ?')) return;
                    VenteStore.deleteBon(id);
                    refreshDashboard();
                }
            });
            TableActions.bind('#bonsBody');
        }

        function refreshDashboard() {
            refreshDashStats();
            renderBons();
        }

        window.onCatalogueSynced = refreshDashboard;
        window.onVentesSynced = refreshDashboard;
        var bootDash = Promise.all([
            (window.StockStore && StockStore.initCatalogFromServer) ? StockStore.initCatalogFromServer() : Promise.resolve(),
            (window.VenteStore && VenteStore.initFromServer) ? VenteStore.initFromServer() : Promise.resolve()
        ]);
        bootDash.then(refreshDashboard);
        window.addEventListener('storage', refreshDashboard);
        window.addEventListener('focus', function () {
            var jobs = [];
            if (window.StockStore && StockStore.initCatalogFromServer) jobs.push(StockStore.initCatalogFromServer());
            if (window.VenteStore && VenteStore.initFromServer) jobs.push(VenteStore.initFromServer());
            Promise.all(jobs).then(refreshDashboard);
        });
    </script>
</body>
</html>
