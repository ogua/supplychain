<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ["id"];

    public function companyusers() : BelongsToMany {
        return $this->belongsToMany(User::class,'company_users','company_id','user_id')
        ->withPivot([
            'role'
        ]);
    }

    public function products() : HasMany {
        return $this->hasMany(Products::class,"company_id");
    }
}
