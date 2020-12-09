/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';
import { TextControl, Button, Dashicon } from '@wordpress/components';

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
import './style.scss'; // display style on front end
import './index.scss'; // editor style

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
		},
		slug: {
			type: 'string'
		},
		dataError: {
			type: 'boolean',
			default: null
		}
	},
	edit(props) {
		const { attributes: { ticketLink, buttonLabel, dataError }, setAttributes } = props;
		const onChangeLink = (newContent) => {
			setAttributes({ ticketLink: newContent });
		};
		const onChangeLabel = (newContent) => {
			setAttributes({ buttonLabel: newContent });
		};
		const onClickGo = () => {
			let slugParse = ticketLink && ticketLink.split('/')[3];
			if (slugParse) {
				setAttributes({ slug: slugParse });
				setAttributes({ dataError: false });
			} else {
				setAttributes({ slug: '' });
				setAttributes({ dataError: true });
			}
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
				<div class="control-container">
					<Button
						isSecondary
						onClick = { onClickGo }>
						Add Button!
					</Button>
					{dataError && (
						<Dashicon
							className = 'validate'
							icon = 'no' />
					)}
					{dataError === false && (
						<Dashicon
							className = 'validate'
							icon = 'yes' />
					)}
				</div>
			</div>
		);
	},
	save(props) {
        return  !props.attributes.dataError && '[showpass_widget slug="' + props.attributes.slug + '" label="' + props.attributes.buttonLabel + '"]';
    },
} );
