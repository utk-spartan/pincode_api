<?php
/**
 * Created by PhpStorm.
 * User: utkarshsaxena
 * Date: 29/06/18
 * Time: 12:39
 */

namespace App\Core;

use App\Models\Pincode;
use App\Models\State;

class SyncDB
{

    /**
     * Synchronize the local Database with data from data.gov.in
     *
     */
    public function syncLocalDB()
    {
        $url    = config('services.datagovin.uri')
                  . "&format=json&offset=";
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $res    = $client->get($url . '0' . "&limit=1");
        if ($res->getStatusCode() != 200)
        {
            echo "Error connecting external api or invalid respponse";

            return;
        }

        $this->prepareDatabase();
        $tot = json_decode($res->getBody(), true)['total'];

        for ($off = 0; $off <= $tot; $off += 1000)
        {
            $res  = $client->get($url . $off . "&limit=1000");
            $json = json_decode($res->getBody(), true);
            if ($res->getStatusCode() == 200)
            {
                $datas = $json['records'];
                foreach ($datas as $data)
                {
                    $data = array(
                        $data['pincode'],
                        $data['taluk'],
                        ((new State())->where(config('database.tables.state.name'), $data['statename'])
                                      ->first()
                                      ->getAttribute(config('database.tables.state.tin'))),
                        $data['districtname']
                    );
                    $this->addToPincodeTable($data);
                }

            }
        }

    }


    /**
     * Add data from input array to pincode table
     *
     * @param $data
     */
    protected function addToPincodeTable($data)
    {
        $pincode = new Pincode();
        $pincode->fill([config('database.tables.pincode.pincode')  => $data[0],
                        config('database.tables.pincode.city')     => $data[1],
                        config('database.tables.pincode.statetin') => $data[2],
                        config('database.tables.pincode.district') => $data[3],
                       ]);
        $pincode->save();
    }

    /**
     * Prepare database for synchronization by purging the pincode table
     */
    protected function prepareDatabase()
    {
        $table = new Pincode();
        $table->newModelQuery()->delete();
    }
}
