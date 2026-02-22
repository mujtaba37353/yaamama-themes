<?php
/**
 * Header template — doctype, html, head, body start, navbar
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?php echo esc_url( beauty_time_asset( 'assets/icon.png' ) ); ?>" type="image/png">
	<link rel="manifest" href="<?php echo esc_url( beauty_time_asset( 'templates/manifest.json' ) ); ?>">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/header' ); ?>
