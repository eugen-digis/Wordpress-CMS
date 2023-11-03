import moment from 'moment';
import 'moment-timezone';

jQuery(document).ready(function ($) {
    "use strict";

    const currentDate         = $('.dtp__header-date'),
          allDays             = $('.dtp__calendar-days'),
          monthSwitchIcon     = $('.navigation-icon'),
          monthSwitchIconPrev = $('.navigation-icon.icon-prev'),
          resultDate          = $('.dtp__result-date'),
          timeList            = $('.dtp__time'),
          todayButton         = $('.dtp__result-btn'),
          today               = $('.dtp__calendar-days .today'),
          currentTime         = moment(),
          endTime             = moment().hours(20).minutes(0).seconds(0),
          searchInput         = document.getElementById('timezoneSearch'),
          timezoneList        = document.getElementById('timezoneList'),
          resultElement       = $('.dtp__timezone-result .result');

    let date              = new Date(),
        currYear          = date.getFullYear(),
        currMonth         = date.getMonth(),
        startMonth        = date.getMonth(),
        startYear         = date.getFullYear(),
        chosenDate        = '',
        chosenTime        = '',
        is24HourFormat    = false,
        selectedTimeIndex = -1;

    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 
                    'August', 'September', 'October', 'November', 'December'];


    // functions for Calendar Render
    const renderCalendar = () => {
        let firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(),
            lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate(),
            lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay(),
            lastDateOfLastMonth = new Date(currYear, currMonth, 0).getDate(),
            daysTag = '';

        for (let i = firstDayOfMonth; i > 0; i--) {
            daysTag += `<li class="disabled">${lastDateOfLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateOfMonth; i++) {
            let isPast = '';

            let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                        && currYear === new Date().getFullYear() ? 'today' : '';

            if ( isToday === 'today' && currentTime.isAfter(endTime) || date > new Date(currYear, currMonth, i + 1) ) {
                isPast = 'disabled';
            }

            daysTag += `<li class="${isToday} ${isPast}">${i}</li>`;
        }

        for (let i = lastDayOfMonth; i < 6; i++) {
            daysTag += `<li class="disabled">${i - lastDayOfMonth + 1}</li>`;
        }

        if (currMonth === startMonth && currYear === startYear) {
            monthSwitchIconPrev.addClass('disabled');
        } else {
            monthSwitchIconPrev.removeClass('disabled');
        }

        if ( currentTime.isAfter(endTime) ) {
            todayButton.addClass('disabled');
        }

        currentDate.text(`${months[currMonth]} ${currYear}`);
        allDays.html(daysTag);
    }
    renderCalendar();


    // functions for TimeList Render
    const renderTime = () => {
        const today = $('.dtp__calendar-days .today'),
              currentTime = moment(),
              startTime = moment().hours(7).minutes(0).seconds(0);
              

        let timeTag = '';
        let isDisabled = '';
    
        for (let i = 0; i < 30; i++) {
            let isActive = i === selectedTimeIndex ? 'active' : '';

            if ( today.hasClass('active') ) {
                isDisabled = currentTime.isAfter(startTime) ? 'disabled' : '';
            }

            timeTag += `<li class="${isDisabled} ${isActive}">${startTime.format(is24HourFormat ? 'HH:mm' : 'hh:mm A')}</li>`;
    
            startTime.add(30, 'minutes');
        }
    
        timeList.html(timeTag);
    };
    renderTime();


    // Generate Result, which passes to contact form, after ach day/time changed
    const generateResult = () => {
        resultDate.text(chosenDate + chosenTime);
    }


    // functions for Timezone Render
    function updateTimezoneList(searchValue) {
        timezoneList.innerHTML = ''; // Clear the existing list

        const addedEntries = new Set(); // To keep track of added entries

        moment.tz.names().forEach(timezone => {
            const currentTime = moment().tz(timezone).format(is24HourFormat ? 'HH:mm' : 'hh:mm A'); // Format based on is24HourFormat
            const utcOffset = moment.tz(timezone).format('Z');

            let cityName = timezone;
            if ( timezone.includes('/') ) {
                cityName = timezone.split('/').pop(); // Get last part of the timezone name
                cityName = cityName.replace(/_/g, ' '); // Replace underscores with spaces
            }

            const entry = `${cityName} / ${currentTime} / ${utcOffset}`;
            const entryLower = entry.toLowerCase();
            const searchLower = searchValue.toLowerCase();

            if ( entryLower.includes(searchLower) && !addedEntries.has(entryLower) ) {
                const listItem = document.createElement('li');
                listItem.textContent = entry;

                timezoneList.appendChild(listItem);
                addedEntries.add(entryLower);
            }
        });
    }

    // Function to format the client's entry
    function getClientEntry(cityName, utcOffset) {
        return `${cityName.split('/').pop()} / ${moment().tz(cityName).format(is24HourFormat ? 'HH:mm' : 'hh:mm A')} / ${utcOffset}`;
    }

    // Function to update the client's entry based on the time switcher
    function updateClientEntry() {
        const existingEntry = resultElement.text();
        const [, currentTime] = existingEntry.split(' / ');
        const [, , utcOffset] = existingEntry.split(' / ');
        const formattedTime = moment.tz(currentTime, is24HourFormat ? 'HH:mm' : 'hh:mm A', utcOffset).format(is24HourFormat ? 'HH:mm' : 'hh:mm A');

        resultElement.text(`${existingEntry.split(' / ')[0]} / ${formattedTime} / ${utcOffset}`);
    }

    // Detect client's timezone only once when the page loads
    const clientTimezone = moment.tz.guess();
    const clientOffset = moment.tz(clientTimezone).format('Z');
    const clientEntry = getClientEntry(clientTimezone, clientOffset);
    resultElement.text(clientEntry);

    searchInput.addEventListener('input', event => {
        const searchValue = event.target.value;
        updateTimezoneList(searchValue);
        confirmTimezone();
    });

    const confirmTimezone = function(){
        $('.dtp__timezone-result__search-list li').on('click', function(){
            event.stopPropagation();
            $('.dtp__timezone-result').removeClass('active');
            $('.dtp__timezone-result__search-list li').removeClass('active');
            $(this).addClass('active');
    
            let timezone = $(this).text(); // Get the text of the clicked list item
            $('.dtp__timezone-result .result').text(timezone);
        });
    }
    updateTimezoneList('');
    confirmTimezone();


    // All Clickers 
    
    allDays.on('click', 'li:not(.disabled)', function () {
        $('.dtp-bottom').removeClass('hide');
        const day = $(this).text(),
              month = months[currMonth].slice(0, 3);
    
        $('.dtp-right').removeClass('hide');
        allDays.find('li.active').removeClass('active');
        $(this).addClass('active');
    
        renderTime();
        $('.dtp__time .active').trigger('click');
        if ( $(this).hasClass('today') && $('.dtp__time .active').hasClass('disabled') ) {
            chosenTime = '';
            timeList.find('li').removeClass('active');
            $('.dtp__confirm-btn').addClass('disabled');
        }

        chosenDate = `${month} ${day}, `;

        generateResult();

    });

    monthSwitchIcon.on('click', function () {
        if ( $(this).hasClass('icon-prev') ) {
            currMonth = currMonth - 1 
        } else if ( $(this).hasClass('icon-next') ) {
            currMonth = currMonth + 1 
        }

        if (currMonth < 0 || currMonth > 11) {
            date = new Date(currYear, currMonth);
            currYear = date.getFullYear();
            currMonth = date.getMonth();
        } else {
            date = new Date();
        }
        renderCalendar();
    });

    todayButton.on('click', function () {
        const currentDateElement = allDays.find(`li.today`);
        if (currentDateElement.length > 0) {
            currentDateElement.trigger('click');
        }
    });

    $('#timezone_result').on('click', function(){
        $(this).toggleClass('active');
    });

    $('.dtp__timezone-result__search-field-icon').on('click', function(){
        event.stopPropagation();
        $('#timezone_result').removeClass('active');
    });

    $('#timezoneSearch').on('click', function(){
        event.stopPropagation();
    });

    timeList.on('click', 'li', function () {
        if( !$(this).hasClass('disabled') ) {
            $('.dtp__confirm-btn').removeClass('disabled');
        }

        const time = $(this).text();

        timeList.find('li.active').removeClass('active');
        $(this).addClass('active');

        selectedTimeIndex = $(this).index();

        chosenTime = timeList.find('li.active').hasClass('disabled') ? '' : `${time}`;

        generateResult();
    });

    $('.time-switcher').on('click', function(){
        $(this).toggleClass('active');
        is24HourFormat = !is24HourFormat;
        renderTime();
        updateTimezoneList('');
        updateClientEntry();
        confirmTimezone();
        $('.dtp__time .active').trigger('click');
    });

    $('.dtp__confirm-btn').on('click', function() {
        const timezoneValue = $('.dtp__timezone-result .result').text().trim();
        const dateValue = $('.dtp__result-date').text().trim();
    
        $('#cf_timezone').val(timezoneValue);
        $('#cta_date_picker').val(dateValue);
    
        $('.dtp').addClass('hide');
    });

    $('.cta__form #dtp_wrapper').on('click', function(){
        const form = $('.dtp');
        if( form.hasClass('hide') ) {
            form.removeClass('hide');
        }
    });

    $('.dtp__header-icon.icon-close').on('click', function(event){
        event.stopPropagation();
        $('#datetime_picker').addClass('hide');
    })
});