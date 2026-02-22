<?php
/**
 * The main template file
 *
 * @package MyClinic
 */

get_header();

// Define arrow icon for use throughout the page
$arrow_icon = get_template_directory_uri() . '/assets/images/bottom-arrow.svg';
?>

<main>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="cards">
            <?php for ($i = 1; $i <= 4; $i++): 
                // Get from options, fallback to theme_mod for backward compatibility, then default
                $hero_image = get_option('hero_card' . $i . '_image') ?: get_theme_mod('hero_card' . $i . '_image', get_template_directory_uri() . '/assets/images/hero' . $i . '.jpg');
                $hero_title = get_option('hero_card' . $i . '_title') ?: get_theme_mod('hero_card' . $i . '_title', 'دورك الآن مضمون');
                $hero_text = get_option('hero_card' . $i . '_text') ?: get_theme_mod('hero_card' . $i . '_text', 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل الأطباء');
            ?>
            <div class="card">
                <?php if ($hero_image): ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                <?php endif; ?>
                <div class="content">
                    <h2><?php echo esc_html($hero_title); ?></h2>
                    <p><?php echo esc_html($hero_text); ?></p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <div class="lists">
            <h2>احجز جلستك الآن</h2>
            <div class="bottom">
                <!-- Search input only - specialty filter removed -->
                <div class="list">
                    <input type="text" name="doctor" id="doctor-search" placeholder="اسم الطبيب">
                </div>
                <button class="btn main-button fw invert fh" id="search-btn">
                    ابحث 
                    <?php
                    $search_icon = get_template_directory_uri() . '/assets/images/search.svg';
                    if (file_exists(get_template_directory() . '/assets/images/search.svg')) {
                        echo '<img src="' . esc_url($search_icon) . '" alt="أيقونة البحث">';
                    }
                    ?>
                </button>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="way-section">
        <div class="container y-u-max-w-1200">
            <div class="header">
                <h2><?php 
                    $why_title = get_option('why_section_title') ?: get_theme_mod('why_section_title', 'لماذا MY CLINIC الاختيار الأمثل ؟');
                    echo esc_html($why_title); 
                ?></h2>
            </div>
            <div class="cards">
                <?php for ($i = 1; $i <= 4; $i++): 
                    $default_texts = array(
                        1 => 'حجز سريع وسهل',
                        2 => 'تذكير بالمواعيد عبر الرسائل',
                        3 => 'تذكير بالمواعيد عبر الرسائل',
                        4 => 'تقييمات حقيقية من المرضى'
                    );
                    $way_icon = get_option('why_card' . $i . '_icon') ?: get_theme_mod('why_card' . $i . '_icon', get_template_directory_uri() . '/assets/images/way' . $i . '.png');
                    $way_text = get_option('why_card' . $i . '_text') ?: get_theme_mod('why_card' . $i . '_text', $default_texts[$i]);
                ?>
                <div class="card">
                    <?php if ($way_icon): ?>
                        <img src="<?php echo esc_url($way_icon); ?>" alt="<?php echo esc_attr($way_text); ?>">
                    <?php endif; ?>
                    <p><?php echo esc_html($way_text); ?></p>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container y-u-max-w-1200">
            <h2>اختر التخصص الذى تحتاجه</h2>
            <div class="box-grid">
                <button class="btn main-button invert circle">
                    <?php
                    if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                        echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                    }
                    ?>
                </button>
                <ul class="categories-grid">
                    <?php 
                    $default_specialties = array(
                        'أطفال وحديثي الولادة' => 'baby.png',
                        'أسنان' => 'tooth.png',
                        'نفسي' => 'mind.png',
                        'باطنة' => 'stomach.png',
                        'نساء و توليد' => 'pregnant.png',
                        'عيون' => 'eye.png',
                        'مخ وأعصاب' => 'brain.png',
                        'عظام' => 'bone.png',
                        'جلدية' => 'hand.png',
                        'صدر و جهاز تنفسي' => 'lungs.png',
                        'أنف و أذن و حنجرة' => 'ear.png',
                    );
                    $specialty_index = 1;
                    foreach ($default_specialties as $default_name => $default_icon):
                        $specialty_name = get_option('specialty' . $specialty_index . '_name') ?: get_theme_mod('specialty' . $specialty_index . '_name', $default_name);
                        $specialty_icon = get_option('specialty' . $specialty_index . '_icon') ?: get_theme_mod('specialty' . $specialty_index . '_icon', get_template_directory_uri() . '/assets/images/' . $default_icon);
                        if (empty($specialty_name)) continue; // Skip if name is empty
                    ?>
                    <li class="category-item">
                        <a href="<?php echo esc_url(home_url('/doctors')); ?>">
                            <?php if ($specialty_icon): ?>
                                <img src="<?php echo esc_url($specialty_icon); ?>" alt="أيقونة تخصص <?php echo esc_attr($specialty_name); ?>">
                            <?php endif; ?>
                            <p><?php echo esc_html($specialty_name); ?></p>
                        </a>
                    </li>
                    <?php 
                        $specialty_index++;
                    endforeach; 
                    ?>
                </ul>
                <button class="btn main-button invert circle">
                    <?php
                    if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                        echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                    }
                    ?>
                </button>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section class="doctors-section">
        <div class="container y-u-max-w-1200">
            <h2>الأطباء الأكثر اختيارًا</h2>
        </div>

        <div class="box-grid">
            <button class="btn main-button invert circle doctors-scroll-left">
                <?php
                if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                    echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                }
                ?>
            </button>
            <ul class="doctors-grid">
                <?php
                // Get real doctors from post type 'doctor'
                $doctors = get_posts(array(
                    'post_type' => 'doctor',
                    'post_status' => 'publish',
                    'posts_per_page' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                $eye_icon = get_template_directory_uri() . '/assets/images/eye.svg';
                $default_doctor_img = get_template_directory_uri() . '/assets/images/doctor-img.jpg';

                if (!empty($doctors)) {
                    foreach ($doctors as $doctor) {
                        $doctor_id = $doctor->ID;
                        $doctor_meta = my_clinic_get_doctor_meta($doctor_id);
                        $doctor_url = get_permalink($doctor_id);
                        $doctor_name = $doctor->post_title;
                        $specialty = $doctor_meta['specialty'];
                        $degree = $doctor_meta['degree'];
                        $description = $doctor->post_content ? wp_trim_words($doctor->post_content, 20) : '';
                        $rating = $doctor_meta['rating'];
                        $views = $doctor_meta['views'];
                        
                        $doctor_img = get_the_post_thumbnail_url($doctor_id, 'medium');
                        if (!$doctor_img) {
                            $doctor_img = $default_doctor_img;
                        }
                        ?>
                        <li class="doctor-info">
                            <div class="top">
                                <?php if ($doctor_img): ?>
                                    <img src="<?php echo esc_url($doctor_img); ?>" alt="<?php echo esc_attr($doctor_name); ?>">
                                <?php endif; ?>
                                <div class="desc">
                                    <a href="<?php echo esc_url($doctor_url); ?>">
                                        <p><?php echo esc_html($doctor_name); ?></p>
                                    </a>
                                    <?php if ($degree && $specialty): ?>
                                        <p><?php echo esc_html($degree . ' ' . $specialty); ?></p>
                                    <?php elseif ($degree): ?>
                                        <p><?php echo esc_html($degree); ?></p>
                                    <?php elseif ($specialty): ?>
                                        <p><?php echo esc_html($specialty); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($description): ?>
                                <p><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                            <div class="bottom">
                                <div class="rating">
                                    <strong><?php echo esc_html($rating); ?></strong>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="views">
                                    <p><?php echo esc_html($views); ?></p>
                                    <?php if (file_exists(get_template_directory() . '/assets/images/eye.svg')): ?>
                                        <img src="<?php echo esc_url($eye_icon); ?>" alt="عدد المشاهدات">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
            <button class="btn main-button invert circle doctors-scroll-right">
                <?php
                if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                    echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                }
                ?>
            </button>
        </div>
    </section>

    <!-- Banner Section -->
    <section class="panner">
        <div class="container y-u-max-w-1200">
            <?php
            $banner_image = get_option('banner_image') ?: get_theme_mod('banner_image', get_template_directory_uri() . '/assets/images/panner.jpg');
            $banner_title = get_option('banner_title') ?: get_theme_mod('banner_title', 'احجز دكتورك بسهولة في أي وقت ومن أي مكان');
            if ($banner_image):
            ?>
                <img src="<?php echo esc_url($banner_image); ?>" alt="<?php echo esc_attr($banner_title); ?>">
            <?php endif; ?>
            <div class="content">
                <h3><?php echo esc_html($banner_title); ?></h3>
                <p><?php 
                    $banner_text = get_option('banner_text') ?: get_theme_mod('banner_text', 'ابحث عن أفضل الأطباء والعيادات في كل التخصصات بخطوات سريعة');
                    echo esc_html($banner_text); 
                ?></p>
                <?php 
                $banner_button_text = get_option('banner_button_text') ?: get_theme_mod('banner_button_text', 'ابحث عن دكتورك');
                $banner_button_link = get_option('banner_button_link') ?: get_theme_mod('banner_button_link', home_url('/doctors'));
                if ($banner_button_text && $banner_button_link):
                ?>
                    <a href="<?php echo esc_url($banner_button_link); ?>" class="btn main-button"><?php echo esc_html($banner_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <?php
    // Get all reviews from doctors and clinics
    $all_reviews = array();
    
    // Get reviews from doctors
    $doctors_for_reviews = get_posts(array(
        'post_type' => 'doctor',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
    
    foreach ($doctors_for_reviews as $doctor) {
        $doctor_reviews = my_clinic_get_doctor_reviews($doctor->ID, 'approved');
        foreach ($doctor_reviews as $review) {
            $all_reviews[] = $review;
        }
    }
    
    // Get reviews from clinics
    $clinics_for_reviews = get_posts(array(
        'post_type' => 'clinic',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
    
    foreach ($clinics_for_reviews as $clinic) {
        $clinic_reviews = my_clinic_get_clinic_reviews($clinic->ID, 'approved');
        foreach ($clinic_reviews as $review) {
            $all_reviews[] = $review;
        }
    }
    
    // Sort by date (newest first)
    usort($all_reviews, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    // Limit to 10 reviews
    $all_reviews = array_slice($all_reviews, 0, 10);
    
    if (!empty($all_reviews)):
    ?>
    <section class="doctors-section opinions-section">
        <div class="container y-u-max-w-1200">
            <h2>آراء عملائنا</h2>
        </div>

        <div class="box-grid">
            <button class="btn main-button invert circle">
                <?php
                if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                    echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                }
                ?>
            </button>
            <ul class="doctors-grid">
                <?php foreach ($all_reviews as $review): 
                    $review_name = isset($review['name']) ? $review['name'] : 'مجهول';
                    $review_comment = isset($review['comment']) ? $review['comment'] : '';
                    if (empty($review_comment)) continue; // Skip reviews without comments
                ?>
                <li class="doctor-info">
                    <div class="top">
                        <div class="desc">
                            <p><?php echo esc_html($review_comment); ?> <?php echo isset($review['rating']) && $review['rating'] >= 4 ? '❤' : ''; ?></p>
                        </div>
                    </div>
                    <p><?php echo esc_html($review_name); ?></p>
                </li>
                <?php endforeach; ?>
            </ul>
            <button class="btn main-button invert circle">
                <?php
                if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                    echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم التنقل">';
                }
                ?>
            </button>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();
