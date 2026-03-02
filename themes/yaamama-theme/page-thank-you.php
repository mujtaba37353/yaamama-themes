<?php
get_header();
$sub_id    = isset( $_GET['sub'] ) ? absint( $_GET['sub'] ) : 0;
$store_url = $sub_id ? get_option( "yaamama_store_url_for_sub_{$sub_id}", '' ) : '';
$store_status = $sub_id ? get_option( "yaamama_store_status_for_sub_{$sub_id}", '' ) : '';
$is_ready  = ( $store_status === 'active' && $store_url );
?>

<main class="thank-you-page special-bg">
	<div class="container y-u-py-32">
		<div class="confirmation-card">
			<div class="icon-wrapper" id="ty-icon">
				<?php if ( $is_ready ) : ?>
					<i class="fa-solid fa-check"></i>
				<?php else : ?>
					<div class="ty-spinner" style="width:48px;height:48px;border:4px solid #e5e7eb;border-top-color:var(--y-color-primary, #3b82f6);border-radius:50%;animation:yaamama-spin 1s linear infinite;margin:0 auto;"></div>
				<?php endif; ?>
			</div>

			<h1 class="title">تم تأكيد الدفع</h1>
			<h2 class="subtitle" id="ty-subtitle"><?php echo $is_ready ? 'متجرك جاهز!' : 'جاري تجهيز المتجر'; ?></h2>
			<p class="note" id="ty-note"><?php echo $is_ready ? 'يمكنك استعراض متجرك الآن' : 'قد يستغرق من ثوانٍ لبضع دقائق'; ?></p>

			<?php if ( $sub_id && ! $is_ready ) : ?>
				<div id="ty-progress" style="width:100%;max-width:320px;margin:0 auto 20px;">
					<div style="width:100%;height:6px;background:#e5e7eb;border-radius:3px;overflow:hidden;">
						<div id="ty-bar" style="width:0%;height:100%;background:linear-gradient(90deg,#3b82f6,#6366f1);border-radius:3px;transition:width .6s ease;"></div>
					</div>
					<p id="ty-step" style="font-size:12px;color:#6b7280;margin-top:6px;text-align:center;"></p>
				</div>
			<?php endif; ?>

			<div class="ty-actions">
				<?php if ( $is_ready ) : ?>
					<div class="ty-actions__row">
						<a href="<?php echo esc_url( $store_url ); ?>" class="btn main-button" target="_blank">استعرض متجرك</a>
						<button type="button" class="btn ty-dashboard-btn yaamama-auto-login-btn" data-subscription-id="<?php echo esc_attr( $sub_id ); ?>">
							لوحة تحكم المتجر
						</button>
					</div>
				<?php elseif ( $sub_id ) : ?>
					<div class="ty-actions__row">
						<a href="#" class="btn main-button" id="ty-store-btn" style="opacity:0.5;pointer-events:none;">استعرض متجرك</a>
						<button type="button" class="btn ty-dashboard-btn yaamama-auto-login-btn" id="ty-dashboard-btn" data-subscription-id="<?php echo esc_attr( $sub_id ); ?>" style="opacity:0.5;pointer-events:none;">
							لوحة تحكم المتجر
						</button>
					</div>
				<?php else : ?>
					<div class="ty-actions__row ty-actions__row--single">
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>" class="btn main-button">متاجري</a>
					</div>
				<?php endif; ?>
				<div class="ty-actions__row ty-actions__row--center">
					<a href="<?php echo esc_url( home_url( '/my-account#page-2' ) ); ?>" class="btn secondary-btn">الذهاب إلى متاجري</a>
				</div>
			</div>
		</div>
	</div>
<style>
.ty-actions {
	display: flex;
	flex-direction: column;
	gap: 12px;
	width: 100%;
	max-width: 420px;
	margin: 0 auto;
}
.ty-actions__row {
	display: flex;
	gap: 12px;
}
.ty-actions__row .btn {
	flex: 1;
	justify-content: center;
	text-align: center;
	padding: 14px 16px;
	font-size: 15px;
	font-weight: 700;
	border-radius: 10px;
	text-decoration: none;
	cursor: pointer;
	display: flex;
	align-items: center;
}
.ty-actions__row--center {
	justify-content: center;
}
.ty-actions__row--center .btn {
	flex: 0 1 auto;
	min-width: 200px;
}
.ty-actions__row--single {
	justify-content: center;
}
.ty-dashboard-btn {
	background: #1e40af;
	color: #fff;
	border: none;
}
.ty-dashboard-btn:hover {
	background: #1e3a8a;
}
@media (max-width: 480px) {
	.ty-actions__row {
		flex-direction: column;
	}
	.ty-actions__row .btn {
		width: 100%;
		font-size: 14px;
		padding: 12px 12px;
	}
	.ty-actions__row--center .btn {
		min-width: unset;
		width: 100%;
	}
}
</style>
</main>

<?php if ( $sub_id && ! $is_ready ) : ?>
<style>@keyframes yaamama-spin { to { transform: rotate(360deg); } }</style>
<script>
(function(){
	var subId = <?php echo (int) $sub_id; ?>;
	var ajaxUrl = '<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>';
	var btn = document.getElementById('ty-store-btn');
	var dashBtn = document.getElementById('ty-dashboard-btn');
	var bar = document.getElementById('ty-bar');
	var step = document.getElementById('ty-step');
	var subtitle = document.getElementById('ty-subtitle');
	var note = document.getElementById('ty-note');
	var icon = document.getElementById('ty-icon');
	var progress = document.getElementById('ty-progress');
	var interval = 3000;
	var maxInterval = 15000;

	function poll() {
		fetch(ajaxUrl + '?action=yaamama_check_provision_status&subscription_id=' + subId, { credentials: 'same-origin' })
			.then(function(r) { return r.json(); })
			.then(function(res) {
				if (!res.success || !res.data) { schedule(); return; }
				var d = res.data;

				if (bar && typeof d.progress !== 'undefined') {
					bar.style.width = d.progress + '%';
				}
				if (step && d.step_label) {
					step.textContent = d.step_label + ' (' + (d.progress || 0) + '%)';
				}

				if (d.status === 'completed' || d.status === 'active') {
					if (subtitle) subtitle.textContent = 'متجرك جاهز!';
					if (note) note.textContent = 'يمكنك استعراض متجرك الآن';
					if (bar) bar.style.width = '100%';
					if (step) step.textContent = 'اكتمل!';
					if (icon) icon.innerHTML = '<i class="fa-solid fa-check"></i>';
					if (progress) { setTimeout(function(){ progress.style.display = 'none'; }, 1500); }

					var url = d.store_url || '';
					if (btn && url) {
						btn.href = url;
						btn.target = '_blank';
						btn.style.opacity = '1';
						btn.style.pointerEvents = 'auto';
						btn.classList.add('ty-ready-pulse');
					}
					if (dashBtn) {
						dashBtn.style.opacity = '1';
						dashBtn.style.pointerEvents = 'auto';
					}
					return;
				}

				if (d.status === 'failed') {
					if (subtitle) subtitle.textContent = 'حدث خطأ في إنشاء المتجر';
					if (note) note.textContent = 'يمكنك المحاولة مرة أخرى من صفحة متاجري';
					if (icon) icon.innerHTML = '<i class="fa-solid fa-xmark" style="color:#ef4444;"></i>';
					if (progress) progress.style.display = 'none';
					if (btn) {
						btn.href = '<?php echo esc_url( home_url( '/store' ) ); ?>';
						btn.textContent = 'الذهاب إلى متاجري';
						btn.style.opacity = '1';
						btn.style.pointerEvents = 'auto';
					}
					return;
				}

				schedule();
			})
			.catch(function() { schedule(); });
	}

	function schedule() {
		interval = Math.min(interval * 1.3, maxInterval);
		setTimeout(poll, interval);
	}

	setTimeout(poll, 2000);
})();
</script>
<style>
.ty-ready-pulse {
	animation: ty-pulse 0.6s ease-in-out 3;
}
@keyframes ty-pulse {
	0%, 100% { transform: scale(1); }
	50% { transform: scale(1.05); }
}
</style>
<?php endif; ?>

<script>
document.addEventListener('click', function(e) {
	var btn = e.target.closest('.yaamama-auto-login-btn');
	if (!btn) return;
	e.preventDefault();
	var subId = btn.getAttribute('data-subscription-id');
	if (!subId) return;
	var origText = btn.textContent;
	btn.textContent = 'جاري فتح لوحة التحكم...';
	btn.disabled = true;
	var fd = new FormData();
	fd.append('action', 'yaamama_store_auto_login');
	fd.append('subscription_id', subId);
	fetch('<?php echo esc_url( admin_url("admin-ajax.php") ); ?>', { method:'POST', credentials:'same-origin', body:fd })
	.then(function(r){ return r.json(); })
	.then(function(d){
		if(d.success && d.data && d.data.login_url) window.open(d.data.login_url,'_blank');
		else alert(d.data || 'فشل فتح لوحة التحكم');
		btn.textContent = origText; btn.disabled = false;
	}).catch(function(){ alert('حدث خطأ'); btn.textContent = origText; btn.disabled = false; });
});
</script>
<?php
get_footer();
?>
