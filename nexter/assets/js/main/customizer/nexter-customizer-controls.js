/**
 * Customizer Controls Js
 *
 * @package	Nexter
 * @since	1.0.0
 */

(function ($) {
    'use strict';
	
	/* Background Control */
    $(window).on("load", function () {
        $('html').addClass('background-colorpicker-ready');
    });

    wp.customize.controlConstructor['nxt-background'] = wp.customize.Control.extend({

        ready: function () {
            'use strict';
            var control = this,
                value = control.setting._value,
                colorpicker = control.container.find('.nxt-color-control');

            if (_.isUndefined(value['bg-image']) || value['bg-image'] === '') {
                control.container.find('.nxt-control-background > .nxt-bg-size, .nxt-control-background > .nxt-bg-position, .nxt-control-background > .nxt-bg-repeat, .nxt-control-background > .nxt-bg-attachment').addClass("hidden");
            }

			// bg reset button
			control.container.on('click', '.nxt-bg-reset', function () {
				var wrap 		= $( this ).closest( '.nxt-bg-type-list' ),
					databg   	= wrap.find( 'input' );

                databg.prop('checked', false);
				control.setData('bg-type', '');
                databg.trigger('change');
                control.container.find(".nxt-bg-image").addClass("hidden");
			});

            // Background Color (alpha/opacity enabled).
            colorpicker.wpColorPicker({
                change: function () {
                    if ($('html').hasClass('background-colorpicker-ready')) {
                        setTimeout(function () {
                            control.setData('bg-color', colorpicker.val());
                        }, 100);
                    }
                },
                clear: function (e) {
                    var el = $(e.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];

                    if (el) {
                        control.setData('bg-color', '');
                    }
                }
            });

            // Background Type.
            control.container.on('change', '.nxt-bg-type-list input', function (e) {
                e.preventDefault();
                var val = $(this).val();
                control.setData('bg-type', val);
                if (val === 'color') {
                    control.container.find(".nxt-bg-color").removeClass("hidden");
                    control.container.find(".nxt-bg-image").addClass("hidden");
                    control.container.find('.nxt-control-background > .nxt-bg-size, .nxt-control-background > .nxt-bg-position, .nxt-control-background > .nxt-bg-repeat, .nxt-control-background > .nxt-bg-attachment').addClass("hidden");
                } else if (val === 'image') {
                    control.container.find(".nxt-bg-image").removeClass("hidden");
                    control.container.find(".nxt-bg-color").addClass("hidden");
                    if (control.setting._value['bg-image'] !== '' && control.setting._value['bg-image'] !== undefined) {
                        control.container.find('.nxt-control-background > .nxt-bg-size, .nxt-control-background > .nxt-bg-position, .nxt-control-background > .nxt-bg-repeat, .nxt-control-background > .nxt-bg-attachment').removeClass("hidden");
                    }

                }
            });

            // Background Position.
            control.container.on('change', '.nxt-bg-position select', function () {
                control.setData('bg-position', $(this).val());
            });

            // Background Size.
            control.container.on('change', '.nxt-bg-size select', function () {
                control.setData('bg-size', $(this).val());
            });

            // Background Repeat.
            control.container.on('change', '.nxt-bg-repeat select', function () {
                control.setData('bg-repeat', $(this).val());
            });

            // Background Attachment.
            control.container.on('change', '.nxt-bg-attachment select', function () {
                control.setData('bg-attachment', $(this).val());
            });

            // Background Image.
            control.container.on('click', '.bg-image-upload-button', function (e) {
                var image = wp.media({
                    multiple: false
                }).open().on('select', function () {

                    var selectImg = image.state().get('selection').first(),
                        previewImg = selectImg.toJSON().sizes.full.url,
                        imgUrl,
                        imgID,
                        imgWidth,
                        imgHeight,
                        preview,
                        removeBtn;

                    if (!_.isUndefined(selectImg.toJSON().sizes.medium)) {
                        previewImg = selectImg.toJSON().sizes.medium.url;
                    } else if (!_.isUndefined(selectImg.toJSON().sizes.thumbnail)) {
                        previewImg = selectImg.toJSON().sizes.thumbnail.url;
                    }

                    imgUrl = selectImg.toJSON().sizes.full.url;
                    imgID = selectImg.toJSON().id;
                    imgWidth = selectImg.toJSON().width;
                    imgHeight = selectImg.toJSON().height;

                    if (imgUrl !== '') {
                        control.container.find('.nxt-control-background > .nxt-bg-repeat, .nxt-control-background > .nxt-bg-position, .nxt-control-background > .nxt-bg-size, .nxt-control-background > .nxt-bg-attachment').removeClass("hidden");
                    }

                    control.setData('bg-image', imgUrl);

                    preview = control.container.find('.placeholder, .thumbnail');
                    removeBtn = control.container.find('.bg-image-upload-remove-button');

                    if (preview.length) {
                        preview.removeClass().addClass('thumbnail thumbnail-image').html('<img src="' + previewImg + '" alt="" />');
                    }
                    if (removeBtn.length) {
                        removeBtn.show();
                    }
                });

                e.preventDefault();
            });

            control.container.on('click', '.bg-image-upload-remove-button', function (e) {

                var preview, removeBtn;

                e.preventDefault();

                control.setData('bg-image', '');

                preview = control.container.find('.placeholder, .thumbnail');
                removeBtn = control.container.find('.bg-image-upload-remove-button');

                // Hide controls.
                control.container.find('.nxt-control-background > .nxt-bg-repeat,.nxt-control-background > .nxt-bg-position,.nxt-control-background > .nxt-bg-size,.nxt-control-background > .nxt-bg-attachment').addClass("hidden");

                if (preview.length) {
                    preview.innerHTML = '';
                    preview.removeClass().addClass('placeholder').html(nexterControlBg.placeholder);
                }
                if (removeBtn.length) {
                    removeBtn.hide();
                }
            });

        },

        /**
         * Set Data Of value.
         */
        setData: function (property, value) {

            var control = this,
                inputval = $('#customize-control-' + control.id.replace('[', '-').replace(']', '') + ' .background-hidden-val'),
                val = control.setting._value;

            val[property] = value;

            $(inputval).attr('value', JSON.stringify(val)).trigger('change');

            control.setting.set(val);
        }
    });
	/* Background Control */
	
	/*Color Control*/
	$(window).on("load", function() {
		$('html').addClass('colorpicker-ready');
	});

	wp.customize.controlConstructor['nxt-color'] = wp.customize.Control.extend({

		ready: function() {

			'use strict';

			var control = this;
			var paletteSettingId = control.params.palette_setting_id || '';

			var palette = ( control.params.palette && control.params.palette.length ) ? control.params.palette : true;

			this.container.find('.nxt-color-picker-alpha' ).wpColorPicker({
				alpha: true,
				palettes: palette,

			    change: function (e, ui) {
			        var element = e.target;
			        var color = ui.color.toString();
					
					//Set Value of Color
			        if ( $('html').hasClass('colorpicker-ready') ) {
						control.setting.set( color );
			        }
			    },

			    /**
			     * @param {Event} event - standard jQuery event, produced by "Clear" button.
			     */
			    clear: function (e) {
			        var element = $(e.target).closest('.wp-picker-input-wrap').find('.wp-color-picker')[0];
			        var color = '';
					
					//Set empty value
			        if (element) {
			        	control.setting.set( color );
			        }
			    }
			});
		}
	});
	/*Color Control*/
	
	/*Multi-checkbox Control*/
	wp.customize.controlConstructor['nxt-multi-checkbox'] = wp.customize.Control.extend({

		ready: function() {
			'use strict';
			var control = this;

			control.container.on( 'change', 'input', function() {
				var value = [],
					i = 0;

				$.each( control.params.choices, function( key, checkValue ) {
					if ( control.container.find( 'input[value="' + key + '"]' ).is( ':checked' ) ) {
						value[ i ] = key;
						i++;
					}
				});

				control.setting.set( value );

			});
		}
	});
	/*Multi-checkbox Control*/
	
	/*Responsive Control*/
	wp.customize.controlConstructor['nxt-responsive'] = wp.customize.Control.extend({
		
		ready: function() {
			'use strict';
			var control = this, val;
			
			//Device Unit Button
			control.container.find( '.nxt-responsive-devices button' ).on( 'click', function( event ) {

				var device = $(this).attr('data-device');
				if( device == 'desktop' ) {
					device = 'tablet';
				} else if( device == 'tablet' ) {
					device = 'mobile';
				} else {
					device = 'desktop';
				}

				$( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
			});
			
			// Inputs And Select On change / keyup / paste Events
			this.container.on( 'change keyup paste', '.nxt-responsive-number, .nxt-responsive-unit', function() {

				val = $( this ).val();
				// Set Value
				control.setValue();
			});

			//Preview iframe On Blur
			this.container.on( 'blur', 'input', function() {
				
				val = $( this ).val() || '';
				if ( val == '' ) {
					wp.customize.previewer.refresh();
				}
			});

		},

		//Set Value Customizer
		setValue: function() {

			var control = this, newValue = {};

		    // Set the spacing container.
			control.responsiveWrap = control.container.find( '.nxt-responsive-control-wrap' ).first();
			
			//Input Number
			control.responsiveWrap.find( '.nxt-responsive-number' ).each( function() {
				var $this = $( this ),
				itemId = $this.data( 'id' ),
				itemValue = $this.val();

				newValue[itemId] = itemValue;

			});
			
			//Unit
			control.responsiveWrap.find( '.nxt-responsive-unit' ).each( function() {
				var $this = $( this ),
				itemId = $this.data( 'id' ),
				itemValue = $this.val();

				newValue[itemId] = itemValue;
			});

			control.setting.set( newValue );
		},
	});
	
	/*Responsive Control*/
	
	/*Responsive Slider Control*/
	wp.customize.controlConstructor['nxt-responsive-slider'] = wp.customize.Control.extend({

		
		ready: function() {
			let mainRange = this.container;
			'use strict';
			var control = this, value,	thisInput,	inputDefault, changeAction;
			let rangeWrap = mainRange[0].querySelectorAll('.nxt-slider-wrap');
			rangeWrap.forEach((rw)=>{
				const style = document.createElement('style');
				style.setAttribute('name', mainRange[0].id);
				rw.appendChild(style);

				let getInput = rw.querySelector('input[type=range]');
				control.fillChanges(mainRange, getInput);	
			})
					

			control.container.on( 'click', '.nxt-resp-slider-devices button', function( e ) {
				e.preventDefault();				
				var device = $(this).attr('data-device');
				$( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
			});

			control.container.on( 'change', '.nxt-slider-units-devices', function() {
				var selectedOption = $(this).find('option:selected');
				if(selectedOption.hasClass('active')){
					return false;
				}
				$(this).find('option').removeClass('active');
				selectedOption.addClass('active');
				var	unit_value 	= selectedOption.attr('data-unit'),
					device 		= $('.wp-full-overlay-footer .devices button.active').attr('data-device');
				control.container.find('.nxt-slider-unit-inner .nxt-slider-' + device + '-unit').val( unit_value );
				control.setValue();
			});

			// Update the text value.
			this.container.on( 'input change', 'input[type=range]', function() {
				var value 		 = $( this ).val(),
					slide_num = $( this ).closest( '.nxt-slider-wrap' ).find( '.nxt-responsive-slider-number' );
				slide_num.val( value );
				slide_num.trigger( 'change' );				
				control.fillChanges(mainRange, this);
			});

			// Save changes.
			this.container.on( 'input change', 'input[type=number]', function() {
				var value = $( this ).val();
				$( this ).closest( '.nxt-slider-wrap' ).find( 'input[type=range]' ).val( value );
				
				control.setValue();
				control.fillChanges(mainRange, this);
			});
		},

		fillChanges: function(mainRange, crt){

			const min = parseInt(crt.min);
			const max = parseInt(crt.max);
			const value = parseInt(crt.value);
			
			let percentage = ((value - min) / (max - min)) * 100;
						
			let slideWrap = crt.closest('.nxt-slider-wrap');
			const styleElement = slideWrap.querySelector('style[name="'+mainRange[0].id+'"]');
			
			let resClass = crt.getAttribute("data-id");
			styleElement.innerHTML = '#'+mainRange[0].id+' .nxt-slider-wrap.'+resClass+' input[type=range]::-webkit-slider-runnable-track{ background: linear-gradient( to right, #162D9E '+percentage+'%, #E7E7F6 '+(percentage)+'% ) !important;} #'+mainRange[0].id+' .nxt-slider-wrap.'+resClass+' input[type=range]::-moz-range-track{ background: linear-gradient( to right, #162D9E '+percentage+'%, #E7E7F6 '+(percentage)+'% ) !important;} ';
		},

		//Set Value Customizer
		setValue: function() {

			var control = this,
		    newValue = {};
			
		    // Set the spacing container.
			control.responsiveContainer = control.container.find( '.wrapper' ).first();

			control.responsiveContainer.find( '.nxt-responsive-slider-number' ).each( function() {
				var $this = $( this ),
				itemId = $this.data( 'id' ),
				itemValue = $this.val();
				newValue[itemId] = itemValue;
			});
			
			control.responsiveContainer.find('.nxt-slider-unit-inner .nxt-slider-unit-hidden').each( function() {
				var spacing_unit 	= $( this ),
					device 			= spacing_unit.attr('data-device'),
					device_val 		= spacing_unit.val(),
					name 			= device + '-unit';
				newValue[ name ] = device_val;
			});

			control.setting.set( newValue );
		},

	});
	/*Responsive Slider Control*/
	
	/*Responsive Spacing Control*/
	wp.customize.controlConstructor['nxt-responsive-spacing'] = wp.customize.Control.extend({

		ready: function() {
			'use strict';
			var control = this, value;
			
			control.nxtResponsiveInit();

			// Update value
			this.container.on( 'change keyup paste', 'input.nxt-spacing-input', function() {

				value = $( this ).val();

				// Update value on change.
				control.updateValue();
			});
		},

		/**
		 * Updates the spacing values
		 */
		updateValue: function() {
			'use strict';
			var control = this,
				newValue = { 'md' : {}, 'sm' : {},'xs' : {}, 'md-unit' : 'px', 'sm-unit' : 'px', 'xs-unit' : 'px' };

			control.container.find( 'input.nxt-spacing-desktop' ).each( function() {
				var $this = $( this ),
				item = $this.data( 'id' ),
				value = $this.val();

				newValue['md'][item] = value;
			});

			control.container.find( 'input.nxt-spacing-tablet' ).each( function() {
				var $this = $( this ),
				item = $this.data( 'id' ),
				value = $this.val();

				newValue['sm'][item] = value;
			});

			control.container.find( 'input.nxt-spacing-mobile' ).each( function() {
				var $this = $( this ),
				item = $this.data( 'id' ),
				value = $this.val();

				newValue['xs'][item] = value;
			});

			control.container.find('.nxt-spacing-unit-inner .nxt-spacing-unit-hidden').each( function() {
				var spacing_unit 	= $( this ),
					device 			= spacing_unit.attr('data-device'),
					device_val 		= spacing_unit.val(),
					name 			= device + '-unit';
					
				newValue[ name ] = device_val;
			});

			control.setting.set( newValue );
		},

		/**
		 * Set responsive devices fields
		 */
		nxtResponsiveInit : function() {
			'use strict';
			var control = this;			
			control.container.find( '.nxt-resp-spacing-btns button' ).on( 'click', function( event ) {				
				var device = $(this).attr('data-device');
				$( '.wp-full-overlay-footer .devices button[data-device="' + device + '"]' ).trigger( 'click' );
			});

			// Unit click
			control.container.on( 'change', '.nxt-spacing-units-devices', function() {
				var selectedOption = $(this).find('option:selected');
				if(selectedOption.hasClass('active')){
					return false;
				}
				$(this).find('option').removeClass('active');
				selectedOption.addClass('active');
				var	unit_value 	= selectedOption.attr('data-unit'),
					device 		= $('.wp-full-overlay-footer .devices button.active').attr('data-device');
					control.container.find('.nxt-spacing-unit-inner .nxt-spacing-' + device + '-unit').val( unit_value );

				control.updateValue();
			});
		},
	});
	$(document).ready(function () {

		// Linked button
		$('.nxt-spacing-input-link-unlink').on('click', function() {
			if($(this).hasClass('disconnected')){
				$(this).parent('.nxt-spacing-devices').find('input').removeClass('connected').attr('data-element-connect', '');
				$(this).removeClass('disconnected');
			}else{
				var elements = $(this).data('element-connect');
				$(this).parent('.nxt-spacing-devices').find('input').addClass('connected').attr('data-element-connect', elements);
				$(this).addClass('disconnected');
			}
			
		})

		// Values linked inputs
		$('.nxt-spacing-input-item').on('input', '.connected', function () {

			var dataElement = $(this).attr('data-element-connect'),
				currentFieldValue = $(this).val();

			$(this).parent().parent('.nxt-spacing-devices').find('.connected[ data-element-connect="' + dataElement + '" ]').each(function (key, value) {
				$(this).val(currentFieldValue).change();
			});

		});
	});
	/*Responsive Spacing Control*/
	
	/*Slider Control*/
	wp.customize.controlConstructor['nxt-slider'] = wp.customize.Control.extend({
		ready: function() {
			'use strict';

			var control = this, value, thisInput, inputDefault,	changeAction;
			let mainRange = this.container;

			let rangeWrap = mainRange[0].querySelectorAll('.nxt_slider_wrap');
			rangeWrap.forEach((rw)=>{
				const style = document.createElement('style');
				style.setAttribute('name', mainRange[0].id);
				rw.appendChild(style);

				let getInput = rw.querySelector('input[type=range]');
				control.fillChanges(mainRange, getInput);	
			})

			// Update the text value.
			control.container.on( 'input change', 'input[type=range]', function() {
				var $this  = $( this ),
					value	= $this.val(),
					input_val = $this.closest( '.nxt_slider_wrap' ).find( '.nxt_range_value .value' );

				input_val.val( value );
				input_val.trigger( 'change' );
				control.fillChanges(mainRange, this);
			});

			// slider reset button
			$( '.nxt-slider-reset' ).on( 'click', function() {
				var wrap 		= $( this ).closest( '.nxt_slider_wrap' ),
					range   	= wrap.find( 'input[type=range]' ),
					input_val 	= wrap.find( '.nxt_range_value .value' ),
					reset_value	= range.data( 'reset_value' );

				range.val( reset_value );
				input_val.val( reset_value );
				input_val.change();
			});

			// Save changes
			control.container.on( 'input change', 'input[type=number]', function() {
				var $this  = $( this ),
					value = $this.val();
				$this.closest( '.nxt_slider_wrap' ).find( 'input[type=range]' ).val( value );
				control.setting.set( value );
				control.fillChanges(mainRange, this);
			});
		},
		fillChanges: function(mainRange, crt){

			const min = crt.min;
			const max = crt.max;
			const value = crt.value;
			
			let percentage = ((value - min) / (max - min)) * 100;
			
			let slideWrap = crt.closest('.nxt_slider_wrap');
			const styleElement = slideWrap.querySelector('style[name="'+mainRange[0].id+'"]');
						
			styleElement.innerHTML = '#'+mainRange[0].id+' .nxt_slider_wrap input[type=range]::-webkit-slider-runnable-track{ background: linear-gradient( to right, #162D9E '+percentage+'%, #E7E7F6 '+(percentage)+'% ) !important;} #'+mainRange[0].id+' .nxt_slider_wrap input[type=range]::-moz-range-track{ background: linear-gradient( to right, #162D9E '+percentage+'%, #E7E7F6 '+(percentage)+'% ) !important;} ';
		}
	});
	/*Slider Control*/
	
	/*Switcher Control*/
	wp.customize.controlConstructor['nxt-switcher'] = wp.customize.Control.extend({

		ready: function() {
			'use strict';
			var control = this;
	
			let getInput = this.container[0].querySelector('input.switch-input');
			if(getInput.value == 'off'){
				getInput.checked = false;
			}
			
			// Change the value when the checkbox is toggled
			this.container.on('change', 'input.switch-input', function(e) {
				e.preventDefault();
	
				// Determine if the checkbox is checked
				var isChecked = $(this).prop('checked');
	
				// Only update the setting if the value actually changes
				if (isChecked && control.setting.get() !== 'on') {
					control.setting.set('on');
				} else if (!isChecked && control.setting.get() !== 'off') {
					control.setting.set('off');
				}
			});
	
			// Update checkbox state when the setting changes externally
			control.setting.bind(function(value) {
				var input = control.container.find('input.switch-input');
				var isChecked = input.prop('checked');
				
				// Update checkbox based on the value, only if it differs
				if (value === 'on' && !isChecked) {
					input.prop('checked', true);
				} else if (value === 'off' && isChecked) {
					input.prop('checked', false);
				}
			});
		}
	});
	/*Switcher Control*/

	/*Style Control*/
	wp.customize.controlConstructor['nxt-style'] = wp.customize.Control.extend({

		ready: function() {
			'use strict';
			var control = this;

			// Change the value
			this.container.on( 'change', 'input.radio-input', function(e) {
				e.preventDefault();				
				control.setting.set($(this).val());
			});
		}
	});
	/*Style Control*/

	/*Color Palette Control*/
	wp.customize.controlConstructor['nxt-color-palette'] = wp.customize.Control.extend({

		ready: function() {
			'use strict';
			var control = this;
			var colors = [];
			var hiddenInput = control.container.find('.nxt-color-palette-value');
			var currentEditingIndex = -1;
			var $modal = control.container.find('.nxt-color-picker-modal');
			var $wpWrapper = control.container.find('.nxt-color-picker-wp-wrapper');
			var $currentPicker = null;
			
			// Initialize colors from saved value or default (supports hex, rgb, rgba)
			if ( control.setting._value && Array.isArray( control.setting._value ) ) {
				colors = control.setting._value.slice();
			} else if ( control.setting._value && typeof control.setting._value === 'string' ) {
				try {
					var parsed = JSON.parse( control.setting._value );
					colors = Array.isArray( parsed ) ? parsed : [];
				} catch ( e ) {
					colors = [];
				}
			} else if ( control.params.default && Array.isArray( control.params.default ) ) {
				colors = control.params.default.slice();
			}
			
			// Function to update the setting
			function updateSetting() {
				var jsonValue = JSON.stringify( colors );
				hiddenInput.val( jsonValue ).trigger('change');
				control.setting.set( colors );
				updateColorSwatches();
			}
			
			// Update color swatches visual display (hex, rgb, rgba all work in background-color)
			function updateColorSwatches() {
				control.container.find('.nxt-color-swatch').each(function() {
					var index = parseInt($(this).closest('.nxt-color-swatch-item').data('index'));
					if ( !isNaN(index) && colors[index] !== undefined ) {
						$(this).css('background-color', colors[index]);
					}
				});
			}

			// Check if string is a valid CSS color (hex, rgb, rgba, etc.)
			function isValidColorString( str ) {
				if ( !str || typeof str !== 'string' ) { return false; }
				str = str.trim();
				if ( /^#[A-Fa-f0-9]{3}$|^#[A-Fa-f0-9]{6}$|^#[A-Fa-f0-9]{8}$/.test( str ) ) { return true; }
				if ( /^rgba?\s*\(\s*[\d.]+\s*,\s*[\d.]+\s*,\s*[\d.]+\s*(,\s*[\d.]+\s*)?\)$/.test( str ) ) { return true; }
				if ( /^hsla?\s*\(/.test( str ) ) { return true; }
				return false;
			}

			// Escape color for use in HTML attribute (avoid breaking quotes)
			function escapeColorForAttr( color ) {
				if ( !color ) { return ''; }
				return String( color ).replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' ).replace( /</g, '&lt;' );
			}
			
			// Initialize individual color picker for a specific index
			function initColorPicker(index) {
				var $picker = control.container.find('.nxt-color-picker-' + index);
				var $pickerWrapper = $picker.closest('.nxt-color-picker-wrapper');
				
				if ( $picker.length && !$picker.hasClass('wp-color-picker-initialized') ) {
					// Temporarily show wrapper if hidden (needed for proper initialization)
					var wasHidden = $pickerWrapper.css('display') === 'none';
					if ( wasHidden ) {
						$pickerWrapper.css({
							'display': 'block',
							'visibility': 'hidden',
							'position': 'absolute',
							'left': '-9999px'
						});
					}
					
					$picker.wpColorPicker({
						alpha: true,
						change: function(e, ui) {
							var color = ui.color.toString();
							var pickerIndex = parseInt($(this).data('index'));
							if ( colors[pickerIndex] !== undefined ) {
								colors[pickerIndex] = color;
								updateSetting();
							}
						},
						clear: function(e) {
							var color = '#ffffff';
							var pickerIndex = parseInt($(this).data('index'));
							if ( colors[pickerIndex] !== undefined ) {
								colors[pickerIndex] = color;
								updateSetting();
							}
						}
					});
					
					// Reset wrapper visibility after initialization
					if ( wasHidden ) {
						$pickerWrapper.css({
							'display': 'none',
							'visibility': '',
							'position': '',
							'left': ''
						});
					}
					
					$picker.addClass('wp-color-picker-initialized');
				}
			}
			
			// Initialize all color pickers
			function initAllColorPickers() {
				control.container.find('.nxt-color-picker-alpha').each(function() {
					var index = parseInt($(this).data('index'));
					if ( !isNaN(index) ) {
						initColorPicker(index);
					}
				});
			}
			
			// Function to open color picker in modal
			function openColorPicker(index) {
				currentEditingIndex = index;
				var color = colors[index] || '#ffffff';
				
				// Remove existing picker if any
				$wpWrapper.empty();
				
				// Create new picker input for modal with proper attributes (value supports hex, rgb, rgba)
				var pickerId = 'nxt-color-picker-modal-' + control.id.replace(/[\[\]]/g, '-') + '-' + index;
				var $modalPicker = $('<input type="text" id="' + pickerId + '" name="' + pickerId + '" class="nxt-modal-color-picker nxt-color-picker-alpha" data-alpha="true" value="' + escapeColorForAttr( color ) + '" />');
				$wpWrapper.append($modalPicker);
				
				// Show modal first so wpColorPicker can initialize properly
				$modal.fadeIn(2);
				
				// Position modal
				var $swatch = control.container.find('.nxt-color-swatch-item[data-index="' + index + '"]');
				if ($swatch.length) {
					var offset = $swatch.offset();
					var modalWidth = 300;
					var leftPos = offset.left - (modalWidth / 2) + ($swatch.outerWidth() / 2);
					$modal.css({
						position: 'fixed',
					//	top: offset.top + $swatch.outerHeight() + 10 + 'px',
						left: Math.max(20, leftPos) + 'px',
						zIndex: 100000
					});
				}
				
				// Initialize wpColorPicker after modal is visible (alpha/opacity enabled)
				//setTimeout(function() {
					$modalPicker.wpColorPicker({
						alpha: true,
						change: function(e, ui) {
							var selectedColor = ui.color.toString();
							if (currentEditingIndex >= 0 && colors[currentEditingIndex] !== undefined) {
								colors[currentEditingIndex] = selectedColor;
								updateSetting();
							}
						},
						clear: function(e) {
							var defaultColor = '#ffffff';
							if (currentEditingIndex >= 0 && colors[currentEditingIndex] !== undefined) {
								colors[currentEditingIndex] = defaultColor;
								updateSetting();
							}
						}
					});
					
					// Update the input field value to match the color
					$modalPicker.val(color);
					
					// Ensure input wrap is visible
					var $inputWrap = $modalPicker.closest('.wp-picker-container').find('.wp-picker-input-wrap');
					$inputWrap.removeClass('hidden').show();
					
					// Also listen to input field changes (accept hex, rgb, rgba)
					$modalPicker.on('input change', function() {
						var inputColor = $(this).val().trim();
						if (inputColor && isValidColorString( inputColor )) {
							if (currentEditingIndex >= 0 && colors[currentEditingIndex] !== undefined) {
								colors[currentEditingIndex] = inputColor;
								updateSetting();
							}
						}
					});
					
					// Trigger iris change to update the picker display
					if ($modalPicker.data('wp-color-picker')) {
						$modalPicker.iris('color', color);
					}
					
					// Open the color picker automatically by clicking the input or triggering iris
					//setTimeout(function() {
						// Try to open picker by focusing the input and triggering iris
						$modalPicker.focus();
						if ($modalPicker.data('wp-color-picker')) {
							var $pickerHolder = $modalPicker.closest('.wp-picker-container').find('.wp-picker-holder');
							if ($pickerHolder.length) {
								$pickerHolder.show();
							}
						}
					//}, 50);
				//}, 1);
			}
			
			// Function to close color picker modal
			function closeColorPicker() {
				// Close any open wpColorPicker first
				var $pickerHolder = $('.nxt-color-palette-control').find('.wp-picker-holder');
				if ($pickerHolder.length) {
					$pickerHolder.hide();
				}
				
				// Close modal
				$modal.fadeOut(5);
				currentEditingIndex = -1;
				
				// Clean up the picker
				var $modalPicker = $wpWrapper.find('.nxt-modal-color-picker');
				if ($modalPicker.length && $modalPicker.data('wp-color-picker')) {
					$modalPicker.wpColorPicker('close');
				}
			}
			
			// Click on swatch to open picker
			control.container.on('click', '.nxt-color-swatch', function(e) {
				if ($(e.target).closest('.nxt-color-remove').length) {
					return;
				}
				var index = parseInt($(this).closest('.nxt-color-swatch-item').data('index'));
				if (!isNaN(index)) {
					openColorPicker(index);
				}
			});
			
			// Remove color
			control.container.on('click', '.nxt-color-remove', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var index = parseInt($(this).data('index'));
				if (colors.length > 1) {
					colors.splice(index, 1);
					updateColorList();
				} else {
					alert('You must have at least one color in the palette.');
				}
			});
			
			// Add new color
			control.container.on('click', '.nxt-add-color', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var newIndex = colors.length;
				colors.push('#ffffff');
				updateColorList();
				/* setTimeout(function() {
					openColorPicker(newIndex);
				}, 100); */
			});
			
			// Close picker on outside click
			$(document).on('click', function(e) {
				if (!$(e.target).closest('.nxt-color-picker-modal').length &&
					!$(e.target).closest('.wp-picker-holder').length &&
					!$(e.target).closest('.nxt-color-swatch-item').length &&
					!$(e.target).closest('.wp-picker-container').length) {
					closeColorPicker();
				}
			});
			
			// Update color list in DOM (hex, rgb, rgba; escape for HTML attributes)
			function updateColorList() {
				var $swatches = control.container.find('.nxt-color-palette-swatches');
				$swatches.find('.nxt-color-swatch-item:not(.nxt-add-color-item)').remove();
				
				_.each(colors, function(color, index) {
					var safeAttr = escapeColorForAttr( color );
					var safeStyle = String( color ).replace( /"/g, '\\"' );
					var $item = $('<div class="nxt-color-swatch-item" data-index="' + index + '">' +
						'<div class="nxt-color-swatch" style="background-color: ' + safeStyle + ';" data-color="' + safeAttr + '">' +
						'<button type="button" class="nxt-color-remove" data-index="' + index + '" title="Remove Color">' +
						'<svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" fill="none" viewBox="0 0 6 6"><path fill="#666" d="M4.459 5.078 2.603 3.22.747 5.078a.437.437 0 1 1-.619-.619l1.856-1.856L.128.747A.438.438 0 0 1 .747.128l1.856 1.856L4.459.128a.438.438 0 0 1 .618.619L3.221 2.603l1.856 1.856a.437.437 0 1 1-.618.619"/></svg>' +
						'</button>' +
						'</div>' +
						'<div class="nxt-color-picker-wrapper">' +
						'<input type="text" class="nxt-color-picker-alpha color-picker-hex nxt-color-picker-' + index + '" data-alpha="true" data-index="' + index + '" value="' + safeAttr + '" style="display: none;" />' +
						'</div>' +
						'</div>');
					$swatches.find('.nxt-add-color-item').before($item);
					
					// Initialize picker for this new swatch
					setTimeout(function() {
						initColorPicker(index);
					}, 50);
				});
				
				updateSetting();
			}
			
			// Initialize all color pickers on ready
			initAllColorPickers();
			updateColorSwatches();
		}
	});
	/*Color Palette Control*/
	
	//Device Responsive And Responsive Slider Preview
	$(' .wp-full-overlay-footer .devices button ').on('click', function() {
		var device = $(this).attr('data-device');
		//Responsive Device
		$( '.customize-control-nxt-responsive .nxt-responsive-control-wrap input, .customize-control .nxt-responsive-devices > li' ).removeClass( 'active' );
		$( '.customize-control-nxt-responsive .nxt-responsive-control-wrap input.' + device + ', .customize-control .nxt-responsive-devices > li.' + device ).addClass( 'active' );
		
		//Responsive Slider Device
		$( '.customize-control-nxt-responsive-slider .nxt-slider-wrap, .customize-control .nxt-resp-slider-devices > li' ).removeClass( 'active' );
		$( '.customize-control-nxt-responsive-slider .nxt-slider-wrap.' + device + ', .customize-control .nxt-resp-slider-devices > li.' + device ).addClass( 'active' );
		
		//Responsive Spacing Device
		$( '.customize-control-nxt-responsive-spacing .nxt-spacing-inner-wrap .nxt-spacing-devices, .customize-control .nxt-resp-spacing-btns > li' ).removeClass( 'active' );
		$( '.customize-control-nxt-responsive-spacing .nxt-spacing-inner-wrap .nxt-spacing-devices.' + device + ', .customize-control .nxt-resp-spacing-btns > li.' + device ).addClass( 'active' );
	});
})(jQuery);