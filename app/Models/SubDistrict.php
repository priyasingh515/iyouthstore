<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'sub_districts';

    protected $fillable = [
        'name',
        'district_id',
        'status'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function district()
    {
        return $this->belongsTo(City::class);
    }

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
