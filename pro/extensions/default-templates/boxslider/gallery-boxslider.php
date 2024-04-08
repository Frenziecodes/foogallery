<?php
/**
 * FooGallery BoxSlider PRO gallery template.
 */
global $current_foogallery;
global $current_foogallery_arguments;

$text_prev_default  = foogallery_get_setting( 'language_boxslider_prev_text', __( 'Prev', 'foogallery' ) );
$text_prev          = foogallery_gallery_template_setting( 'text-prev', $text_prev_default );

$text_next_default  = foogallery_get_setting( 'language_boxslider_next_text', __( 'Next', 'foogallery' ) );
$text_next          = foogallery_gallery_template_setting( 'text-next', $text_next_default );

$text_play_default  = foogallery_get_setting( 'language_boxslider_play_text', __( 'Play', 'foogallery' ) );
$text_play          = foogallery_gallery_template_setting( 'text-play', $text_play_default );

$text_pause_default = foogallery_get_setting( 'language_boxslider_pause_text', __( 'Pause', 'foogallery' ) );
$text_pause         = foogallery_gallery_template_setting( 'text-pause', $text_pause_default );

// common options.
$slider          = foogallery_gallery_template_setting( 'effect', '' );
$speed           = foogallery_gallery_template_setting( 'speed', '' );
$auto_scroll     = foogallery_gallery_template_setting( 'autoScroll', '' );
$timeout         = foogallery_gallery_template_setting( 'timeout', '' );
$pause_on_hover  = foogallery_gallery_template_setting( 'pauseOnHover', '' );
$swipe           = foogallery_gallery_template_setting( 'swipe', '' );

// end of common options.

$timing_function = foogallery_gallery_template_setting( 'timingFunction', '' ); // fade slider.
$tile_effect     = foogallery_gallery_template_setting( 'tileEffect', '' ); // tile slider.
$rows            = foogallery_gallery_template_setting( 'rows', '' ); // tile slider.
$row_offset      = foogallery_gallery_template_setting( 'rowOffset', '' ); // tile slider.
$direction       = foogallery_gallery_template_setting( 'direction', '' ); // cube slider.
$cover           = foogallery_gallery_template_setting( 'cover', '' ); // Carousel Slider.

$lightbox = foogallery_gallery_template_setting_lightbox();

$foogallery_default_classes    = foogallery_build_class_attribute_safe( $current_foogallery, 'foogallery-lightbox-' . $lightbox );
$foogallery_default_attributes = foogallery_build_container_attributes_safe( $current_foogallery, array( 'class' => $foogallery_default_classes ) );

?>
<div <?php echo $foogallery_default_attributes; ?>>

	<div class="fg-bx">     

		<section class="fg-template-boxslider <?php echo esc_attr( $slider ); ?>">

			<section id="fg-template-boxslider-inner">    
				<?php
				foreach ( foogallery_current_gallery_attachments_for_rendering() as $attachment ) {
					$image_src    = wp_get_attachment_url( $attachment->ID );
					$image_srcset = wp_get_attachment_image_srcset( $attachment->ID, 'full' );
					?>
					<figure class="slide">
						<picture class="fg-image-wrap">
							<source srcset="<?php echo esc_attr( $image_srcset ); ?>" />
							<img src="<?php echo esc_attr( $image_src ); ?>" alt="<?php echo esc_attr( $attachment->alt ); ?>" class="fg-boxslider-template-image" />
						</picture>
					</figure>
				<?php } ?>
			</section>

			
		</section>

		<?php
		if ( 'true' === $swipe ) {
			?>
			<section style="margin-top: 20px;">
				<div class="fg-boxslider-slider-controls">
					<div class="fg-boxslider-slider-controls-left">
						<button id="fg-temp-boxslider-prev-slide" aria-controls="foogallery-box-slider-template"><?php echo esc_html( $text_prev ); ?></button>
						<button id="fg-temp-boxslider-next-slide" aria-controls="foogallery-box-slider-template"><?php echo esc_html( $text_next ); ?></button>
					</div>
					<div class="fg-boxslider-slider-controls-right">
						<button id="fg-temp-boxslider-play-slide" aria-controls="foogallery-box-slider-template"><?php echo esc_html( $text_play ); ?></button>
						<button id="fg-temp-boxslider-pause-slide" aria-controls="foogallery-box-slider-template"><?php echo esc_html( $text_pause ); ?></button>
					</div>
				</div>
			</section>
			<?php
		}
		?>
	
	</div>
</div>

<script type="module">

	import {
		BoxSlider,
		FadeSlider,
		TileSlider,
		CubeSlider,
		CarouselSlider,
	} from 'https://cdn.jsdelivr.net/npm/@boxslider/slider/+esm'


	// Common options for both FadeSlider and TileSlider
	const commonOptions = {
		speed: <?php echo intval( $speed ); ?>,
		autoScroll: <?php echo $auto_scroll === 'true' ? 'true' : 'false'; ?>,
		timeout: <?php echo intval( $timeout ); ?>,
		pauseOnHover: <?php echo $pause_on_hover === 'true' ? 'true' : 'false'; ?>,
		swipe: <?php echo $swipe === 'true' ? 'true' : 'false'; ?>,
	};

	// Options specific to FadeSlider
	const fadeOptions = Object.assign({}, commonOptions, {
		timingFunction: "<?php echo $timing_function; ?>",
	});

	// Specific options for TileSlider
	const tileOptions = Object.assign({}, commonOptions, {
		tileEffect: "<?php echo $tile_effect; ?>",
		rows: <?php echo intval( $rows); ?>,
		rowOffset: <?php echo intval( $row_offset); ?>,
	});

	// Specific options for CubeSlider
	const cubeOptions = Object.assign({}, commonOptions, {
		direction: "<?php echo $direction; ?>",
	});

	// Specific options for CarouselSlider
	const carouselOptions = Object.assign({}, commonOptions, {
		cover: <?php echo $cover === 'true' ? 'true' : 'false'; ?>,
	});

	// Get the slider effect.
	const sliderEffect = "<?php echo $slider; ?>";

	let slider;
	switch (sliderEffect) {
		case "fg-fade-slider":
		slider = new BoxSlider(document.getElementById('fg-template-boxslider-inner'), new FadeSlider(), fadeOptions);
		break;
		case "fg-tile-slider":
		slider = new BoxSlider(document.getElementById('fg-template-boxslider-inner'), new TileSlider(), tileOptions);
		break;
		case "fg-cube-slider":
		slider = new BoxSlider(document.getElementById('fg-template-boxslider-inner'), new CubeSlider(), cubeOptions);
		break;
		case "fg-carousel-slider":
		slider = new BoxSlider(document.getElementById('fg-template-boxslider-inner'), new CarouselSlider(), carouselOptions);
		break;
		default:
		// Default to TileSlider for any other case
		slider = new BoxSlider(document.getElementById('fg-template-boxslider-inner'), new TileSlider(), tileOptions);
		break;
	}

	// Get the slider controls
	const prevButton = document.getElementById('fg-temp-boxslider-prev-slide');
	const nextButton = document.getElementById('fg-temp-boxslider-next-slide');
	const playButton = document.getElementById('fg-temp-boxslider-play-slide');
	const pauseButton = document.getElementById('fg-temp-boxslider-pause-slide'); 

	// Add event listeners to the buttons
	prevButton.addEventListener('click', () => slider.prev());
	nextButton.addEventListener('click', () => slider.next());
	playButton.addEventListener('click', () => slider.play());
	pauseButton.addEventListener('click', () => slider.pause());

</script>

<style>
	
	.fg-boxslider{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	.fg-bx{
		border: solid #333;
	}
	.fg-template-boxslider{
		position: relative;
		height: 400px;
		width: 600px;
	}
	#fg-template-boxslider-inner {
		font-family: 'Open Sans', 'Helvetica Neue', Arial, sans-serif;
		border-bottom: solid #333;
		width: 100%;
		height: 100%;
		transform-style: preserve-3d;
	}
	/* tile slider effect */

	.fg-tile-slider #fg-template-boxslider-inner {
		transform-style: preserve-3d;
	}

	.fg-boxslider-template-image {
		height: 400px;
		width: 600px;
	}

	.fg-boxslider.fg-left {
		text-align: left;
	}

	.fg-boxslider.fg-center {
		text-align: center;
	}
	.fg-boxslider.fg-right {
		text-align: right;
	}

	.fg-boxslider-slider-controls, .fg-boxslider-slider-controls-right, .fg-boxslider-slider-controls-left {
		display: flex;
		flex-direction: row;
	}
	.fg-boxslider-slider-controls{
		justify-content: space-between;
	}

	/* Drop Shadows */
	.foogallery.fg-bx.fg-light.fg-shadow-outline,
	.foogallery.fg-bx.fg-dark.fg-shadow-outline,
	.foogallery.fg-bx.fg-light.fg-shadow-small,
	.foogallery.fg-bx.fg-dark.fg-shadow-small,
	.foogallery.fg-bx.fg-light.fg-shadow-medium,
	.foogallery.fg-bx.fg-dark.fg-shadow-medium,
	.foogallery.fg-bx.fg-light.fg-shadow-large,
	.foogallery.fg-bx.fg-dark.fg-shadow-large {
		box-shadow: none;
	}

	.foogallery.fg-bx.fg-light.fg-shadow-outline .fg-template-boxslider {
		box-shadow: 0 0 0 1px #ddd;
	}
	.foogallery.fg-bx.fg-dark.fg-shadow-outline .fg-template-boxslider {
		box-shadow: 0 0 0 1px #222;
	}
	.foogallery.fg-bx.fg-light.fg-shadow-small .fg-template-boxslider,
	.foogallery.fg-bx.fg-dark.fg-shadow-small .fg-template-boxslider {
		box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.5);
	}
	.foogallery.fg-bx.fg-light.fg-shadow-medium .fg-template-boxslider,
	.foogallery.fg-bx.fg-dark.fg-shadow-medium .fg-template-boxslider {
		box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.5);
	}
	.foogallery.fg-bx.fg-light.fg-shadow-large .fg-template-boxslider,
	.foogallery.fg-bx.fg-dark.fg-shadow-large .fg-template-boxslider {
		box-shadow: 0 1px 16px 0 rgba(0, 0, 0, 0.5);
	}

	/* Rounded corners */
	.foogallery.fg-bx.fg-round-small,
	.foogallery.fg-bx.fg-round-small .fg-template-boxslider {
		border-radius: 5px;
	}
	.foogallery.fg-bx.fg-round-small,
	.foogallery.fg-bx.fg-round-small {
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}
	.foogallery.fg-bx.fg-round-small .fg-boxslider-slider-controls  button {
		border-radius: 3px;
	}

	.foogallery.fg-bx.fg-border-thin.fg-round-small,
	.foogallery.fg-bx.fg-border-thin.fg-round-small,
	.foogallery.fg-bx.fg-border-thin.fg-round-small .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-medium.fg-round-small,
	.foogallery.fg-bx.fg-border-medium.fg-round-small,
	.foogallery.fg-bx.fg-border-medium.fg-round-small .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thick.fg-round-small,
	.foogallery.fg-bx.fg-border-thick.fg-round-small,
	.foogallery.fg-bx.fg-border-thick.fg-round-small .fg-boxslider-slider-controls  button {
		border-radius: 3px;
	}

	.foogallery.fg-bx.fg-round-medium,
	.foogallery.fg-bx.fg-round-medium,
	.foogallery.fg-bx.fg-round-medium .fg-template-boxslider {
		border-radius: 10px;
	}
	.foogallery.fg-bx.fg-round-medium,
	.foogallery.fg-bx.fg-round-medium {
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}
	.foogallery.fg-bx.fg-round-medium .fg-boxslider-slider-controls  button {
		border-radius: 5px;
	}
	.foogallery.fg-bx.fg-border-thin.fg-round-medium,
	.foogallery.fg-bx.fg-border-thin.fg-round-medium,
	.foogallery.fg-bx.fg-border-thin.fg-round-medium .fg-boxslider-slider-controls  button{
		border-radius: 5px;
	}
	.foogallery.fg-bx.fg-border-medium.fg-round-medium,
	.foogallery.fg-bx.fg-border-medium.fg-round-medium,
	.foogallery.fg-bx.fg-border-medium.fg-round-medium .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thick.fg-round-medium,
	.foogallery.fg-bx.fg-border-thick.fg-round-medium,
	.foogallery.fg-bx.fg-border-thick.fg-round-medium .fg-boxslider-slider-controls  button {
		border-radius: 3px;
	}

	.foogallery.fg-bx.fg-round-large,
	.foogallery.fg-bx.fg-round-large,
	.foogallery.fg-bx.fg-round-large .fg-template-boxslider {
		border-radius: 15px;
	}
	.foogallery.fg-bx.fg-round-large,
	.foogallery.fg-bx.fg-round-large {
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}
	.foogallery.fg-bx.fg-round-large .fg-boxslider-slider-controls  button {
		border-radius: 11px;
	}
	.foogallery.fg-bx.fg-border-thin.fg-round-large,
	.foogallery.fg-bx.fg-border-thin.fg-round-large,
	.foogallery.fg-bx.fg-border-thin.fg-round-large .fg-boxslider-slider-controls  button {
		border-radius: 11px;
	}

	.foogallery.fg-bx.fg-border-medium.fg-round-large,
	.foogallery.fg-bx.fg-border-medium.fg-round-large,
	.foogallery.fg-bx.fg-border-medium.fg-round-large .fg-boxslider-slider-controls  button {
		border-radius: 5px;
	}

	.foogallery.fg-bx.fg-border-thick.fg-round-large,
	.foogallery.fg-bx.fg-border-thick.fg-round-large,
	.foogallery.fg-bx.fg-border-thick.fg-round-large .fg-boxslider-slider-controls  button {
		border-radius: 3px;
	}

	.foogallery.fg-bx.fg-round-full .fg-template-boxslider,
	.foogallery.fg-bx.fg-round-full .fg-boxslider-slider-controls  button {
		border-radius: 50%;
	}

	/* Border Size */
	.foogallery.fg-bx .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thin #fg-template-boxslider-inner {
		border-width: 2px;
	}
	.foogallery.fg-bx.fg-border-medium .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-medium #fg-template-boxslider-inner {
		border-width: 10px;
	}
	.foogallery.fg-bx.fg-border-thick .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thick #fg-template-boxslider-inner {
		border-width: 16px;
	}
	.foogallery.fg-bx .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thin .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-medium .fg-boxslider-slider-controls  button,
	.foogallery.fg-bx.fg-border-thick .fg-boxslider-slider-controls  button {
		border-top-width: 1px;
	}

	/* Captions */ 

	.fg-bx.fg-caption-always .fg-caption {
		padding: 0;
		border: none;
	}
	.fg-bx.fg-caption-always .fg-caption-title {
		padding: 10px 10px 10px 10px;
	}
	.fg-bx.fg-caption-always .fg-caption-desc {
		padding: 10px 10px 10px 10px;
	}
	.fg-bx.fg-caption-always .fg-caption-title+.fg-caption-desc {
		padding: 0 10px 10px 10px;
	}

	/* light theme(default) */
	.fg-light .fg-template-boxslider {
		background-color: #fff;
		color: #333;
		border: 1px solid #333;
	}
	.fg-light .fg-boxslider-slider-controls  button {
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		background-color: transparent;
		border: solid #333;
		height: 30px;
		color: #333;
		cursor: pointer;
		margin: 2px;
		transition: all 0.3s ease;
	}
	.fg-light .fg-boxslider-slider-controls  button:hover {
		background-color: #333;
		color: #fff;
	}

	/* dark theme */
	.fg-dark .fg-template-boxslider {
		background-color: #333;
		color: #FFF;
		border: solid #FFF;
	}
	.fg-dark .fg-boxslider-slider-controls  button {
		display: flex;
		justify-content: center;
		align-items: center;
		background-color: #333;
		border: solid #fff;
		height: 30px;
		min-width: 60px;
		cursor: pointer;
		margin: 2px;
		transition: all 0.3s ease;
		box-shadow: inset 0 0 0 1px #222;
	}
	.fg-dark .fg-boxslider-slider-controls  button:hover {
		background-color: #444;
	}

</style>
