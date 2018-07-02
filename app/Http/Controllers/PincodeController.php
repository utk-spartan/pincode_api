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
        $validator = Validator::make(array($pin), [$pin => 'bail | integer | between:99999,1000000']);

        if ($validator->fails())
        {
            return response()->json("Invalid Pincode", 404);
        }

        $pincodes = new Pincode();
        $res      = $pincodes->findPin($pin);
        if ($res === null | $res->isNotEmpty())
        {
            return response()->json($res->groupBy('pincode'), 200);
        }
        else
        {
            return response()->json("Pincode not found", 404);
        }
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
            'city'  => 'nullable|max:30',
            'state' => 'required|max:30',
        ]);

        if ($validator->fails())
        {
            return $validator->getMessageBag();
        }

        $res = $this->getPinFromAddress($request->query('city'), $request->query('state'));
        if ($res !== null)
        {
            return response()->json($res->groupBy('pincode'), 200);
        }
        else
        {
            return response()->json("Not found", 404);
        }
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
        $state = $state->where('name', 'LIKE', '%' . $stName . '%')
                       ->first();
        if ($state === null)
        {
            return null;
        }
        $state = $state->pincodes();
        if ($city !== null)
        {
            $state = $state->where('city', $city);
        }

        $res = $state->get();
        if($res->isEmpty())return NULL;
        else return $res;
    }
}