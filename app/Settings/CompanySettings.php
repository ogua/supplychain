<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    public string $name;
    
    public string $companyusers;

    public string $address;
    
    
    public static function group(): string
    {
        return 'schoolsettings';
    }
}