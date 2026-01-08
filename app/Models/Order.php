<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total_amount',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // This is the direct relationship to order items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // This is the Many-to-Many relationship through junction table
    public function menus()
    {
        return $this->belongsToMany(Crud::class, 'order_items', 'order_id', 'crud_id')
            ->withPivot('quantity', 'unit_price', 'subtotal', 'special_instructions', 'status')
            ->withTimestamps();
    }

    // Add this alias if you want to keep using 'menu' in your code
    public function menu()
    {
        return $this->belongsToMany(Crud::class, 'order_items', 'order_id', 'crud_id')
            ->withPivot('quantity', 'unit_price', 'subtotal', 'special_instructions', 'status')
            ->withTimestamps();
    }
}
