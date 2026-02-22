<?php
get_header();
$settings = function_exists('mykitchen_get_policy_settings') ? mykitchen_get_policy_settings() : array();
$policy = $settings['refund'] ?? array();
$title = $policy['title'] ?? get_the_title();
$content = $policy['content'] ?? '';
$image_id = (int) ($policy['image_id'] ?? 0);
$image_url = $policy['image'] ?? '';
if ($image_id) {
  $image_url = wp_get_attachment_url($image_id) ?: $image_url;
}
?>

<header data-y="design-header"></header>

<main data-y="main">
  <div class="main-container y-u-my-10">
    <div data-y="breadcrumb"></div>
    <div class="policy-container">
      <div>
        <h1><?php echo esc_html($title); ?></h1>
        <?php if ($image_url) : ?>
          <p><img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width:100%;height:auto;"></p>
        <?php endif; ?>
        <?php echo wpautop(wp_kses_post($content)); ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
