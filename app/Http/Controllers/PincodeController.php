<?php
/**
 * Created by PhpStorm.
 * User: utkarshsaxena
 * Date: 29/06/18
 * Time: 13:03
 */

namespace App\Http\Controllers;

use App\Models\Pincode;
use App\Models\State;
use Illuminate\Http\Request;

class PincodeController
{
    public function searchPin($pin)
    {
        $pincodes = new Pincode();

        return response()->json($pincodes->findPin($pin));
    }

    public function searchAddress(Request $request)
    {
        return $this->getPinFromAddress($request->query('city'), $request->query('state'));
    }

    public function getPinFromAddress($city, $stName)
    {
        $state = new State();
        return $state->where('name',$stName)
                     ->first()
                     ->pincodes()
                     ->where('city',$city)
                     ->get();
    }
}