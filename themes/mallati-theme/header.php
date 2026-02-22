<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part('parts/header'); ?>
<?php if (function_exists('woocommerce_output_all_notices')) : ?>
<div class="container y-u-max-w-1200 y-woo-notices">
  <?php woocommerce_output_all_notices(); ?>
</div>
<?php endif; ?>
