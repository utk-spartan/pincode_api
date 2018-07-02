<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincode';

    /*
     * Make all fields mass assignable required for synchronizing DB
     */
    protected $guarded = [];

    /**
     * Fetch all addresses having the given pincode
     *
     * @param $pin
     *
     * @return Collection
     */
    function findPin($pin)
    {
        $pincode = $this->where(config('database.tables.pincode.pincode'), $pin)
                        ->get()
                        ->unique();

        $res = new Collection();

        foreach ($pincode as $item)
        {

            $res->add(
                collect($item)->except(config('database.tables.pincode.statetin'))
                              ->put(
                                  "state",
                                  ($item->state())->first()->stateName()
                              )
                              ->put(
                                  "stateCode",
                                  ($item->state())->first()->stateCode()
                              )
            );
        }

        return $res;
    }

    /**
     * DB table relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', config('database.tables.pincode.statetin'), config('database.tables.state.tin'));
    }
}
