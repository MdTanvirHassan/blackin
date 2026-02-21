<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PreventDemoModeChanges;

class OrderDetail extends Model
{
    use PreventDemoModeChanges;

    protected $fillable = [
        'order_id',
        'seller_id',
        'product_id',
        'variation',
        'price',
        'tax',
        'shipping_type',
        'shipping_cost',
        'quantity',
        'payment_status',
        'delivery_status',
        'pickup_point_id',
        'product_referral_code',
        'earn_point',
        'refund_days',
        'combo_id',
        'group_product_slot_id',
        'group_product_slot_combination_hash',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function combo()
    {
        return $this->belongsTo(GroupProduct::class, 'combo_id');
    }
}
