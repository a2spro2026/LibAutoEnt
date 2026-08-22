/**
 * Menu latéral — sous-menus + masquer/afficher le panneau
 * État persisté en sessionStorage / localStorage
 */
(function (window, document) {
    'use strict';

    var STORAGE_KEY = 'libautoent_sidebar_open';
    var VIS_KEY = 'libautoent_sidebar_visible';

    function readOpen() {
        try {
            var raw = sessionStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : {};
        } catch (e) {
            return {};
        }
    }

    function writeOpen(map) {
        try {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(map));
        } catch (e) { /* ignore */ }
    }

    function isVisiblePreferred() {
        try {
            var v = localStorage.getItem(VIS_KEY);
            if (v === null) return true;
            return v !== '0';
        } catch (e) {
            return true;
        }
    }

    function setVisiblePreferred(visible) {
        try {
            localStorage.setItem(VIS_KEY, visible ? '1' : '0');
        } catch (e) { /* ignore */ }
    }

    function menuKey(group) {
        return group.getAttribute('data-menu') || '';
    }

    function setOpen(group, open) {
        var btn = group.querySelector('.menu-btn');
        if (open) {
            group.classList.add('open');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        } else {
            group.classList.remove('open');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        }
    }

    function restore() {
        var map = readOpen();
        document.querySelectorAll('.menu-group[data-menu]').forEach(function (group) {
            var key = menuKey(group);
            if (group.classList.contains('is-muted')) {
                setOpen(group, false);
                if (key && map[key]) {
                    delete map[key];
                    writeOpen(map);
                }
                return;
            }
            setOpen(group, !!(key && map[key]));
        });
    }

    function bind() {
        document.querySelectorAll('.menu-group[data-menu] > .menu-btn').forEach(function (btn) {
            if (btn.getAttribute('data-sidebar-bound') === '1') return;
            btn.setAttribute('data-sidebar-bound', '1');
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var group = btn.closest('.menu-group');
                if (!group || group.classList.contains('is-muted') || btn.disabled) return;
                var key = menuKey(group);
                var willOpen = !group.classList.contains('open');
                setOpen(group, willOpen);
                if (key) {
                    var map = readOpen();
                    if (willOpen) map[key] = true;
                    else delete map[key];
                    writeOpen(map);
                }
            });
        });
    }

    function ensureShowBtn() {
        var btn = document.getElementById('sidebarShow');
        if (btn) return btn;
        btn = document.createElement('button');
        btn.type = 'button';
        btn.id = 'sidebarShow';
        btn.className = 'sidebar-show-btn';
        btn.setAttribute('aria-label', 'Afficher le panneau');
        btn.title = 'Afficher le panneau';
        btn.innerHTML =
            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">' +
            '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>' +
            '<circle cx="12" cy="12" r="3"/>' +
            '</svg>';
        document.body.appendChild(btn);
        return btn;
    }

    function applyVisibility(visible) {
        var app = document.querySelector('.app');
        var sidebar = document.getElementById('sidebar');
        var overlay = document.getElementById('overlay');
        var showBtn = ensureShowBtn();

        if (!app || !sidebar) return;

        if (visible) {
            app.classList.remove('sidebar-hidden');
            showBtn.classList.remove('is-visible');
            showBtn.setAttribute('aria-hidden', 'true');
        } else {
            app.classList.add('sidebar-hidden');
            sidebar.classList.remove('open');
            if (overlay) overlay.classList.remove('show');
            document.body.style.overflow = '';
            showBtn.classList.add('is-visible');
            showBtn.setAttribute('aria-hidden', 'false');
        }
        setVisiblePreferred(visible);
    }

    function bindVisibility() {
        var hideBtn = document.getElementById('sidebarHide');
        var showBtn = ensureShowBtn();

        if (hideBtn && hideBtn.getAttribute('data-vis-bound') !== '1') {
            hideBtn.setAttribute('data-vis-bound', '1');
            hideBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                applyVisibility(false);
            });
        }

        if (showBtn.getAttribute('data-vis-bound') !== '1') {
            showBtn.setAttribute('data-vis-bound', '1');
            showBtn.addEventListener('click', function (e) {
                e.preventDefault();
                applyVisibility(true);
            });
        }

        // Desktop only restore — on mobile keep default overlay drawer behavior
        if (window.matchMedia && window.matchMedia('(min-width: 901px)').matches) {
            applyVisibility(isVisiblePreferred());
        } else {
            applyVisibility(true);
        }
    }

    function init() {
        bind();
        restore();
        bindVisibility();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.SidebarMenu = {
        init: init,
        restore: restore,
        show: function () { applyVisibility(true); },
        hide: function () { applyVisibility(false); }
    };
})(window, document);
