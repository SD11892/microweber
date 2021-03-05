<?php

namespace MicroweberPackages\User\Models;

use EloquentFilter\Filterable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use MicroweberPackages\Customer\Models\Customer;
use MicroweberPackages\Database\Casts\StripTagsCast;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\User\Models\ModelFilters\UserFilter;
use MicroweberPackages\User\Notifications\MailResetPasswordNotification;
use MicroweberPackages\User\Notifications\MustVerifyEmailTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Validation\Rule;

use carbon\carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, HasApiTokens, Filterable, MustVerifyEmailTrait, CanResetPassword, CacheableQueryBuilderTrait;

    protected $casts = [
        'username' => StripTagsCast::class,
    ];

    protected $attributes = [
        'is_active' => 1,
        'is_admin' =>0,
        'is_verified' =>0,
    ];

    // use the trait
    //  use RevisionableTrait;

    // Set revisionable whitelist - only changes to any
    // of these fields will be tracked during updates.
    protected $revisionable = [
        'email',
        'username',
        'first_name',
        'last_name',
        'phone',
        'name',
        'last_login',
        'last_login_ip',
        'created_by',
        'edited_by',
        'username',
        'password',
        'email',
        'is_active',
        'is_admin',
        'is_verified',
        'is_public',
        'oauth_uid',
        'oauth_provider',
    ];

    // Or revisionable blacklist - if $revisionable is not set
    // then you can exclude some fields from being tracked.
    protected $nonRevisionable = [
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'api_key',
        'remember_token',
        'oauth_token',
        'oauth_token_secret',
        'password',
        'is_admin',
    ];

    //protected $hidden = array('password', 'remember_token');
    protected $fillable = array(
        'updated_at',
        'created_at',
        'expires_on',
        'last_login',
        'last_login_ip',
        'created_by',
        'edited_by',
        'username',
        'password',
        'email',
        'is_active',
        'is_admin',
        'is_verified',
        'is_public',
        'basic_mode',
        'first_name',
        'last_name',
        'thumbnail',
        'parent_id',

        'user_information',
        'subscr_id',
        'role',
        'medium',
        'oauth_uid',
        'oauth_provider',
        'profile_url',
        'website_url',
        'phone',

    );

    protected $rules = [
        'email' => 'required'
    ];

    private $validator;


    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {

           //$model->is_active = 0;
           $model->is_verified = 0;
        });
    }

    public function modelFilter()
    {
        return $this->provideFilter(UserFilter::class);
    }

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = (Hash::needsRehash($pass) ? Hash::make($pass) : $pass);
    }

    /**
     * Find the user instance for the given username.
     *
     * @param  string $username
     * @return \App\User
     */
    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    public function isAdmin()
    {
        return ($this->role == 'admin');
    }

    public static function login($request)
    {
        $remember = $request->remember;
        $email = $request->email;
        $password = $request->password;
        return (\Auth::attempt(array('email' => $email, 'password' => $password), $remember));
    }

//    public function getFormattedCreatedAtAttribute($value)
//    {
//        $dateFormat = CompanySetting::getSetting('carbon_date_format', $this->company_id);
//        return Carbon::parse($this->created_at)->format($dateFormat);
//    }

    /**
     * Override the mail body for reset password notification mail.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    public function scopeWhereOrder($query, $orderByField, $orderBy)
    {
        $query->orderBy($orderByField, $orderBy);
    }

    public function scopeWhereSearch($query, $search)
    {
        foreach (explode(' ', $search) as $term) {
            $query->where(function ($query) use ($term) {
                $query->where('name', 'LIKE', '%' . $term . '%')
                    ->orWhere('company_name', 'LIKE', '%' . $term . '%');
            });
        }
    }

    public function scopeWhereContactName($query, $contactName)
    {
        return $query->where('contact_name', 'LIKE', '%' . $contactName . '%');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

//    public function scopeWhereCompany($query, $company_id)
//    {
//        $query->where('users.company_id', $company_id);
//    }

    public function scopeApplyInvoiceFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('from_date') && $filters->get('to_date')) {
            $start = Carbon::createFromFormat('d/m/Y', $filters->get('from_date'));
            $end = Carbon::createFromFormat('d/m/Y', $filters->get('to_date'));
            $query->invoicesBetween($start, $end);
        }
    }

    public function scopeInvoicesBetween($query, $start, $end)
    {
        $query->whereHas('invoices', function ($query) use ($start, $end) {
            $query->whereBetween(
                'invoice_date',
                [$start->format('Y-m-d'), $end->format('Y-m-d')]
            );
        });
    }

    public function getAvatarAttribute()
    {
        $avatar = $this->getMedia('admin_avatar')->first();
        if ($avatar) {
            return asset($avatar->getUrl());
        }
        return;
    }

    public function getValidatorMessages()
    {
        return $this->validator->messages()->toArray();
    }

    public function validateAndFill($data)
    {
        if (!empty($data['password']) && !empty($data['verify_password'])) {
            $this->rules['password'] = 'required|min:1';
            $this->rules['verify_password'] = 'required|same:password';
        }

        $requireUsername = false;
        if ((!isset($data['username']) || empty($data['username'])) && (!isset($data['email']) || empty($data['email']))) {
            $requireUsername = true;
        }

        if ($requireUsername && isset($data['id']) && $data['id'] > 0) {
            $this->rules['username'] = [
                'required',
                'min:1',
                Rule::unique('users', 'username')->ignore($data['id'], 'id')
            ];
        }

        $this->validator = \Validator::make($data, $this->rules);
        if ($this->validator->fails()) {
            return false;
        }
        $this->fill($data);

        return true;
    }



}
