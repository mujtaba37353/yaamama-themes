<?php
/**
 * Template for displaying doctors page
 *
 * @package MyClinic
 */

get_header();
?>

<main>
    <section class="doctor-page doctors-page">
        <div class="container y-u-max-w-1200">
            <div class="lists">
                <div class="bottom">
                    <div class="list">
                        <input type="checkbox" name="list1" id="list1">
                        <label for="list1">التخصص 
                            <?php
                            $arrow_icon = get_template_directory_uri() . '/assets/images/bottom-arrow.svg';
                            if (file_exists(get_template_directory() . '/assets/images/bottom-arrow.svg')) {
                                echo '<img src="' . esc_url($arrow_icon) . '" alt="سهم القائمة">';
                            }
                            ?>
                        </label>
                        <div class="items">
                            <?php
                            // Get specialties only from doctors displayed on this page
                            $paged = 1;
                            if (isset($_GET['paged']) && $_GET['paged'] > 0) {
                                $paged = max(1, intval($_GET['paged']));
                            } elseif (get_query_var('paged') && get_query_var('paged') > 0) {
                                $paged = max(1, intval(get_query_var('paged')));
                            }
                            
                            // Get all doctors (not just current page) to show all available specialties
                            $all_doctors = my_clinic_get_doctors(array('posts_per_page' => -1, 'paged' => 1));
                            $specialties = my_clinic_get_specialties_from_doctors($all_doctors);
                            
                            if (!empty($specialties)) {
                                foreach ($specialties as $specialty) {
                                    echo '<button class="specialty-filter" data-specialty="' . esc_attr($specialty) . '">' . esc_html($specialty) . '</button>';
                                }
                            } else {
                                // Fallback options if no specialties found
                                echo '<button class="specialty-filter" data-specialty="">عرض الكل</button>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="list">
                        <input type="text" name="doctor" id="doctor-search" placeholder="اسم الطبيب">
                    </div>
                    <button class="btn main-button fw invert fh" id="search-btn">ابحث 
                        <?php
                        $search_icon = get_template_directory_uri() . '/assets/images/search.svg';
                        if (file_exists(get_template_directory() . '/assets/images/search.svg')) {
                            echo '<img src="' . esc_url($search_icon) . '" alt="أيقونة البحث">';
                        }
                        ?>
                    </button>
                </div>
            </div>
            <div class="breadcrumbs-container">
                <div class="breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                    /
                    <a href="<?php echo esc_url(home_url('/doctors')); ?>">الأطباء</a>
                </div>
            </div>
            <div class="bottom">
                <div class="content" id="about">
                    <div class="left">
                        <div class="doctors-box-clinic">
                                    <?php
                            // Get current page number from URL - check $_GET first for custom pages
                            $paged = 1;
                            if (isset($_GET['paged']) && $_GET['paged'] > 0) {
                                $paged = max(1, intval($_GET['paged']));
                            } elseif (get_query_var('paged') && get_query_var('paged') > 0) {
                                $paged = max(1, intval(get_query_var('paged')));
                            }
                            
                            // Get doctors from database with pagination
                            $doctors = my_clinic_get_doctors(array('paged' => $paged));
                            $total_doctors = my_clinic_get_doctors_count();
                            $total_pages = ceil($total_doctors / 8);
                            
                            // Icons paths
                                        $eye_icon = get_template_directory_uri() . '/assets/images/eye.svg';
                                    $address_icon = get_template_directory_uri() . '/assets/images/address.svg';
                                        $clock_icon = get_template_directory_uri() . '/assets/images/clock.svg';
                                        $ryal_icon = get_template_directory_uri() . '/assets/images/ryal-primary.svg';
                                        $phone_icon = get_template_directory_uri() . '/assets/images/phone-primary.svg';
                            $default_doctor_img = get_template_directory_uri() . '/assets/images/doctor-img.jpg';
                            
                            if (!empty($doctors)) {
                                $doctor_index = 1;
                                foreach ($doctors as $doctor) {
                                    $doctor_id = $doctor->ID;
                                    $doctor_meta = my_clinic_get_doctor_meta($doctor_id);
                                    $doctor_url = get_permalink($doctor_id);
                                    $doctor_image = get_the_post_thumbnail_url($doctor_id, 'medium') ?: $default_doctor_img;
                                    $work_schedule_today = my_clinic_format_work_schedule($doctor_meta['work_schedule']);
                                    
                                    // Format work schedule for booking link
                                    $booking_time = $work_schedule_today ?: '04:00 مساءً - 07:00 مساءً';
                            ?>
                            <!-- Doctor <?php echo $doctor_index; ?> -->
                            <div class="box main-box">
                                <div class="top">
                                    <?php
                                    if (file_exists(get_template_directory() . '/assets/images/doctor-img.jpg') || $doctor_image) {
                                        echo '<img src="' . esc_url($doctor_image) . '" alt="صورة الطبيب">';
                                    }
                                    ?>
                                    <div class="content">
                                        <a href="<?php echo esc_url($doctor_url); ?>">
                                            <h3><?php echo esc_html($doctor->post_title); ?></h3>
                                        </a>
                                        <p><?php echo esc_html($doctor_meta['degree'] . ' ' . $doctor_meta['specialty']); ?></p>
                                    </div>
                                </div>
                                <div class="icons">
                                    <div class="rating">
                                        <strong><?php echo esc_html($doctor_meta['rating']); ?></strong>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                                <p><?php echo esc_html($doctor_meta['degree'] . ' ' . $doctor_meta['specialty']); ?></p>
                                <?php if ($doctor_meta['address']): ?>
                                <p class="address">
                                    <?php
                                    if (file_exists(get_template_directory() . '/assets/images/address.svg')) {
                                        echo '<img src="' . esc_url($address_icon) . '" alt="العنوان">';
                                    }
                                    ?>
                                    <?php echo esc_html($doctor_meta['address']); ?>
                                </p>
                                <?php endif; ?>
                                <div class="infos">
                                    <?php if ($doctor_meta['phone']): 
                                        $phone_number = preg_replace('/[^0-9+]/', '', $doctor_meta['phone']);
                                        if (!empty($phone_number)): ?>
                                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="info" style="text-decoration: none; color: inherit; display: block; cursor: pointer;">
                                        <?php
                                        if (file_exists(get_template_directory() . '/assets/images/phone-primary.svg')) {
                                            echo '<img src="' . esc_url($phone_icon) . '" alt="رقم الهاتف">';
                                        }
                                        ?>
                                        <p>اتصل احجز</p>
                                        <p><?php echo esc_html($doctor_meta['phone']); ?></p>
                                    </a>
                                    <?php else: ?>
                                    <div class="info">
                                        <?php
                                        if (file_exists(get_template_directory() . '/assets/images/phone-primary.svg')) {
                                            echo '<img src="' . esc_url($phone_icon) . '" alt="رقم الهاتف">';
                                        }
                                        ?>
                                        <p>اتصل احجز</p>
                                        <p><?php echo esc_html($doctor_meta['phone']); ?></p>
                                    </div>
                                    <?php endif; endif; ?>
                                    <div class="info">
                                        <?php
                                        if (file_exists(get_template_directory() . '/assets/images/ryal-primary.svg')) {
                                            echo '<img src="' . esc_url($ryal_icon) . '" alt="سعر الكشف">';
                                        }
                                        ?>
                                        <p>سعر الكشف</p>
                                        <p><?php echo esc_html($doctor_meta['price']); ?> ريال</p>
                                    </div>
                                    <?php if ($doctor_meta['whatsapp']): ?>
                                    <div class="info">
                                        <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $doctor_meta['whatsapp'])); ?>" target="_blank" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; gap: var(--y-space-4);">
                                            <i class="fa-brands fa-whatsapp" style="font-size: 30px; color: #25D366;"></i>
                                            <p>واتساب</p>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($work_schedule_today): ?>
                                <a href="<?php echo esc_url(home_url('/booking?doctor_id=' . $doctor_id)); ?>" class="btn main-button fw">احجز اليوم (<?php echo esc_html($booking_time); ?>)</a>
                                <?php else: ?>
                                <button class="btn main-button fw" disabled style="opacity: 0.6; cursor: not-allowed;">احجز اليوم (غير متاح)</button>
                                <?php endif; ?>
                                <a href="<?php echo esc_url($doctor_url); ?>" class="btn succend-button fw">اختر موعد آخر</a>
                            </div>
                                    <?php
                                    $doctor_index++;
                                }
                            } else {
                                // No doctors found - show message
                                echo '<p style="text-align: center; padding: 2rem;">لا توجد أطباء متاحة حالياً.</p>';
                                        }
                                        ?>
                                    </div>
                                    <?php
                        // Display pagination if there are more than 8 doctors
                        if ($total_doctors > 8 && $total_pages > 1) {
                            // Get current page URL
                            $current_url = get_permalink();
                            // Remove existing paged parameter if exists
                            $current_url = remove_query_arg('paged', $current_url);
                            
                            echo '<div class="pagination-wrapper" style="margin-top: 2rem; display: flex; justify-content: center; align-items: center; gap: 0.5rem; flex-wrap: wrap;">';
                            
                            // Previous button
                            if ($paged > 1) {
                                if ($paged == 2) {
                                    $prev_url = remove_query_arg('paged', $current_url);
                                } else {
                                    $prev_url = add_query_arg('paged', $paged - 1, $current_url);
                                }
                                echo '<a href="' . esc_url($prev_url) . '" class="pagination-link pagination-prev" style="padding: 0.5rem 1rem; border: 1px solid var(--y-color-border); border-radius: 4px; text-decoration: none; color: var(--y-color-txt); transition: all 0.3s ease;">السابق</a>';
                            }
                            
                            // Page numbers
                            for ($i = 1; $i <= $total_pages; $i++) {
                                if ($i == $paged) {
                                    echo '<span class="pagination-current" style="padding: 0.5rem 1rem; background-color: var(--y-color-primary); color: #fff; border-radius: 4px; font-weight: bold;">' . $i . '</span>';
                                } else {
                                    if ($i == 1) {
                                        $page_url = remove_query_arg('paged', $current_url);
                                    } else {
                                        $page_url = add_query_arg('paged', $i, $current_url);
                                    }
                                    echo '<a href="' . esc_url($page_url) . '" class="pagination-link" style="padding: 0.5rem 1rem; border: 1px solid var(--y-color-border); border-radius: 4px; text-decoration: none; color: var(--y-color-txt); transition: all 0.3s ease;">' . $i . '</a>';
                                }
                            }
                            
                            // Next button
                            if ($paged < $total_pages) {
                                $next_url = add_query_arg('paged', $paged + 1, $current_url);
                                echo '<a href="' . esc_url($next_url) . '" class="pagination-link pagination-next" style="padding: 0.5rem 1rem; border: 1px solid var(--y-color-border); border-radius: 4px; text-decoration: none; color: var(--y-color-txt); transition: all 0.3s ease;">التالي</a>';
                            }
                            
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>
