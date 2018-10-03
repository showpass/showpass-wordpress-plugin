(function($) {

    $(window).ready(function() {

        var isMobile = /Mobi/.test(navigator.userAgent);
        if (isMobile == true) {
            //$('.showpass-calendar .calendar-contain-mobile').show();
        } else {
            //$('.showpass-calendar .calendar-contain-desktop').show();
        }

        function initializeTooltip () {
            $('.show-tooltip').tooltipster({
                interactive: true,
                minWidth: 300,
                maxWidth: 320,
                contentCloning: true
            });
        }

        function setDisplayView () {
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

        var single_day = $('#single-day').val();
      	var months =  ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May' , 'Jun' , 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
      	var days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        var calendar_day = $('#calendar-day').val();
        var calendar_month = $('#calendar-month').val()-1;
        var calendar_year= $('#calendar-year').val();
        var use_widget = $('#use-widget').val();
      	var now = new Date(calendar_year, calendar_month, calendar_day);
      	var cur_month = now.getMonth();
      	var cur_year = now.getFullYear();
      	var today_first = parseInt($('#current_day').val());
      	var month_enable = $('#month_enable').val();
      	var week_enable = $('#week_enable').val();
      	var current_day = now.getDay();

        var widget_class = '';

        if (use_widget) {
            widget_class = 'open-ticket-widget'
        }

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
        $('.showpass-next-week').click(function(){

            let nextWeek = $(this).attr('data-next-week');
        	renderCalendarWeek(nextWeek);

        });

        // Render new calendar when prev month button pressed
    	$('.showpass-prev-month').click(function() {

    		var month_number = parseInt($(this).attr('data-month'));
    		var year = parseInt($('.showpass-year').text());
            $('.showpass-next-month').removeClass('disabled');
    		if (month_number == 0) {
    			month_number = 12;
    			year = year - 1;
    		}

    		if (month_number == (cur_month+1)) {
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
    		var month_number = parseInt($(this).attr('data-month'));
    		var year = parseInt($('.showpass-year').text());
    		$('.showpass-prev-month').removeClass('disabled');

    		if(month_number == 13) {
    			month_number = 1;
    			$('.showpass-year').text(year + 1);
    			year++;
    		}

    		if(month_number == cur_month && year == (cur_year+1)) {
    			$(this).addClass('disabled');
    		}

    		$('.showpass-month').html(months[month_number]);
    		$(this).attr('data-month', month_number + 1);
    		$('.showpass-prev-month').attr('data-month', month_number - 1);
    		renderCalendar(year, month_number);

    	});

        // render daily calendar
        function renderDailyCalendar (date) {
            $('#schedule-display').empty();
            $("#daily-card-view .showpass-layout-flex").empty();
            $('.showpass-day-view').addClass('active');
            $('.showpass-calendar').addClass('daily');
            $('.calendar-contain-desktop').hide();
            $('.showpass-calendar-month').hide();
            $('.showpass-calendar-week').hide();
            $('.daily-view-toggle').show();
            $('.horizontal-schedule-display').show();
            $('.loader-home').show();
            var date = moment(date, "DD-MM-YYYY").format();
            var dayStart = moment(date).startOf('day').toISOString();
            var dayEnd = moment(date).endOf('day').toISOString();
            var tags = $('#tags').val();
            var venue = $('#venue_id').val();

            // Set values for display toggle
            $('.showpass-month-view').attr('current_date', moment(date).startOf('month').format());
            $('.showpass-week-view').attr('current_date', moment(date).startOf('week').format());

            // Set displays and data attributes for the controls
            $('.showpass-day').html(moment(date).format('dddd') + '<br/>' + moment(date).format('MMM D'));
            $('.showpass-prev-day').attr('data-day', moment(moment(date).format()).subtract(1, 'day').format('DD-MM-YYYY'));
            $('.showpass-next-day').attr('data-day', moment(moment(date).format()).add(1, 'day').format('DD-MM-YYYY'));
            if (venue) {
                // set initial URL
    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100&starts_on__gte=" + dayStart + "&ends_on__lt=" + dayEnd;
                // if tags param append to url
                if (tags) {
                    url = url+"&tags=" + tags;
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
                                var time_start = moment.tz(event.starts_on, event.timezone).format();
                                var time_end = moment.tz(event.ends_on, event.timezone).format();
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
                            let locationGroup =  _.values(_.mapValues(_.groupBy(events, 'location.id')));
                            for (var i = 0; i < locationGroup.length; i++) {
                                var locationEvents = locationGroup[i];
                                var html_loc = "<div class='location location-" + locationEvents[0].location.id +  "' style='width:" + schedule_width +  "px;'><span class='location-name gradient'><span class='sticky'><i class='fa fa-map-marker'></i>" + locationEvents[0].location.name + "</span></span><div class='time-scale'></div><div class='daily-contain'></div></div>";
                                $('#schedule-display').append(html_loc);
                                for (var e = 0; e < locationEvents.length; e++) {
                                    var timezone = locationEvents[e].timezone;
                                    var starts_on = locationEvents[e].starts_on;
                                    var ends_on = locationEvents[e].ends_on;
                                    var a = moment.tz(starts_on, timezone).format();
                                    date_month = a;
                                    var date_day = date_month.split("-");
                                    var day_event = parseInt(date_day[2].substring(0,2));
                                    var month_event = parseInt(date_day[1]);
                                    var year_event = parseInt(date_day[0]);
                                    var event_name = locationEvents[e].name;
                                    var image_thumb = locationEvents[e].thumbnail;
                                    var event_slug = locationEvents[e].slug;
                                    var event_location = locationEvents[e].location.name;
                                    var event_city = locationEvents[e].location.city + ', ' + locationEvents[e].location.province;
                                    var timezone_abbr = moment.tz(locationEvents[e].timezone).format('z');
                                    var event_duration = moment.duration(moment(ends_on).diff(moment(starts_on))).asHours();
                                    var tile_width = event_duration * time_scale;
                                    var horizontal_position = moment.duration(moment(starts_on).diff(moment(start_of_schedule))).asHours() * time_scale;
                                    var html_tmp = "<div class='daily-event gradient' style='width: " + tile_width + "px; left: " + horizontal_position + "px'><div class='event-info'><div class='event-name'>" + event_name + "</div>" +
                                    "<div class='time'><small><i class='fa fa-clock-o'></i>" + moment.tz(starts_on, timezone).format('h:mm A') + " - " + moment.tz(ends_on, timezone).format('h:mm A') + " " + timezone_abbr + "</small></div></div></div></div>";
                                    $('.location-' + locationEvents[e].location.id + ' .daily-contain').append(html_tmp);
                                }
                            }

                            _.forEach(events, function(event) {

                                var timezone = event.timezone;
                                var starts_on = event.starts_on;
                                var ends_on = event.ends_on;
                                var a = moment.tz(starts_on, timezone).format();
                                date_month = a;
                                var date_day = date_month.split("-");
                                var day_event = parseInt(date_day[2].substring(0,2));
                                var month_event = parseInt(date_day[1]);
                                var year_event = parseInt(date_day[0]);
                                var event_name = event.name;
                                var image_thumb = event.image;
                                var image_banner = event.image_banner;
                                var event_slug = event.slug;
                                var event_location = event.location.name;
                                var event_city = event.location.city + ', ' + event.location.province;

                                var html_card = "<div class='flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-event-card'><div class='showpass-event-layout-list showpass-layout-flex m15'><div class='flex-25 showpass-flex-column list-layout-flex showpass-no-border showpass-no-padding p0'>" +
                                                "<a class='showpass-image-banner showpass-hide-mobile' style='background-image: url(" + image_thumb + "); href='/event-detail/?slug=wordfest-present-gary-shteyngart'></a>" +
                                                "<a class='showpass-image showpass-hide-large' style='background-image: url(" + image_banner + ");' href='/event-detail/?slug=wordfest-present-gary-shteyngart'></a> </div>" +
                                                "<div class='flex-75 showpass-flex-column list-layout-flex showpass-no-border showpass-background-white'><div class='showpass-full-width'><div class='showpass-layout-flex'><div class='flex-100 showpass-flex-column list-layout-flex showpass-no-border showpass-title-wrapper'><div class='showpass-event-title'><h3>" +
                                                "<a href='/event-detail/?slug=wordfest-present-gary-shteyngart'>" + event_name + "</a>" +
                                                "</h3></div></div></div><div class='showpass-layout-flex'><div class='flex-100 showpass-flex-column showpass-no-border showpass-detail-event-date'><div>" +
                                                "<div class='info'><i class='fa fa-calendar icon-center'></i>" + moment.tz(starts_on, timezone).format('dddd MMMM D, YYYY') + "</div>" +
                                                "<div class='info'><i class='fa fa-clock-o icon-center'></i>" + moment.tz(starts_on, timezone).format('h:mm A') + "</div>" +
                                                "<div class='info'><i class='fa fa-map-marker icon-center'></i>" + event_location + "</div>" +
                                                "<div class='info'><i class='fa fa-map-marker icon-center'></i>" + event_city + "</div>" +
                                                "</div></div></div><div class='showpass-showpass-layout-flex'><div class='showpass-layout-flex showpass-list-button-layout'><div class='flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-left'><div class='showpass-button-full-width-list'>" +
                                                "<a class='showpass-list-ticket-button showpass-button open-ticket-widget' id='wordfest-present-gary-shteyngart'>BUY TICKETS</a>" +
                                                "</div></div><div class='flex-50 showpass-flex-column list-layout-flex showpass-no-border showpass-button-pull-right'><div class='showpass-button-full-width-list'>" +
                                                "<a class='showpass-list-ticket-button showpass-button-secondary' href='/event-detail/?slug=wordfest-present-gary-shteyngart'>More Info</a>"
                                                "</div></div></div></div></div></div></div></div>";
                                $("#daily-card-view > .showpass-layout-flex").append(html_card);

                            });

                            // create time scale display
                            let loop_length = (24 - first_event.$durationFromStart).toFixed(0) * 2; // * 2 for every :30 min
                            let starting_time = moment.tz(first_event.starts_on, first_event.timezone).format();
                            //let first_time = "<span class='time'>" + starting_time.format('h:mm A') + "</span>";
                            //$('.time-scale').append(first_time);
                            for (var i = 0; i < loop_length; i++) {
                                let scale = 0.5;
                                let time = moment(starting_time).add(scale * i, 'hours').format('h:mm A');
                                let html = "<span class='time' style='left:" + time_scale * scale * i + "px;'>" + time + "</span>";
                                $('.time-scale').append(html);
                            }
                        } else {
                            var noEvents = '<div class="no-events showpass-flex-column list-layout-flex showpass-no-border showpass-layout-flex justify-center"><div>No events today!</div></div>';
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

    	function renderCalendarWeek (week) {

            $('.loader-home').show();
            $('.showpass-view').removeClass('active');
            $('.showpass-week-view').addClass('active');
            $('.showpass-calendar').removeClass('daily');
            $('.horizontal-schedule-display').hide();
            $('.calendar-contain-desktop').show();
            $('.showpass-calendar-month').hide();
            $('.showpass-calendar-week').show();
            $('.showpass-week').html('');
            $('.daily-view-toggle').hide();
            // Find current week and get start end dates for event query
            // Set proper dates for selecting
            let currentWeek = moment(week).format();
            let startWeek = moment(currentWeek).startOf('week').toISOString();
            let endWeek = moment(currentWeek).endOf('week').toISOString();
            $('.showpass-month-view').attr('current_date', moment(currentWeek).startOf('month').format());
            $('.showpass-week-view, .showpass-day-view').attr('current_date', moment(currentWeek).startOf('week').format());
            $('.showpass-calendar').removeClass('daily');

            // Set Prev/Next Week Values
            $('.showpass-prev-week').attr('data-prev-week', moment(currentWeek).subtract(1, 'week').format());
            $('.showpass-next-week').attr('data-next-week', moment(currentWeek).add(1, 'week').format());
        	var page_type = $('#page_type').val();
        	var site_url = $('#site_url').val();
            var tags = $('#tags').val();
            var month = moment(currentWeek).format('M');
            var year = moment(currentWeek).format('YYYY');
        	$('.showpass-calendar-body').empty();
        	var html = "";
    		var venue = $('#venue_id').val();
    		if (venue) {
                // set initial URL
    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100&starts_on__gte=" + startWeek + "&ends_on__lt=" + endWeek;
                // if tags param append to url
                if (tags) {
                    url = url+"&tags=" + tags;
                }
                $('.showpass-week').html('Week of <br/>' + moment(currentWeek).format('MMM') + ' ' + moment(currentWeek).format('D'));
    			$.ajax({
    				method: "GET",
    				url: url,
    				success: function(data) {
                        for (var i = 0; i < 7; i++) {
                            html += "<div class='showpass-calendar-item' id='event_on_" + moment(currentWeek).add(i, 'days').format('M') + "_" + moment(currentWeek).add(i, 'days').format('D') + "' data-month='" + moment(currentWeek).add(i, 'days').format('M') + "' data-day='" + moment(currentWeek).add(i, 'days').format('D') + "' data-year='" + year +"'><div class='day_number_showpass'>" + moment(currentWeek).add(i, 'days').format('D') + "</div></div>";
                        }

    					$('.showpass-calendar-body').html(html);

    					for (var i = 0; i < data.results.length; i++) {
    						var timezone = data.results[i].timezone;
    						var date_month = data.results[i].starts_on;
    						var a = moment.tz(date_month, timezone).format();
    						date_month = a;
    						var date_day = date_month.split("-");
    						var day_event = parseInt(date_day[2].substring(0,2));
    						var month_event = parseInt(date_day[1]);
    						var year_event = parseInt(date_day[0]);
    						var event_name = data.results[i].name;
    						var image_event = data.results[i].image_banner;
    						var event_slug = data.results[i].slug;
                            var event_location = data.results[i].location.name;
                            var event_city = data.results[i].location.city + ', ' + data.results[i].location.province;
                            var timezone = moment.tz(data.results[i].timezone).format('z');

    						if(page_type !== "" || widget_class !=='') {
    							var url_event = site_url + "/" + page_type + "?slug=" + event_slug;
                                var target = "_self";
    						} else {
    							var url_event = data.results[i].frontend_details_url;
                                var target = "_blank"
    						}

							// var tmp_event = $('.showpass-calendar-item-single[data-day=' + day_event +']').find('.a_link').attr('href');
							var tmp = month_event + '_' + day_event;
							// $('#event_on_' + tmp).empty();
                            var html_tmp = "<div class='showpass-calendar-item-single show-tooltip' data-tooltip-content='#template-"+event_slug+"' style='background:url(" + image_event + ") no-repeat'>" +
                            "<div class='day_number_showpass'>" + day_event + "</div>"+
                            "<div class='link'></div>" +
                            "<div class='tooltip_templates'><div class='calendar-tooltip' id='template-"+event_slug+"'><img class='tooltip-thumb' src='" + image_event + "' alt='" + event_name + "' />" +
                            "<div class='info'><div class='event-name'>" + event_name + "</div>" +
                            "<div class='location'><i class='fa fa-map-marker'></i>" + event_location + "</div>" +
                            "<div class='location'><i class='fa fa-map-marker'></i>" + event_city + "</div>" +
                            "<div class='time'><i class='fa fa-clock-o'></i>" + moment(a).format('hh:mmA') + " " + timezone + "</div>" +
                            "<div class='buttons'><a target='" + target + "' class='calendar-button " + widget_class + "' id='" + event_slug + "' href='" + url_event + "'><i class='fa fa-tags'></i>Tickets</a></div>" +
                            "</div></div>"+
                            "</div>";
							$('#event_on_' + tmp).append(html_tmp);
    					}

    					$('.showpass-calendar-item').addClass('item-week-view');
    					$('.showpass-calendar-item-single').addClass('single-item-week-view');


                        $('.showpass-calendar-item').each(function (index, value) {
                            var length = $(this).children('.showpass-calendar-item-single').length;
                            var id = $(this).attr('id');
                            if (length >= 1) {
                                var color = $('#option_widget_color').val() || '';
                                var single_day = "<span class='go-to-day showpass-button' style='background: #" + color +"'>View Day</span>";
    							$(this).append(single_day);
                            }
                        });

    					var height = 0;

    					$('.item-week-view').each(function() {
    						if($(this).height() > height) {
    							height = $(this).height();
    						}
    					});

    					$('.item-week-view').css('height', height + "px");


    					current_day = 0;
                        $('.loader-home').hide();
                        initializeTooltip();

    				}

    			});
    		}
    	}

    	function renderCalendar (year , month) {
            let currentMonth = moment('01-' + month + "-" + year, "DD-MM-YYYY").format();
            let startMonth = moment(currentMonth).startOf('month').toISOString();
            let endMonth = moment(currentMonth).endOf('month').toISOString();
            // Set values for display toggle
            $('.showpass-week-view').attr('current_date', moment(currentMonth).startOf('week').format());
            $('.showpass-month-view, .showpass-day-view').attr('current_date', currentMonth);
    		$('.loader-home').show();
            $('.daily-view-toggle').hide();
    		var d = new Date();
    		var current_month = d.getMonth();
    		var page_type = $('#page_type').val();
    		var site_url = $('#site_url').val();
            var tags = $('#tags').val();
    		$('.showpass-calendar-body').empty();
            $('.showpass-calendar-mobile').empty();
            $('.showpass-calendar').removeClass('daily');
    		var firstDay = new Date(year, month-1 , 1);  //  number + 1 = current
    		var firstDayString = firstDay.toString();
    		var first_day = firstDayString.substring(0,3).toLowerCase();
    		var first_day_of_the_month = days.indexOf(first_day);
    		var days_in_month = new Date(year, month, 0).getDate(); //excactly
    		var html = "";

    		var venue = $('#venue_id').val();

    		if (venue) {

    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100&starts_on__gte=" + startMonth + "&ends_on__lt=" + endMonth;

                if (tags) {
                    url = url+"&tags=" + tags;
                }

    			$.ajax({
    				method: "GET",
    				url: url,
    				success: function(data) {
    					if(first_day_of_the_month == 7) {
    						for (var j = first_day_of_the_month - 6; j <= days_in_month; j++) {
    							for (var i = 0; i < data.results.length; i++) {
    								var date_month = data.results[i].starts_on;
    								var date_day = date_month.split("-");
    								var day_event = parseInt(date_day[2].substring(0,2));
    								var month_event = parseInt(date_day[1]);
    								var year_event = parseInt(date_day[0]);
    								var image_event = data.results[i].thumbnail;
    								var event_slug = data.results[i].slug;

    								if(page_type !== "") {
    									var url_event = site_url + "/" + page_type + "?slug=" + event_slug;
    								} else {
    									var url_event = data.results[i].frontend_details_url;
    								}

    								if((month == month_event) && (j == day_event)) {
    									html += '<div class="showpass-calendar-item"></div>';
    								} else {
    									html += '<div class="showpass-calendar-item"></div>';
    								}
    							}
    						}
    					} else {
    						for(var j = (first_day_of_the_month * (-1)) + 1; j <= days_in_month; j++ ) {
    							if (j < 1) {
    								html += "<div class='showpass-calendar-item'></div>";
    							} else {
    								html += "<div class='showpass-calendar-item'><div class='day_number_showpass'>" + j + "</div><div id='event_on_" + month + "_" + j + "' class='showpass-calendar-item-event-container' data-day='" + j + "' data-month='" + month + "' data-year='" + year + "'></div></div>";
    							}
    						}
    					}

    					$('.showpass-calendar-body').html(html);
                        var eventCounter = 0;
    					for (var i = 0; i < data.results.length; i++) {
    						var timezone = data.results[i].timezone;
    						var date_month = data.results[i].starts_on;
    						var a = moment(date_month).tz(timezone).format();
    						date_month = a;
    						var date_day = date_month.split("-");
    						var day_event = parseInt(date_day[2].substring(0,2));
    						var month_event = parseInt(date_day[1]);
    						var year_event = parseInt(date_day[0]);
    						var event_name = data.results[i].name;
    						var event_slug = data.results[i].slug;
                            var event_location = data.results[i].location.name;
                            var event_city = data.results[i].location.city + ', ' + data.results[i].location.province
                            var timezone = moment.tz(data.results[i].timezone).format('z')
                            var image_event = data.results[i].image;
                            var image_banner = data.results[i].image_banner;
                            if (!image_event) {
                                image_event = 'https://showpass-live.s3.amazonaws.com/static/assets/img/default-square.png'
                            }
                            if (!image_banner) {
                                image_banner = 'https://showpass-live.s3.amazonaws.com/static/assets/img/default-banner.png'
                            }
                            if (page_type !== "" || widget_class !=='') {
                                var url_event = site_url + "/" + page_type + "?slug=" + event_slug;
                                var target = "_self";
                            } else {
                                var url_event = data.results[i].frontend_details_url;
                                var target = "_blank"
                            }

							var tmp = month_event + '_' + day_event;
                            var html_tmp = "<div class='showpass-calendar-item-single show-tooltip' data-tooltip-content='#template-" + event_slug + "' data-month='" + month + "' data-day='" + day_event + "' data-year='" + year +"' style='background:url(" + image_event + ") no-repeat'>" +
                            "<div class='tooltip_templates'><div class='calendar-tooltip tooltip-content' id='template-" + event_slug + "'><img class='tooltip-thumb' src='" + image_banner + "' alt='" + event_name + "' />" +
                            "<div class='info'><div class='event-name'>" + event_name + "</div>" +
                            "<div class='location'><i class='fa fa-map-marker'></i>" + event_location + "</div>" +
                            "<div class='time'><i class='fa fa-clock-o'></i>" + moment(date_month).tz(data.results[i].timezone).format('hh:mmA') + " " + timezone + "</div>" +
                            "<div class='buttons'><a class='calendar-button " + widget_class + "' id='" + event_slug + "' href='" + url_event + "'><i class='fa fa-tags'></i>Tickets</a></div>" +
                            "</div></div>" +
                            "</div>";

							$('#event_on_' + tmp).append(html_tmp);

                            eventCounter++;

                            if ( i+1 == data.results.length && eventCounter == 0) {
                                $(".showpass-calendar-mobile").html('<div class="not-found">No Events Found!</div>');
                            }

    					}

                        $('.showpass-calendar-item-event-container').each(function (index, value) {
                            var length = $(this).children('.showpass-calendar-item-single').length;
                            var id = $(this).attr('id');
                            if (length > 4) {
                                var single_day = "<span class='multiple-event-popup'></span>";
    							$(this).append(single_day);
                            }
                        });

    					$('.loader-home').hide();
                        initializeTooltip();

    				}
    			});
    		}
    	}  // ending render calendar

    	var date_now = now;
    	var month_now = date_now.getMonth();
    	var year_now = date_now.getFullYear();

        if (single_day) {

            setDisplayView();

            renderDailyCalendar(single_day);

        } else if (month_enable === 'disabled')	{
            let startingDate = $('#starting-date').val();
            let initiateTime;
            if (startingDate != '') {
                // USE STARTING_DATE PARAM
                initiateTime = moment(startingDate, "DD-MM-YYYY").startOf('week').format();
            } else {
                // USE NOW AND SET TO START OF WEEK
                initiateTime = moment().startOf('week').format();
            }
            // RENDER WEEK VIEW IF MONTH DISABLED
      		$('.showpass-week-view').addClass('active');
            $('.showpass-week-view').addClass('active');
      		$('.showpass-month-view').removeClass('active');
      		$('.horizontal-schedule-display').hide();
      		$('.showpass-contain-desktop').show();
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
            initializeTooltip();
    	} else {
            // RENDER & SHOW BOTH WEEKLY & MONTHLY & DAILY
            $('.horizontal-schedule-display').hide();
            $('.calendar-contain-desktop').show();
            $('.showpass-month-view').addClass('active');
    		renderCalendar(year_now, month_now + 1);
    	}

    	$('.showpass-month-view').click(function() {

            // Currently this function resets back to now
            // Should reset to month of current week or day you are viewing

    		if(!$(this).hasClass('active')) {
            $('.calendar-contain-desktop').show();
			var date_now = now;
			var month_now = date_now.getMonth();
			var year_now = date_now.getFullYear();
            // Set Calendar Header Date
            $('.showpass-month').html(months[month_now + 1]);
            $('.showpass-year').text(year_now);
            $('.showpass-calendar').removeClass('daily');
            // Reset data attribute for next-month
            $('.showpass-next-month').attr('data-month', month_now + 2);
            $('.horizontal-schedule-display').hide();
            $('.calendar-contain-desktop').show();
            renderCalendar(year_now, month_now + 1);
            $('.showpass-view').removeClass('active');
            $(this).addClass('active');
            $('.showpass-calendar-month').show();
            $('.showpass-calendar-week').hide();
    		}
    	});

        $('.showpass-day-view').click(function() {
            if (!$(this).hasClass('active')) {
                $('.showpass-view').removeClass('active');
                $(this).addClass('active');
                renderDailyCalendar(moment($(this).attr('current_date')).format('DD-MM-YYYY'));
            }
        });

    	$('.showpass-week-view').click(function() {
    		if (!$(this).hasClass('active')) {
                var initiateTime = moment($(this).attr('current_date')).startOf('week').format();
                renderCalendarWeek(initiateTime);
    		}
    	});

        /*
        * When user clicks overlay on month view
        */
        $('body').on('click', '.multiple-event-popup', function (e) {
            let container = $(this).closest('.showpass-calendar-item-event-container');
            var day = $(container).attr('data-day');
            var month = $(container).attr('data-month');
            var year = $(container).attr('data-year');
            // SET START OF WEEK
            var initiateTime = moment(day + '-' + month + '-' + year, "DD-MM-YYYY").startOf('week').format();
            renderCalendarWeek(initiateTime);
        });

        /*
        * When user clicks "view day" button in week view
        */
        $('body').on('click', '.go-to-day', function (e) {
            $('.showpass-view').removeClass('active');
            let container = $(this).closest('.showpass-calendar-item');
            var day = $(container).attr('data-day');
            var month = $(container).attr('data-month');
            var year = $(container).attr('data-year');
            var initiateTime = day + '-' + month + '-' + year;
            renderDailyCalendar(initiateTime);
        });

        $('.icon-button').on('click', function () {
            if (!$(this).hasClass('active')) {
                $('.icon-button').removeClass('active');
                $(this).addClass('active');
                singleDisplay = $(this).attr('id');
                setDisplayView();
            }
        });

    });

})(jQuery);
