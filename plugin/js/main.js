(function (window, document, src) {
  var config = window.__shwps;
  if (typeof config === "undefined") {
    config = function () {
      config.c(arguments)
    };
    config.q = [];
    config.c = function (args) {
      config.q.push(args)
    };
    window.__shwps = config;

    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.async = true;
    s.src = src;
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  }
})(window, document, 'https://beta.myshowpass.com/static/dist/sdk.js');

$(document).ready(function(){

	var months =  ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May' , 'Jun' , 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
	var now = new Date();
	var cur_month = now.getMonth();
	var cur_year = now.getFullYear();
	var today_first = parseInt($('#current_day').val());
	var month_enable = $('#month_enable').val();
	var week_enable = $('#week_enable').val();
	var current_day = now.getDay();

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

    	if( parseInt(month_number) == (cur_month + 1) && today < today_first) {
    		$(this).hide();
    	}
        
		$('.showpass-week').html('Week of ' + (today - current_day) +' of ' + months[month_number]);
    	$('#current_day').val(today);

    });

    $('.showpass-next-week').click(function(){

    	$('.showpass-prev-week').show();
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
		$('.showpass-week').html('Week of ' + (today - current_day) +' of ' + months[month_number]);
    	$('#current_day').val(today);

    });

	$('.showpass-prev-month').click(function() {

		var month_number = parseInt($(this).attr('data-month'));
		var year = parseInt($('.showpass-year').text());

		if (month_number == 0) {
			month_number = 12;
			year = year - 1;
		}

		if (month_number == (cur_month+1)) {
			$(this).hide();
		}

		$('.showpass-month').html(months[month_number]);
		$(this).attr('data-month', month_number - 1);
		$('.showpass-next-month').attr('data-month', month_number + 1);
		$('.showpass-year').text(year);

		renderCalendar(year, month_number);

	});

	$('.showpass-next-month').click(function() {
		var month_number = parseInt($(this).attr('data-month'));
		var year = parseInt($('.showpass-year').text());
		$('.showpass-prev-month').show();

		if(month_number == 13) {
			month_number = 1;
			$('.showpass-year').text(year + 1);
			year++;
		}

		if(month_number == cur_month && year == (cur_year+1)) {
			$(this).hide();
		}

		$('.showpass-month').html(months[month_number]);
		$(this).attr('data-month', month_number + 1);
		$('.showpass-prev-month').attr('data-month', month_number - 1);
		renderCalendar(year, month_number);

	});


	function renderCalendarWeek(year, month, today) {
		$('.loader_home').show();
		var d = new Date();
		var current_month = d.getMonth();
		var page_type = $('#page_type').val();
		var site_url = $('#site_url').val();
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
			var url = "https://beta.myshowpass.com/api/public/events/?venue=" + venue + "&page_size=1000";

			$.ajax({
				method: "GET",
				url: url,
				success: function(data){
					$('.showpass-week').html('Week of ' + (today - current_day) +' of ' + months[month]);

					if((today + 6) > days_in_month) {
						for(var j = (today - current_day); j <= days_in_month; j++ ) {
							html += "<div class='showpass-calendar-item' id='event_on_" + month + "_" + j + "'><div class='day_number_showpass'>" + j + "</div><a class='a_link' target='_blank'><div class='link'></div></a></div>";
						} for(var k = 1; k < ((today + 6) - days_in_month) + 1; k++) {
							html += "<div class='showpass-calendar-item' id='event_on_" + (parseInt(month)+1) + "_" + k + "'><div class='day_number_showpass'>" + k + "</div><a class='a_link' target='_blank'><div class='link'></div></a></div>";
						}
					} else {
						for(var j = (today - current_day); j <= today + (6 - current_day); j++ ) {
							html += "<div class='showpass-calendar-item' id='event_on_" + month + "_" + j + "'><div class='day_number_showpass'>" + j + "</div><a class='a_link' target='_blank'><div class='link'></div></a></div>";
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
						var image_event = data.results[i].image_lg_square;
						var event_slug = data.results[i].slug;

						if(page_type !== "") {
							var url_event = site_url + "/" + page_type + "?slug=" + event_slug;
						} else {
							var url_event = data.results[i].frontend_details_url;
						}

						if(month == month_event && year == year_event) {
							// var tmp_event = $('.showpass-calendar-item-single[data-day=' + day_event +']').find('.a_link').attr('href');
							var tmp = month_event + '_' + day_event;
							// $('#event_on_' + tmp).empty();
							var html_tmp = "<div class='showpass-calendar-item-single' data-month='" + month + "' data-day='" + day_event + "' data-year='" + year +"' style='background:url(" + image_event + ") no-repeat'><div class='day_number_showpass'>" + day_event + "</div><a class='a_link' target='_blank' href='" + url_event + "'><div class='link'><div class='tooltiptext'><img src='" + image_event + "' width='200' /><h4>" + event_name + "</h4></div></div></a></div>";
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

					$('.loader_home').hide();

					current_day = 0;
				}

			});
		}
	}

	function renderCalendar(year , month) {

		$('.loader_home').show();
		var d = new Date();
		var current_month = d.getMonth();
		var page_type = $('#page_type').val();
		var site_url = $('#site_url').val();
		$('.showpass-calendar-body').empty();
		var firstDay = new Date(year, month-1 , 1);  //  number + 1 = current
		var firstDayString = firstDay.toString();
		var first_day = firstDayString.substring(0,3).toLowerCase();
		var first_day_of_the_month = days.indexOf(first_day);
		var days_in_month = new Date(year, month, 0).getDate(); //excactly
		var html = "";

		var venue = $('#venue_id').val();

		if (venue) {

			var url = "https://beta.myshowpass.com/api/public/events/?venue=" + venue + "&page_size=1000";

			$.ajax({
				method: "GET",
				url: url,
				success: function(data){
					if(first_day_of_the_month == 7) {
						for (var j = first_day_of_the_month - 6; j <= days_in_month; j++) {
							for (var i = 0; i < data.results.length; i++) {
								var date_month = data.results[i].starts_on;
								var date_day = date_month.split("-");
								var day_event = parseInt(date_day[2].substring(0,2));
								var month_event = parseInt(date_day[1]);
								var year_event = parseInt(date_day[0]);
								var image_event = data.results[i].image_lg_square;
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
								html += "<div class='showpass-calendar-item' id='event_on_" + month + "_" + j + "'><div class='day_number_showpass'>" + j + "</div><a class='a_link' target='_blank'><div class='link'></div></a></div>";
							}
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
						var image_event = data.results[i].image_lg_square;
						var event_slug = data.results[i].slug;

						if (page_type !== "") {
							var url_event = site_url + "/" + page_type + "?slug=" + event_slug;
						} else {
							var url_event = data.results[i].frontend_details_url;
						}

						if(month == month_event && year == year_event) {
							// var tmp_event = $('.showpass-calendar-item-single[data-day=' + day_event +']').find('.a_link').attr('href');
							var tmp = month_event + '_' + day_event;
							// $('#event_on_' + tmp).empty();
							var html_tmp = "<div class='showpass-calendar-item-single' data-month='" + month + "' data-day='" + day_event + "' data-year='" + year +"' style='background:url(" + image_event + ") no-repeat'><div class='day_number_showpass'>" + day_event + "</div><a class='a_link' target='_blank' href='" + url_event + "'><div class='link'><div class='tooltiptext'><img src='" + image_event + "' width='200' /><h4>" + event_name + "</h4></div></div></a></div>";
							$('#event_on_' + tmp).append(html_tmp);
						}
					}
					$('.loader_home').hide();
				}
			});
		}
	}  // ending render calendar

	var date_now = new Date();
	var month_now = date_now.getMonth();
	var year_now = date_now.getFullYear();

	if(month_enable === 'disabled')	{

		// $('.showpass-week-view').addClass('active');
		$('.showpass-month-view').hide();
		var date_now = new Date();
		var month_now = date_now.getMonth();
		var year_now = date_now.getFullYear();
		// $('#current_day').val(today_first-current_day);
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
		$('.showpass-prev-week').hide();

	} else if(week_enable === 'disabled') {

		$('.showpass-week-view').hide();
		$('.showpass-month-view').css('border-right', '0px');
		renderCalendar(year_now, month_now + 1);

	} else {

		renderCalendar(year_now, month_now + 1);

	}

	$('.showpass-month-view').click(function() {

		if(!$(this).hasClass('active')) {

			var date_now = new Date();
			var month_now = date_now.getMonth();
			var year_now = date_now.getFullYear();
			renderCalendar(year_now, month_now + 1);
			$(this).addClass('active');
			$('.showpass-week-view').removeClass('active');
			$('.showpass-calendar-month').show();
			$('.showpass-calendar-week').hide();

		}
	});

	$('.showpass-week-view').click(function(){

		if(!$(this).hasClass('active')) {

			var date_now = new Date();
			var month_now = date_now.getMonth();
			var year_now = date_now.getFullYear();
			// $('#current_day').val(today_first-current_day);
			current_day = date_now.getDay();
			$('.showpass-week').html('');
			renderCalendarWeek(year_now, month_now + 1, today_first);
			$('#current_day').val(today_first-current_day);
			$('#current-month').val(month_now +1);
			$(this).addClass('active');
			$('.showpass-month-view').removeClass('active');
			$('.showpass-calendar-month').hide();
			$('.showpass-calendar-week').show();
			$('.showpass-prev-week').hide();

		}
	});

});
