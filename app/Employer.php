<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use SoftDeletes;

    const MESSENGER_TYPE = [
        'Whatsapp',
        'Skype',
        'Zoom',
        'Others'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'designation',
        'business_name',
        'city_id',
        'state_id',
        'country_id',
        'industry_id',
        'phone',
        'messenger_type',
        'messenger_id',
        'email',
        'status_id',
        'requisition_id',
        'user_id',
        'fees'
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'employer_user');
    }

    public function current_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function canEdit()
    {
        return (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->id === $this->user_id));
    }

    public function canDelete()
    {
        return (auth()->check() && auth()->user()->isAdmin());
    }

    public function canAssign()
    {
        return $this->canEdit();
    }

    public function canComment()
    {
        return $this->canEdit();
    }

}
