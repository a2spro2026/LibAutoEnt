/**
 * Menu latéral — toggle indépendant, reste ouvert jusqu'au reclic
 * État persisté en sessionStorage (survit à la navigation)
 */
(function (window, document) {
    'use strict';

    var STORAGE_KEY = '7ssabhani_sidebar_open';

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
                if (!group) return;
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

    function init() {
        bind();
        restore();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.SidebarMenu = { init: init, restore: restore };
})(window, document);
