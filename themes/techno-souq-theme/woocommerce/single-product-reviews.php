<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
    return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="woocommerce-Reviews-title">
            <?php
            $count = $product->get_review_count();
            if ($count && wc_review_ratings_enabled()) {
                /* translators: 1: reviews count 2: product name */
                $reviews_title = sprintf(esc_html(_n('%1$s مراجعة لـ %2$s', '%1$s مراجعات لـ %2$s', $count, 'techno-souq-theme')), esc_html($count), '<span>' . get_the_title() . '</span>');
                echo apply_filters('woocommerce_reviews_title', $reviews_title, $count, $product); // WPCS: XSS ok.
            } else {
                esc_html_e('المراجعات', 'techno-souq-theme');
            }
            ?>
        </h2>

        <?php if (have_comments()) : ?>
            <ol class="commentlist">
                <?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
            </ol>

            <?php
            if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                echo '<nav class="woocommerce-pagination">';
                paginate_comments_links(
                    apply_filters(
                        'woocommerce_comment_pagination_args',
                        array(
                            'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                            'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                            'type'      => 'list',
                        )
                    )
                );
                echo '</nav>';
            endif;
            ?>
        <?php else : ?>
            <p class="woocommerce-noreviews"><?php esc_html_e('لا توجد مراجعات بعد.', 'techno-souq-theme'); ?></p>
        <?php endif; ?>
    </div>

    <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
        <div id="review_form_wrapper">
            <div id="review_form">
                <?php
                $commenter    = wp_get_current_commenter();
                $comment_form = array(
                    /* translators: %s is product title */
                    'title_reply'         => have_comments() ? esc_html__('أضف مراجعة', 'techno-souq-theme') : sprintf(esc_html__('كن أول من يراجع &ldquo;%s&rdquo;', 'techno-souq-theme'), get_the_title()),
                    /* translators: %s is product title */
                    'title_reply_to'      => esc_html__('اترك رداً على %s', 'techno-souq-theme'),
                    'title_reply_before'  => '<span id="reply-title" class="comment-reply-title" role="heading" aria-level="3">',
                    'title_reply_after'   => '</span>',
                    'comment_notes_after' => '',
                    'label_submit'        => esc_html__('شارك', 'techno-souq-theme'),
                    'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s y-c-btn y-c-btn-primary y-c-btn-full" data-y="review-submit-btn">%4$s</button>',
                    'submit_field'        => '<p class="form-submit">%1$s %2$s</p>',
                    'logged_in_as'        => '',
                    'comment_field'       => '',
                );

                $name_email_required = (bool) get_option('require_name_email', 1);
                $fields              = array(
                    'author' => array(
                        'label'        => __('الاسم', 'techno-souq-theme'),
                        'type'         => 'text',
                        'value'        => $commenter['comment_author'],
                        'required'     => $name_email_required,
                        'autocomplete' => 'name',
                    ),
                    'email'  => array(
                        'label'        => __('البريد الإلكتروني', 'techno-souq-theme'),
                        'type'         => 'email',
                        'value'        => $commenter['comment_author_email'],
                        'required'     => $name_email_required,
                        'autocomplete' => 'email',
                    ),
                );

                $comment_form['fields'] = array();

                foreach ($fields as $key => $field) {
                    $field_html  = '<p class="comment-form-' . esc_attr($key) . '">';
                    $field_html .= '<label for="' . esc_attr($key) . '">' . esc_html($field['label']);

                    if ($field['required']) {
                        $field_html .= '&nbsp;<span class="required">*</span>';
                    }

                    $field_html .= '</label><input id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" type="' . esc_attr($field['type']) . '" autocomplete="' . esc_attr($field['autocomplete']) . '" value="' . esc_attr($field['value']) . '" size="30" ' . ($field['required'] ? 'required' : '') . ' /></p>';

                    $comment_form['fields'][$key] = $field_html;
                }

                $account_page_url = wc_get_page_permalink('myaccount');
                if ($account_page_url) {
                    /* translators: %s opening and closing link tags respectively */
                    $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf(esc_html__('يجب أن تكون %1$sمسجلاً%2$s لنشر مراجعة.', 'techno-souq-theme'), '<a href="' . esc_url($account_page_url) . '">', '</a>') . '</p>';
                }

                if (wc_review_ratings_enabled()) {
                    $comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating" id="comment-form-rating-label">' . esc_html__('تقييمك', 'techno-souq-theme') . (wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '') . '</label><select name="rating" id="rating" required>
                        <option value="">' . esc_html__('قيم...', 'techno-souq-theme') . '</option>
                        <option value="5">' . esc_html__('ممتاز', 'techno-souq-theme') . '</option>
                        <option value="4">' . esc_html__('جيد', 'techno-souq-theme') . '</option>
                        <option value="3">' . esc_html__('متوسط', 'techno-souq-theme') . '</option>
                        <option value="2">' . esc_html__('ليس سيئاً', 'techno-souq-theme') . '</option>
                        <option value="1">' . esc_html__('سيء جداً', 'techno-souq-theme') . '</option>
                    </select></div>';
                }

                $comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__('مراجعتك', 'techno-souq-theme') . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8" required></textarea></p>';

                comment_form(apply_filters('woocommerce_product_review_comment_form_args', $comment_form));
                ?>
            </div>
        </div>
    <?php else : ?>
        <p class="woocommerce-verification-required"><?php esc_html_e('فقط العملاء المسجلون الذين اشتروا هذا المنتج يمكنهم ترك مراجعة.', 'techno-souq-theme'); ?></p>
    <?php endif; ?>

    <div class="clear"></div>
</div>
