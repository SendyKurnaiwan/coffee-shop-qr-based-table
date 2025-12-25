<?php

namespace App\Traits;

trait hasFormatRupiah
{
    public function formatCurrency($number, $currency = 'IDR')
    {
        if ($number >= 1000) {
            $formatted_number = number_format($number/1000, 1) . 'K';
        } else {
            $formatted_number = $number;
        }

        return $currency . ' ' . $formatted_number;
    }
}
