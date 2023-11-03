<div class="dtp hide" id="datetime_picker">
    <div class="dtp-top">
        <div class="dtp-left">
            <header class="dtp__header">
                <svg class="dtp__header-icon navigation-icon icon-prev"><use xlink:href="#angle-right"></use></svg>
                <span class="dtp__header-date d-block"></span>
                <svg class="dtp__header-icon navigation-icon icon-next"><use xlink:href="#angle-right"></use></svg>
                <svg class="dtp__header-icon icon-close"><use xlink:href="#close"></use></svg>

            </header>
            <div class="dtp__result">
                <div class="dtp__result-date"></div>
                <div class="btn btn-secondary btn-outline dtp__result-btn"><?php _e( 'today', 'digis' ); ?></div>
            </div>

            <div class="dtp__calendar">
                <ul class="dtp__calendar-weeks">
                    <li><?php _e('Mon', 'digis' ); ?></li>
                    <li><?php _e('Tue', 'digis' ); ?></li>
                    <li><?php _e('Wed', 'digis' ); ?></li>
                    <li><?php _e('Thu', 'digis' ); ?></li>
                    <li><?php _e('Fri', 'digis' ); ?></li>
                    <li><?php _e('Sat', 'digis' ); ?></li>
                    <li><?php _e('Sun', 'digis' ); ?></li>
                </ul>
                <ul class="dtp__calendar-days"></ul>
            </div>
        </div>
        <div class="dtp-right hide">
            <div class="dtp__time-switcher">
                <span class="d-block"><?php _e('am/pm', 'digis');?></span>
                <span class="d-block"><?php _e('24h', 'digis');?></span>
                <div class="time-switcher"></div>
            </div>
            <ul class="dtp__time"></ul>
        </div>
    </div>
    <div class="dtp-bottom hide">
        <div class="dtp__timezone">
            <div class="dtp__timezone-result" id="timezone_result">
                <span class="result d-block"></span>
                <svg class="dtp__timezone-result-icon"><use xlink:href="#angle-right"></use></svg>
                <div class="dtp__timezone-result__search">
                    <div class="dtp__timezone-result__search-field">
                        <input type="text" id="timezoneSearch" placeholder="Search...">
                        <svg class="dtp__timezone-result__search-field-icon"><use xlink:href="#close"></use></svg>

                    </div>
                    <ul class="dtp__timezone-result__search-list" id="timezoneList"></ul>
                </div>
            </div>
        </div>
        <div class="btn btn-primary dtp__confirm-btn disabled"><?php _e( 'confirm', 'digis' ); ?></div>
    </div>
</div>