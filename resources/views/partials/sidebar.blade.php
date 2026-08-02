@php
    $activePage = $activePage ?? '';
@endphp
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
        <a href="{{ route('dashboard') }}" class="menu-link {{ $activePage === 'dashboard' ? '' : 'menu-link--quiet' }}" @if($activePage === 'dashboard') aria-current="page" @endif>
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
                <a href="{{ route('bon-achat') }}" class="{{ $activePage === 'bon-achat' ? 'is-active' : '' }}">Bon Achat</a>
                <a href="{{ route('reglement-achat') }}" class="{{ $activePage === 'reglement-achat' ? 'is-active' : '' }}">Règlement Achat</a>
                <a href="{{ route('balance-achat') }}" class="{{ $activePage === 'balance-achat' ? 'is-active' : '' }}">Balance Achat</a>
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
                <a href="{{ route('bon-vente') }}" class="{{ $activePage === 'bon-vente' ? 'is-active' : '' }}">Bon Vente</a>
                <a href="{{ route('reglement-vente') }}" class="{{ $activePage === 'reglement-vente' ? 'is-active' : '' }}">Règlement Vente</a>
                <a href="{{ route('balance-vente') }}" class="{{ $activePage === 'balance-vente' ? 'is-active' : '' }}">Balance Vente</a>
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
                <a href="{{ route('categorie-produit') }}" class="{{ $activePage === 'categorie-produit' ? 'is-active' : '' }}">Catégorie Produit</a>
                <a href="{{ route('etat-produit') }}" class="{{ $activePage === 'etat-produit' ? 'is-active' : '' }}">État Produit</a>
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
<script src="{{ asset('js/sidebar-menu.js') }}?v=1"></script>
