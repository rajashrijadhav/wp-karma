import { __ } from '@wordpress/i18n';

import {
	InspectorControls,
	__experimentalLinkControl as LinkControl,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	SelectControl,
	BaseControl,
} from '@wordpress/components';

import { CAPTION_STYLES } from '../attributes';
import BackgroundPanel from '../../../components/reusable/background/panel';

export default ( props ) => {
    const { attributes, setAttributes } = props;
    const {
      background,
      minWidth,
			linkUrl,
			autoCaption,
			opensInNewTab,
    } = attributes;

		let linkControlValue = {
			url: linkUrl,
			opensInNewTab,
		};
    return (
      <InspectorControls>
				<BackgroundPanel
					value={ background }
					onChange={ bg => setAttributes( { background: bg } ) }
					controlsEnabled={ [ 'none', 'color', 'image' ] }
					overlayEnabled={ true }
					minWidth={ minWidth }
					onChangeMinWidth={ mw => setAttributes( { minWidth: mw } ) }
				/>
				<PanelBody
					title={ __( 'Slide Settings', 'gutenslider' ) }
					className="gutenslide-controls-slide"
				>
					<BaseControl
						label={ __( 'Slide Link', 'gutenslider' ) }
						help={ __( 'Set an url that opens, when the user clicks the slide', 'gutenslider' ) }
					>
						<LinkControl
							className="wp-block-navigation-link__inline-link-input"
							value= { {
								url: linkUrl,
								opensInNewTab,
							} }
							onChange={ ( val ) => {
								setAttributes( {
									linkUrl: val.url,
									opensInNewTab: val.opensInNewTab,
								} );
							} }
							onRemove={ () => {
								linkControlValue = { url: '', opensInNewTab }
							} }
						/>
					</BaseControl>
					<SelectControl
						label={ __( 'Automatic Captions', 'gutenslider' ) }
						value={ autoCaption }
						options={ CAPTION_STYLES }
						onChange={ ( val ) => setAttributes( { autoCaption: val } ) }
					/>
				</PanelBody>
      </InspectorControls>
    );
}
