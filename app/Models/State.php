<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table = 'state';

    protected $guarded = [];

    /**
     * DB table relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pincodes()
    {
        return $this->hasMany('App\Models\Pincode', 'statetin', 'tin');
    }
}
