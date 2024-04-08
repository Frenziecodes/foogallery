<?php

/**
 * FILEPATH: foogallery/pro/extensions/default-templates/boxslider/class-boxslider-gallery-template.php.
 *
 * This file contains the definition of the FooGallery_BoxSlider_Gallery_Template class.
 * The class is responsible for handling the box slider gallery template in the FooGallery plugin.
 *
 * @package FooGallery
 */

if ( ! class_exists( 'FooGallery_BoxSlider_Gallery_Template' ) ) {

	/**
	 * Class FooGallery_BoxSlider_Gallery_Template
	 */
	class FooGallery_BoxSlider_Gallery_Template {

		const TEMPLATE_ID = 'boxslider';

		/**
		 * Wire up everything we need to run the extension
		 */
		public function __construct() {
			add_filter( 'foogallery_gallery_templates', array( $this, 'add_template' ), 99, 1 );
			add_filter( 'foogallery_gallery_templates_files', array( $this, 'register_myself' ) );

			// Build up the arguments needed for rendering this template.
			add_filter( 'foogallery_gallery_template_arguments-boxslider', array( $this, 'build_gallery_template_arguments' ) );

			// Build up the thumb dimensions from some arguments.
			add_filter( 'foogallery_calculate_thumbnail_dimensions-boxslider', array( $this, 'build_thumbnail_dimensions_from_arguments' ), 10, 2 );

			// Build up the thumb dimensions on save.
			add_filter( 'foogallery_template_thumbnail_dimensions-slider', array( $this, 'get_thumbnail_dimensions' ), 10, 2 );
			// Add the necessary data options for boxslider.
			add_filter( 'foogallery_build_container_data_options-boxslider', array( $this, 'add_data_options' ), 10, 3 );

			add_filter( 'foogallery_admin_settings_override', array( $this, 'add_language_settings' ), 50 );
			// Append classes needed for the gallery template.
			add_filter( 'foogallery_build_class_attribute', array( $this, 'append_classes' ), 10, 2);
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
		}

		/**
		 * Enqueue scripts and styles for the FooPilot modal.
		 */
		public function enqueue_scripts_and_styles() {
			wp_enqueue_style( 'foogallery.admin.boxslider', FOOGALLERY_URL . 'pro/extensions/default-templates/boxslider/css/gallery-boxslider.css', array(), FOOGALLERY_VERSION );
			wp_enqueue_script( 'foogallery.admin.boxslider', FOOGALLERY_URL . 'pro/extensions/default-templates/boxslider/js/admin-gallery-boxslider.js', array( 'jquery' ), FOOGALLERY_VERSION, true );
			wp_enqueue_script( 'foogallery.admin.boxslider.min', FOOGALLERY_URL . 'pro/extensions/default-templates/boxslider/js/boxslider.min.js', array(), FOOGALLERY_VERSION, true );
		}

		/**
		 * Register myself so that all associated JS and CSS files can be found and automatically included.
		 *
		 * @param array $extensions The array of extensions.
		 *
		 * @return array The updated array of extensions.
		 */
		public function register_myself( $extensions ) {
			$extensions[] = __FILE__;
			return $extensions;
		}

		/**
		 * Add our gallery template to the list of templates available for every gallery.
		 *
		 * @param array $gallery_templates The array of gallery templates.
		 *
		 * @return array The updated array of gallery templates.
		 */
		public function add_template( $gallery_templates ) {
			$gallery_templates[] = array(
				'slug'                  => self::TEMPLATE_ID,
				'name'                  => __( 'Box Slider', 'foogallery' ),
				'preview_support'       => true,
				'common_fields_support' => true,
				'lazyload_support'      => true,
				'paging_support'        => false,
				'mandatory_classes'     => 'fg-boxslider',
				'thumbnail_dimensions'  => true,
				'filtering_support'     => true,
				'enqueue_core'          => true,
				'fields'                => array(
					array(
						'id'   => 'lightbox',
						'desc' => __( 'Choose which lightbox you want to use in the gallery', 'foogallery' ),
						'type' => 'lightbox',
					),
					array(
						'id' => 'effect',
						'title' => __( 'Effect', 'foogallery' ),
						'desc' => __( 'Determines the type of transition effect between slides.', 'foogallery' ),
						'type' => 'select',
						'default' => 'fade',
						'choices' => array(
							'fg-fade-slider' => __( 'Fade Slider', 'foogallery' ),
							'fg-tile-slider' => __( 'Tile Slider', 'foogallery' ),
							'fg-carousel-slider' => __( 'Carousel Slider', 'foogallery' ),
							'fg-cube-slider' => __( 'Cube Slider', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-change-selector' => 'select',
							'data-foogallery-value-selector' => 'option:selected',
							'data-foogallery-preview' => 'shortcode',
						),
					),

					// CONDITIONAL FIELDS FOR SLIDER EFFECTS BELOW THIS LINE.
					// IF FADESLIDER IS CHOSEN SHOW THESE FIELDS.
					array(
						'id' => 'timingFunction',
						'title' => __( 'Timing function', 'foogallery' ),
						'desc' => __( 'The CSS transition timing function to use when fading slide opacity', 'foogallery' ),
						'type' => 'select',
						'default' => 'ease-in',
						'choices' => array(
							'ease-in' => __( 'Ease-in', 'foogallery' ),
							'ease-out' => __( 'Ease-out', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-fade-slider',
							'data-foogallery-change-selector' => 'select',
							'data-foogallery-value-selector' => 'option:selected',
							'data-foogallery-preview' => 'shortcode',
						),
					),

					// IF TILESLIDER IS CHOSEN SHOW THESE FIELDS.
					array(
						'id' => 'tileEffect',
						'title' => __( 'Tile Effect', 'foogallery' ),
						'desc' => __( 'The transition effect for animating the tiles during slide transitions.', 'foogallery' ),
						'type' => 'select',
						'default' => 'flip',
						'choices' => array(
							'flip' => __( 'Flip', 'foogallery' ),
							'fade' => __( 'Fade', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-tile-slider',
							'data-foogallery-change-selector' => 'select',
							'data-foogallery-value-selector' => 'option:selected',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					array(
						'id' => 'rows',
						'title' => __( 'Rows', 'foogallery' ),
						'desc' => __( 'Specifies the time interval in milliseconds within which the slide animation will complete.', 'foogallery' ),
						'type' => 'number',
						'default' => 8,
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-tile-slider',
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					array(
						'id' => 'rowOffset',
						'title' => __( 'Row Offset', 'foogallery' ),
						'desc' => __( 'The time offset for starting to animate the tiles in a row.', 'foogallery' ),
						'type' => 'number',
						'default' => 50,
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-tile-slider',
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input',
							'data-foogallery-preview' => 'shortcode',
						),
					),

					// IF CUBESLIDER IS CHOSEN SHOW THESE FIELDS.
					array(
						'id' => 'direction',
						'title' => __( 'Direction', 'foogallery' ),
						'desc' => __( 'The direction in which the cube should rotate to the next slide.', 'foogallery' ),
						'type' => 'select',
						'default' => 'horizontal',
						'choices' => array(
							'horizontal' => __( 'Horizontal', 'foogallery' ),
							'vertical' => __( 'Vertical', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-cube-slider',
							'data-foogallery-change-selector' => 'select',
							'data-foogallery-value-selector' => 'option:selected',
							'data-foogallery-preview' => 'shortcode',
						),
					),

					// IF CAROUSELSLIDER IS CHOSEN SHOW THESE FIELDS.
					array(
						'id' => 'cover',
						'title' => __( 'Cover', 'foogallery' ),
						'desc' => __( 'If true sets the slide effect to cover over the previous slide.', 'foogallery' ),
						'type' => 'select',
						'default' => 'false',
						'choices' => array(
							'false' => __( 'False', 'foogallery' ),
							'true' => __( 'True', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-carousel-slider',
							'data-foogallery-change-selector' => 'select',
							'data-foogallery-value-selector' => 'option:selected',
							'data-foogallery-preview' => 'shortcode',
						),
					),

					// COMMON FIELDS FOR ALL SLIDER EFFECTS BELOW THIS LINE.

					array(
						'id' => 'speed',
						'title' => __( 'Speed of Transition', 'foogallery' ),
						'desc' => __( 'Specifies the time interval in milliseconds within which the slide animation will complete.', 'foogallery' ),
						'type' => 'number',
						'default' => 800,
						'row_data' => array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					array(
						'id' => 'swipe',
						'title' => __( 'swipe', 'foogallery' ),
						'desc' => __( 'Enable swiping the box to navigate to the next or previous slide.', 'foogallery' ),
						'type' => 'radio',
						'default' => 'false',
						'choices' => array(
							'true' => __( 'True', 'foogallery' ),
							'false' => __( 'false', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input:checked',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					array(
						'id' => 'autoScroll',
						'title' => __( 'Auto-Scrolling', 'foogallery' ),
						'desc' => __( 'Enables or disables automatic transitioning through the slides.', 'foogallery' ),
						'type' => 'radio',
						'default' => 'true',
						'choices' => array(
							'true' => __( 'True', 'foogallery' ),
							'false' => __( 'False', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input:checked',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					// Shown when autoscroll is set to true.
					array(
						'id' => 'timeout',
						'title' => __( 'Timeout', 'foogallery' ),
						'desc' => __( ' Sets the time interval between slide transitions.', 'foogallery' ),
						'type' => 'number',
						'default' => 5000,
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'autoScroll',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'true',
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input',
							'data-foogallery-preview' => 'shortcode',
						),
					),
					array(
						'id' => 'pauseOnHover',
						'title' => __( 'Pause On Hover', 'foogallery' ),
						'desc' => __( 'Pause an auto-scrolling slider when the users mouse hovers over it.', 'foogallery' ),
						'type' => 'radio',
						'default' => 'false',
						'choices' => array(
							'true' => __( 'True', 'foogallery' ),
							'false' => __( 'false', 'foogallery' ),
						),
						'row_data' => array(
							'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'autoScroll',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'true',
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input:checked',
							'data-foogallery-preview' => 'shortcode'
						)
					),
					array(
						'id'      => 'language-help',
						'desc'    => __( 'You can change the "Prev", "Next", "Play" and "pause" text used in the gallery from the settings page, under the Language tab.', 'foogallery' ),
						'section' => __( 'General', 'foogallery' ),
						'type'    => 'help',
					),
				),
			);

			return $gallery_templates;
		}

		/**
		 * Add language settings to the provided settings array.
		 *
		 * This function adds language-related settings for the foogallery Box Slider section.
		 *
		 * @param array $settings An array of existing settings.
		 *
		 * @return array The modified settings array with added language settings.
		 */
		public function add_language_settings( $settings ) {

			$settings['settings'][] = array(
				'id'      => 'language_boxslider_prev_text',
				'title'   => __( 'Boxslider Prev Text', 'foogallery_user_uploads' ),
				'type'    => 'text',
				'default' => __( 'Prev', 'foogallery_user_uploads' ),
				'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
				'tab'     => 'language',
			);

			$settings['settings'][] = array(
				'id'      => 'language_boxslider_next_text',
				'title'   => __( 'Boxslider Next Text', 'foogallery_user_uploads' ),
				'type'    => 'text',
				'default' => __( 'Next', 'foogallery_user_uploads' ),
				'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
				'tab'     => 'language',
			);

			$settings['settings'][] = array(
				'id'      => 'language_boxslider_play_text',
				'title'   => __( 'Boxslider Play Text', 'foogallery_user_uploads' ),
				'type'    => 'text',
				'default' => __( 'Play', 'foogallery_user_uploads' ),
				'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
				'tab'     => 'language',
			);

			$settings['settings'][] = array(
				'id'      => 'language_boxslider_pause_text',
				'title'   => __( 'Boxslider Pause Text', 'foogallery_user_uploads' ),
				'type'    => 'text',
				'default' => __( 'Pause', 'foogallery_user_uploads' ),
				'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
				'tab'     => 'language',
			);

			return $settings;
		}

		/**
		 * Builds thumb dimensions from arguments
		 *
		 * @param array $dimensions The existing thumb dimensions.
		 * @param array $arguments The arguments for building thumb dimensions.
		 *
		 * @return mixed
		 */
		public function build_thumbnail_dimensions_from_arguments( $dimensions, $arguments ) {
			if ( array_key_exists( 'thumbnail_size', $arguments ) ) {
				return array(
					'height' => intval( $arguments['thumbnail_size']['height']),
					'width' => intval( $arguments['thumbnail_size']['width']),
					'crop' => $arguments['thumbnail_size']['crop'],
				);
			}
			return null;
		}

		/**
		 * Get the thumb dimensions arguments saved for the gallery for this gallery template
		 *
		 * @param array      $dimensions The existing thumb dimensions.
		 * @param FooGallery $foogallery The FooGallery instance.
		 *
		 * @return mixed
		 */
		public function get_thumbnail_dimensions( $dimensions, $foogallery ) {
			$dimensions = $foogallery->get_meta(
				'boxslider_thumbnail_size',
				array(
					'width'  => 600,
					'height' => 400,
					'crop'   => true,
				),
			);
			if ( ! array_key_exists( 'crop', $dimensions ) ) {
				$dimensions['crop'] = true;
			}
			return $dimensions;
		}

		/**
		 * Build up the arguments needed for rendering this gallery template
		 *
		 * @param array $args The arguments for rendering the gallery template.
		 * @return array The built-up arguments for rendering the gallery template.
		 */
		public function build_gallery_template_arguments( $args ) {
			$args = foogallery_gallery_template_setting(
				'thumbnail_size',
				array(
					'width' => 600,
					'height' => 400,
					'crop' => true,
				)
			);

			if ( ! array_key_exists( 'crop', $args ) ) {
				$args['crop'] = '1';
			}
			$args['link'] = foogallery_gallery_template_setting( 'thumbnail_link', 'image' );

			return $args;
		}

		/**
		 * Add the required data options if needed
		 *
		 * @param array      $options The options array.
		 * @param FooGallery $foogallery The FooGallery instance.
		 *
		 * @return array The updated options array.
		 */
		public function add_data_options( $options, $foogallery ) {
			if ( isset( $foogallery ) && isset( $foogallery->gallery_template ) && self::TEMPLATE_ID === $foogallery->gallery_template ) {

				// common options.
				$slider         = foogallery_gallery_template_setting( 'effect', '' );
				$speed          = foogallery_gallery_template_setting( 'speed', '' );
				$auto_scroll    = foogallery_gallery_template_setting( 'autoScroll', '' );
				$timeout        = foogallery_gallery_template_setting( 'timeout', '' );
				$pause_on_hover = foogallery_gallery_template_setting( 'pauseOnHover', '' );
				$swipe          = foogallery_gallery_template_setting( 'swipe', '' );

				// end of common options.

				$timing_function = foogallery_gallery_template_setting( 'timingFunction', '' ); // fade slider.
				$tile_effect     = foogallery_gallery_template_setting( 'tileEffect', '' ); // tile slider.
				$rows            = foogallery_gallery_template_setting( 'rows', '' ); // tile slider.
				$row_offset      = foogallery_gallery_template_setting( 'rowOffset', '' ); // tile slider.
				$direction       = foogallery_gallery_template_setting( 'direction', '' ); // cube slider.
				$cover           = foogallery_gallery_template_setting( 'cover', '' ); // Carousel Slider.

				$options['template']['slider']         = $slider;
				$options['template']['autoScroll']     = $auto_scroll;
				$options['template']['effect']         = $tile_effect;
				$options['template']['direction']      = $direction;
				$options['template']['speed']          = $speed;
				$options['template']['timeout']        = $timeout;
				$options['template']['pauseOnHover']   = $pause_on_hover;
				$options['template']['swipe']          = $swipe;
				$options['template']['timingFunction'] = $timing_function;
				$options['template']['tileEffect']     = $tile_effect;
				$options['template']['rows']           = $rows;
				$options['template']['rowOffset']      = $row_offset;
				$options['template']['cover']          = $cover;
			}

			return $options;
		}

		/**
		 * Adds the classes onto the container
		 *
		 * @param array      $classes The classes to be added onto the container.
		 * @param FooGallery $foogallery The FooGallery instance.
		 * @return array The updated classes.
		 */
		public function append_classes( $classes, $foogallery ) {
			if ( isset( $foogallery ) && isset( $foogallery->gallery_template ) && self::TEMPLATE_ID === $foogallery->gallery_template ) {

				$position = foogallery_gallery_template_setting( 'caption_position', '' );

				if ( '' !== $position ) {
					$classes[] = $position;
				}
			}

			return $classes;
		}
	}
}
