<?php
/**
 * Template for displaying single doctor post
 *
 * @package MyClinic
 */

get_header();

// Get current doctor post
global $post;
$doctor_id = $post->ID;
$doctor_meta = my_clinic_get_doctor_meta($doctor_id);

// Get doctor image
$default_doctor_img = get_template_directory_uri() . '/assets/images/doctor-img.jpg';
$doctor_image = get_the_post_thumbnail_url($doctor_id, 'medium') ?: $default_doctor_img;

// Icons paths
$eye_icon = get_template_directory_uri() . '/assets/images/eye.svg';
$address_icon = get_template_directory_uri() . '/assets/images/address.svg';
$clock_icon = get_template_directory_uri() . '/assets/images/clock.svg';
$ryal_icon = get_template_directory_uri() . '/assets/images/ryal-primary.svg';
$phone_icon = get_template_directory_uri() . '/assets/images/phone-primary.svg';
$ryal_simple_icon = get_template_directory_uri() . '/assets/images/ryal.svg';

// Format work schedule for today
$work_schedule_today = my_clinic_format_work_schedule($doctor_meta['work_schedule']);
$booking_time = $work_schedule_today ?: '04:00 مساءً - 07:00 مساءً';

// Get other doctors (excluding current doctor)
$other_doctors = my_clinic_get_doctors(array(
    'posts_per_page' => 3,
    'post__not_in' => array($doctor_id),
));

// Get doctor description from post content
$doctor_description = get_the_content();
if (empty($doctor_description)) {
    $doctor_description = 'استاذ جراحة العظام والعمود الفقري وخبرة واسعة في الجراحات التقويمية واستبدال مفاصل الحوض والركبة';
}
$doctor_description = apply_filters('the_content', $doctor_description);
?>

<main>
    <section class="doctor-page">
        <div class="container y-u-max-w-1200">
            <div class="top">
                <?php if ($doctor_image): ?>
                    <img src="<?php echo esc_url($doctor_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php endif; ?>
                <div class="content">
                    <h3><?php echo esc_html(get_the_title()); ?></h3>
                    <p><?php echo esc_html($doctor_meta['degree'] . ' ' . $doctor_meta['specialty']); ?></p>
                    <div class="icons">
                        <div class="rating">
                            <strong><?php echo esc_html($doctor_meta['rating']); ?></strong>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bottom">
                <div class="content">
                    <div class="right">
                        <div class="box main-box">
                            <h3>دكتور <?php echo esc_html(get_the_title()); ?></h3>
                            <?php 
                            // Display clinic name if doctor is associated with a clinic
                            if (!empty($doctor_meta['clinic_id'])) {
                                $clinic = get_post($doctor_meta['clinic_id']);
                                if ($clinic && $clinic->post_status === 'publish') {
                                    echo '<p style="color: var(--y-color-primary, #007bff); font-weight: 600; margin-bottom: 0.5rem;">';
                                    echo '<i class="fa-solid fa-hospital" style="margin-left: 5px;"></i>';
                                    echo esc_html($clinic->post_title);
                                    echo '</p>';
                                }
                            }
                            ?>
                            <?php if ($doctor_meta['address']): ?>
                                <p><?php echo esc_html($doctor_meta['address']); ?></p>
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
                                    <?php if (file_exists(get_template_directory() . '/assets/images/ryal-primary.svg')): ?>
                                        <img src="<?php echo esc_url($ryal_icon); ?>" alt="سعر الكشف">
                                    <?php endif; ?>
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
                            
                            <button class="btn succend-button fw" id="open-calendar-btn">اختر موعد آخر</button>
                            
                            <div id="calendar-popover" class="calendar-popover" data-work-schedule="<?php echo esc_attr(json_encode($doctor_meta['work_schedule'])); ?>" data-booking-time="<?php echo esc_attr($booking_time); ?>" data-doctor-id="<?php echo esc_attr($doctor_id); ?>">
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
                                    <a href="<?php echo esc_url(home_url('/booking?doctor_id=' . $doctor_id)); ?>" class="btn main-button fw" id="reservation-link">احجز في</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="left">
                        <input type="radio" name="tab" id="about" checked>
                        <input type="radio" name="tab" id="rating">
                        <div class="tabs">
                            <label for="about">عن الطبيب</label>
                            <label for="rating">تقييمات</label>
                        </div>
                        
                        <div class="about-box">
                            <?php if (!empty($doctor_description)): ?>
                            <div class="box">
                                <p class="title">عن الطبيب</p>
                                <div><?php echo wp_kses_post($doctor_description); ?></div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor_meta['specialty'])): ?>
                            <div class="box">
                                <p class="title">متخصص فى</p>
                                <p><?php echo esc_html($doctor_meta['specialty']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor_meta['degree'])): ?>
                            <div class="box">
                                <p class="title">المؤهلات العلمية</p>
                                <p><?php echo esc_html($doctor_meta['degree']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($doctor_meta['work_schedule'])): ?>
                            <div class="box">
                                <p class="title">مواعيد العمل</p>
                                <div class="work-schedule-table" style="margin-top: 1rem;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr style="background-color: var(--y-color-bg); border-bottom: 2px solid var(--y-color-border);">
                                                <th style="padding: 0.75rem; text-align: right; font-weight: 700; color: var(--y-color-txt);">اليوم</th>
                                                <th style="padding: 0.75rem; text-align: right; font-weight: 700; color: var(--y-color-txt);">من</th>
                                                <th style="padding: 0.75rem; text-align: right; font-weight: 700; color: var(--y-color-txt);">إلى</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $days_map = array(
                                                'sunday' => 'الأحد',
                                                'monday' => 'الاثنين',
                                                'tuesday' => 'الثلاثاء',
                                                'wednesday' => 'الأربعاء',
                                                'thursday' => 'الخميس',
                                                'friday' => 'الجمعة',
                                                'saturday' => 'السبت',
                                            );
                                            $schedule = $doctor_meta['work_schedule'];
                                            $days_order = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
                                            
                                            foreach ($days_order as $day_key):
                                                $day_name = isset($days_map[$day_key]) ? $days_map[$day_key] : $day_key;
                                                $day_schedule = isset($schedule[$day_key]) ? $schedule[$day_key] : null;
                                            ?>
                                                <tr style="border-bottom: 1px solid var(--y-color-border);">
                                                    <td style="padding: 0.75rem; text-align: right; color: var(--y-color-txt);"><?php echo esc_html($day_name); ?></td>
                                                    <?php if ($day_schedule && isset($day_schedule['from']) && isset($day_schedule['to'])): ?>
                                                        <td style="padding: 0.75rem; text-align: right; color: var(--y-color-txt);"><?php echo esc_html($day_schedule['from']); ?></td>
                                                        <td style="padding: 0.75rem; text-align: right; color: var(--y-color-txt);"><?php echo esc_html($day_schedule['to']); ?></td>
                                                    <?php else: ?>
                                                        <td colspan="2" style="padding: 0.75rem; text-align: center; color: var(--y-color-grey);">مغلق</td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                        
                        <div class="rating-box">
                            <?php 
                            // Get real reviews
                            $reviews = my_clinic_get_doctor_reviews($doctor_id);
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
                                <button class="btn main-button center" id="load-more-reviews">المزيد</button>
                            <?php endif; ?>
                            
                            <!-- Add Review Form -->
                            <div class="box" style="margin-top: var(--y-space-24);">
                                <p class="title">أضف تقييمك</p>
                                <form id="add-review-form" class="review-form">
                                    <?php wp_nonce_field('add_doctor_review', 'review_nonce'); ?>
                                    <input type="hidden" name="doctor_id" value="<?php echo esc_attr($doctor_id); ?>">
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">الاسم</label>
                                        <input type="text" name="review_name" required style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-border); border-radius: var(--y-radius-l);">
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">التقييم العام</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_rating" value="<?php echo $i; ?>" id="rating-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="rating-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">الخدمة الطبية</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_medical_service" value="<?php echo $i; ?>" id="medical-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="medical-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">مكان الانتظار</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_waiting_place" value="<?php echo $i; ?>" id="waiting-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="waiting-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">مساعد الطبيب</label>
                                        <div class="star-rating-input" data-rating="5">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="review_assistant" value="<?php echo $i; ?>" id="assistant-<?php echo $i; ?>" <?php echo $i == 5 ? 'checked' : ''; ?>>
                                                <label for="assistant-<?php echo $i; ?>" class="star-label"><i class="fa-solid fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div style="margin-bottom: var(--y-space-16);">
                                        <label style="display: block; margin-bottom: var(--y-space-8); font-weight: 600;">التعليق (اختياري)</label>
                                        <textarea name="review_comment" rows="4" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-border); border-radius: var(--y-radius-l);"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn main-button fw">إرسال التقييم</button>
                                    <div id="review-message" style="margin-top: var(--y-space-16); display: none;"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($other_doctors)): ?>
                        <div class="other-doctors-section">
                            <h2>يمكنك الحجز مع أطباء أخريين</h2>
                            <?php foreach ($other_doctors as $other_doctor): 
                                $other_doctor_id = $other_doctor->ID;
                                $other_doctor_meta = my_clinic_get_doctor_meta($other_doctor_id);
                                $other_doctor_url = get_permalink($other_doctor_id);
                                $other_doctor_image = get_the_post_thumbnail_url($other_doctor_id, 'medium') ?: $default_doctor_img;
                                $other_work_schedule = my_clinic_format_work_schedule($other_doctor_meta['work_schedule']);
                                $other_booking_time = $other_work_schedule ?: '04:00 مساءً - 07:00 مساءً';
                            ?>
                                <div class="box doctor-info">
                                    <div class="top">
                                        <?php if ($other_doctor_image): ?>
                                            <img src="<?php echo esc_url($other_doctor_image); ?>" alt="<?php echo esc_attr($other_doctor->post_title); ?>">
                                        <?php endif; ?>
                                        <div class="desc">
                                            <p><?php echo esc_html($other_doctor->post_title); ?></p>
                                            <p><?php echo esc_html($other_doctor_meta['degree'] . ' ' . $other_doctor_meta['specialty']); ?></p>
                                        </div>
                                    </div>
                                    <div class="bottom">
                                        <div class="rating">
                                            <strong><?php echo esc_html($other_doctor_meta['rating']); ?></strong>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <div class="price">
                                            <p><?php echo esc_html($other_doctor_meta['price']); ?></p>
                                            <?php if (file_exists(get_template_directory() . '/assets/images/ryal.svg')): ?>
                                                <img src="<?php echo esc_url($ryal_simple_icon); ?>" alt="سعر الكشف بالريال">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if ($other_work_schedule): ?>
                                        <a href="<?php echo esc_url($other_doctor_url); ?>" class="btn main-button fw">احجز اليوم (<?php echo esc_html($other_booking_time); ?>)</a>
                                    <?php else: ?>
                                        <a href="<?php echo esc_url($other_doctor_url); ?>" class="btn main-button fw">احجز اليوم</a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
?>
