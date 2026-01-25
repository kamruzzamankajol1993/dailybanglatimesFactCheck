<?php

use Carbon\Carbon;

if (!function_exists('bangla_date')) {
    /**
     * Convert English Date/Time to Bangla
     *
     * @param mixed $date Carbon instance or date string
     * @param string $format Date format (default: d F Y)
     * @return string
     */
    function bangla_date($date, $format = 'd F Y')
    {
        if (!$date) return '';

        // যদি $date কার্বন ইন্সট্যান্স না হয়, তবে কার্বনে কনভার্ট করে নেওয়া
        if (!($date instanceof Carbon)) {
            $date = Carbon::parse($date);
        }

        $englishDate = $date->format($format);

        $search = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
            'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
            'am', 'pm', 'AM', 'PM'
        ];

        $replace = [
            '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯',
            'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
            'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর',
            'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার',
            'পূর্বাহ্ণ', 'অপরাহ্ণ', 'পূর্বাহ্ণ', 'অপরাহ্ণ'
        ];

        return str_replace($search, $replace, $englishDate);
    }
}