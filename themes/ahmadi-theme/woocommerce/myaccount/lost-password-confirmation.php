<?php
/**
 * Password reset confirmation.
 */
defined('ABSPATH') || exit;

wc_print_notice(
    'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني. قد يستغرق وصوله بضع دقائق، يرجى الانتظار 10 دقائق قبل المحاولة مرة أخرى.',
    'success'
);
