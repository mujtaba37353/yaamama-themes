<?php
$settings = yaamama_get_contact_settings();
$error = isset( $_GET['contact_error'] ) ? sanitize_text_field( wp_unslash( $_GET['contact_error'] ) ) : '';
$success = isset( $_GET['contact_success'] ) ? sanitize_text_field( wp_unslash( $_GET['contact_success'] ) ) : '';
get_header();
?>

<main class="special-bg">
	<section class="container y-u-max-w-1200 contact-section y-u-py-40">
		<h1 class="u-font-bold y-u-text-center y-u-max-w-600 y-u-mx-auto"><?php echo esc_html( $settings['page']['title'] ); ?></h1>
		<p class="y-t-muted y-u-text-center y-u-m-b-24 y-u-text-m y-u-max-w-600 y-u-mx-auto"><?php echo esc_html( $settings['page']['description'] ); ?></p>
		<?php if ( $error ) : ?>
			<p class="y-u-text-center y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $error ); ?></p>
		<?php endif; ?>
		<?php if ( $success ) : ?>
			<p class="y-u-text-center y-u-m-b-16" style="color: var(--y-color-primary);"><?php echo esc_html( $success ); ?></p>
		<?php endif; ?>
		<div class="contact-grid y-u-grid y-u-grid-2 y-u-gap-32">
			<div class="contact-form-panel">
				<form id="contact-form" class="contact-form y-u-flex y-u-flex-col y-u-gap-16" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
					<?php wp_nonce_field( 'yaamama_contact_submit', 'yaamama_contact_nonce' ); ?>
					<input type="hidden" name="action" value="yaamama_contact_submit">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url( '/contact-message' ) ); ?>">
					<div class="form-group">
						<label class="y-u-block y-u-m-b-8 y-u-font-bold" for="email">البريد الإلكتروني</label>
						<input type="email" class="y-u-w-full y-u-p-12 y-u-rounded-s" placeholder="example@email.com" id="email"
							name="email" required autocomplete="email">
					</div>
					<div class="form-group">
						<label class="y-u-block y-u-m-b-8 y-u-font-bold" for="name">الاسم</label>
						<input type="text" class="y-u-w-full y-u-p-12 y-u-rounded-s" placeholder="الاسم بالكامل" id="name"
							name="name" required autocomplete="name">
					</div>
					<div class="y-u-grid y-u-grid-2 y-u-gap-16">
						<div class="form-group">
							<label class="y-u-block y-u-m-b-8 y-u-font-bold " for="subject">عنوان الرسالة</label>
							<input type="text" class="y-u-w-full y-u-p-12 y-u-rounded-s" placeholder="الموضوع" id="subject"
								name="subject" required>
						</div>
						<div class="form-group">
							<label class="y-u-block y-u-m-b-8 y-u-font-bold " for="phone">رقم الهاتف</label>
							<input type="tel" maxlength="10" class="y-u-w-full y-u-p-12 y-u-rounded-s" placeholder="05xxxxxxxx"
								id="phone" name="phone" autocomplete="tel">
						</div>
					</div>
					<div class="form-group">
						<label class="y-u-block y-u-m-b-8 y-u-font-bold " for="message">الرسالة</label>
						<textarea class="y-u-w-full y-u-p-12 y-u-rounded-s" maxlength="500" rows="5"
							placeholder="اكتب رسالتك هنا..." id="message" name="message" required></textarea>
					</div>
					<div class="y-u-flex">
						<button type="submit" id="contact-btn" class="btn main-button">إرسال</button>
					</div>
				</form>
			</div>
			<div class="contact-info-panel">
				<div class="info-card y-u-rounded-m y-u-p-24 y-u-m-b-24">
					<h2 class="y-u-text-m y-u-font-bold">بيانات التواصل</h2>

					<div class="info-item y-u-flex y-u-items-center y-u-gap-12">
						<div class="info-icon y-u-flex y-u-items-center y-u-justify-center y-u-rounded-s">
							<i class="fa-regular fa-envelope"></i>
						</div>
						<div class="info-content">
							<p class="y-u-text-s y-u-font-bold"><?php echo esc_html( $settings['info']['support_label'] ); ?></p>
							<?php
							$support_value = $settings['info']['support_value'];
							$support_link = is_email( $support_value ) ? 'mailto:' . sanitize_email( $support_value ) : '';
							?>
							<?php if ( $support_link ) : ?>
								<p class="y-u-text-xs y-u-font-bold"><a href="<?php echo esc_url( $support_link ); ?>"><?php echo esc_html( $support_value ); ?></a></p>
							<?php else : ?>
								<p class="y-u-text-xs y-u-font-bold"><?php echo esc_html( $support_value ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<div class="info-item y-u-flex y-u-items-center y-u-gap-12">
						<div class="info-icon y-u-flex y-u-items-center y-u-justify-center y-u-rounded-s">
							<i class="fa-regular fa-calendar-days"></i>
						</div>
						<div class="info-content">
							<p class="y-u-text-s y-u-font-bold">ساعات العمل</p>
							<p class="y-u-text-xs y-u-font-bold"><?php echo esc_html( $settings['info']['work_hours'] ); ?></p>
						</div>
					</div>

					<div class="info-item y-u-flex y-u-items-center y-u-gap-12 y-u-m-b-24">
						<div class="info-icon y-u-flex y-u-items-center y-u-justify-center y-u-rounded-s">
							<i class="fa-regular fa-clock"></i>
						</div>
						<div class="info-content">
							<p class="y-u-text-s y-u-font-bold">وقت الاستجابة</p>
							<p class="y-u-text-xs y-u-font-bold"><?php echo esc_html( $settings['info']['response_time'] ); ?></p>
						</div>
					</div>

					<div class="social-links">
						<p class="y-u-text-s y-u-font-bold"><?php echo esc_html( $settings['info']['social_title'] ); ?></p>
						<div class="social-icons y-u-flex y-u-gap-12">
							<?php if ( ! empty( $settings['info']['social_links']['x'] ) ) : ?>
								<a href="<?php echo esc_url( $settings['info']['social_links']['x'] ); ?>"><i class="fa-brands fa-x"></i></a>
							<?php endif; ?>
							<?php if ( ! empty( $settings['info']['social_links']['instagram'] ) ) : ?>
								<a href="<?php echo esc_url( $settings['info']['social_links']['instagram'] ); ?>"><i class="fa-brands fa-instagram"></i></a>
							<?php endif; ?>
							<?php if ( ! empty( $settings['info']['social_links']['facebook'] ) ) : ?>
								<a href="<?php echo esc_url( $settings['info']['social_links']['facebook'] ); ?>"><i class="fa-brands fa-facebook"></i></a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="info-card location-card y-u-rounded-m y-u-p-24">
					<div class="y-u-flex y-u-items-center y-u-gap-12">
						<div class="info-icon y-u-flex y-u-items-center y-u-justify-center y-u-rounded-s">
							<i class="fa-solid fa-location-arrow"></i>
						</div>
						<h2 class="y-u-text-m y-u-font-bold"><?php echo esc_html( $settings['info']['location_title'] ); ?></h2>
					</div>
					<p class="y-u-text-s y-u-font-bold"><?php echo esc_html( $settings['info']['location_line1'] ); ?></p>
					<p class="y-u-text-s y-u-font-bold"><?php echo esc_html( $settings['info']['location_line2'] ); ?></p>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
