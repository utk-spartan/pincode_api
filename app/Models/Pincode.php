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
        $pincode = $this->where('pincode', $pin)
                        ->get()
                        ->unique();

        $res = new Collection();

        foreach ($pincode as $item)
        {

            $res->add(
                collect($item)->except('statetin')
                              ->put(
                                  "state",
                                  $this->stateName($item->state())
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
        return $this->belongsTo('App\Models\State', 'statetin', 'tin');
    }

    /**
     * Get state name from the state table using the state tin number
     *
     * @param $state
     *
     * @return mixed
     */
    public function stateName($state)
    {
        return $state->first()
                     ->getAttribute('name');
    }


}
