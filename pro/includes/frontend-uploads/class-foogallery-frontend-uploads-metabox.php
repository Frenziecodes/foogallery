<?php

// Include the necessary file
require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metaboxes.php';
class FrontEnd_Upload_FooGallery_Admin_Gallery_MetaBoxes extends FooGallery_Admin_Gallery_MetaBoxes {

    private $gallery_id;

    public function __construct() {
        parent::__construct();
        $this->gallery_id = isset($_POST['gallery_id']) ? intval($_POST['gallery_id']) : null;
    }

    public function add_meta_boxes_to_gallery($post) {
        parent::add_meta_boxes_to_gallery($post);

        add_meta_box(
            'custom_metabox_id',
            __('Front End Upload', 'foogallery'),
            array($this, 'render_frontend_upload_metabox'),
            FOOGALLERY_CPT_GALLERY,
            'normal',
            'low'
        );
    }

    public function render_frontend_upload_metabox($post) {
        $gallery = $this->get_gallery($post);
        $shortcode = $gallery->shortcode();
    
        // Use preg_match to find the ID within the shortcode
        if (preg_match('/\[foogallery id="(\d+)"\]/', $shortcode, $matches)) {
            
            $gallery_id = $matches[1];
            ?>
			<p class="foogallery-shortcode">
                <input type="text" id="Upload_Form_copy_shortcode" size="<?php echo strlen( $shortcode ) + 2; ?>" value="<?php echo htmlspecialchars('[Upload_Form id="' . $gallery_id . '"]'); ?>" readonly="readonly" />
            </p>

			<p>
				<?php _e( 'Paste the above shortcode into a post or page to show the Image Upload Form.', 'foogallery' ); ?>
			</p>
			<script>
				jQuery(function($) {
					var shortcodeInput = document.querySelector('#Upload_Form_copy_shortcode');
					shortcodeInput.addEventListener('click', function () {
						try {
							// select the contents
							shortcodeInput.select();
							//copy the selection
							document.execCommand('copy');
							//show the copied message
							$('.foogallery-shortcode-message').remove();
							$(shortcodeInput).after('<p class="foogallery-shortcode-message"><?php _e( 'Shortcode copied to clipboard :)','foogallery' ); ?></p>');
						} catch(err) {
							console.log('Oops, unable to copy!');
						}
					}, false);
				});
			</script>
            <?php
        } else {
            // No ID found
            echo 'No ID found in the shortcode.';
        }
    }
    
    
    
}


$custom_foogallery_meta_boxes = new FrontEnd_Upload_FooGallery_Admin_Gallery_MetaBoxes();
