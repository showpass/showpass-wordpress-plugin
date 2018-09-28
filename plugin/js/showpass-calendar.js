(function($) {

    $(window).ready(function() {

        var isMobile = /Mobi/.test(navigator.userAgent);
        if (isMobile == true) {
            $('.showpass-calendar .calendar-contain-mobile').show();
        } else {
            $('.showpass-calendar .calendar-contain-desktop').show();
        }

        function initializeTooltip () {
            $('.show-tooltip').tooltipster({
                interactive: true,
                minWidth: 300,
                maxWidth: 320,
                contentCloning: true
            });
        }

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

        $('.showpass-prev-week').click(function() {

        	var month_number = $('#current-month').val();
    		var today = parseInt($('#current_day').val());
    		var year = parseInt($('.showpass-year').text());
    		var days_in_month = new Date(year, month_number, 0).getDate(); //excactly

        	if (today <= 7) {
        		days_in_month = new Date(year, month_number-1, 0).getDate();
        		today = (today - 7) * (-1);
        		today = days_in_month - today;
        		month_number -= 1;
        		$('#current-month').val(month_number);
        	} else {
        		today -= 7;
        	}

        	renderCalendarWeek(year, month_number, today);

        	if (parseInt(month_number) == (cur_month + 1) && today < today_first) {
        		$(this).hide();
        	}

    		$('.showpass-week').html('Week of <br/>' + months[month_number] + ' ' + (today - current_day));
        	$('#current_day').val(today);

        });

        $('.showpass-next-week').click(function(){

        	$('.showpass-prev-week').removeClass('disabled');
        	var month_number = $('#current-month').val();
    		var today = parseInt($('#current_day').val());
    		var year = parseInt($('.showpass-year').text());
    		var days_in_month = new Date(year, month_number, 0).getDate(); //excactly

        	if ((today + 6) >= days_in_month) {
        		today = (today + 7) - days_in_month;
        		month_number = parseInt(month_number) + 1;
        		$('#current-month').val(month_number);
        	} else {
        		today += 7;
        	}

        	renderCalendarWeek(year, month_number, today);
    		$('.showpass-week').html('Week of <br/>' + months[month_number] + ' ' + (today - current_day));
        	$('#current_day').val(today);

        });

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
            $('.loader-home').show();
            var date = moment(date, "DD-MM-YYYY");
            var dayStart = date.startOf('day').toISOString();
            var dayEnd = date.endOf('day').toISOString();
            var tags = $('#tags').val();
            var venue = $('#venue_id').val();
            if (venue) {
                // set initial URL
    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100&starts_on__gte=" + dayStart + "&ends_on__lt=" + dayEnd;
                // if tags param append to url
                if (tags) {
                    url = url+"&tags=" + tags;
                }

                console.log(url);

                $.ajax({
    				method: "GET",
    				url: url,
    				success: function(data) {

                        // set initial data points
                        let start_of_day = date.startOf('day').format();
                        let end_of_day = date.endOf('day').format();
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
                            var html_loc = "<div class='location location-" + locationEvents[0].location.id +  "' style='width:" + schedule_width +  "px;'><span class='location-name'><span class='sticky'><i class='fa fa-map-marker'></i>" + locationEvents[0].location.name + "</span></span><div class='time-scale'></div><div class='daily-contain'></div></div>";
                            $('#single-event').append(html_loc);
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
                                var html_tmp = "<div class='daily-event' style='width: " + tile_width + "px; left: " + horizontal_position + "px'><div class='event-info'><div class='event-name'>" + event_name + "</div>" +
                                "<div class='time'><small><i class='fa fa-clock-o'></i>" + moment.tz(starts_on, timezone).format('h:mm A') + " - " + moment.tz(ends_on, timezone).format('h:mm A') + " " + timezone_abbr + "</small></div></div></div></div>";
                                $('.location-' + locationEvents[e].location.id + ' .daily-contain').append(html_tmp);
                            }
                        }
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

                        $('.loader-home').hide();
                    }
                });

            }
        }

        // Keep the location name on the single day horizontal scroller sticky
        $('#single-event').scroll(function() {
            $(this).find('.sticky').css('left', $(this).scrollLeft());
        });

    	function renderCalendarWeek (year, month, today) {
    		$('.loader-home').show();
    		var d = new Date();
    		var current_month = d.getMonth();
    		var page_type = $('#page_type').val();
    		var site_url = $('#site_url').val();
            var tags = $('#tags').val();
    		$('.showpass-calendar-body').empty();
    		var firstDay = new Date(year, month-1 , 1);  //  number + 1 = current
    		var firstDayString = firstDay.toString();
    		var first_day = firstDayString.substring(0,3).toLowerCase();
    		var first_day_of_the_month = days.indexOf(first_day);
    		var days_in_month = new Date(year, month, 0).getDate(); //excactly
    		var max_prev = today - current_day;
    		var html = "";

    		var venue = $('#venue_id').val();
    		if (venue) {
                // set initial URL
    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100";
                // if tags param append to url
                if (tags) {
                    url = url+"&tags=" + tags;
                }
                $('.showpass-week').html('Week of <br/>' + months[month] + ' ' + (today - current_day));
    			$.ajax({
    				method: "GET",
    				url: url,
    				success: function(data) {

    					if((today + 6) > days_in_month) {
    						for(var j = (today - current_day); j <= days_in_month; j++ ) {
    							html += "<div class='showpass-calendar-item' id='event_on_" + month + "_" + j + "'><div class='day_number_showpass'>" + j + "</div></div>";
    						} for(var k = 1; k < ((today + 6) - days_in_month) + 1; k++) {
    							html += "<div class='showpass-calendar-item' id='event_on_" + (parseInt(month)+1) + "_" + k + "'><div class='day_number_showpass'>" + k + "</div></div>";
    						}
    					} else {
    						for(var j = (today - current_day); j <= today + (6 - current_day); j++ ) {
    							html += "<div class='showpass-calendar-item' id='event_on_" + month + "_" + j + "'><div class='day_number_showpass'>" + j + "</div></div>";
    						}
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

    						if(month == month_event && year == year_event) {
    							// var tmp_event = $('.showpass-calendar-item-single[data-day=' + day_event +']').find('.a_link').attr('href');
    							var tmp = month_event + '_' + day_event;
    							// $('#event_on_' + tmp).empty();
                                var html_tmp = "<div class='showpass-calendar-item-single show-tooltip' data-tooltip-content='#template-"+event_slug+"' data-month='" + month + "' data-day='" + day_event + "' data-year='" + year +"' style='background:url(" + image_event + ") no-repeat'>" +
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
    					}

    					$('.showpass-calendar-item').addClass('item-week-view');
    					$('.showpass-calendar-item-single').addClass('single-item-week-view');

    					var height = 0;

    					$('.item-week-view').each(function() {
    						if($(this).height() > height) {
    							height = $(this).height();
    						}
    					});

    					$('.item-week-view').css('height', height + "px");

    					$('.loader-home').hide();

    					current_day = 0;
                        initializeTooltip();

    				}

    			});
    		}
    	}

    	function renderCalendar (year , month) {

    		$('.loader-home').show();
    		var d = new Date();
    		var current_month = d.getMonth();
    		var page_type = $('#page_type').val();
    		var site_url = $('#site_url').val();
            var tags = $('#tags').val();
    		$('.showpass-calendar-body').empty();
            $('.showpass-calendar-mobile').empty();
    		var firstDay = new Date(year, month-1 , 1);  //  number + 1 = current
    		var firstDayString = firstDay.toString();
    		var first_day = firstDayString.substring(0,3).toLowerCase();
    		var first_day_of_the_month = days.indexOf(first_day);
    		var days_in_month = new Date(year, month, 0).getDate(); //excactly
    		var html = "";

    		var venue = $('#venue_id').val();

    		if (venue) {

    			var url = "https://www.showpass.com/api/public/events/?venue__in=" + venue + "&page_size=100";

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
    								html += "<div class='showpass-calendar-item'><div class='day_number_showpass'>" + j + "</div><div id='event_on_" + month + "_" + j + "' class='showpass-calendar-item-event-container'></div></div>";
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
    						if (month == month_event && year == year_event) {
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

                                var html_mobile = "<div class='mobile-event clearfix'><div class='date-display'>" + day_event + "</div><img class='mobile-thumb' src='" + image_banner + "' alt='" + event_name + "' />" +
                                "<div class='info'><div class='event-name'><h3>" + event_name + "</h3></div>" +
                                "<div class='info-detail'><i class='fa fa-map-marker'></i>" + event_location + "</div>" +
                                "<div class='info-detail'><i class='fa fa-calendar-o'></i>" + moment(a).format('ddd MMM Do YYYY') + "</div>" +
                                "<div class='info-detail'><i class='fa fa-clock-o'></i>" + moment(a).format('hh:mmA') + " " + timezone + "</div>" +
                                "<div class='buttons'><a target='" + target + "' class='btn showpass-button " + widget_class + "' id='" + event_slug + "' href='" + url_event + "'><i class='fa fa-tags'></i>Tickets</a></div></div>";

                                $(".showpass-calendar-mobile").append(html_mobile);
                                eventCounter++;
                            }

                            if ( i+1 == data.results.length && eventCounter == 0) {
                                $(".showpass-calendar-mobile").html('<div class="not-found">No Events Found!</div>');
                            }

    					}

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
            // RENDER SINGLE DAY CALENDAR
            $('.showpass-calendar').addClass('daily');
            renderDailyCalendar(single_day);
        } else if (month_enable === 'disabled')	{
            // RENDER WEEK VIEW IF MONTH DISABLED
    		$('.showpass-week-view').addClass('active');
    		$('.showpass-month-view').hide();
    		var date_now = now;
    		var month_now = date_now.getMonth();
    		var year_now = date_now.getFullYear();
    		current_day = date_now.getDay();
    		$('.showpass-week').html('');
    		$('.showpass-calendar-week').show();
    		renderCalendarWeek(year_now, month_now + 1, today_first);
    		$('#current_day').val(today_first-current_day);
    		$('#current-month').val(month_now +1);
    		$('.showpass-week-view').addClass('active');
    		$('.showpass-month-view').removeClass('active');
    		$('.showpass-calendar-month').hide();
    		$('.showpass-calendar-week').show();
    		$('.showpass-prev-week').addClass('disabled');

    	} else if (week_enable === 'disabled') {
            // SHOW ONLY MONTHLY VIEW
    		$('.showpass-week-view').hide();
    		$('.showpass-month-view').css('border-right', '0px');
    		renderCalendar(year_now, month_now + 1);
            initializeTooltip()

    	} else {
            // RENDER & SHOW BOTH WEEKLY & MONTHLY
    		renderCalendar(year_now, month_now + 1);

    	}

    	$('.showpass-month-view').click(function() {

            // Currently this function resets back to now
            // Should reset to month of current week or day you are viewing

    		if(!$(this).hasClass('active')) {

    			var date_now = now;
    			var month_now = date_now.getMonth();
    			var year_now = date_now.getFullYear();

                // Set Calendar Header Date
        		$('.showpass-month').html(months[month_now + 1]);
                $('.showpass-year').text(year_now);

                // Reset data attribute for next-month
                $('.showpass-next-month').attr('data-month', month_now + 2);

    			renderCalendar(year_now, month_now + 1);
    			$(this).addClass('active');
    			$('.showpass-week-view').removeClass('active');
    			$('.showpass-calendar-month').show();
    			$('.showpass-calendar-week').hide();

    		}
    	});

    	$('.showpass-week-view').click(function(){

    		if (!$(this).hasClass('active')) {
    			var date_now = now;
    			var month_now = date_now.getMonth();
    			var year_now = date_now.getFullYear();
    			current_day = date_now.getDay();
    			$('.showpass-week').html('');
    			renderCalendarWeek(year_now, month_now + 1, today_first);
    			$('#current_day').val(today_first-current_day);
    			$('#current-month').val(month_now +1);
    			$(this).addClass('active');
    			$('.showpass-month-view').removeClass('active');
    			$('.showpass-calendar-month').hide();
    			$('.showpass-calendar-week').show();
    			$('.showpass-prev-week').addClass('disabled');

    		}
    	});

    });

})(jQuery);
