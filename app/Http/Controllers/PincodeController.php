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
use Illuminate\Support\Facades\Validator;

class PincodeController
{
    /**
     * Handles request for finding all adresses for a given pincode
     *
     * @param $pin
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPin($pin)
    {
        $pincodes = new Pincode();

        return response()->json($pincodes->findPin($pin));
    }

    /**
     * Handles request for finding all pincodes assigned to an address
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function searchAddress(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'city' => 'nullable|max:30',
            'state' => 'required|max:30',
        ]);

        if ($validator->fails()) {
            return $validator->getMessageBag();
        }

        return $this->getPinFromAddress($request->query('city'), $request->query('state'));
    }

    /**
     * Queries the database for retrieving pincodes of given address
     * If the city is not provided it returns all pincodes corresponding to that state
     *
     * @param $city
     * @param $stName
     *
     * @return mixed
     */
    public function getPinFromAddress($city, $stName)
    {
        $state = new State();
        $state = $state->where('name', $stName)
                       ->first()
                       ->pincodes();
        if ($city !== null)
        {
            $state = $state->where('city', $city);
        }

        return $state->get();
    }
}