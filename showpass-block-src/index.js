/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */

import { Component } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { TextControl, Button, Dashicon, Spinner } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

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
 * edit buy tickets block class
 */
class BuyTicketBlock extends Component {

	constructor(props) {
		super(props);

		this.state = {
			loading: false
		}
	}

	render() {
		const { attributes: { ticketLink, buttonLabel, dataError }, setAttributes } = this.props;

		const onChangeLink = (newContent) => {
			setAttributes({ ticketLink: newContent });
		};

		const onChangeLabel = (newContent) => {
			setAttributes({ buttonLabel: newContent });
		};

		const onClickGo = () => {
			this.setState({
				loading: true
			});
			checkValidURL().then(data => {
				this.setState({
					loading: false
				});
				let slugParse = ticketLink && ticketLink.split('/')[3];

				if (slugParse) {
					setAttributes({ slug: slugParse });
					setAttributes({ dataError: false });
				} else {
					setAttributes({ slug: '' });
					setAttributes({ dataError: true });
				}
			});
		};

		const checkValidURL = () => {
			return apiFetch({
				path: 'showpass/v1/process-url',
			});
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
						isBusy = { this.state.loading }
						onClick = { onClickGo }>
						Add Button!
					</Button>
					{this.state.loading && (
						<Spinner />
					)}
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
	}
}

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
	edit: BuyTicketBlock,
	save: (props) => {
		const { attributes } = props;
        return  !attributes.dataError && attributes.slug && '[showpass_widget slug="' + attributes.slug + '" label="' + attributes.buttonLabel + '"]';
    },
});
