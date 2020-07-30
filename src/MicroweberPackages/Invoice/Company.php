<?php
namespace MicroweberPackages\Invoice;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Invoice\User;
use MicroweberPackages\Invoice\CompanySetting;

class Company extends Model
{
    protected $fillable = ['name', 'logo', 'unique_hash'];

    protected $appends=['logo'];

    /*public function getLogoAttribute()
    {
        $logo = $this->getMedia('logo')->first();
        if ($logo) {
            return asset($logo->getUrl());
        }
        return ;
    }*/

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function settings()
    {
        return $this->hasMany(CompanySetting::class);
    }
}
