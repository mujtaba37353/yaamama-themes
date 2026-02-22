<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?> data-current-page="<?php echo esc_attr(mykitchen_current_page()); ?>">
    <?php wp_body_open(); ?>
    <div data-y="nav"></div>
