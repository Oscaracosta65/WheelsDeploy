/**
 * com_wheels – Frontend JavaScript (ES5 compliant)
 *
 * @package     com_wheels
 * @copyright   Copyright (C) 2024 LottoExpert.net. All rights reserved.
 * @license     GNU General Public License version 2 or later
 *
 * IMPORTANT: All code in this file must be ES5 compliant.
 * Do NOT use: arrow functions, const/let, template literals,
 * optional chaining, or JavaScript modules.
 */

(function (document, window) {
    'use strict';

    /* ------------------------------------------------------------------ */
    /* Wheel filter: read URL query-string and apply active CSS class      */
    /* ------------------------------------------------------------------ */
    function initCategoryFilter() {
        var links = document.querySelectorAll('.wheels-category-link');

        if (!links || links.length === 0) {
            return;
        }

        var currentPath = window.location.pathname;

        for (var i = 0; i < links.length; i++) {
            var link = links[i];
            var href = link.getAttribute('href') || '';

            // Mark currently active category
            if (href && currentPath.indexOf(href) !== -1) {
                link.className = link.className + ' wheels-category-link--active';
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* Combination table: "sticky header" row counter & row highlight      */
    /* ------------------------------------------------------------------ */
    function initCombinationTable() {
        var table = document.getElementById('wheels-combination-table');

        if (!table) {
            return;
        }

        var rows = table.querySelectorAll('tbody tr');

        for (var r = 0; r < rows.length; r++) {
            rows[r].setAttribute('data-row', r + 1);
        }

        // Highlight row on hover (keyboard accessible)
        table.addEventListener('mouseover', function (e) {
            var target = e.target || e.srcElement;
            var row = target.parentNode;

            if (row && row.tagName === 'TR') {
                row.className = 'wheels-table__row--hover';
            }
        });

        table.addEventListener('mouseout', function (e) {
            var target = e.target || e.srcElement;
            var row = target.parentNode;

            if (row && row.tagName === 'TR') {
                row.className = '';
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /* Scroll-to-table button                                              */
    /* ------------------------------------------------------------------ */
    function initScrollButton() {
        var btn = document.getElementById('wheels-scroll-to-table');

        if (!btn) {
            return;
        }

        btn.addEventListener('click', function () {
            var target = document.getElementById('combination-table');

            if (!target) {
                return;
            }

            // Smooth-scroll polyfill (ES5)
            var targetTop = target.getBoundingClientRect().top + window.pageYOffset - 80;

            if (window.scrollTo) {
                window.scrollTo(0, targetTop);
            }
        });
    }

    /* ------------------------------------------------------------------ */
    /* Print wheel button                                                  */
    /* ------------------------------------------------------------------ */
    function initPrintButton() {
        var btn = document.getElementById('wheels-print-btn');

        if (!btn) {
            return;
        }

        btn.addEventListener('click', function () {
            window.print();
        });
    }

    /* ------------------------------------------------------------------ */
    /* Category accordion: collapse long lists on mobile                  */
    /* ------------------------------------------------------------------ */
    function initCategoryAccordion() {
        var sections = document.querySelectorAll('.wheels-category-section');

        if (!sections || sections.length === 0) {
            return;
        }

        // Only activate accordion on narrow viewports
        if (window.innerWidth >= 768) {
            return;
        }

        for (var s = 0; s < sections.length; s++) {
            (function (section) {
                var heading = section.querySelector('h2');
                var list    = section.querySelector('.wheels-category-list');

                if (!heading || !list) {
                    return;
                }

                // Collapse by default on mobile
                list.style.display = 'none';
                heading.setAttribute('role', 'button');
                heading.setAttribute('tabindex', '0');
                heading.setAttribute('aria-expanded', 'false');

                function toggleList() {
                    var expanded = heading.getAttribute('aria-expanded') === 'true';

                    list.style.display   = expanded ? 'none' : '';
                    heading.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                }

                heading.addEventListener('click', toggleList);
                heading.addEventListener('keydown', function (e) {
                    if (e.keyCode === 13 || e.keyCode === 32) {
                        e.preventDefault();
                        toggleList();
                    }
                });
            }(sections[s]));
        }
    }

    /* ------------------------------------------------------------------ */
    /* Bootstrap all initialisers on DOMContentLoaded                     */
    /* ------------------------------------------------------------------ */
    function onReady() {
        initCategoryFilter();
        initCombinationTable();
        initScrollButton();
        initPrintButton();
        initCategoryAccordion();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', onReady);
    } else {
        onReady();
    }

}(document, window));
