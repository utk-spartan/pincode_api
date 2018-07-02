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
        return $this->hasMany('App\Models\Pincode',
                              config('database.tables.pincode.statetin'),
                              config('database.tables.state.tin')
        );
    }

    /**
     * Return the associated state Name
     *
     * @return mixed
     */
    public function stateName()
    {
        return $this->getAttribute(config('database.tables.state.name'));
    }

    /**
     * Return the associated state Code
     *
     * @return mixed
     */
    public function stateCode()
    {
        return $this->getAttribute(config('database.tables.state.code'));
    }
}
