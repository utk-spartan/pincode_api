<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Pincode extends Model
{
    protected $table = 'pincode';

    protected $guarded = [];

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

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'statetin', 'tin');
    }

    public function stateName($state)
    {
        return $state->first()
                     ->getAttribute('name');
    }


}
