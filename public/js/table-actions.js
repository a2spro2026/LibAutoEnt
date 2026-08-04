/**
 * Actions tableaux 7ssabHani — onclick direct (fiable sur tous navigateurs)
 */
(function (window, document) {
    'use strict';

    var pageHandlers = {};

    function iconsHtml(actions) {
        actions = actions || ['view', 'edit', 'delete'];
        var map = {
            view: { title: 'Voir', svg: '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/>' },
            edit: { title: 'Modifier', svg: '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>' },
            delete: { title: 'Supprimer', svg: '<path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/>' },
            import: { title: 'Importer', svg: '<path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/>' },
            pdf: { title: 'PDF', svg: '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V9l-5-6Z"/><path d="M14 3v6h6"/><path d="M8 13h8M8 17h5"/>' },
            print: { title: 'Imprimer', svg: '<path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/>' },
            suspend: { title: 'Suspendre', svg: '<circle cx="12" cy="12" r="9"/><path d="M10 9v6M14 9v6"/>' }
        };

        var html = '<div class="actions">';
        for (var i = 0; i < actions.length; i++) {
            var key = actions[i];
            var a = map[key];
            if (!a) continue;
            html +=
                '<button type="button" class="icon-btn icon-' + key + '"' +
                ' data-action="' + key + '"' +
                ' title="' + a.title + '"' +
                ' aria-label="' + a.title + '"' +
                ' onclick="return window.TableActions.handle(event,\'' + key + '\')">' +
                '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" style="pointer-events:none">' +
                a.svg +
                '</svg></button>';
        }
        html += '</div>';
        return html;
    }

    function findRow(el) {
        var node = el;
        while (node && node !== document.body) {
            if (node.tagName === 'TR') return node;
            node = node.parentNode;
        }
        return null;
    }

    function handle(event, action) {
        try {
            if (event) {
                if (event.preventDefault) event.preventDefault();
                if (event.stopPropagation) event.stopPropagation();
            }
            var btn = event && (event.currentTarget || event.srcElement);
            if (!btn) return false;
            // Si on a cliqué le SVG, remonter au bouton
            while (btn && btn.tagName !== 'BUTTON' && btn !== document.body) {
                btn = btn.parentNode;
            }
            if (!btn || btn.tagName !== 'BUTTON') return false;

            var tr = findRow(btn);
            if (!tr) return false;

            action = action || btn.getAttribute('data-action');
            if (!action) return false;

            if (typeof pageHandlers[action] === 'function') {
                pageHandlers[action](tr, btn, event);
                return false;
            }

            var label = (tr.cells && tr.cells[1]) ? String(tr.cells[1].textContent || '').replace(/^\s+|\s+$/g, '') : '';
            if (action === 'delete') {
                if (window.confirm('Supprimer ' + (label || 'cette ligne') + ' ?')) {
                    if (tr.parentNode) tr.parentNode.removeChild(tr);
                }
            } else if (action === 'view') {
                window.alert('Voir : ' + (label || 'élément'));
            } else if (action === 'edit') {
                window.alert('Modifier : ' + (label || 'élément'));
            } else if (action === 'import') {
                window.alert('Importer : ' + (label || 'élément'));
            } else if (action === 'pdf') {
                window.alert('PDF : ' + (label || 'élément'));
            }
        } catch (err) {
            if (window.console && console.error) console.error('TableActions', err);
            window.alert('Action impossible');
        }
        return false;
    }

    function setHandlers(handlers) {
        pageHandlers = handlers || {};
    }

    function bind(root, handlers) {
        // Compat: enregistre les handlers de page
        if (handlers) setHandlers(handlers);
        // Ré-attache aussi un listener (secours) sans dépendre de closest()
        var el = typeof root === 'string' ? document.querySelector(root) : root;
        if (!el || el.__taBound) return;
        el.__taBound = true;
        el.onclick = function (e) {
            var t = e.target || e.srcElement;
            while (t && t !== el) {
                if (t.tagName === 'BUTTON' && t.getAttribute && t.getAttribute('data-action')) {
                    return handle(e, t.getAttribute('data-action'));
                }
                t = t.parentNode;
            }
        };
    }

    function fillCells(selector, actions) {
        var nodes = document.querySelectorAll(selector);
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].innerHTML = iconsHtml(actions);
        }
    }

    window.TableActions = {
        iconsHtml: iconsHtml,
        handle: handle,
        setHandlers: setHandlers,
        bind: bind,
        fillCells: fillCells
    };
})(window, document);
