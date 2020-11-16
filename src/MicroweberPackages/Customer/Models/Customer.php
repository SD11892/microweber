<?php

namespace MicroweberPackages\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Currency\Currency;
use MicroweberPackages\Payment\Payment;

class Customer extends Model
{
    public $fillable = [
        'name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'active',
        'user_id',
        'currency_id',
        'company_id'
    ];

    public $translatable = ['first_name','last_name'];

    public function getActiveAttribute($attribute)
    {
        return $this->activeOptions()[$attribute];
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::BILLING_TYPE);
    }

    public function shippingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::SHIPPING_TYPE);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(\MicroweberPackages\User\Models\User::class);
    }

    public function scopeWhereDisplayName($query, $displayName)
    {
        return $query->where('name', 'LIKE', '%'.$displayName.'%');
    }

    public function scopeWherePhone($query, $phone)
    {
        return $query->where('phone', 'LIKE', '%'.$phone.'%');
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('contact_name')) {
            $query->whereContactName($filters->get('contact_name'));
        }

        if ($filters->get('name')) {
            $query->where('first_name', 'like', '%' . $filters->get('name') . '%');
            $query->orWhere('last_name', 'like', '%' . $filters->get('name') . '%');
        }

        if ($filters->get('phone')) {
            $query->wherePhone($filters->get('phone'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'name';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }
    }

    public function activeOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'In-Progress'
        ];
    }

   /* public function delete()
    {
        if ($this->payments()->exists()) {
            $this->payments()->delete();
        }

        if ($this->addresses()->exists()) {
            $this->addresses()->delete();
        }

        $this->delete();

        return true;
    }*/
}
