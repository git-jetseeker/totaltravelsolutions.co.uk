{{-- APB-style filters panel (left column beside deal cards; collapsible on mobile/tablet) --}}
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
                placeholder="Search by name or feature…"
                autocomplete="off"
                aria-label="Search by name or feature"
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
            <h3 class="js-apb-filter-group__title">Parking Type</h3>
            <ul class="js-apb-filter-list" id="js-filter-parking-type">
                <li>
                    <label class="js-apb-filter-check is-active">
                        <input type="checkbox" name="filter_parking_type" value="all" checked data-filter-all="parking">
                        <span class="js-apb-filter-check__label">All</span>
                        <span class="js-filter-count" data-filter="all">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_parking_type" value="Meet and Greet">
                        <span class="js-apb-filter-check__label">Meet and Greet</span>
                        <span class="js-filter-count" data-filter="Meet and Greet">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_parking_type" value="Park and Ride">
                        <span class="js-apb-filter-check__label">Park and Ride</span>
                        <span class="js-filter-count" data-filter="Park and Ride">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_parking_type" value="Onsite">
                        <span class="js-apb-filter-check__label">Onsite</span>
                        <span class="js-filter-count" data-filter="Onsite">(0)</span>
                    </label>
                </li>
            </ul>
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
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_terminal" value="1">
                        <span class="js-apb-filter-check__label">Terminal 1</span>
                        <span class="js-terminal-count" data-terminal="1">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_terminal" value="2">
                        <span class="js-apb-filter-check__label">Terminal 2</span>
                        <span class="js-terminal-count" data-terminal="2">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_terminal" value="3">
                        <span class="js-apb-filter-check__label">Terminal 3</span>
                        <span class="js-terminal-count" data-terminal="3">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_terminal" value="4">
                        <span class="js-apb-filter-check__label">Terminal 4</span>
                        <span class="js-terminal-count" data-terminal="4">(0)</span>
                    </label>
                </li>
                <li>
                    <label class="js-apb-filter-check">
                        <input type="checkbox" name="filter_terminal" value="5">
                        <span class="js-apb-filter-check__label">Terminal 5</span>
                        <span class="js-terminal-count" data-terminal="5">(0)</span>
                    </label>
                </li>
            </ul>
        </div>
        </div>{{-- /.js-apb-filters__panel --}}
    </div>
</aside>