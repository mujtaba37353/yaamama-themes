<?php
defined('ABSPATH') || exit;
if (!isset($input_id)) $input_id = uniqid('quantity_');
$label = !empty($product_name) ? sprintf(esc_html__('%s quantity', 'woocommerce'), wp_strip_all_tags($product_name)) : esc_html__('Quantity', 'woocommerce');
$readonly = isset($readonly) ? $readonly : false;
$type = isset($type) ? $type : 'number';
$classes = isset($classes) ? $classes : array('input-text', 'qty', 'text');
$min_value = isset($min_value) ? $min_value : 1;
$max_value = isset($max_value) ? $max_value : '';
$step = isset($step) ? $step : 1;
?>
<div class="quantity pd-qty">
  <button type="button" class="qty-btn" aria-label="<?php esc_attr_e('تقليل', 'mallati-theme'); ?>" data-qty-down><?php echo esc_html('−'); ?></button>
  <input type="<?php echo esc_attr($type); ?>" <?php echo $readonly ? 'readonly' : ''; ?> id="<?php echo esc_attr($input_id); ?>" class="qty-input <?php echo esc_attr(implode(' ', (array) $classes)); ?>" name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>" min="<?php echo esc_attr($min_value); ?>" <?php if (0 < $max_value) : ?>max="<?php echo esc_attr($max_value); ?>"<?php endif; ?> step="<?php echo esc_attr($step); ?>" aria-label="<?php echo esc_attr($label); ?>" />
  <button type="button" class="qty-btn" aria-label="<?php esc_attr_e('زيادة', 'mallati-theme'); ?>" data-qty-up>+</button>
</div>
