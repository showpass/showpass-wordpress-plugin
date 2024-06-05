(function($) {

    $(window).ready(function () {
        
        let apiURL = 'https://www.showpass.com/api'

        let useBeta = $('#option_use_showpass_beta').val();

        let useDemo = $('#option_use_showpass_demo').val();

        if (useBeta) {
            apiURL = 'https://beta.showpass.com/api'
        } else if (useDemo) {
            apiURL = 'https://demo.showpass.com/api'
        }

        let isMobile = /Mobi/.test(navigator.userAgent);

        if (isMobile == true) {
            $('#view-select .week').hide();
        }

        function setDisplayView() {
            if (singleDisplay === 'card-view') {
                $('#schedule-display').hide();
                $('#daily-card-view').show();
            } else {
                $('#schedule-display').show();
                $('#daily-card-view').hide();
            }
        }

        let singleDisplay = 'card-view';
        $('#card-view').addClass('active');
        setDisplayView();

        let single_day = $('#single-day').val();
        let months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        let days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        let calendar_day = $('#calendar-day').val();
        let calendar_month = $('#calendar-month').val() - 1;
        let calendar_year = $('#calendar-year').val();
        let now = new Date(calendar_year, calendar_month, calendar_day);
        let cur_month = now.getMonth();
        let cur_year = now.getFullYear();
        let month_enable = $('#month_enable').val();
        let week_enable = $('#week_enable').val();
        let default_banner = $('#showpass-default-banner').val();
        let default_square = $('#showpass-default-square').val();

        // Render new calendar when prev day button pressed
        $('.showpass-prev-day').click(function() {
            $('#schedule-display').empty();
            renderDailyCalendar($(this).attr('data-day'));
        });

        // Render new calendar when prev day button pressed
        $('.showpass-next-day').click(function() {
            $('#schedule-display').empty();
            renderDailyCalendar($(this).attr('data-day'));
        });

        // Render new calendar when prev week button pressed
        $('.showpass-prev-week').click(function() {
            let prevWeek = $(this).attr('data-prev-week');
            renderCalendarWeek(prevWeek);

        });

        // Render new calendar when next week button pressed
        $('.showpass-next-week').click(function() {

            let nextWeek = $(this).attr('data-next-week');
            renderCalendarWeek(nextWeek);

        });

        // Render new calendar when prev month button pressed
        $('.showpass-prev-month').click(function() {

            let month_number = parseInt($(this).attr('data-month'));
            let year = parseInt($('.showpass-year').text());
            $('.showpass-next-month').removeClass('disabled');
            if (month_number == 0) {
                month_number = 12;
                year = year - 1;
            }

            if (month_number == (cur_month + 1)) {
                $(this).addClass('disabled');
            }

            // Set Calendar Header Date
            $('.showpass-month').html(months[month_number]);
            $('.showpass-year').text(year);

            $(this).attr('data-month', month_number - 1);
            $('.showpass-next-month').attr('data-month', month_number + 1);

            renderCalendar(year, month_number);

        });

        // Render new calendar when next month button pressed
        $('.showpass-next-month').click(function() {
            let month_number = parseInt($(this).attr('data-month'));
            let year = parseInt($('.showpass-year').text());
            $('.showpass-prev-month').removeClass('disabled');

            if (month_number == 13) {
                month_number = 1;
                $('.showpass-year').text(year + 1);
                year++;
            }

            if (month_number == cur_month && year == (cur_year + 1)) {
                $(this).addClass('disabled');
            }

            $('.showpass-month').html(months[month_number]);
            $(this).attr('data-month', month_number + 1);
            $('.showpass-prev-month').attr('data-month', month_number - 1);
            renderCalendar(year, month_number);

        });

        // render daily calendar
        function renderDailyCalendar(date) {
            $('.showpass-calendar').removeClass('monthly');
            $('.showpass-calendar').removeClass('weekly');
            $('#view-select').val('day');
            $('#schedule-display').empty();
            $("#daily-card-view .showpass-layout-flex").empty();
            $('.showpass-calendar').addClass('daily');
            $('.calendar-contain-desktop').hide();
            $('.showpass-calendar-month').hide();
            $('.showpass-calendar-week').hide();
            $('.daily-view-toggle').show();
            $('.horizontal-schedule-display').show();
            $('.loader-home').show();
            date = moment(date, "DD-MM-YYYY").format();
            let dayStart = moment(date).startOf('day').toISOString();
            let dayEnd = moment(date).endOf('day').toISOString();
            let tags = $('#tags').val();
            let venue = $('#venue_id').val();
            let only_parents = $('#only-parents').val();
            let hide_children = $('#hide-children').val();


            // Set values for display toggle
            $('#view-select .month').attr('current_date', moment(date).startOf('month').format());
            $('#view-select .week').attr('current_date', moment(date).startOf('week').format());

            // Set displays and data attributes for the controls
            $('.showpass-day').html(moment(date).format('dddd') + '<br/>' + moment(date).format('MMM D'));
            $('.showpass-prev-day').attr('data-day', moment(moment(date).format()).subtract(1, 'day').format('DD-MM-YYYY'));
            $('.showpass-next-day').attr('data-day', moment(moment(date).format()).add(1, 'day').format('DD-MM-YYYY'));
            if (venue) {
                // set initial URL
    			let url = apiURL + "/public/events/?venue__in=" + venue + "&page_size=100&starts_on__gte=" + dayStart + "&starts_on__lt=" + dayEnd;
                // if tags param append to url
                if (tags) {
                    url = url + "&tags=" + tags;
                }

                if (hide_children) {
                    url = url + "&hide_children=" + hide_children;
                }

                if(only_parents) {
                    url = url + "&only_parents=" + only_parents;
                }

                $.ajax({
                    method: "GET",
                    url: url,
                    success: function(data) {
                        if (data.results.length) {
                            // set initial data points
                            let start_of_day = moment(date).startOf('day').format();
                            let end_of_day = moment(date).endOf('day').format();
                            let events = data.results;
                            // pixels per hour
                            let time_scale = 420;
                            // Loop through events, find duration from start & end of day
                            _.forEach(events, function(event) {
                                let time_start = moment.tz(event.starts_on, event.timezone).format();
                                let time_end = moment.tz(event.ends_on, event.timezone).format();
                                event.$durationFromStart = moment.duration(moment(time_start).diff(moment(start_of_day))).asHours();
                                event.$durationFromEnd = moment.duration(moment(time_end).diff(moment(end_of_day))).asHours() * -1;
                            });

                            // Find the first event in the schedule, and the last
                            let first_event = _.minBy(events, '$durationFromStart');
                            let last_event = _.minBy(events, '$durationFromEnd');

                            // set schedule start and length of schedule_length
                            let start_of_schedule = moment.tz(first_event.starts_on, first_event.timezone).format();
                            let schedule_length = (24 - (first_event.$durationFromStart + last_event.$durationFromEnd)).toFixed(2);
                            let schedule_width = schedule_length * time_scale;

                            // group events by location
                            let locationGroup = _.values(_.mapValues(_.groupBy(events, 'location.id')));
                            for (let i = 0; i < locationGroup.length; i++) {
                                let locationEvents = locationGroup[i];
                                let html_loc = "<div class='location location-" + locationEvents[0].location.id + "' style='width:" + schedule_width + "px;'><span class='location-name gradient'><span class='sticky'><i class='fa fa-map-marker'></i>" + locationEvents[0].location.name + "</span></span><div class='time-scale'></div><div class='daily-contain'></div></div>";
                                $('#schedule-display').append(html_loc);
                                for (let e = 0; e < locationEvents.length; e++) {
                                    let timezone = locationEvents[e].timezone;
                                    let starts_on = locationEvents[e].starts_on;
                                    let ends_on = locationEvents[e].ends_on;
                                    let a = moment.tz(starts_on, timezone).format();
                                    date_month = a;
                                    let event_name = locationEvents[e].name;
                                    let event_slug = locationEvents[e].slug;
                                    let timezone_abbr = moment.tz(locationEvents[e].timezone).format('z');
                                    let event_duration = moment.duration(moment(ends_on).diff(moment(starts_on))).asHours();
                                    let tile_width = event_duration * time_scale;
                                    let horizontal_position = moment.duration(moment(starts_on).diff(moment(start_of_schedule))).asHours() * time_scale;
                                    let external_link = locationEvents[e].external_link;
                                    let html_tmp = "<div class='daily-event gradient" + (external_link ? "' href='" + external_link : " open-ticket-widget' id='" + event_slug) + "' style='width: " + tile_width + "px; left: " + horizontal_position + "px'><div class='event-info'><div class='event-name'>" + event_name + "</div>" +
                                        "<div class='time'><i class='fa fa-clock-o'></i>" + moment.tz(starts_on, timezone).format('h:mm A') + " - " + moment.tz(ends_on, timezone).format('h:mm A') + " " + timezone_abbr + "</div></div></div></div>";
                                    $('.location-' + locationEvents[e].location.id + ' .daily-contain').append(html_tmp);
                                }
                            }

                            _.forEach(events, function(event) {

                                timezone = event.timezone;
                                let timezone_abbr = moment.tz(event.timezone).format('z');
                                let starts_on = event.starts_on;
                                let ends_on = event.ends_on;
                                let a = moment.tz(starts_on, timezone).format();
                                date_month = a;
                                let event_name = event.name;
                                let image_thumb = event.image || default_square;
                                let image_banner = event.image_banner || default_banner;
                                let event_slug = event.slug;
                                let event_location = event.location.name;
                                let event_city = event.location.city + ', ' + event.location.province;

                                let external_link = event.external_link;

                                let html_card = "<div class='flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-event-card'><div class='showpass-event-layout-list showpass-layout-flex m15'><div class='card-image showpass-flex-column list-layout-flex showpass-no-border showpass-no-padding p0'>" +
                                    "<span class='showpass-image-banner showpass-hide-mobile' style='background-image: url(" + image_thumb + ");'></span>" +
                                    "<span class='showpass-image showpass-hide-large' style='background-image: url(" + image_banner + ");'></span></div>" +
                                    "<div class='list-info showpass-flex-column list-layout-flex showpass-no-border showpass-background-white'><div class='showpass-space-between showpass-full-width showpass-layout-fill'><div class='showpass-layout-flex'><div class='flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-title-wrapper'><div class='showpass-event-title'><h3>" +
                                    "<span>" + event_name + "</span>" +
                                    "</h3></div></div></div><div class='showpass-layout-flex'><div class='flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date'><div>" +
                                    (event.is_recurring_parent && moment.tz(starts_on, timezone).format('ddd MMM D, YYYY') !== moment.tz(ends_on, timezone).format('ddd MMM D, YYYY') ? "<div class='info'><i class='fa fa-calendar icon-center'></i>" + moment.tz(starts_on, timezone).format('ddd MMM D, YYYY') + " - " + moment.tz(ends_on, timezone).format('ddd MMM D, YYYY') + "</div>" : "<div class='info'><i class='fa fa-calendar icon-center'></i>" + moment.tz(starts_on, timezone).format('ddd MMM D, YYYY') + "</div>") +
                                    (!(event.is_recurring_parent && moment.tz(starts_on, timezone).format('ddd MMM D, YYYY') !== moment.tz(ends_on, timezone).format('ddd MMM D, YYYY')) ? "<div class='info'><i class='fa fa-clock-o icon-center'></i>" + moment.tz(starts_on, timezone).format('h:mm A') + " - " + moment.tz(ends_on, timezone).format('h:mm A') + " " + timezone_abbr + "</div>" : "") +
                                    "<div class='info'><i class='fa fa-map-marker icon-center'></i>" + event_location + "</div>" +
                                    "<div class='info'><i class='fa fa-map-marker icon-center'></i>" + event_city + "</div>" +
                                    "</div></div></div><div class='showpass-layout-flex showpass-list-button-layout'><div class='flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left'><div class='showpass-button-full-width-list'>" +
                                    "<a class='showpass-list-ticket-button showpass-button" + (external_link ? "' href='" + external_link : " open-ticket-widget' id='" + event_slug) + "'>BUY TICKETS</a>" +
                                    "</div></div><div class='flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-right'><div class='showpass-button-full-width-list'>" +
                                    // "<a class='showpass-list-ticket-button showpass-button-secondary' href='" + redirect + "?slug=" + event_slug + "'>More Info</a>" +
                                    "</div></div></div></div></div></div></div>";
                                $("#daily-card-view > .showpass-layout-flex").append(html_card);

                            });

                            // create time scale display
                            let loop_length = (24 - first_event.$durationFromStart).toFixed(0) * 2; // * 2 for every :30 min
                            let starting_time = moment.tz(first_event.starts_on, first_event.timezone).format();
                            //let first_time = "<span class='time'>" + starting_time.format('h:mm A') + "</span>";
                            //$('.time-scale').append(first_time);
                            for (let i = 0; i < loop_length; i++) {
                                let scale = 0.5;
                                let time = moment(starting_time).add(scale * i, 'hours').format('h:mm A');
                                let html = "<span class='time' style='left:" + time_scale * scale * i + "px;'>" + time + "</span>";
                                $('.time-scale').append(html);
                            }
                        } else {
                            let noEvents = '<div class="no-events showpass-flex-column list-layout-flex showpass-no-border showpass-layout-flex justify-center"><div>No events today!</div></div>';
                            $('#schedule-display, #daily-card-view .showpass-layout-flex').html(noEvents);
                        }
                        $('.loader-home').hide();
                        // Scroll back to start
                        $('#schedule-display').scrollLeft(0);
                    }
                });

            }
        }

        // Keep the location name on the single day horizontal scroller sticky
        $('#schedule-display').scroll(function() {
            $(this).find('.sticky').css('left', $(this).scrollLeft());
        });

        function renderCalendarWeek(week) {
            $('.loader-home').show();
            $('.showpass-calendar').removeClass('monthly');
            $('.showpass-calendar').removeClass('daily');
            $('.showpass-calendar').addClass('weekly');
            $('.horizontal-schedule-display').hide();
            $('.calendar-contain-desktop').show();
            $('.showpass-calendar-month').hide();
            $('.showpass-calendar-week').show();
            $('.showpass-week').html('');
            $('.daily-view-toggle').hide();
            // Find current week and get start end dates for event query
            // Set proper dates for selecting
            let currentWeek = moment(week).format();
            let startOfWeek = moment(currentWeek).startOf('week').toISOString();
            let endOfWeek = moment(currentWeek).endOf('week').toISOString();

            $('#view-select .month').attr('current_date', moment(currentWeek).startOf('month').format());
            $('#view-select .week, #view-select .day').attr('current_date', moment(currentWeek).startOf('week').format());
            $('.showpass-calendar').removeClass('daily');

            // Set Prev/Next Week Values
            $('.showpass-prev-week').attr('data-prev-week', moment(currentWeek).subtract(1, 'week').format());
            $('.showpass-next-week').attr('data-next-week', moment(currentWeek).add(1, 'week').format());
            let tags = $('#tags').val();

            let year = moment(currentWeek).format('YYYY');
            $('.showpass-calendar-body').empty();
            let html = "";
            let venue = $('#venue_id').val();
            let only_parents = $('#only-parents').val();
            let hide_children = $('#hide-children').val();
            if (venue) {
                // set initial URL
    			let url = apiURL + "/public/events/?venue__in=" + venue +
                    "&page_size=100&starts_on__lte=" + endOfWeek + "&ends_on__gte=" + startOfWeek;
                // if tags param append to url
                if (tags) {
                    url = url + "&tags=" + tags;
                }

                if (hide_children) {
                    url = url + "&hide_children=" + hide_children;
                }

                if(only_parents) {
                    url = url + "&only_parents=" + only_parents;
                }

                $('.showpass-week').html('Week of <br/>' + moment(currentWeek).format('MMM') + ' ' + moment(currentWeek).format('D'));
                $.ajax({
                    method: "GET",
                    url: url,
                    success: function(data) {
                        for (let i = 0; i < 7; i++) {
                            html += "<div class='showpass-calendar-item' id='event_on_" + moment(currentWeek).add(i, 'days').format('M') + "_" + moment(currentWeek).add(i, 'days').format('D') + "' data-month='" + moment(currentWeek).add(i, 'days').format('M') + "' data-day='" + moment(currentWeek).add(i, 'days').format('D') + "' data-year='" + year + "'><div class='day_number_showpass'>" + moment(currentWeek).add(i, 'days').format('D') + "</div></div>";
                        }

                        $('.showpass-calendar-body').html(html);

                        for (let i = 0; i < data.results.length; i++) {
                            let timezone = data.results[i].timezone;
                            let date_month = data.results[i].starts_on;
                            let a = moment.tz(date_month, timezone).format();
                            date_month = a;
                            let date_day = date_month.split("-");
                            let day_event = parseInt(date_day[2].substring(0, 2));
                            let month_event = parseInt(date_day[1]);
                            let image_event = data.results[i].image || default_square;
                            let event_slug = data.results[i].slug;
                            timezone = moment.tz(data.results[i].timezone).format('z');
                            let external_link = data.results[i].external_link

                            // let tmp_event = $('.showpass-calendar-item-single[data-day=' + day_event +']').find('.a_link').attr('href');
                            let tmp = month_event + '_' + day_event;
                            // $('#event_on_' + tmp).empty();
                            let html_tmp = "<div class='showpass-calendar-item-single show-tooltip' style='background:url(" + image_event + ") no-repeat'>" +
                                "<div class='link" + (external_link ? "' href='" + external_link : " open-ticket-widget' id='" + event_slug) + "'></div></div>";
                            $('#event_on_' + tmp).append(html_tmp);
                        }

                        $('.showpass-calendar-item').addClass('item-week-view');
                        $('.showpass-calendar-item-single').addClass('single-item-week-view');

                        $('.showpass-calendar-item').each(function(index, value) {
                            let length = $(this).children('.showpass-calendar-item-single').length;
                            if (length >= 1) {
                                let color = $('#option_widget_color').val();
                                let single_day = "<span class='go-to-day showpass-button' style='background: #" + color + "'><i class='fa fa-arrow-right'></i><span>View Day</span></span>";
                                $(this).append(single_day);
                            }
                        });

                        if ($('.showpass-calendar-item-single').length === 0) {
                            let noEvents = '<div class="no-events">No events this week!</div>';
                            $('.showpass-calendar-body').append(noEvents);
                        }

                        let height = 0;

                        $('.item-week-view').each(function() {
                            if ($(this).height() > height) {
                                height = $(this).height();
                            }
                        });

                        $('.item-week-view').css('height', height + "px");

                        current_day = 0;
                        $('.loader-home').hide();

                    }

                });
            }
        }

        function renderCalendar(year, month) {
            $('.showpass-calendar-body').empty();
            $('.showpass-calendar-mobile').empty();
            $('.showpass-calendar').removeClass('daily');
            $('.showpass-calendar').removeClass('weekly');
            $('.showpass-calendar').addClass('monthly');

            let currentMonth = moment('01-' + month + "-" + year, "DD-MM-YYYY").format();
            let startOfMonth = moment(currentMonth).startOf('month').toISOString();
            let endOfMonth = moment(currentMonth).endOf('month').toISOString();

            // Set values for display toggle
            $('#view-select .week').attr('current_date', moment(currentMonth).startOf('week').format());
            $('#view-select .month, #view-select .day').attr('current_date', currentMonth);
            $('.loader-home').show();
            $('.daily-view-toggle').hide();

            let tags = $('#tags').val();
            let only_parents = $('#only-parents').val();
            let hide_children = $('#hide-children').val();
            let show_all = $('#show-all').val();

            let firstDay = new Date(year, month - 1, 1); //  number + 1 = current
            let firstDayString = firstDay.toString();
            let first_day = firstDayString.substring(0, 3).toLowerCase();
            let first_day_of_the_month = days.indexOf(first_day);

            let days_in_month = new Date(year, month, 0).getDate(); //excactly
            let html = "";

            let venue = $('#venue_id').val();

            if (venue) {

    			let url = apiURL + "/public/events/?venue__in=" + venue +
                    "&page_size=100&starts_on__lte=" + endOfMonth + "&ends_on__gte=" + startOfMonth;

                if (tags) {
                    url = url + "&tags=" + tags;
                }

                if (hide_children) {
                    url = url + "&hide_children=" + hide_children;
                }

                if(only_parents) {
                    url = url + "&only_parents=" + only_parents;
                }

                if(show_all) {
                    url = url + "&show=" + show_all;
                }

                $.ajax({
                    method: "GET",
                    url: url,
                    success: function(data) {
                        if (first_day_of_the_month == 7) {
                            for (let j = first_day_of_the_month - 6; j <= days_in_month; j++) {
                                for (let i = 0; i < data.results.length; i++) {
                                    let date_month = data.results[i].starts_on;
                                    let date_day = date_month.split("-");
                                    let day_event = parseInt(date_day[2].substring(0, 2));
                                    let month_event = parseInt(date_day[1]);

                                    if ((month == month_event) && (j == day_event)) {
                                        html += '<div class="showpass-calendar-item"></div>';
                                    } else {
                                        html += '<div class="showpass-calendar-item"></div>';
                                    }
                                }
                            }
                        } else {
                            for (let j = (first_day_of_the_month * (-1)) + 1; j <= days_in_month; j++) {
                                if (j < 1) {
                                    html += "<div class='showpass-calendar-item'></div>";
                                } else {
                                    html += "<div class='showpass-calendar-item'><div class='day_number_showpass'>" + j + "</div><div class='item-container'><div id='event_on_" + month + "_" + j + "' class='showpass-calendar-item-event-container' data-day='" + j + "' data-month='" + month + "' data-year='" + year + "'></div></div></div>";
                                }
                            }
                        }

                        $('.showpass-calendar-body').html(html);
                        let eventCounter = 0;
                        for (let i = 0; i < data.results.length; i++) {
                            let timezone = data.results[i].timezone;
                            let date_month = data.results[i].starts_on;
                            let a = moment(date_month).tz(timezone).format();
                            date_month = a;
                            let date_day = date_month.split("-");
                            let day_event = parseInt(date_day[2].substring(0, 2));
                            let month_event = parseInt(date_day[1]);
                            let event_slug = data.results[i].slug;
                            timezone = moment.tz(data.results[i].timezone).format('z');
                            let image_event = data.results[i].image || default_square;
                            let tmp = month_event + '_' + day_event;
                            let html_tmp = "<div class='showpass-calendar-item-single' data-slug='" + event_slug + "' data-month='" + month + "' data-day='" + day_event + "' data-year='" + year + "' style='background:url(" + image_event + ") no-repeat;'><div class='space-filler'></div></div>";
                            $('#event_on_' + tmp).append(html_tmp);
                            eventCounter ++;
                        }

                        let color = $('#option_widget_color').val() || '000000';

                        if (isMobile === false) {
                            // DISPLAY FOR DESKTOP
                            $('.showpass-calendar-item-event-container').each(function(index, value) {
                                let length = $(this).children('.showpass-calendar-item-single').length;
                                if (length > 0) {
                                    let single_day = '';
                                    if (length > 1) {
                                        single_day += "<div class='x-events-today'><strong>" + length + "</strong> Events</div>";
                                        let show_first = $(this).children('.showpass-calendar-item-single:first-child');
                                        $(this).html(show_first);
                                    }
                                    single_day += "<span class='multiple-event-popup' style='background-color: #" + color + ";' data-length='" + length + "'></span>";
                                    $(this).closest('.showpass-calendar-item').append(single_day);
                                }
                            });
                        } else {
                            // DISPLAY FOR MOBILE
                            $('.showpass-calendar-item').each(function(index, value) {
                                $(this).addClass('mobile');
                            });

                            $('.showpass-calendar-item-event-container').each(function(index, value) {
                                let length = $(this).children('.showpass-calendar-item-single').length;
                                if (length > 0) {
                                    let single_day = "<span class='mobile-event-popup multiple' style='background-color: #" + color + ";' data-length='" + length + "'></span>";
                                    $(this).closest('.showpass-calendar-item').append(single_day);
                                }
                            });
                        }
                        $('.loader-home').hide();

                    }
                });
            }
        } // ending render calendar

        let date_now = now;
        let month_now = date_now.getMonth();
        let year_now = date_now.getFullYear();

        if (single_day) {

            setDisplayView();

            renderDailyCalendar(single_day);

        } else if (month_enable === 'disabled') {
            let startingDate = $('#starting-date').val();
            let initiateTime;
            if (startingDate) {
                // USE STARTING_DATE PARAM
                initiateTime = moment(startingDate, "DD-MM-YYYY").startOf('week').format();
            } else {
                // USE NOW AND SET TO START OF WEEK
                initiateTime = moment().startOf('week').format();
            }
            // RENDER WEEK VIEW IF MONTH DISABLED
            $('.horizontal-schedule-display').hide();
            $('.showpass-calendar-month').hide();
            $('.showpass-calendar-week').show();
            $('.showpass-month-view').hide();
            $('.showpass-week').html('');

            renderCalendarWeek(initiateTime);

        } else if (week_enable === 'disabled') {
            // SHOW ONLY MONTHLY VIEW
            $('.showpass-day-view').hide();
            $('.showpass-week-view').hide();
            $('.horizontal-schedule-display').hide();
            $('.showpass-month-view').css('border-right', '0px');
            renderCalendar(year_now, month_now + 1);
        } else {
            // RENDER & SHOW BOTH WEEKLY & MONTHLY & DAILY
            $('.horizontal-schedule-display').hide();
            renderCalendar(year_now, month_now + 1);
        }

        $('#view-select').change(function() {
            let view = $(this).val();
            if (view === 'month') {
                let date_now = now;
                let month_now = date_now.getMonth();
                let year_now = date_now.getFullYear();
                // Set Calendar Header Date
                $('.showpass-month').html(months[month_now + 1]);
                $('.showpass-year').text(year_now);
                $('.showpass-calendar').removeClass('daily');
                // Reset data attribute for next-month
                $('.showpass-next-month').attr('data-month', month_now + 2);
                $('.horizontal-schedule-display').hide();
                $('.calendar-contain-desktop').show();
                renderCalendar(year_now, month_now + 1);
                $('.showpass-calendar-month').show();
                $('.showpass-calendar-week').hide();
            } else if (view === 'week') {
                let initiateTime = moment($(this).find(':selected').attr('current_date')).startOf('week').format();
                renderCalendarWeek(initiateTime);
            } else if (view === 'day') {
                renderDailyCalendar(moment($(this).find(':selected').attr('current_date')).format('DD-MM-YYYY'));
            }
        });

        /*
         * When user clicks overlay on month view
         */
        $('body').on('click', '.multiple-event-popup, .mobile-event-popup.multiple', function (e) {
            let length = $(this).attr('data-length');

            if (length > 1) {
                let container = $(this).parent().find('.showpass-calendar-item-event-container');
                let day = $(container).attr('data-day');
                let month = $(container).attr('data-month');
                let year = $(container).attr('data-year');
                renderDailyCalendar(day + '-' + month + '-' + year);
            } else {
                e.preventDefault();
                let slug = $(this).parent().find('.showpass-calendar-item-single').attr('data-slug');
                var params = {
                    'theme-primary': $('#option_widget_color').val(),
                    'keep-shopping':$('#option_keep_shopping').val() || true,
                    'theme-dark': $('#option_theme_dark').val(),
                    'show-description': $('#option_show_widget_description').val() || 'false'
                };

                // Overwrite tracking-id if set in URL
                if (Cookies.get('affiliate')) {
                    params['tracking-id'] = Cookies.get('affiliate');
                }

                showpass.tickets.eventPurchaseWidget(slug, params);
            }


        });

        /*
         * When user clicks "view day" button in week view
         */
        $('body').on('click', '.go-to-day', function(e) {
            let container = $(this).closest('.showpass-calendar-item');
            let day = $(container).attr('data-day');
            let month = $(container).attr('data-month');
            let year = $(container).attr('data-year');
            let initiateTime = day + '-' + month + '-' + year;
            renderDailyCalendar(initiateTime);
        });

        /*
         * Change daily view display
         */
        $('.icon-button').on('click', function() {
            if (!$(this).hasClass('active')) {
                $('.icon-button').removeClass('active');
                $(this).addClass('active');
                singleDisplay = $(this).attr('id');
                setDisplayView();
            }
        });

    });

})(jQuery);
