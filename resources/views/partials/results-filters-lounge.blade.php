{{-- Lounge results filters — same APB pattern as parking (sort, search, terminal) --}}
<aside class="js-apb-filters" id="js-results-filters" aria-label="Search filters">
    <div class="js-apb-filters__card">
        <div class="js-apb-filters__head">
            <button
                type="button"
                class="js-apb-filters__toggle"
                id="js-filter-toggle"
                aria-expanded="false"
                aria-controls="js-filter-panel"
            >
                <span class="js-apb-filters__title">Filters</span>
                <i class="fa fa-chevron-down js-apb-filters__chevron" aria-hidden="true"></i>
            </button>
            <button type="button" class="js-apb-filters__reset" id="js-filter-reset">Reset All</button>
        </div>

        <div class="js-apb-filters__panel" id="js-filter-panel">
            <p class="js-apb-filters__count" id="js-results-count" aria-live="polite">Loading results…</p>
            <div class="js-apb-filters__search">
                <i class="fa fa-search js-apb-filters__search-icon" aria-hidden="true"></i>
                <input
                    type="search"
                    id="js-filter-search"
                    class="js-apb-filters__search-input"
                    placeholder="Search by lounge name or feature…"
                    autocomplete="off"
                    aria-label="Search lounges"
                >
            </div>

            <div class="js-apb-filter-group">
                <h3 class="js-apb-filter-group__title">Sort By</h3>
                <select id="js-filter-sort" class="js-apb-filter-select price-sorting" aria-label="Sort results">
                    <option value="Default" selected>Default</option>
                    <option value="Lowest">Price Low to High</option>
                    <option value="Highest">Price High to Low</option>
                </select>
            </div>

            <div class="js-apb-filter-group">
                <h3 class="js-apb-filter-group__title">
                    <i class="fa fa-map-marker js-apb-filter-group__icon" aria-hidden="true"></i> Terminal
                </h3>
                <ul class="js-apb-filter-list" id="js-filter-terminal">
                    <li>
                        <label class="js-apb-filter-check is-active">
                            <input type="checkbox" name="filter_terminal" value="all" checked data-filter-all="terminal">
                            <span class="js-apb-filter-check__label">All</span>
                        </label>
                    </li>
                    @foreach (['1', '2', '3', '4', '5'] as $term)
                        <li>
                            <label class="js-apb-filter-check">
                                <input type="checkbox" name="filter_terminal" value="{{ $term }}">
                                <span class="js-apb-filter-check__label">Terminal {{ $term }}</span>
                                <span class="js-terminal-count" data-terminal="{{ $term }}">(0)</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</aside>
