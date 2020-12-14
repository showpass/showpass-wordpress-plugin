/**
 * Showpass buy tickets block
 */

import apiFetch from '@wordpress/api-fetch';
import { Component } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { TextControl, Button, Dashicon, Spinner } from '@wordpress/components';

/**
 * Import scss files for webpack to process
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss'; // display style on front end
import './index.scss'; // editor style

/**
 * Display and logic for the buy tickets button block editor
 */
class BuyTicketBlock extends Component {

	constructor(props) {
		super(props);

		this.state = {
			loading: false,
			errorMessage: ''
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
			setAttributes({ dataError: null });
			this.setState({
				loading: true,
				errorMessage: ''
			});
			checkValidURL(ticketLink).then(data => {
				console.log(data);
				this.setState({
					loading: false
				});
				if (data) {
					setAttributes({ slug: data });
					setAttributes({ dataError: false });
				}
			}).catch(error => {
				this.setState({
					loading: false,
					errorMessage: error.data
				});
				console.log(error);
				setAttributes({ dataError: true });
			});
		};

		const checkValidURL = (url) => {
			if (url) {
				return apiFetch({
					path: 'showpass/v1/process-url/?url=' + encodeURI(url),
					method: 'GET'
				});
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
						isBusy = { this.state.loading }
						onClick = { onClickGo }>
						Add Button!
					</Button>
					{this.state.loading && (
						<Spinner />
					)}
					{dataError !== null && (
						<Dashicon
							className = 'validate'
							icon = { dataError ? 'no' : 'yes' } />
					)}
					{this.state.errorMessage && (
						<p class="error-message">{ this.state.errorMessage }</p>
					)}
				</div>
			</div>
		);
	}
}

/**
 * Register the showpass buy tickets button block
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
