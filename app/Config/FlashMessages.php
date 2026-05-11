<?php

namespace App\Config;

class FlashMessages
{
    // پیام‌های موفقیت
    public static $success = [
        'user_create_success' => [
            'title' => 'کاربر جدید',
            'message' => 'کاربر با موفقیت ایجاد شد.',
            'type' => 'success'
        ],
        'user_update_success' => [
            'title' => 'بروزرسانی کاربر',
            'message' => 'اطلاعات کاربر با موفقیت بروزرسانی شد.',
            'type' => 'success'
        ],
        'user_delete_success' => [
            'title' => 'حذف کاربر',
            'message' => 'کاربر با موفقیت حذف شد.',
            'type' => 'success'
        ],
    ];

    // پیام‌های خطا
    public static $error = [
        'user_create_error' => [
            'title' => 'خطا در ایجاد',
            'message' => 'مشکلی در ایجاد کاربر پیش آمده. لطفاً دوباره تلاش کنید.',
            'type' => 'danger'
        ],
        'user_update_error' => [
            'title' => 'خطا در بروزرسانی',
            'message' => 'مشکلی در بروزرسانی پیش آمده. لطفاً دوباره تلاش کنید.',
            'type' => 'danger'
        ],
        'user_not_found' => [
            'title' => 'کاربر یافت نشد',
            'message' => 'کاربر مورد نظر در سیستم وجود ندارد.',
            'type' => 'danger'
        ],
        'validation_error' => [
            'title' => 'خطای اعتبارسنجی',
            'message' => 'لطفاً اطلاعات را بررسی کنید.',
            'type' => 'danger'
        ],
    ];

    // پیام‌های اطلاع‌رسانی
    public static $info = [
        'loading' => [
            'title' => 'لطفا صبر کنید',
            'message' => 'در حال پردازش اطلاعات...',
            'type' => 'theme'
        ],
        'no_data' => [
            'title' => 'اطلاعاتی یافت نشد',
            'message' => 'هیچ داده‌ای برای نمایش وجود ندارد.',
            'type' => 'info'
        ],
    ];

    public static function get($key, $customMessage = null)
    {
        if (isset(self::$success[$key])) {
            $msg = self::$success[$key];
        }
        elseif (isset(self::$error[$key])) {
            $msg = self::$error[$key];
        }
        elseif (isset(self::$info[$key])) {
            $msg = self::$info[$key];
        }
        else {
            return [
                'title' => 'پیام',
                'message' => $customMessage ?? 'عملیات با موفقیت انجام شد.',
                'type' => 'info'
            ];
        }

        if ($customMessage) {
            $msg['message'] = $customMessage;
        }

        return $msg;
    }
}