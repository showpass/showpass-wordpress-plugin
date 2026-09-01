const assert = require("assert");
const fs = require("fs");

function readTemplate(name) {
	return fs.readFileSync("plugin/inc/" + name, "utf8");
}

const grid = readTemplate("default-grid.php");
assert.match(
	grid,
	/class="showpass-image ratio banner<\?php if \(\$image_opens_widget\) echo ' open-ticket-widget'; \?>"/,
	"event grid images must open the event widget when eligible"
);
assert.match(grid, /data-show-description="<\?= \$show_widget_description \?>"/);

const list = readTemplate("default-list.php");
assert.strictEqual(
	(list.match(/\$image_opens_widget\) echo ' open-ticket-widget'/g) || []).length,
	2,
	"both desktop and mobile event list images must open the event widget"
);
assert.strictEqual(
	(list.match(/data-show-description="<\?= \$show_widget_description \?>"/g) || [])
		.length,
	2,
	"both event list images must pass widget description settings"
);

const membershipGrid = readTemplate("default-membership-grid.php");
assert.match(
	membershipGrid,
	/class="showpass-image ratio banner open-membership-widget"/,
	"membership grid images must open the membership widget"
);
assert.doesNotMatch(
	membershipGrid,
	/\$membership_href/,
	"membership images must not use the undefined membership_href variable"
);

for (const productTemplate of ["default-product-grid.php", "default-product-list.php"]) {
	assert.match(
		readTemplate(productTemplate),
		/class="showpass-image open-product-widget/,
		productTemplate + " images must continue to open the product widget"
	);
}

console.log("Card image widget template tests passed.");
