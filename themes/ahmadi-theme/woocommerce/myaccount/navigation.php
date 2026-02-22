<?php
defined('ABSPATH') || exit;

$items = wc_get_account_menu_items();
?>
<nav class="woocommerce-MyAccount-navigation y-c-account-nav">
    <div class="y-c-Profile-menu">
        <?php foreach ($items as $endpoint => $label) : ?>
            <?php
            $url = wc_get_account_endpoint_url($endpoint);
            $classes = wc_get_account_menu_item_classes($endpoint);
            $is_logout = $endpoint === 'customer-logout';
            ?>
            <a href="<?php echo esc_url($url); ?>"
               class="y-c-account-link <?php echo esc_attr($classes); ?>"
               <?php echo $is_logout ? 'aria-label="تسجيل خروج" title="تسجيل خروج"' : ''; ?>>
                <p>
                    <?php if ($is_logout) : ?>
                        <i class="fa-solid fa-right-from-bracket y-c-logout-icon" aria-hidden="true"></i>
                    <?php else : ?>
                        <?php echo esc_html($label); ?>
                    <?php endif; ?>
                </p>
            </a>
        <?php endforeach; ?>
    </div>
</nav>
