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
		calendar_date: "2026-09-14",
		calendar_timeslot: "evening",
		calendar_special_event: ["opening", "closing"],
		display_price_patron_type_id: 17,
		hide_price: true,
		highlight_multi_day_events_on_start_date_only: true,
	}
);

assert.deepStrictEqual(
	normalizedParams({
		"data-calendar-special-event": "opening",
		"data-hide-price": "false",
	}),
	{
		calendar_special_event: "opening",
		hide_price: false,
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

assert.deepStrictEqual(normalizedParams({
  "data-calendar-open-state": " special-events ",
  "data-calendar-special-event": "all",
}), {calendar_open_state: "special-events", calendar_special_event: "all"});
assert.deepStrictEqual(normalizedParams({"data-calendar-open-state": "calendar"}), {calendar_open_state: "calendar"});
assert.deepStrictEqual(normalizedParams({"data-calendar-open-state": "invalid"}), {});

// Execute the real initialization and delegated click handler, not source regexes.
const integrationSource = fs.readFileSync("plugin/js/showpass-custom.js", "utf8");
function calendarCall(attributes, embedded) {
  const element = elementWith(Object.assign({"data-org-id": "42", "data-lang": "fr"}, attributes));
  const calls = [];
  const handlers = {};
  const document = {
    cookie: "",
    body: {},
    getElementById: (id) => embedded && id === "showpass-calendar-widget" ? element : null,
    querySelectorAll: () => [],
  };
  const window = {location: {href: "https://example.com/calendar", search: ""}};
  function jQuery(target) {
    return {
      on: (event, selector, handler) => { if (handler) handlers[selector] = handler; },
      ready: (callback) => callback(),
      attr: (name) => target && target.getAttribute ? target.getAttribute(name) : undefined,
      val: () => undefined,
      html: () => {},
    };
  }
  jQuery.isEmptyObject = (value) => Object.keys(value).length === 0;
  const context = {
    window, document, location: window.location, jQuery, URL, console,
    Cookies: {get: () => undefined, set: () => {}, remove: () => {}},
    MutationObserver: function () { this.observe = () => {}; },
    showpass: {tickets: {calendarWidget: (...args) => calls.push(args)}},
  };
  vm.runInNewContext(source, context);
  vm.runInNewContext(integrationSource, context);
  if (!embedded) {
    let prevented = false;
    handlers[".open-calendar-widget"].call(element, {preventDefault: () => { prevented = true; }});
    assert.ok(prevented, "modal click prevents navigation");
  }
  assert.strictEqual(calls.length, 1, "each calendar initializes exactly once");
  return JSON.parse(JSON.stringify(calls[0]));
}

for (const embedded of [false, true]) {
  const call = calendarCall({
    "data-calendar-date": "2026-09-14",
    "data-calendar-timeslot": "evening",
    "data-calendar-special-event": "opening, closing",
    "data-calendar-open-state": "special-events",
    "data-display-price-patron-type-id": "17",
    "data-hide-price": " FALSE ",
    "data-highlight-multi-day-events-on-start-date-only": "1",
  }, embedded);
  assert.strictEqual(call[0], "42");
  const params = call[1];
  assert.strictEqual(params.calendar_date, "2026-09-14");
  assert.strictEqual(params.calendar_timeslot, "evening");
  assert.deepStrictEqual(params.calendar_special_event, ["opening", "closing"]);
  assert.strictEqual(params.calendar_open_state, "special-events");
  assert.strictEqual(params.display_price_patron_type_id, 17);
  assert.strictEqual(params.hide_price, false);
  assert.strictEqual(params.highlight_multi_day_events_on_start_date_only, true);
  assert.strictEqual(params.lang, "fr");
  assert.strictEqual(call.length, embedded ? 3 : 2);
  if (embedded) assert.strictEqual(call[2], "showpass-calendar-widget");

  const defaults = calendarCall({}, embedded)[1];
  for (const key of ["calendar_date", "calendar_timeslot", "calendar_special_event", "calendar_open_state", "display_price_patron_type_id", "hide_price", "highlight_multi_day_events_on_start_date_only"]) {
    assert.ok(!(key in defaults), "omitted option must preserve SDK default: " + key);
  }
  const invalid = calendarCall({
    "data-calendar-open-state": "invalid", "data-calendar-special-event": ", ,",
    "data-display-price-patron-type-id": "9007199254740992", "data-hide-price": "sometimes",
    "data-highlight-multi-day-events-on-start-date-only": "sometimes",
  }, embedded)[1];
  for (const key of ["calendar_open_state", "calendar_special_event", "display_price_patron_type_id", "hide_price", "highlight_multi_day_events_on_start_date_only"]) {
    assert.ok(!(key in invalid), "invalid option must preserve SDK default: " + key);
  }
}
assert.deepStrictEqual(normalizedParams({"data-hide-price": "0"}), {hide_price: false});
assert.deepStrictEqual(normalizedParams({"data-hide-price": "TRUE"}), {hide_price: true});
console.log("Calendar SDK parameter and initialization tests passed.");

for (const embedded of [false, true]) {
  const params = calendarCall({
    "data-theme-primary": "24727b", "data-theme-secondary": "ffffff",
    "data-theme-dark": "false", "data-keep-shopping": "false",
    "data-show-description": "false", "data-show-specific-tickets": "17,18",
    "data-tracking-id": "campaign", "data-prompt-for-quantity": "true",
    "data-ticket-type-selection-required": "true", "data-redirect-url": "https%3A%2F%2Fexample.com%2Freturn",
  }, embedded)[1];
  assert.strictEqual(params.theme_primary, "24727b");
  assert.strictEqual(params.theme_secondary, "ffffff");
  assert.strictEqual(params.theme_dark, false);
  assert.strictEqual(params.keep_shopping, false);
  assert.strictEqual(params.show_description, false);
  assert.strictEqual(params.show_specific_tickets, "17,18");
  assert.strictEqual(params.tracking_id, "campaign");
  assert.strictEqual(params.prompt_for_quantity, true);
  assert.strictEqual(params.ticket_type_selection_required, true);
  assert.strictEqual(params.redirect_url, "https%3A%2F%2Fexample.com%2Freturn");
  ["theme-primary", "keep-shopping", "redirect-url", "tracking-id", "hidePrice"].forEach((key) => assert.ok(!(key in params)));
}
