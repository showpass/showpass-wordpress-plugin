(function (root) {
	"use strict";

	function getAttribute(element, name) {
		if (!element || typeof element.getAttribute !== "function") {
			return "";
		}

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
		if (value === "true") {
			return true;
		}
		if (value === "false") {
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
		var calendarOpenState = getAttribute(element, "data-calendar-open-state");
		var calendarDate = getAttribute(element, "data-calendar-date");
		var calendarTimeslot = getAttribute(element, "data-calendar-timeslot");
		var calendarSpecialEvent = parseSpecialEvent(
			getAttribute(element, "data-calendar-special-event")
		);
		var displayPricePatronTypeId = parsePositiveInteger(
			getAttribute(element, "data-display-price-patron-type-id")
		);
		var hidePrice = parseBoolean(getAttribute(element, "data-hide-price"));
		var highlightMultiDayEventsOnStartDateOnly = parseBoolean(
			getAttribute(
				element,
				"data-highlight-multi-day-events-on-start-date-only"
			)
		);

		if (calendarOpenState === "calendar" || calendarOpenState === "special-events") {
			params.calendarOpenState = calendarOpenState;
		}

		if (calendarDate !== "") {
			params.calendarDate = calendarDate;
		}
		if (calendarTimeslot !== "") {
			params.calendarTimeslot = calendarTimeslot;
		}
		if (calendarSpecialEvent !== undefined) {
			params.calendarSpecialEvent = calendarSpecialEvent;
		}
		if (displayPricePatronTypeId !== undefined) {
			params.displayPricePatronTypeId = displayPricePatronTypeId;
		}
		if (hidePrice !== undefined) {
			params.hidePrice = hidePrice;
		}
		if (highlightMultiDayEventsOnStartDateOnly !== undefined) {
			params.highlightMultiDayEventsOnStartDateOnly =
				highlightMultiDayEventsOnStartDateOnly;
		}

		return params;
	}

	root.ShowpassCalendarParams = {
		fromElement: fromElement,
	};
})(window);
