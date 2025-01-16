<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function items() : HasMany {
        return $this->hasMany(OrderItem::class,"order_id");
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class,"user_id");
    }

    public function company() : BelongsTo {
        return $this->belongsTo(Company::class,"company_id");
    }

    public function payment() : HasOne {
        return $this->hasOne(Payment::class,"order_id");
    }

    public function payments() : HasMany {
        return $this->hasMany(Payment::class,"order_id");
    }

    public function client() : BelongsTo {
        return $this->belongsTo(Customer::class,"user_id");
    }

    public function delivery() : HasOne {
        return $this->hasOne(Shipment::class,"order_id");
    }
}
