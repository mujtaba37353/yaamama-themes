<?php
/*
Template Name: About Us
*/

get_header();

$about_post = ahmadi_theme_get_latest_post('ahmadi_about_content');
$about_title = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_title', true) : '';
$about_text = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_text', true) : '';
$trust_title = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_trust_title', true) : '';
$trust_items_raw = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_trust_items', true) : '';
$counter_1_number = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_1_number', true) : '';
$counter_1_label = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_1_label', true) : '';
$counter_2_number = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_2_number', true) : '';
$counter_2_label = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_2_label', true) : '';
$counter_3_number = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_3_number', true) : '';
$counter_3_label = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_3_label', true) : '';
$counter_4_number = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_4_number', true) : '';
$counter_4_label = $about_post ? get_post_meta($about_post->ID, 'ahmadi_about_counter_4_label', true) : '';

if ($about_title === '') {
    $about_title = 'من نحن';
}
if ($about_text === '') {
    $about_text = 'لقد بنينا سمعتنا من خلال الالتزام بالجودة، والاهتمام بأدق التفاصيل، وتقديم تجربة تسوّق إلكتروني سلسة وآمنة تلبي تطلعات عملائنا. تميزنا لا يأتي من فراغ، بل هو ثمرة رؤية واضحة وفريق عمل محترف، ودعم متواصل لكل عميل يسعى للتميز في عالم التجارة الرقمية.';
}
if ($trust_title === '') {
    $trust_title = 'لماذا يثق بنا عملاؤنا في السوق الإلكتروني؟';
}
$trust_items = array_filter(array_map('trim', explode("\n", (string) $trust_items_raw)));
if (!$trust_items) {
    $trust_items = [
        'سرعة تنفيذ الطلبات والتوصيل في الوقت المحدد',
        'دعم فني دائم وسريع الاستجابة',
        'منتجات عالية الجودة بأسعار منافسة',
        'رضا العملاء هو أولويتنا القصوى',
        'واجهة استخدام سهلة وآمنة لجميع المستخدمين',
    ];
}
if ($counter_1_number === '') {
    $counter_1_number = '+10';
}
if ($counter_1_label === '') {
    $counter_1_label = 'سنوات الخبرة';
}
if ($counter_2_number === '') {
    $counter_2_number = '+50';
}
if ($counter_2_label === '') {
    $counter_2_label = 'عملاء سعداء';
}
if ($counter_3_number === '') {
    $counter_3_number = '+15';
}
if ($counter_3_label === '') {
    $counter_3_label = 'عملاء التوصيل';
}
if ($counter_4_number === '') {
    $counter_4_number = '+80';
}
if ($counter_4_label === '') {
    $counter_4_label = 'عملاء الجملة';
}
?>

<section class="y-c-hero-About-us">
    <div class="y-c-hero-container">
        <div class="y-c-hero-content-About-us">
            <div class="y-c-hero-text-About-us">
                <h1><?php echo esc_html($about_title); ?></h1>
                <p><?php echo esc_html($about_text); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="y-c-container">
    <div class="y-c-trust-section">
        <h2 class="y-c-title"><?php echo esc_html($trust_title); ?></h2>

        <div class="y-c-qualifications">
            <?php foreach ($trust_items as $item) : ?>
                <div class="y-c-qual-item">
                    <i class="fa-solid fa-check"></i>
                    <p><?php echo esc_html($item); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="y-c-counters-background">
    <div class="y-c-counters-section">
        <div class="y-c-counter-item">
            <div class="y-c-counter-number"><?php echo esc_html($counter_1_number); ?></div>
            <div class="y-c-counter-text"><?php echo esc_html($counter_1_label); ?></div>
        </div>
        <div class="y-c-counter-item">
            <div class="y-c-counter-number"><?php echo esc_html($counter_2_number); ?></div>
            <div class="y-c-counter-text"><?php echo esc_html($counter_2_label); ?></div>
        </div>
        <div class="y-c-counter-item">
            <div class="y-c-counter-number"><?php echo esc_html($counter_3_number); ?></div>
            <div class="y-c-counter-text"><?php echo esc_html($counter_3_label); ?></div>
        </div>
        <div class="y-c-counter-item">
            <div class="y-c-counter-number"><?php echo esc_html($counter_4_number); ?></div>
            <div class="y-c-counter-text"><?php echo esc_html($counter_4_label); ?></div>
        </div>
    </div>
</section>

<?php
get_footer();
