/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';
import { TextControl } from '@wordpress/components';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';
import './index.scss';
import './editor.scss';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType('create-block/showpass-button-block', {
	title: 'Buy Now Button',
	category: 'showpass-blocks',
	icon: 'tickets-alt',
	supports: {},
	attributes: {
		ticketLink: {
			type: 'string',
		},
		buttonLabel: {
			type: 'string',
			default: 'Buy Now',
		}
	},
	edit(props) {
		const { attributes: { ticketLink, buttonLabel }, setAttributes } = props;
		const onChangeLink = (newContent) => {
			setAttributes({ ticketLink: newContent });
		};
		const onChangeLabel = (newContent) => {
			setAttributes({ buttonLabel: newContent });
		};
		return (
			<div class="wp-showpass-block-container">
				<span class="dashicons dashicons-tickets-alt"></span>
				<h4>Buy Now Button</h4>
				<TextControl
					label = "Button Label"
					value = { buttonLabel }
					onChange = { onChangeLabel }
					key='ticketLink'
					default="Buy Now"
					/>
				<TextControl
					label = "Enter in the full URL"
					value = { ticketLink }
					onChange = { onChangeLink }
					key = 'ticketLink'
					help = 'Example: https://showpass.com/event-slug/'
					/>
			</div>
		);
	},
	save(props) {
		let slug = props.attributes.ticketLink && props.attributes.ticketLink.split('/')[3];
        return  '[showpass_widget slug="' + slug + '" label="' + props.attributes.buttonLabel + '"]';
    },
} );
