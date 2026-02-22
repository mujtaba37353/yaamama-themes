<?php
/**
 * Template for displaying single clinic post
 *
 * @package MyClinic
 */

get_header();

// Get current clinic post
global $post;
$clinic_id = $post->ID;
$clinic_meta = my_clinic_get_clinic_meta($clinic_id);

// Get clinic image
$default_clinic_img = get_template_directory_uri() . '/assets/images/pro-clinic.jpg';
$clinic_image = get_the_post_thumbnail_url($clinic_id, 'medium') ?: $default_clinic_img;

// Icons paths
$eye_icon = get_template_directory_uri() . '/assets/images/eye.svg';
$address_icon = get_template_directory_uri() . '/assets/images/address.svg';
$scope_icon = get_template_directory_uri() . '/assets/images/scope.png';
$medical_icon = get_template_directory_uri() . '/assets/images/medical.png';
$phone_icon = get_template_directory_uri() . '/assets/images/phone-primary.svg';
$ryal_icon = get_template_directory_uri() . '/assets/images/ryal-primary.svg';
$ryal_simple_icon = get_template_directory_uri() . '/assets/images/ryal.svg';

// Format work schedule for today
$work_schedule_today = my_clinic_format_work_schedule($clinic_meta['work_schedule']);
$booking_time = $work_schedule_today ?: '04:00 مساءً - 07:00 مساءً';

// Get doctors in this clinic
$clinic_doctors = get_posts(array(
    'post_type' => 'doctor',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
        array(
            'key' => '_doctor_clinic_id',
            'value' => $clinic_id,
            'compare' => '='
        )
    )
));

// Get clinic description from post content
$clinic_description = get_the_content();
if (empty($clinic_description)) {
    $clinic_description = 'تأسست برو كلينيك عام 2015 كعيادة طبية متكاملة تهدف إلى تقديم رعاية صحية متميزة في تخصصي العظام والجلدية. تضم العيادة فريقًا طبيًا متخصصًا يستخدم أحدث التقنيات لتشخيص وعلاج الحالات بدقة وكفاءة، مع الحرص على توفير تجربة علاجية مريحة وشاملة تضمن لمرضانا أفضل النتائج والعناية الفائقة في كل زيارة.';
}
$clinic_description = apply_filters('the_content', $clinic_description);
?>

<main>
    <section class="doctor-page clinic-page">
        <div class="container y-u-max-w-1200">
            <div class="top">
                <?php if ($clinic_image): ?>
                    <img src="<?php echo esc_url($clinic_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php endif; ?>
                <div class="content">
                    <h3><?php echo esc_html(get_the_title()); ?></h3>
                    <?php if ($clinic_meta['address']): ?>
                        <p><?php echo esc_html($clinic_meta['address']); ?></p>
                    <?php endif; ?>
                    <div class="icons">
                        <div class="rating">
                            <strong><?php echo esc_html($clinic_meta['rating']); ?></strong>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bottom">
                <div class="content">
                    <div class="right">
                        <div class="box main-box">
                            <h3><?php echo esc_html(get_the_title()); ?></h3>
                            <?php if ($clinic_meta['address']): ?>
                                <p><?php echo esc_html($clinic_meta['address']); ?></p>
                            <?php endif; ?>
                            <div class="infos">
                                <?php if ($clinic_meta['phone']): 
                                    $phone_number = preg_replace('/[^0-9+]/', '', $clinic_meta['phone']);
                                    if (!empty($phone_number)): ?>
                                <a href="tel:<?php echo esc_attr($phone_number); ?>" class="info" style="text-decoration: none; color: inherit; display: block; cursor: pointer;">
                                    <?php
                                    if (file_exists(get_template_directory() . '/assets/images/phone-primary.svg')) {
                                        echo '<img src="' . esc_url($phone_icon) . '" alt="رقم الهاتف">';
                                    }
                                    ?>
                                    <p>اتصل احجز</p>
                                    <p><?php echo esc_html($clinic_meta['phone']); ?></p>
                                </a>
                                <?php else: ?>
                                <div class="info">
                                    <?php
                                    if (file_exists(get_template_directory() . '/assets/images/phone-primary.svg')) {
                                        echo '<img src="' . esc_url($phone_icon) . '" alt="رقم الهاتف">';
                                    }
                                    ?>
                                    <p>اتصل احجز</p>
                                    <p><?php echo esc_html($clinic_meta['phone']); ?></p>
                                </div>
                                <?php endif; endif; ?>
                                <?php
                                // Get average price from clinic doctors
                                $clinic_doctors_for_price = get_posts(array(
                                    'post_type' => 'doctor',
                                    'posts_per_page' => -1,
                                    'meta_query' => array(
                                        array(
                                            'key' => '_doctor_clinic_id',
                                            'value' => $clinic_id,
                                            'compare' => '='
                                        )
                                    )
                                ));
                                $prices = array();
                                foreach ($clinic_doctors_for_price as $doc) {
                                    $price = get_post_meta($doc->ID, '_doctor_price', true);
                                    if ($price) {
                                        $prices[] = intval($price);
                                    }
                                }
                                $avg_price = !empty($prices) ? round(array_sum($prices) / count($prices)) : '';
                                $ryal_icon = get_template_directory_uri() . '/assets/images/ryal-primary.svg';
                                ?>
                                <div class="info">
                                    <?php if (file_exists(get_template_directory() . '/assets/images/ryal-primary.svg')): ?>
                                        <img src="<?php echo esc_url($ryal_icon); ?>" alt="سعر الكشف">
                                    <?php endif; ?>
                                    <p>سعر الكشف</p>
                                    <p><?php echo $avg_price ? esc_html($avg_price) . ' ريال' : 'غير متوفر'; ?></p>
                                </div>
                                <?php if ($clinic_meta['whatsapp']): ?>
                                <div class="info">
                                    <a href="https://wa.me/<?php echo esc_attr(preg_replace('/[^0-9]/', '', $clinic_meta['whatsapp'])); ?>" target="_blank" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; align-items: center; gap: var(--y-space-4);">
                                        <i class="fa-brands fa-whatsapp" style="font-size: 30px; color: #25D366;"></i>
                                        <p>واتساب</p>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($work_schedule_today): ?>
                                <a href="<?php echo esc_url(home_url('/booking?clinic_id=' . $clinic_id)); ?>" class="btn main-button fw">احجز اليوم (<?php echo esc_html($booking_time); ?>)</a>
                            <?php else: ?>
                                <button class="btn main-button fw" disabled style="opacity: 0.6; cursor: not-allowed;">احجز اليوم (غير متاح)</button>
                            <?php endif; ?>
                            
                            <button class="btn succend-button fw" id="open-calendar-btn-clinic">اختر موعد آخر</button>
                            
                            <div id="calendar-popover-clinic" class="calendar-popover" data-work-schedule="<?php echo esc_attr(json_encode($clinic_meta['work_schedule'])); ?>" data-booking-time="<?php echo esc_attr($booking_time); ?>" data-clinic-id="<?php echo esc_attr($clinic_id); ?>">
                                <div class="calendar-header">
                                    <button class="prev-month">&lt;</button>
                                    <h3 class="month-year">نوفمبر 2025</h3>
                                    <button class="next-month">&gt;</button>
                                </div>
                                <div class="calendar-body">
                                    <div class="day-names">
                                        <span>الأحد</span>
                                        <span>الاثنين</span>
                                        <span>الثلاثاء</span>
                                        <span>الأربعاء</span>
                                        <span>الخميس</span>
                                        <span>الجمعة</span>
                                        <span>السبت</span>
                                    </div>
                                    <div class="days-grid"></div>
                                </div>
                                <div class="calendar-footer">
                                    <a href="<?php echo esc_url(home_url('/booking?clinic_id=' . $clinic_id)); ?>" class="btn main-button fw" id="reservation-link-clinic">احجز في</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="left">
                        <input type="radio" name="tab" id="about-clinic" checked>
                        <input type="radio" name="tab" id="rating-clinic">
                        <div class="tabs">
                            <label for="about-clinic">عن العيادة</label>
                            <label for="rating-clinic">تقييمات</label>
                        </div>
                        
                        <div class="about-box-clinic">
                            <?php if (!empty($clinic_description)): ?>
                            <div class="box">
                                <p class="title">عن <?php echo esc_html(get_the_title()); ?></p>
                                <div><?php echo wp_kses_post($clinic_description); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php 
                            // Get clinic features from meta
                            $clinic_features = $clinic_meta['features'];
                            if (!empty($clinic_features) && is_array($clinic_features)): 
                            ?>
                            <div class="box">
                                <p class="title">مميزات العيادة</p>
                                <div class="featuers">
                                    <?php foreach ($clinic_features as $feature): 
                                        $feature_name = isset($feature['name']) ? $feature['name'] : '';
                                        $feature_icon = isset($feature['icon']) ? $feature['icon'] : '';
                                        
                                        if (!empty($feature_name)):
                                    ?>
                                        <div class="feature">
                                            <?php if (!empty($feature_icon)): ?>
                                                <img src="<?php echo esc_url($feature_icon); ?>" alt="<?php echo esc_attr($feature_name); ?>">
                                            <?php endif; ?>
                                            <p><?php echo esc_html($feature_name); ?></p>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php 
                            // Get clinic images from gallery (if available)
                            $clinic_images = get_post_meta($clinic_id, '_clinic_images', true);
                            if (!empty($clinic_images) && is_array($clinic_images)): 
                            ?>
                            <div class="box">
                                <p class="title">صور العيادة</p>
                                <div class="imgs">
                                    <?php 
                                    foreach ($clinic_images as $clinic_img): 
                                        if (!empty($clinic_img)):
                                    ?>
                                        <img src="<?php echo esc_url($clinic_img); ?>" alt="صورة من داخل العيادة">
                                    <?php 
                                        endif;
                                    endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="rating-box-clinic">
                            <?php 
                            // Get real reviews
                            $reviews = my_clinic_get_clinic_reviews($clinic_id);
                            ?>
                            
                            <?php 
                            // Display real reviews
                            if (!empty($reviews)): 
                                $display_reviews = array_slice($reviews, 0, 5); // Show first 5 reviews
                                foreach ($display_reviews as $review): 
                                    $review_rating = floatval($review['rating']);
                                    $review_full_stars = floor($review_rating);
                                    $review_has_half = ($review_rating - $review_full_stars) >= 0.5;
                                    $review_date = date_i18n('d/m/Y', strtotime($review['date']));
                            ?>
                                <div class="box">
                                    <p class="title"><?php echo esc_html($review['name']); ?> <span><?php echo esc_html($review_date); ?></span></p>
                                    <div class="ratings">
                                        <?php for ($i = 0; $i < 5; $i++): 
                                            if ($i < $review_full_stars): ?>
                                                <i class="fa-solid fa-star"></i>
                                            <?php elseif ($i == $review_full_stars && $review_has_half): ?>
                                                <i class="fa-solid fa-star-half-stroke"></i>
                                            <?php else: ?>
                                                <i class="fa-regular fa-star inactive"></i>
                                            <?php endif;
                                        endfor; ?>
                                    </div>
                                    <?php if (!empty($review['comment'])): ?>
                                        <p style="margin-top: 0.5rem; color: var(--y-color-txt);"><?php echo esc_html($review['comment']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php 
                                endforeach; 
                            else: 
                            ?>
                                <div class="box">
                                    <p class="title" style="text-align: center; color: var(--y-color-grey);">لا توجد تقييمات بعد</p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (count($reviews) > 5): ?>
                                <button class="btn main-button center" id="load-more-reviews-clinic">المزيد</button>
                            <?php endif; ?>
                            
                            <!-- Add Review Form -->
                            <div class="box" style="margin-top: var(--y-space-24);">
                                <p class="title">أضف تقييمك</p>
                                <form id="add-review-form-clinic" class="review-form">
                                    <?php wp_nonce_field('add_clinic_review', 'review_nonce'); ?>
                                    <input type="hidden" name="clinic_id" value="<?php echo esc_attr($clinic_id); ?>">
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">الاسم</label>
                                        <input type="text" name="review_name" required style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-border); border-radius: var(--y-radius-l);">
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">التقييم العام</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_rating" value="<?php echo $i; ?>" id="rating-clinic-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="rating-clinic-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">الخدمة الطبية</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_medical_service" value="<?php echo $i; ?>" id="medical-clinic-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="medical-clinic-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">مكان الانتظار</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_waiting_place" value="<?php echo $i; ?>" id="waiting-clinic-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="waiting-clinic-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">مساعد الطبيب</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_assistant" value="<?php echo $i; ?>" id="assistant-clinic-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="assistant-clinic-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">التعليق (اختياري)</label>
                                        <textarea name="review_comment" rows="4" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-border); border-radius: var(--y-radius-l);"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn main-button fw">إرسال التقييم</button>
                                    <div id="review-message-clinic" style="margin-top: var(--y-space-16); display: none;"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php if (!empty($clinic_doctors)): ?>
    <section class="doctor-page doctors-page" style="padding-top: var(--y-space-32);">
        <div class="container y-u-max-w-1200">
            <div class="bottom">
                <div class="content" id="about">
                    <div class="left">
                        <div class="doctors-box-clinic">
                            <h2 style="margin-bottom: var(--y-space-24); font-size: var(--y-space-24); font-weight: 700; color: var(--y-color-txt);">الأطباء في هذه العيادة</h2>
                            <?php
                            // Icons paths
                            $eye_icon = get_template_directory_uri() . '/assets/images/eye.svg';
                            $address_icon = get_template_directory_uri() . '/assets/images/address.svg';
                            $clock_icon = get_template_directory_uri() . '/assets/images/clock.svg';
                            $ryal_icon = get_template_directory_uri() . '/assets/images/ryal-primary.svg';
                            $phone_icon = get_template_directory_uri() . '/assets/images/phone-primary.svg';
                            $default_doctor_img = get_template_directory_uri() . '/assets/images/doctor-img.jpg';
                            
                            $doctor_index = 1;
                            foreach ($clinic_doctors as $doctor) {
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
                                <button class="btn succend-button fw" id="open-calendar-btn-doctor-clinic-<?php echo $doctor_index; ?>">اختر موعد آخر</button>
                                <div id="calendar-popover-doctor-clinic-<?php echo $doctor_index; ?>" class="calendar-popover" data-work-schedule="<?php echo esc_attr(json_encode($doctor_meta['work_schedule'])); ?>" data-booking-time="<?php echo esc_attr($booking_time); ?>" data-doctor-id="<?php echo esc_attr($doctor_id); ?>">
                                    <div class="calendar-header">
                                        <button class="prev-month">&lt;</button>
                                        <h3 class="month-year">نوفمبر 2025</h3>
                                        <button class="next-month">&gt;</button>
                                    </div>
                                    <div class="calendar-body">
                                        <div class="day-names">
                                            <span>الأحد</span>
                                            <span>الاثنين</span>
                                            <span>الثلاثاء</span>
                                            <span>الأربعاء</span>
                                            <span>الخميس</span>
                                            <span>الجمعة</span>
                                            <span>السبت</span>
                                        </div>
                                        <div class="days-grid"></div>
                                    </div>
                                    <div class="calendar-footer">
                                        <a href="<?php echo esc_url(home_url('/booking?doctor_id=' . $doctor_id)); ?>" class="btn main-button fw" id="reservation-link-doctor-clinic-<?php echo $doctor_index; ?>">احجز في</a>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $doctor_index++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
