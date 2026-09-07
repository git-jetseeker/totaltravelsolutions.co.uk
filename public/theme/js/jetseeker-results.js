/**
 * Jetseeker parking search results — APB-style filters, sort, mobile drawer
 */
(function () {
    'use strict';

    var originalCardOrder = [];
    var resultsLoadRequest = null;

    document.addEventListener('DOMContentLoaded', function () {
        initAmendPanel();
        initFilterAccordion();
        initFilterControls();
        initResultsLoader();
        document.addEventListener('jetseeker:resultsLoaded', onResultsLoaded);
    });

    function initResultsLoader() {
        var page = document.querySelector('.js-results-page');
        if (!page) {
            return;
        }

        loadSearchResults(false);

        window.addEventListener('pageshow', function (event) {
            if (!document.querySelector('.js-results-page')) {
                return;
            }

            if (event.persisted) {
                loadSearchResults(true);
            }
        });
    }

    function getResultsSearchPayload() {
        var page = document.querySelector('.js-results-page');
        if (!page) {
            return null;
        }

        var params = new URLSearchParams(window.location.search);

        return {
            airport_id: params.get('airport_id') || page.dataset.airportId || '',
            dropoffdate: params.get('dropoffdate') || page.dataset.dropoffdate || '',
            dropoftime: params.get('dropoftime') || params.get('dropofftime') || page.dataset.dropoftime || '09:00',
            departure_date: params.get('departure_date') || page.dataset.departureDate || '',
            pickup_time: params.get('pickup_time') || params.get('pickuptime') || page.dataset.pickupTime || '09:00',
            email: params.get('email') || page.dataset.email || '',
            promo: params.get('promo') || page.dataset.promo || '',
            promo2: params.get('promo2') || page.dataset.promo2 || '',
            src: params.get('src') || page.dataset.src || 'ORG',
            _token: page.dataset.csrf || ''
        };
    }

    function showResultsLoadError(message) {
        var container = document.getElementById('ajax_search_results');
        if (!container) {
            return;
        }

        container.innerHTML = '<div class="js-results-error" role="alert"><p>' + message + '</p><button type="button" class="js-results-error__retry">Try again</button></div>';

        var retryBtn = container.querySelector('.js-results-error__retry');
        if (retryBtn) {
            retryBtn.addEventListener('click', function () {
                loadSearchResults(true);
            });
        }
    }

    function loadSearchResults(forceReload) {
        if (!window.jQuery) {
            return null;
        }

        var page = document.querySelector('.js-results-page');
        var payload = getResultsSearchPayload();

        if (!page || !payload || !payload.airport_id || !payload.dropoffdate || !payload.departure_date) {
            return null;
        }

        if (resultsLoadRequest && !forceReload) {
            return resultsLoadRequest;
        }

        if (resultsLoadRequest && forceReload && resultsLoadRequest.abort) {
            resultsLoadRequest.abort();
        }

        var loader = document.querySelector('.js-results-loader');
        if (loader) {
            loader.style.display = '';
            loader.setAttribute('aria-busy', 'true');
        }

        resultsLoadRequest = jQuery.ajax({
            url: page.dataset.searchUrl,
            type: 'POST',
            data: payload,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (data) {
            jQuery('#ajax_search_results').empty().html(data);
            jQuery('.spiner_container').hide();

            if (window.JetseekerResults && typeof window.JetseekerResults.onResultsLoaded === 'function') {
                window.JetseekerResults.onResultsLoaded();
            }

            document.dispatchEvent(new CustomEvent('jetseeker:resultsLoaded'));

            if (jQuery('#to-top').length) {
                jQuery('#to-top').click();
            }
        }).fail(function () {
            jQuery('.spiner_container').hide();
            showResultsLoadError('Unable to load parking results. Please try again.');
        }).always(function () {
            resultsLoadRequest = null;
            if (loader) {
                loader.setAttribute('aria-busy', 'false');
            }
        });

        return resultsLoadRequest;
    }

    function initAmendPanel() {
        var panel = document.getElementById('js-results-amend-panel');
        var toggleBtn = document.getElementById('js-results-amend-toggle');
        var editBtn = document.getElementById('js-results-meta-edit');

        if (!panel) return;

        function setOpen(open) {
            panel.classList.toggle('is-open', open);
            panel.hidden = !open;
            panel.setAttribute('aria-hidden', open ? 'false' : 'true');

            [toggleBtn, editBtn].forEach(function (btn) {
                if (btn) {
                    btn.classList.toggle('is-open', open);
                    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                }
            });

            if (open) {
                initAmendWidgetControls();
                setTimeout(function () {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 120);
            }
        }

        function initAmendWidgetControls() {
            if (window.JetseekerSearch && typeof window.JetseekerSearch.initResultPageDatepickers === 'function') {
                window.JetseekerSearch.initResultPageDatepickers();
            }

            if (window.jQuery && jQuery.fn.select2) {
                jQuery('.js-results-amend-panel .select2me').each(function () {
                    if (!jQuery(this).data('select2')) {
                        jQuery(this).select2({ width: '100%' });
                    }
                });
            }

            if (window.JetseekerSearch && typeof window.JetseekerSearch.initBookingWidget === 'function') {
                window.JetseekerSearch.initBookingWidget();
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                setOpen(!panel.classList.contains('is-open'));
            });
        }

        if (editBtn) {
            editBtn.addEventListener('click', function () {
                setOpen(!panel.classList.contains('is-open'));
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && panel.classList.contains('is-open')) {
                setOpen(false);
            }
        });
    }

    function initFilterAccordion() {
        var filters = document.getElementById('js-results-filters');
        var toggle = document.getElementById('js-filter-toggle');
        var resetBtn = document.getElementById('js-filter-reset');
        var mq = window.matchMedia('(max-width: 991px)');

        if (!filters || !toggle) return;

        function isCollapsible() {
            return mq.matches;
        }

        function setExpanded(open) {
            if (!isCollapsible()) {
                filters.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = '';
                return;
            }

            filters.classList.toggle('is-open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.style.overflow = '';
        }

        function initState() {
            if (isCollapsible()) {
                setExpanded(false);
            } else {
                setExpanded(true);
            }
        }

        toggle.addEventListener('click', function () {
            if (!isCollapsible()) return;
            setExpanded(!filters.classList.contains('is-open'));
        });

        if (resetBtn) {
            resetBtn.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && isCollapsible() && filters.classList.contains('is-open')) {
                setExpanded(false);
            }
        });

        if (typeof mq.addEventListener === 'function') {
            mq.addEventListener('change', initState);
        } else if (typeof mq.addListener === 'function') {
            mq.addListener(initState);
        }

        initState();
    }

    function initFilterControls() {
        var resetBtn = document.getElementById('js-filter-reset');
        var sortSelect = document.getElementById('js-filter-sort');
        var searchInput = document.getElementById('js-filter-search');

        bindCheckboxGroup('filter_parking_type', 'parking');
        bindCheckboxGroup('filter_terminal', 'terminal');

        if (resetBtn) {
            resetBtn.addEventListener('click', resetFilters);
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                applyFilters();
            });
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                sortCards(sortSelect.value);
            });
        }

        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('price-sorting') && e.target.id !== 'js-filter-sort') {
                var sidebarSort = document.getElementById('js-filter-sort');
                if (sidebarSort) sidebarSort.value = e.target.value;
                sortCards(e.target.value);
            }
        });
    }

    function bindCheckboxGroup(name, groupKey) {
        var allCheckbox = document.querySelector('input[name="' + name + '"][data-filter-all="' + groupKey + '"]');
        var specificCheckboxes = document.querySelectorAll('input[name="' + name + '"]:not([data-filter-all])');

        document.querySelectorAll('input[name="' + name + '"]').forEach(function (input) {
            input.addEventListener('change', function () {
                if (input.hasAttribute('data-filter-all')) {
                    if (input.checked) {
                        specificCheckboxes.forEach(function (cb) {
                            cb.checked = false;
                        });
                    } else if (!Array.prototype.some.call(specificCheckboxes, function (cb) {
                        return cb.checked;
                    })) {
                        input.checked = true;
                    }
                } else if (input.checked && allCheckbox) {
                    allCheckbox.checked = false;
                }

                var anySpecific = Array.prototype.some.call(specificCheckboxes, function (cb) {
                    return cb.checked;
                });

                if (!anySpecific && allCheckbox) {
                    allCheckbox.checked = true;
                }

                setActiveFilterLabels();
                applyFilters();
            });
        });
    }

    function resetFilters() {
        var sortSelect = document.getElementById('js-filter-sort');
        var searchInput = document.getElementById('js-filter-search');

        document.querySelectorAll('input[data-filter-all]').forEach(function (input) {
            input.checked = true;
        });

        document.querySelectorAll('input[name="filter_parking_type"]:not([data-filter-all]), input[name="filter_terminal"]:not([data-filter-all])').forEach(function (input) {
            input.checked = false;
        });

        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.value = 'Default';

        setActiveFilterLabels();
        applyFilters();
        sortCards('Default');
    }

    function setActiveFilterLabels() {
        document.querySelectorAll('.js-apb-filter-check').forEach(function (label) {
            var input = label.querySelector('input[type="checkbox"]');
            label.classList.toggle('is-active', !!(input && input.checked));
        });
    }

    function onResultsLoaded() {
        hydrateResultCards();
        storeOriginalOrder();
        updateFilterCounts();
        applyFilters();
        setActiveFilterLabels();
        var sortSelect = document.getElementById('js-filter-sort');
        if (sortSelect && sortSelect.value !== 'Default') {
            sortCards(sortSelect.value);
        }
    }

    function normalizeParkingType(type) {
        if (!type) return '';
        var t = type.trim().toLowerCase();
        if (t.indexOf('on-site') !== -1 || t.indexOf('onsite') !== -1 || t.indexOf('on site') !== -1) return 'Onsite';
        if (t.indexOf('meet') !== -1 && t.indexOf('greet') !== -1) return 'Meet and Greet';
        if (t.indexOf('park and ride') !== -1 || t.indexOf('park & ride') !== -1 || t.indexOf('park&ride') !== -1) return 'Park and Ride';
        return type.trim();
    }

    function extractTerminalsFromText(text) {
        var terminals = [];
        var haystack = (text || '').toLowerCase();
        var matches = haystack.match(/terminal[\s#]*([0-9,\s&\/\-]+)/gi);

        if (matches) {
            matches.forEach(function (chunk) {
                var nums = chunk.match(/\d+/g);
                if (nums) {
                    nums.forEach(function (num) {
                        if (terminals.indexOf(num) === -1) {
                            terminals.push(num);
                        }
                    });
                }
            });
        }

        return terminals;
    }

    function getCardTerminals(card) {
        var fromAttr = card.getAttribute('data-terminals') || card.getAttribute('data-terminal') || '';
        var terminals = fromAttr
            ? fromAttr.split(/[,\s]+/).filter(Boolean)
            : [];

        if (!terminals.length) {
            var searchText = card.getAttribute('data-search-text') || '';
            var nameEl = card.querySelector('.company-name');
            var nameText = nameEl ? nameEl.textContent : '';
            terminals = extractTerminalsFromText(nameText + ' ' + searchText);
        }

        return terminals;
    }

    function hydrateResultCards() {
        document.querySelectorAll('.js-result-card').forEach(function (card) {
            var priceEl = card.querySelector('.product-card[data-price], .l-price[data-price]');
            if (priceEl) {
                card.setAttribute('data-price', priceEl.getAttribute('data-price'));
            }

            if (!card.getAttribute('data-parking-type')) {
                var typeEl = card.querySelector('.js-apb-deal-card__subtitle, .listing-typ');
                if (typeEl) {
                    card.setAttribute('data-parking-type', normalizeParkingType(typeEl.textContent));
                }
            } else {
                card.setAttribute('data-parking-type', normalizeParkingType(card.getAttribute('data-parking-type')));
            }

            var searchParts = [];
            var nameEl = card.querySelector('.company-name');
            if (nameEl) searchParts.push(nameEl.textContent);
            card.querySelectorAll('.js-apb-deal-card__feature-list li, .list-fac li').forEach(function (li) {
                searchParts.push(li.textContent);
            });
            card.setAttribute('data-search-text', searchParts.join(' ').toLowerCase().replace(/\s+/g, ' ').trim());

            var terminals = getCardTerminals(card);
            if (terminals.length) {
                card.setAttribute('data-terminals', terminals.join(','));
            }
        });
    }

    function storeOriginalOrder() {
        originalCardOrder = getCards().slice();
    }

    function getCards() {
        return Array.prototype.slice.call(document.querySelectorAll('.js-result-card'));
    }

    function getSelectedValues(name) {
        var allCheckbox = document.querySelector('input[name="' + name + '"][data-filter-all]');
        if (allCheckbox && allCheckbox.checked) {
            return null;
        }

        var selected = [];
        document.querySelectorAll('input[name="' + name + '"]:checked:not([data-filter-all])').forEach(function (cb) {
            selected.push(cb.value);
        });

        return selected.length ? selected : null;
    }

    function updateFilterCounts() {
        var cards = getCards();
        var total = cards.length;
        var typeCounts = {
            all: total,
            'Meet and Greet': 0,
            'Park and Ride': 0,
            Onsite: 0
        };
        var terminalCounts = { '1': 0, '2': 0, '3': 0, '4': 0, '5': 0 };

        cards.forEach(function (card) {
            var type = normalizeParkingType(card.getAttribute('data-parking-type') || '');
            if (typeCounts[type] !== undefined) {
                typeCounts[type]++;
            }

            getCardTerminals(card).forEach(function (term) {
                if (terminalCounts[term] !== undefined) {
                    terminalCounts[term]++;
                }
            });
        });

        document.querySelectorAll('.js-filter-count').forEach(function (el) {
            var key = el.getAttribute('data-filter');
            if (key && typeCounts[key] !== undefined) {
                el.textContent = '(' + typeCounts[key] + ')';
            }
        });

        document.querySelectorAll('.js-terminal-count').forEach(function (el) {
            var key = el.getAttribute('data-terminal');
            if (key && terminalCounts[key] !== undefined) {
                el.textContent = '(' + terminalCounts[key] + ')';
            }
        });

        updateVisibleCount(total, total);
    }

    function cardMatchesTerminals(card, selectedTerms) {
        if (!selectedTerms || !selectedTerms.length) {
            return true;
        }

        var cardTerms = getCardTerminals(card);
        if (!cardTerms.length) {
            return false;
        }

        return selectedTerms.some(function (term) {
            return cardTerms.indexOf(term) !== -1;
        });
    }

    function applyFilters() {
        var cards = getCards();
        var typeSelected = getSelectedValues('filter_parking_type');
        var termSelected = getSelectedValues('filter_terminal');
        var searchInput = document.getElementById('js-filter-search');
        var searchQuery = searchInput ? searchInput.value.trim().toLowerCase() : '';
        var visible = 0;

        cards.forEach(function (card) {
            var cardType = normalizeParkingType(card.getAttribute('data-parking-type') || '');
            var searchText = card.getAttribute('data-search-text') || '';

            var typeMatch = !typeSelected || typeSelected.indexOf(cardType) !== -1;
            var termMatch = cardMatchesTerminals(card, termSelected);
            var searchMatch = !searchQuery || searchText.indexOf(searchQuery) !== -1;
            var show = typeMatch && termMatch && searchMatch;

            card.style.display = show ? '' : 'none';
            card.setAttribute('aria-hidden', show ? 'false' : 'true');
            if (show) visible++;
        });

        updateVisibleCount(visible, cards.length);
    }

    function updateVisibleCount(visible, total) {
        var countEl = document.getElementById('js-results-count');
        if (countEl) {
            countEl.textContent = visible + ' of ' + total + ' results';
        }
    }

    function sortCards(method) {
        var grid = document.querySelector('.js-apb-results-list');
        if (!grid) return;

        if (method === 'Default') {
            (originalCardOrder.length ? originalCardOrder : getCards()).forEach(function (item) {
                grid.appendChild(item);
            });
            return;
        }

        var items = getCards().slice().sort(function (a, b) {
            var sortA = parseInt(a.getAttribute('data-sort-index') || '0', 10);
            var sortB = parseInt(b.getAttribute('data-sort-index') || '0', 10);

            if (method === 'Lowest' || method === 'Highest') {
                var priceA = parseFloat(a.getAttribute('data-price') || 0);
                var priceB = parseFloat(b.getAttribute('data-price') || 0);
                if (priceA !== priceB) {
                    return method === 'Highest' ? priceB - priceA : priceA - priceB;
                }
                return sortA - sortB;
            }

            return sortA - sortB;
        });

        items.forEach(function (item) {
            grid.appendChild(item);
        });
    }

    window.JetseekerResults = {
        onResultsLoaded: onResultsLoaded,
        hydrateResultCards: hydrateResultCards,
        loadSearchResults: loadSearchResults
    };
})();
