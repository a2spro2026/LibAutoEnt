@php
    $activePage = $activePage ?? '';
    $perms = session('libautoent_permissions');
    if (! is_array($perms)) {
        $statue = strtolower((string) session('libautoent_statut', 'gerant'));
        $perms = function_exists('libautoent_default_permissions')
            ? libautoent_default_permissions($statue)
            : [];
    }
    $canDash = ! empty($perms['dashboard.view']);
    $canStock = ! empty($perms['stock.view']);
    $canVentes = ! empty($perms['ventes.view']);
    $canConfig = ! empty($perms['config.view']);
    $isVendeur = strtolower((string) session('libautoent_statut', 'gerant')) === 'vendeur';
@endphp
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
        <a href="{{ route('dashboard') }}" class="menu-link {{ $activePage === 'dashboard' ? '' : 'menu-link--quiet' }}" @if($activePage === 'dashboard') aria-current="page" @endif>
            <span class="menu-ico">
                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
            </span>
            <span class="menu-label">Tableau de Bord</span>
        </a>

        <div class="menu-group{{ $canStock ? '' : ' is-muted' }}" data-menu="stock" @if(! $canStock) aria-disabled="true" @endif>
            <button type="button" class="menu-btn" aria-expanded="false" @if(! $canStock) disabled tabindex="-1" @endif>
                <span class="menu-ico">
                    <svg viewBox="0 0 24 24"><path d="M3 8.5 12 4l9 4.5-9 4.5L3 8.5Z"/><path d="M3 12.5 12 17l9-4.5"/><path d="M3 16.5 12 21l9-4.5"/></svg>
                </span>
                <span class="menu-label">Stock</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <div class="submenu">
                <a href="{{ route('categorie-produit') }}" class="{{ $activePage === 'categorie-produit' ? 'is-active' : '' }}" @if(! $canStock) tabindex="-1" @endif>Catégorie Produit</a>
                <a href="{{ route('etat-produit') }}" class="{{ $activePage === 'etat-produit' ? 'is-active' : '' }}" @if(! $canStock) tabindex="-1" @endif>État Produit</a>
            </div>
        </div>

        <div class="menu-group{{ $canVentes ? '' : ' is-muted' }}" data-menu="client" @if(! $canVentes) aria-disabled="true" @endif>
            <button type="button" class="menu-btn" aria-expanded="false" @if(! $canVentes) disabled tabindex="-1" @endif>
                <span class="menu-ico">
                    <svg viewBox="0 0 24 24"><path d="M16 19a4 4 0 0 0-8 0"/><circle cx="12" cy="9" r="3.5"/><path d="M19 19a3.5 3.5 0 0 0-2.2-3.2M5 19a3.5 3.5 0 0 1 2.2-3.2M17.5 8.2a3 3 0 1 1-1.2-4"/></svg>
                </span>
                <span class="menu-label">État Vente</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <div class="submenu">
                <a href="{{ route('reglement-vente') }}" class="{{ $activePage === 'reglement-vente' ? 'is-active' : '' }}" @if(! $canVentes) tabindex="-1" @endif>Balance des Ventes</a>
                <a href="{{ route('balance-vente') }}" class="{{ $activePage === 'balance-vente' ? 'is-active' : '' }}" @if(! $canVentes) tabindex="-1" @endif>Rapport Revenue</a>
            </div>
        </div>

        <div class="menu-group{{ $canConfig ? '' : ' is-muted' }}" data-menu="configuration" @if(! $canConfig) aria-disabled="true" @endif>
            <button type="button" class="menu-btn" aria-expanded="false" @if(! $canConfig) disabled tabindex="-1" @endif>
                <span class="menu-ico">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 3.5v2.2M12 18.3v2.2M4.9 6.5l1.6 1.5M17.5 16l1.6 1.5M3.5 12h2.2M18.3 12h2.2M4.9 17.5l1.6-1.5M17.5 8l1.6-1.5"/></svg>
                </span>
                <span class="menu-label">Configuration</span>
                <svg class="chevron" viewBox="0 0 24 24" fill="none"><path d="m6 9 6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
            <div class="submenu">
                <a href="{{ route('utilisateurs') }}" class="{{ $activePage === 'utilisateurs' ? 'is-active' : '' }}" @if(! $canConfig) tabindex="-1" @endif>Utilisateurs</a>
                <a href="#" @if(! $canConfig) tabindex="-1" @endif>Paramètres Système</a>
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
                    <svg viewBox="0 0 24 24"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-3"/></svg>
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
        <a href="{{ route('logout') }}" class="logout-btn" title="Déconnexion" aria-label="Déconnexion">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 7V5a2 2 0 0 1 2-2h7v18h-7a2 2 0 0 1-2-2v-2"/><path d="M15 12H3m0 0 3-3m-3 3 3 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</aside>
<script>window.__LIBAUTOENT_STATUT__=@json(strtolower((string) session('libautoent_statut', 'gerant')));</script>
<script>window.__LIBAUTOENT_PERMISSIONS__=@json(session('libautoent_permissions') ?: new \stdClass);</script>
<script src="{{ asset('js/user-role.js') }}?v=2"></script>
<script src="{{ asset('js/sidebar-menu.js') }}?v=3"></script>
