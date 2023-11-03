jQuery(document).ready(function ($) {
    "use strict";

    // Projects Filters 
    function digis_applyFilter(filter) {
        $.ajax({
            url: filter.attr('action'),
            data: filter.serialize(),
            type: filter.attr('method'),
            beforeSend: function (xhr) {
                filter.find('select').prop('disabled', true);
            },
            success: function (data) {
                filter.find('select').prop('disabled', false);
                $('#response_projects').html(data);
                numProjectsToShow = 9;
                updateTotalCases();
            }
        });
    }

    $('#service_filter select, #country_filter select').on('change', function () {
        let filter = $(this).closest('form');
        if ( filter.is('#country_filter') ) {
            $('#service_filter select').val('');
            $('#service_filter select').val('Service').niceSelect('update');
        } else {
            $('#country_filter select').val('');
            $('#country_filter select').val('Country').niceSelect('update');
        }
        $('form').removeClass('active-dropdown');
        $(this).closest('form').addClass('active-dropdown');
        $('.filter-tag').removeClass('active');

        digis_applyFilter(filter);
    });

    $(document).on('click', '.filter-tag', function () {
        $('.filter-tag').removeClass('active');
        $('form').removeClass('active-dropdown');

        $(this).toggleClass('active');

        var selectedTerms = $('.filter-tag.active').map(function () {
            return $(this).data('term-id');
        }).get();

        var filter = $('#industry_filter');

        $.ajax({
            url: filter.attr('action'),
            data: {
                action: 'myfilter_industry',
                industryfilter: selectedTerms
            },
            type: filter.attr('method'),
            success: function (data) {
                $('#response_projects').html(data);
                numProjectsToShow = 9;
                updateTotalCases();
            }
        });

        $('#country_filter select').val('');
        $('#service_filter select').val('');
    
        $('#country_filter select').val('Country').niceSelect('update');
        $('#service_filter select').val('Service').niceSelect('update');
    });

    $('.m-filters__refresh-btn, .m-vacancies__refresh-btn').on('click', function () {
        var filter = $('#industry_filter');

        $('.filter-tag').removeClass('active');
        $('form').removeClass('active-dropdown');

        filter.find('input[name="industryfilter"]').val('');
    
        $('#country_filter select').val('');
        $('#service_filter select').val('');
    
        $('#country_filter select').val('Country').niceSelect('update');
        $('#service_filter select').val('Service').niceSelect('update');
    
        digis_applyFilter(filter);
    });
});