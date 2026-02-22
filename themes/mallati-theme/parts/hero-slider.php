<?php
$assets = get_template_directory_uri() . '/mallati/assets';
$hero_slides = get_option('mallati_hero_slides', [0, 0, 0]);
$hero_slides = array_pad((array) $hero_slides, 3, 0);
$default_img = $assets . '/hero1.png';
?>
<div class="hero-slider-container">
  <div class="hero-slider">
    <?php foreach ($hero_slides as $i => $slide_id) :
      $active = $i === 0 ? ' active' : '';
      $img_url = $slide_id ? wp_get_attachment_image_url($slide_id, 'full') : $default_img;
      ?>
      <div class="hero-slide<?php echo $active; ?>">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(sprintf(__('الشريحة %d', 'mallati-theme'), $i + 1)); ?>" class="y-u-w-full" onerror="this.src='<?php echo esc_url($default_img); ?>'" />
      </div>
    <?php endforeach; ?>
  </div>
  <div class="hero-slider-indicators">
    <?php foreach ($hero_slides as $i => $slide) : ?>
      <button class="hero-indicator<?php echo $i === 0 ? ' active' : ''; ?>" data-slide="<?php echo $i; ?>" aria-label="<?php echo esc_attr(sprintf(__('الشريحة %d', 'mallati-theme'), $i + 1)); ?>"></button>
    <?php endforeach; ?>
  </div>
</div>
