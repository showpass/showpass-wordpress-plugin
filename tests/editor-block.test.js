const assert = require('assert');
const fs = require('fs');
const path = require('path');
const vm = require('vm');

let registeredBlock;
let registeredName;
let request;
let resolveRequest;
function Component(props) { this.props = props; }
Component.prototype.setState = function (state) {
  this.state = Object.assign({}, this.state, state);
};
function Button() {}
const wp = {
  apiFetch: (options) => {
    request = options;
    return new Promise((resolve) => { resolveRequest = resolve; });
  },
  blocks: { registerBlockType: (name, settings) => { registeredName = name; registeredBlock = settings; } },
  element: {
    Component,
    createElement: (type, props, ...children) => ({ type, props: props || {}, children }),
  },
  components: { Button, TextControl() {}, Dashicon() {}, Spinner() {} },
};
vm.runInNewContext(
  fs.readFileSync(path.join(__dirname, '../plugin/build-blocks/index.js'), 'utf8'),
  { window: { wp }, console: { log() {} } }
);

function findButton(node) {
  if (!node || typeof node !== 'object') return undefined;
  if (node.type === Button) return node.props;
  for (const child of node.children || []) {
    const found = findButton(child);
    if (found) return found;
  }
  return undefined;
}

(async () => {
  assert(registeredBlock, 'the shipped editor bundle registers the block');
  assert.strictEqual(registeredName, 'create-block/showpass-button-block',
    'the existing saved block identifier is preserved');
  assert.strictEqual(registeredBlock.attributes.shortcode.source, 'html',
    'saved shortcode must be read from the existing block content');

  const attributes = { ticketLink: '', buttonLabel: 'Local QA & Tickets', dataError: null };
  const editor = new registeredBlock.edit({
    attributes,
    setAttributes: (updates) => Object.assign(attributes, updates),
  });
  assert.strictEqual(findButton(editor.render()).disabled, true, 'empty URL cannot submit');
  attributes.ticketLink = '   ';
  assert.strictEqual(findButton(editor.render()).disabled, true, 'blank URL cannot submit');

  attributes.ticketLink = 'https://localhost.showpass.com/local-event/?campaign=one&source=two';
  const button = findButton(editor.render());
  assert.strictEqual(button.disabled, false);
  button.onClick();
  assert.strictEqual(findButton(editor.render()).disabled, true, 'request disables duplicate submissions');
  const query = new URL(request.path, 'https://local-wordpress.test/').searchParams;
  assert.strictEqual(query.get('url'), attributes.ticketLink, 'full URL is encoded as one parameter');
  assert.strictEqual(query.get('label'), attributes.buttonLabel, 'button label reaches the endpoint');

  const shortcode = '[showpass_widget slug="local-event" label="Local QA &amp; Tickets" ]';
  resolveRequest({ data: { shortcode } });
  await new Promise(setImmediate);
  assert.strictEqual(attributes.shortcode, shortcode);
  assert.strictEqual(attributes.dataError, false);
  assert.strictEqual(editor.state.loading, false);
  assert.strictEqual(registeredBlock.save({ attributes }), shortcode);
  console.log('Shipped editor block regression tests passed.');
})().catch((error) => { console.error(error); process.exitCode = 1; });
