<?php

// Common includes.
require_once FOOGALLERY_PATH . 'includes/render-functions.php';
require_once FOOGALLERY_PATH . 'includes/class-foogallery.php';
require_once FOOGALLERY_PATH . 'includes/extensions/class-extension.php';
require_once FOOGALLERY_PATH . 'includes/extensions/class-extensions-api.php';
require_once FOOGALLERY_PATH . 'includes/extensions/class-extensions-loader.php';
require_once FOOGALLERY_PATH . 'includes/class-foogallery-widget.php';

// Include built-in thumbnail generation files.
require_once FOOGALLERY_PATH . 'includes/thumbs/includes.php';

// Include bundled extensions.
new FooPlugins\FooGallery\Extensions\Album\FooGallery_Albums_Extension();
new FooPlugins\FooGallery\Extensions\DefaultTemplates\FooGallery_Default_Templates_Extension; // Legacy!
new FooPlugins\FooGallery\Extensions\DemoContentGenerator\FooGallery_Demo_Content_Generator();

// load Template Loader files.
require_once FOOGALLERY_PATH . 'includes/public/class-foogallery-template-loader.php';

if ( is_admin() ) {

	// Only admin includes.
	require_once FOOGALLERY_PATH . 'includes/admin/class-admin.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-extensions.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-settings.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-editor.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metaboxes.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metabox-items.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metabox-fields.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metabox-settings.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-metabox-settings-helper.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-menu.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-columns.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-attachment-fields.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-admin-notices.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-datasources.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-pro-promotion.php';
	require_once FOOGALLERY_PATH . 'includes/admin/class-demo-content.php';
	
	// Admin gallery modal new
	require_once FOOGALLERY_PATH . 'includes/admin/class-gallery-attachment-modal.php';

} else {
	// Only front-end includes.
	require_once FOOGALLERY_PATH . 'includes/public/class-public.php';
	require_once FOOGALLERY_PATH . 'includes/public/class-css-load-optimizer.php';
	require_once FOOGALLERY_PATH . 'includes/public/class-admin-bar.php';
	require_once FOOGALLERY_PATH . 'includes/public/class-yoast-seo-sitemaps.php';
	require_once FOOGALLERY_PATH . 'includes/public/class-rank-math-seo-sitemaps.php';
	require_once FOOGALLERY_PATH . 'includes/public/class-aioseo-sitemaps.php';
}

require_once FOOGALLERY_PATH . 'includes/public/class-shortcodes.php';