const assert = require("assert");
const fs = require("fs");
const vm = require("vm");

const source = fs.readFileSync(
	"plugin/js/showpass-calendar-params.js",
	"utf8"
);
const sandbox = { window: {} };
vm.runInNewContext(source, sandbox);

function elementWith(attributes) {
	return {
		getAttribute: function (name) {
			return Object.prototype.hasOwnProperty.call(attributes, name)
				? attributes[name]
				: null;
		},
	};
}

function normalizedParams(attributes) {
	return JSON.parse(
		JSON.stringify(
			sandbox.window.ShowpassCalendarParams.fromElement(
				elementWith(attributes)
			)
		)
	);
}

assert.deepStrictEqual(
	normalizedParams({
		"data-calendar-date": "2026-09-14",
		"data-calendar-timeslot": "evening",
		"data-calendar-special-event": "opening, closing",
		"data-display-price-patron-type-id": "17",
		"data-hide-price": "true",
		"data-highlight-multi-day-events-on-start-date-only": "true",
	}),
	{
		calendarDate: "2026-09-14",
		calendarTimeslot: "evening",
		calendarSpecialEvent: ["opening", "closing"],
		displayPricePatronTypeId: 17,
		hidePrice: true,
		highlightMultiDayEventsOnStartDateOnly: true,
	}
);

assert.deepStrictEqual(
	normalizedParams({
		"data-calendar-special-event": "opening",
		"data-hide-price": "false",
	}),
	{
		calendarSpecialEvent: "opening",
		hidePrice: false,
	}
);

assert.deepStrictEqual(
	normalizedParams({
		"data-calendar-special-event": ", ,",
		"data-display-price-patron-type-id": "0",
		"data-hide-price": "sometimes",
		"data-highlight-multi-day-events-on-start-date-only": "sometimes",
	}),
	{}
);

const integrationSource = fs.readFileSync("plugin/js/showpass-custom.js", "utf8");
assert.ok(
	/ShowpassCalendarParams\.fromElement\(\s*this\s*\)/.test(integrationSource),
	"modal calendar must apply the shared SDK mapper"
);
assert.ok(
	/ShowpassCalendarParams\.fromElement\(\s*embeddedCalendarWidget\s*\)/.test(
		integrationSource
	),
	"embedded calendar must apply the shared SDK mapper"
);

console.log("Calendar SDK parameter tests passed.");
