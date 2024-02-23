<?php

if ( ! class_exists( 'FooGallery_BoxSlider_Gallery_Template' ) ) {

    class FooGallery_BoxSlider_Gallery_Template
    {

        const TEMPLATE_ID = 'boxslider';

        /**
         * Wire up everything we need to run the extension
         */
        public function __construct()
        {
            add_filter( 'foogallery_gallery_templates', array( $this, 'add_template' ), 99, 1);           
            add_filter( 'foogallery_gallery_templates_files', array( $this, 'register_myself' ) );

            // Build up the arguments needed for rendering this template
            add_filter( 'foogallery_gallery_template_arguments-boxslider', array( $this, 'build_gallery_template_arguments' ) );
            
            //build up the thumb dimensions from some arguments
			add_filter( 'foogallery_calculate_thumbnail_dimensions-boxslider', array( $this, 'build_thumbnail_dimensions_from_arguments' ), 10, 2 );

            //build up the thumb dimensions on save
			add_filter( 'foogallery_template_thumbnail_dimensions-slider', array( $this, 'get_thumbnail_dimensions' ), 10, 2 );
            // Add the necessary data options for boxslider
            add_filter( 'foogallery_build_container_data_options-boxslider', array( $this, 'add_data_options' ), 10, 3 );

            add_filter( 'foogallery_admin_settings_override', array( $this, 'add_language_settings' ), 50 );
         
            // Append classes needed for the gallery template
            add_filter( 'foogallery_build_class_attribute', array( $this, 'append_classes' ), 10, 2);
          
        }        

        /**
         * Register myself so that all associated JS and CSS files can be found and automatically included
         *
         * @param $extensions
         *
         * @return array
         */
        public function register_myself( $extensions ) {
            $extensions[] = __FILE__;
            return $extensions;
        }
        
        /**
         * Add our gallery template to the list of templates available for every gallery
         *
         * @param $gallery_templates
         *
         * @return array
         */
        public function add_template( $gallery_templates ) {
            $gallery_templates[] = array(
                'slug' => self::TEMPLATE_ID,
                'name' => __( 'Box Slider', 'foogallery' ),
                'preview_support' => true,
                'common_fields_support' => true,
                'lazyload_support' => true,
                'paging_support' => false,
                'mandatory_classes' => 'fg-boxslider',
                'thumbnail_dimensions' => true,
                'filtering_support' => true,
                'enqueue_core' => true,
                'fields' => array(
					array(
						'id'      => 'thumbnail_size',
						'title'   => __( 'Thumbnail Size', 'foogallery' ),
						'desc'    => __( 'Choose the size of your thumbs.', 'foogallery' ),
						'type'    => 'thumb_size',
						'default' => array(
							'width' => 320,
							'height' => 180,
							'crop' => true
						),
						'row_data'=> array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-preview' => 'shortcode'
						)
					),
					array(
						'id'      => 'thumbnail_link',
						'title'   => __( 'Thumbnail Link', 'foogallery' ),
						'section' => __( 'General', 'foogallery' ),
						'default' => 'image',
						'type'    => 'thumb_link',
						'desc'    => __( 'You can choose to link each thumbnail to the full size image, the image\'s attachment page, a custom URL, or you can choose to not link to anything.', 'foogallery' ),
					),
					array(
                        'id'      => 'lightbox',
                        'desc'    => __( 'Choose which lightbox you want to use in the gallery', 'foogallery' ),
                        'type'    => 'lightbox',
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

					// show this fields based on the box slider.
					// FADESLIDER
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

                    // TILESLIDER
                    
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

                    // cube slider
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
                    array(
                        'id' => 'perspective',
                        'title' => __( 'Perspective', 'foogallery' ),
                        'desc' => __( 'The perspective to apply to the parent viewport element containing the box.', 'foogallery' ),
                        'type' => 'number',
                        'default' => 1000,
                        'row_data' => array(
                            'data-foogallery-hidden'                   => true,
							'data-foogallery-show-when-field'          => 'effect',
							'data-foogallery-show-when-field-operator' => '===',
							'data-foogallery-show-when-field-value'    => 'fg-cube-slider',
                            'data-foogallery-change-selector' => 'input',
                            'data-foogallery-value-selector' => 'input',
                            'data-foogallery-preview' => 'shortcode',
                        ),
                    ),

                    // CarouselSlider
                    array(
                        'id' => 'timingFunction',
                        'title' => __( 'Timing Function', 'foogallery' ),
                        'desc' => __( ' The CSS transition timing function to use when animating slides into position.', 'foogallery' ),
                        'type' => 'select',
                        'default' => 'ease-in-out',
                        'choices' => array(
                            'ease-in-out' => __( 'ease-in-out', 'foogallery' ),
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

                    // end of conditional fields

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
                        'id' => 'autoScroll',
                        'title' => __( 'Auto-Scrolling', 'foogallery' ),
                        'desc' => __( 'Enables or disables automatic transitioning through the slides.', 'foogallery' ),
                        'type' => 'radio',
                        'default' => 'true',
                        'choices' => array(
                            'true' => __( 'True', 'foogallery' ),
                            'false' => __( 'False', 'foogallery' ),
                        ),
                        'row_data'=> array(
                            'data-foogallery-change-selector' => 'input',
                            'data-foogallery-value-selector' => 'input:checked',
                            'data-foogallery-preview' => 'shortcode'
                        )
                    ),
					array(
                        'id' => 'timeout',
                        'title' => __( 'Timeout', 'foogallery' ),
                        'desc' => __( ' Sets the time interval between slide transitions (for use with auto-scrolling).', 'foogallery' ),
                        'type' => 'number',
                        'default' => 5000,
                        'row_data' => array( 
                            'data-foogallery-change-selector' => 'input',
                            'data-foogallery-value-selector' => 'input',
                            'data-foogallery-preview' => 'shortcode',
                        ),
                    ),
					array(
                        'id' => 'pauseOnHover',
                        'title' => __( 'Pause On Hover', 'foogallery' ),
                        'desc' => __( 'Pause an auto-scrolling slider when the users mouse hovers over it. For use with autoScroll or a slider in play mode.', 'foogallery' ),
                        'type' => 'radio',
                        'default' => 'false',
                        'choices' => array(
                            'true' => __( 'True', 'foogallery' ),
                            'false' => __( 'false', 'foogallery' ),
                        ),
                        'row_data'=> array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input:checked',
							'data-foogallery-preview' => 'shortcode'
						)
                    ),
					array(
                        'id' => 'swipe',
                        'title' => __( 'swipe', 'foogallery' ),
                        'desc' => __( 'Enable swiping the box to navigate to the next or previous slide.', 'foogallery' ),
                        'type' => 'radio',
                        'default' => 'true',
                        'choices' => array(
                            'true' => __( 'True', 'foogallery' ),
                            'false' => __( 'false', 'foogallery' ),
                        ),
                        'row_data'=> array(
							'data-foogallery-change-selector' => 'input',
							'data-foogallery-value-selector' => 'input:checked',
							'data-foogallery-preview' => 'shortcode'
						)
                    ),
					array(
                        'id' => 'swipeTolerance',
                        'title' => __( 'swipe Tolerance', 'foogallery' ),
                        'desc' => __( 'The number of pixels between the pointer down and pointer up events during the swipe action that will trigger the transition.', 'foogallery' ),
                        'type' => 'number',
                        'default' => 30,
                        'row_data' => array(
                            'data-foogallery-change-selector' => 'input',
                            'data-foogallery-value-selector' => 'input',
                            'data-foogallery-preview' => 'shortcode',
                        ),
                    ),
                    // array(
                    //     'id'       => 'show_pagination',
                    //     'title'    => __( 'Show Pagination', 'foogallery' ),
                    //     'section'  => __( 'General', 'foogallery' ),
                    //     'default'  => '',
                    //     'type'     => 'radio',
                    //     'spacer'   => '<span class="spacer"></span>',
                    //     'choices' => array(
                    //         '' => __( 'Shown', 'foogallery' ),
                    //         'fg-boxslider-hide-pagination' => __( 'Hidden', 'foogallery' ),
                    //     ),
                    //     'row_data' => array(
                    //         'data-foogallery-change-selector' => 'input',
                    //         'data-foogallery-preview'         => 'shortcode'
                    //     )
                    // ),

                    array(
                        'id'      => 'language-help',
                        'desc'    => __( 'You can change the "Prev", "Next", "Play" and "pause" text used in the gallery from the settings page, under the Language tab.', 'foogallery' ),
						'section' => __( 'General', 'foogallery' ),
                        'type'    => 'help'
                    )
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
        function add_language_settings( $settings ) {

            $settings['settings'][] = array(
                'id'      => 'language_boxslider_prev_text',
                'title'   => __( 'Boxslider Prev Text', 'foogallery_user_uploads' ),
                'type'    => 'text',
                'default' => __( 'Prev', 'foogallery_user_uploads' ),
                'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
                'tab'     => 'language'
            );

            $settings['settings'][] = array(
                'id'      => 'language_boxslider_next_text',
                'title'   => __( 'Boxslider Next Text', 'foogallery_user_uploads' ),
                'type'    => 'text',
                'default' => __( 'Next', 'foogallery_user_uploads' ),
                'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
                'tab'     => 'language'
            );

            $settings['settings'][] = array(
                'id'      => 'language_boxslider_play_text',
                'title'   => __( 'Boxslider Play Text', 'foogallery_user_uploads' ),
                'type'    => 'text',
                'default' => __( 'Play', 'foogallery_user_uploads' ),
                'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
                'tab'     => 'language'
            );

            $settings['settings'][] = array(
                'id'      => 'language_boxslider_pause_text',
                'title'   => __( 'Boxslider Pause Text', 'foogallery_user_uploads' ),
                'type'    => 'text',
                'default' => __( 'Pause', 'foogallery_user_uploads' ),
                'section' => __( 'Box Slider Template', 'foogallery_user_uploads' ),
                'tab'     => 'language'
            );

            return $settings;
        }

        /**
		 * Builds thumb dimensions from arguments
		 *
		 * @param array $dimensions
		 * @param array $arguments
		 *
		 * @return mixed
		 */
		function build_thumbnail_dimensions_from_arguments( $dimensions, $arguments ) {
            if ( array_key_exists( 'thumbnail_size', $arguments ) ) {
                return array(
                    'height' => intval( $arguments['thumbnail_size']['height']),
                    'width' => intval( $arguments['thumbnail_size']['width']),
                    'crop' => $arguments['thumbnail_size']['crop']
                );
            }
            return null;
		}	

        /**
		 * Get the thumb dimensions arguments saved for the gallery for this gallery template
		 *
		 * @param array $dimensions
		 * @param FooGallery $foogallery
		 *
		 * @return mixed
		 */
		function get_thumbnail_dimensions( $dimensions, $foogallery ) {
			$dimensions = $foogallery->get_meta( 'boxslider_thumbnail_size', array(
				'width' => 640,
				'height' => 360,
                'crop' => true
			) );
            if ( !array_key_exists( 'crop', $dimensions ) ) {
                $dimensions['crop'] = true;
            }
			return $dimensions;
		}

        /**
         * Build up the arguments needed for rendering this gallery template
         *
         * @param $args
         * @return array
         */
        function build_gallery_template_arguments( $args ) {
            $args = foogallery_gallery_template_setting( 'thumbnail_size', array(
	            'width' => 640,
	            'height' => 360,
	            'crop' => true
            ) );
            if ( !array_key_exists( 'crop', $args ) ) {
                $args['crop'] = '1'; //we now force thumbs to be cropped by default
            }
            $args['link'] = foogallery_gallery_template_setting( 'thumbnail_link', 'image' );

            return $args;
        }

        /**
         * Add the required data options if needed
         *
         * @param $options
         * @param $gallery    FooGallery
         *
         * @param $attributes array
         *
         * @return array
         */
        public function add_data_options( $options, $gallery, $attributes ) {
			$autoScroll = foogallery_gallery_template_setting( 'autoScroll', 'true' ) === 'true';
			$effect = foogallery_gallery_template_setting( 'effect', 'fade' );
			$direction = foogallery_gallery_template_setting( 'direction', 'horizontal' );
			$perspective = foogallery_gallery_template_setting( 'perspective', 1000 );
			$speed = foogallery_gallery_template_setting( 'speed', 800 );
			$timeout = foogallery_gallery_template_setting( 'timeout', 5000 );
			$pauseOnHover = foogallery_gallery_template_setting( 'pauseOnHover', 'true' );
			$swipe = foogallery_gallery_template_setting( 'swipe', 'true' );
			$swipeTolerance = foogallery_gallery_template_setting( 'swipeTolerance', 30 );

			$options['template']['autoScroll'] = $autoScroll;			
			$options['template']['effect'] = $effect;
			$options['template']['direction'] = $direction;
			$options['template']['perspective'] = $perspective;
			$options['template']['speed'] = $speed;
			$options['template']['timeout'] = $timeout;
			$options['template']['pauseOnHover'] = $pauseOnHover;
			$options['template']['swipe'] = $swipe;
			$options['template']['swipeTolerance'] = $swipeTolerance;

            return $options;
        }

		/**
		 * Adds the classes onto the container
		 *
		 * @param $classes
		 * @param $foogallery
		 *
		 * @return array
		 */
		function append_classes( $classes, $foogallery ) {
            if ( isset( $foogallery ) && isset( $foogallery->gallery_template ) && $foogallery->gallery_template === self::TEMPLATE_ID ) {

                $position = foogallery_gallery_template_setting( 'caption_position', '' );

                if ( $position !== '' ) {
                    $classes[] = $position;
                }
            }

			return $classes;
		}
		
    }
}
