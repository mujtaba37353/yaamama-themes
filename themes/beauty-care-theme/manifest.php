<?php
/**
 * Dynamic PWA manifest - outputs JSON with correct URLs for subdirectory installs.
 * Access: ?beauty_care_manifest=1 or via rewrite.
 */
if ( ! defined( 'ABSPATH' ) ) {
	return;
}
header( 'Content-Type: application/manifest+json; charset=' . get_bloginfo( 'charset' ) );
$home      = untrailingslashit( home_url( '/' ) );
$theme_uri = get_template_directory_uri();
$icon_uri  = $theme_uri . '/beauty-care/assets/icon.png';

$manifest = array(
	'name'             => 'بيوتي كير',
	'short_name'       => 'بيوتي كير',
	'description'      => 'بيوتي كير - منتجات عناية بالبشرة والشعر',
	'dir'              => 'rtl',
	'lang'             => 'ar',
	'display'          => 'standalone',
	'start_url'        => $home . '/',
	'scope'            => $home . '/',
	'background_color' => '#F3F6CD',
	'theme_color'      => '#D50B8B',
	'icons'            => array(
		array(
			'src'   => $icon_uri,
			'sizes' => '192x192',
			'type'  => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'   => $icon_uri,
			'sizes' => '512x512',
			'type'  => 'image/png',
			'purpose' => 'any maskable',
		),
	),
	'prefer_related_applications' => false,
	'categories'       => array( 'العناية بالبشرة', 'العناية بالشعر', 'التجميل' ),
);

echo wp_json_encode( $manifest, JSON_UNESCAPED_UNICODE );
