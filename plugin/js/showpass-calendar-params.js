(function (root) {
	"use strict";

	function getAttribute(element, name) {
		var value = element.getAttribute(name);
		return typeof value === "string" ? value.trim() : "";
	}

	function parsePositiveInteger(value) {
		if (!/^[1-9][0-9]*$/.test(value)) {
			return undefined;
		}

		var parsed = Number(value);
		return parsed <= 9007199254740991 ? parsed : undefined;
	}

	function parseBoolean(value) {
		value = value.toLowerCase();
		if (value === "true" || value === "1") {
			return true;
		}
		if (value === "false" || value === "0") {
			return false;
		}

		return undefined;
	}

	function parseSpecialEvent(value) {
		var keys = value
			.split(",")
			.map(function (key) {
				return key.trim();
			})
			.filter(function (key) {
				return key !== "";
			});

		if (keys.length === 0) {
			return undefined;
		}

		return keys.length === 1 ? keys[0] : keys;
	}

	function fromElement(element) {
		var params = {};
		var calendar_open_state = getAttribute(element, "data-calendar-open-state");
		var calendar_date = getAttribute(element, "data-calendar-date");
		var calendar_timeslot = getAttribute(element, "data-calendar-timeslot");
		var calendar_special_event = parseSpecialEvent(
			getAttribute(element, "data-calendar-special-event")
		);
		var display_price_patron_type_id = parsePositiveInteger(
			getAttribute(element, "data-display-price-patron-type-id")
		);
		var hide_price = parseBoolean(getAttribute(element, "data-hide-price"));
		var highlight_multi_day_events_on_start_date_only = parseBoolean(
			getAttribute(
				element,
				"data-highlight-multi-day-events-on-start-date-only"
			)
		);

		if (calendar_open_state === "calendar" || calendar_open_state === "special-events") {
			params.calendar_open_state = calendar_open_state;
		}

		if (calendar_date !== "") {
			params.calendar_date = calendar_date;
		}
		if (calendar_timeslot !== "") {
			params.calendar_timeslot = calendar_timeslot;
		}
		if (calendar_special_event !== undefined) {
			params.calendar_special_event = calendar_special_event;
		}
		if (display_price_patron_type_id !== undefined) {
			params.display_price_patron_type_id = display_price_patron_type_id;
		}
		if (hide_price !== undefined) {
			params.hide_price = hide_price;
		}
		if (highlight_multi_day_events_on_start_date_only !== undefined) {
			params.highlight_multi_day_events_on_start_date_only =
				highlight_multi_day_events_on_start_date_only;
		}

		[
			"theme_primary", "theme_secondary", "tracking_id", "redirect_url",
			"show_specific_tickets"
		].forEach(function (name) {
			var value = getAttribute(element, "data-" + name.replace(/_/g, "-"));
			if (value !== "") params[name] = value;
		});
		[
			"theme_dark", "keep_shopping", "show_description", "prompt_for_quantity",
			"ticket_type_selection_required"
		].forEach(function (name) {
			var value = parseBoolean(getAttribute(element, "data-" + name.replace(/_/g, "-")));
			if (value !== undefined) params[name] = value;
		});

		return params;
	}

	root.ShowpassCalendarParams = {
		fromElement: fromElement,
	};
})(window);
