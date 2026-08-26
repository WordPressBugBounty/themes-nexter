/**
 * Typography Preview Control Js
 *
 * Keeps the sample text of a `nxt-typo-preview` control in sync with the
 * font family / weight / transform / size / line height controls of its section,
 * and with the heading colour of the matching Styling > Colors field.
 *
 * @package	Nexter
 * @since	1.0.0
 */

(function ($) {
	'use strict';

	var api = wp.customize;

	wp.customize.controlConstructor['nxt-typo-preview'] = wp.customize.Control.extend({

		ready: function () {

			var control = this;

			control.nxtConnect = control.params.connect || {};
			control.nxtBox     = control.container.find('.nxt-typo-preview-box');
			control.nxtText    = control.container.find('.nxt-typo-preview-text');
			control.nxtMeta    = control.container.find('.nxt-typo-preview-meta');

			// Re-render whenever one of the connected typography settings changes.
			_.each(control.nxtConnect, function (settingId) {
				if (!settingId) {
					return;
				}
				api(settingId, function (setting) {
					setting.bind(function () {
						control.nxtRenderPreview();
					});
				});
			});

			// Font size and line height are responsive, follow the previewed device.
			if (api.previewedDevice) {
				api.previewedDevice.bind(function () {
					control.nxtRenderPreview();
				});
			}

			control.nxtRenderPreview();
		},

		/**
		 * Current value of a connected setting.
		 */
		nxtGetValue: function (key) {

			var settingId = this.nxtConnect[key];

			if (!settingId || 'undefined' === typeof api(settingId)) {
				return '';
			}

			return api(settingId).get();
		},

		/**
		 * Device currently previewed in the customizer.
		 */
		nxtGetDevice: function () {

			if (api.previewedDevice && api.previewedDevice.get()) {
				return api.previewedDevice.get();
			}

			return 'desktop';
		},

		/**
		 * Responsive value of a connected setting.
		 * Falls back mobile > tablet > desktop, the same order the dynamic css uses,
		 * then on the placeholder of the slider control so an untouched section still
		 * previews the value the theme actually renders.
		 */
		nxtGetResponsive: function (key) {

			var control  = this,
				value    = control.nxtGetValue(key),
				device   = control.nxtGetDevice(),
				empty    = { size: '', unit: 'px' },
				settingId, order, i, current, unit, slider, placeholder;

			order = ('mobile' === device) ? ['mobile', 'tablet', 'desktop'] : (('tablet' === device) ? ['tablet', 'desktop'] : ['desktop']);

			if (_.isObject(value)) {
				for (i = 0; i < order.length; i++) {
					current = value[order[i]];
					if ('' !== current && null !== current && 'undefined' !== typeof current) {
						unit = value[order[i] + '-unit'] ? value[order[i] + '-unit'] : 'px';
						return { size: current, unit: unit };
					}
				}
			} else if ('' !== value && null !== value && 'undefined' !== typeof value) {
				return { size: value, unit: 'px' };
			}

			// Nothing stored yet, use the placeholder of the connected slider control.
			settingId = control.nxtConnect[key];
			slider    = settingId ? api.control(settingId) : false;

			if (slider && slider.params && slider.params.placeholder) {
				placeholder = slider.params.placeholder;
				for (i = 0; i < order.length; i++) {
					current = placeholder[order[i]];
					if ('' !== current && null !== current && 'undefined' !== typeof current) {
						return { size: current, unit: 'px' };
					}
				}
			}

			return empty;
		},

		/**
		 * Font stack used by the preview, system fonts get their fallback appended.
		 */
		nxtFontFamilyCss: function (family) {

			if (!family || 'inherit' === family) {
				return this.params.inheritFont || '';
			}

			if (window.NxtLoadFontFamily && window.NxtLoadFontFamily.system && window.NxtLoadFontFamily.system[family] && window.NxtLoadFontFamily.system[family].fallback) {
				return family + ',' + window.NxtLoadFontFamily.system[family].fallback;
			}

			return family;
		},

		/**
		 * Id of the stylesheet this control owns, one per typography section.
		 */
		nxtFontLinkId: function () {
			return 'nxt-typo-preview-font-' + String(this.id).replace(/[^a-z0-9]+/gi, '-').replace(/-+$/, '');
		},

		/**
		 * Google fonts are not loaded in the controls frame, pull the picked one in
		 * so the preview shows the real face.
		 *
		 * The control reuses a single stylesheet and only rewrites its href, otherwise
		 * every font change would leave another <link> behind in the head. All the
		 * weights of the family are requested at once so switching weight never
		 * triggers a new request either.
		 *
		 * Skipped when the local google font option is on, those @font-face rules are
		 * already printed with the control styles.
		 */
		nxtLoadGoogleFont: function (family) {

			var control = this,
				name    = String(family).split(',')[0].replace(/'/g, '').trim(),
				link    = control.nxtFontLink || document.getElementById(control.nxtFontLinkId()),
				weights, href;

			if (window.NxtTypoPreview && window.NxtTypoPreview.localFonts) {
				return;
			}

			if (!name || !window.NxtLoadFontFamily || !window.NxtLoadFontFamily.google || !window.NxtLoadFontFamily.google[name]) {
				// Not a google font, drop the stylesheet this control was using.
				if (link && link.parentNode) {
					link.parentNode.removeChild(link);
				}
				control.nxtFontLink = null;
				return;
			}

			weights = _.filter(window.NxtLoadFontFamily.google[name][0] || [], function (weight) {
				return -1 === String(weight).indexOf('italic');
			});

			if (!weights.length) {
				weights = ['400'];
			}

			href = 'https://fonts.googleapis.com/css?family=' + encodeURIComponent(name) + ':' + weights.join(',') + '&display=swap';

			if (!link) {
				link     = document.createElement('link');
				link.id  = control.nxtFontLinkId();
				link.rel = 'stylesheet';
				document.head.appendChild(link);
			}

			control.nxtFontLink = link;

			if (link.getAttribute('href') !== href) {
				link.setAttribute('href', href);
			}
		},

		/**
		 * Colour the preview sits on, so a light heading colour stays visible.
		 * Only a plain background colour is used, an image would fight the sample text.
		 */
		nxtBackgroundColor: function () {

			var value = this.nxtGetValue('background');

			if (_.isObject(value)) {
				if (value['bg-color'] && ( ! value['bg-type'] || 'color' === value['bg-type'] )) {
					return value['bg-color'];
				}
				return '';
			}

			return value ? value : '';
		},

		/**
		 * Paint the sample text with the current typography options.
		 */
		nxtRenderPreview: function () {

			var control    = this,
				family     = control.nxtGetValue('family'),
				weight     = control.nxtGetValue('weight'),
				transform  = control.nxtGetValue('transform'),
				color      = control.nxtGetValue('color'),
				size       = control.nxtGetResponsive('size'),
				height     = control.nxtGetResponsive('line-height'),
				sizeInPx   = '',
				meta       = '';

			control.nxtLoadGoogleFont(family);

			// A control panel is not the place for a 100px sample, the real value stays
			// readable underneath the preview.
			if ('' !== size.size) {
				sizeInPx = parseFloat(size.size);

				if (!isNaN(sizeInPx)) {
					if ('em' === size.unit || 'rem' === size.unit) {
						sizeInPx = sizeInPx * 16;
					}
					sizeInPx = Math.min(Math.max(sizeInPx, 12), 40) + 'px';
				} else {
					sizeInPx = '';
				}
			}

			control.nxtBox.css('background-color', control.nxtBackgroundColor());

			control.nxtText.css({
				'color': color ? color : '',
				'font-family': control.nxtFontFamilyCss(family),
				'font-weight': (weight && 'inherit' !== weight) ? weight : '',
				'text-transform': transform ? transform : '',
				'font-size': sizeInPx,
				'line-height': ('' !== height.size) ? height.size : ''
			});

			if ('' !== size.size) {
				meta = size.size + size.unit;
			}

			if ('' !== height.size) {
				meta += (meta ? ' / ' : '') + height.size;
			}

			control.nxtMeta.text(meta);
		}

	});

})(jQuery);
