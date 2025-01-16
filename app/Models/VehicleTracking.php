<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleTracking extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    protected $appends = [
    'location'];
 
 
public function location(): Attribute
{
    return Attribute::make(
        get: fn ($value, $attributes) => json_encode([
            'lat' => (float) $attributes['lat'],
            'lng' => (float) $attributes['lng'],
        ]),
        set: fn ($value) => [
            'lat' => $value['lat'],
            'lng' => $value['lng'],
        ],
    );
}

    public function vehicle() : BelongsTo {
        return $this->belongsTo(Vehicle::class,"vehicle_id");
    }

    public function shipment() : BelongsTo {
        return $this->belongsTo(Shipment::class,"shipment_id");
    }
}
