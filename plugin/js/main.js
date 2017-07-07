$(document).ready(function(){

	var months =  ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May' , 'Jun' , 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];

	var now = new Date();

	var cur_month = now.getMonth();
	var cur_year = now.getFullYear();




	$('.showpass-prev-month').click(function(){
		var month_number = parseInt($(this).attr('data-month'));



		var year = parseInt($('.showpass-year').text());
		if(month_number == 0)
		{
			month_number = 12;
			year = year - 1;
		}

		if(month_number == (cur_month+1))
		{
			$(this).hide();
		}

		$('.showpass-month').html(months[month_number]);
		$(this).attr('data-month', month_number - 1);
		$('.showpass-next-month').attr('data-month', month_number + 1);
		$('.showpass-year').text(year);


		renderCalendar(year, month_number);


	});

	$('.showpass-next-month').click(function(){
		var month_number = parseInt($(this).attr('data-month'));
		var year = parseInt($('.showpass-year').text());
		$('.showpass-prev-month').show();
		if(month_number == 13)
		{
			month_number = 1;
			$('.showpass-year').text(year + 1);
			year++;
		}
		if(month_number == cur_month && year == (cur_year+1))
		{
			$(this).hide();
		}
		$('.showpass-month').html(months[month_number]);
		$(this).attr('data-month', month_number + 1);
		$('.showpass-prev-month').attr('data-month', month_number - 1);
		renderCalendar(year, month_number);

	});

	// $('.showpass-prev-year').click(function(){
	// 	var year = parseInt($(this).next().text()) - 1;
	// 	var month_number = parseInt(months.indexOf($('.showpass-month').text()));
	// 	$(this).next().text(year);
	// 	renderCalendar(year, month_number);

	// });

	// $('.showpass-next-year').click(function(){
	// 	var year = parseInt($(this).prev().text()) + 1;
	// 	var month_number = parseInt(months.indexOf($('.showpass-month').text()));
	// 	$(this).prev().text(year);
	// 	renderCalendar(year, month_number);
	// });




	function renderCalendar(year , month){

		$('.loader_home').show();

		var d = new Date();

		var current_month = d.getMonth();
		var venue = $('#venue_id').val();

		$('.showpass-calendar-body').empty();

		var firstDay = new Date(year, month-1 , 1);  //  number + 1 = current
		var firstDayString = firstDay.toString();
		var first_day = firstDayString.substring(0,3).toLowerCase();
		var first_day_of_the_month = days.indexOf(first_day);
		var days_in_month = new Date(year, month, 0).getDate(); //excactly

		var html = "";

		var url = "https://www.myshowpass.com/api/public/events/?venue=" + venue + "&page_size=1000";


		$.ajax({
			method: "GET",
			url: url,
			success: function(data){

				if(first_day_of_the_month == 7)
				{
					for (var j = first_day_of_the_month - 6; j <= days_in_month; j++) {
						for (var i = 0; i < data.results.length; i++) {

							var date_month = data.results[i].starts_on;
							var date_day = date_month.split("-");
							var day_event = parseInt(date_day[2].substring(0,2));
							var month_event = parseInt(date_day[1]);
							var year_event = parseInt(date_day[0]);
							var image_event = data.results[i].image_medium;
							var url_event = data.results[i].frontend_details_url;

							if((month == month_event) && (j == day_event)){
								html += '<div class="showpass-calendar-item"></div>';
							}
							else{
								html += '<div class="showpass-calendar-item"></div>';
							}
						}
					}
				}
				else
				{	

					for(var j = (first_day_of_the_month * (-1)) + 1; j <= days_in_month; j++ )
					{
						if(j < 1)
						{
							html += "<div class='showpass-calendar-item'></div>";
						}
						else
						{
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
					var image_event = data.results[i].image_medium;
					var url_event = data.results[i].frontend_details_url;	

					if(month == month_event && year == year_event)
					{
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

	var date_now = new Date();
	var month_now = date_now.getMonth();
	var year_now = date_now.getFullYear();


	renderCalendar(year_now, month_now + 1);

});