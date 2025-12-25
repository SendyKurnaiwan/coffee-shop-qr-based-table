<?php

namespace App\Models;

use App\Traits\HasFormatRupiah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crud extends Model
{
    use HasFactory;
    use HasFormatRupiah;

    public function getFormattedPriceAttribute()
    {
        return $this->formatCurrency($this->price, 'IDR');
    }
    protected $guarded = [];
}
