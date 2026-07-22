<?php

namespace App\Helpers;

class Terbilang
{
    protected static array $angka = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas',
    ];

    /**
     * Convert a number to its Indonesian word representation.
     */
    public static function convert(int|float $number): string
    {
        $number = (int) round($number);

        if ($number < 0) {
            return 'minus ' . self::convert(abs($number));
        }

        if ($number < 12) {
            return trim(self::$angka[$number]);
        }

        if ($number < 20) {
            return trim(self::convert($number - 10) . ' belas');
        }

        if ($number < 100) {
            $sisa = $number % 10;
            return trim(self::convert((int) ($number / 10)) . ' puluh ' . self::convert($sisa));
        }

        if ($number < 200) {
            return trim('seratus ' . self::convert($number - 100));
        }

        if ($number < 1000) {
            $sisa = $number % 100;
            return trim(self::convert((int) ($number / 100)) . ' ratus ' . self::convert($sisa));
        }

        if ($number < 2000) {
            return trim('seribu ' . self::convert($number - 1000));
        }

        if ($number < 1000000) {
            $sisa = $number % 1000;
            return trim(self::convert((int) ($number / 1000)) . ' ribu ' . self::convert($sisa));
        }

        if ($number < 1000000000) {
            $sisa = $number % 1000000;
            return trim(self::convert((int) ($number / 1000000)) . ' juta ' . self::convert($sisa));
        }

        if ($number < 1000000000000) {
            $sisa = $number % 1000000000;
            return trim(self::convert((int) ($number / 1000000000)) . ' miliar ' . self::convert($sisa));
        }

        $sisa = $number % 1000000000000;
        return trim(self::convert((int) ($number / 1000000000000)) . ' triliun ' . self::convert($sisa));
    }

    /**
     * Convert a number to Indonesian words with a capitalized first letter
     * and a trailing "Rupiah", for use on invoices.
     */
    public static function rupiah(int|float $number): string
    {
        $words = self::convert($number);
        $words = preg_replace('/\s+/', ' ', $words);
        $words = ucwords(trim($words));

        return $words . ' Rupiah';
    }
}