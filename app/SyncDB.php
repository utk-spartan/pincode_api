<?php
/**
 * Created by PhpStorm.
 * User: utkarshsaxena
 * Date: 29/06/18
 * Time: 12:39
 */

namespace App;

use App\Models\Pincode;
use App\Models\State;

class SyncDB
{

    public function syncLocalDB()
    {
        echo $url = config('services.datagovin.uri')
                    . "&format=json&offset=";
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $tot    = json_decode($client->get($url . '0' . "&limit=1")->getBody(), true)['total'];


        for ($off = 0; $off <= $tot; $off += 1000)
        {
            //$res = $client->get('https://pincode.saratchandra.in/api/pincode/'.$pin);
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
                        ((new State())->where('name', $data['statename'])
                                      ->first()
                                      ->getAttribute('tin')),
                        $data['districtname']
                    );
                    $this->addToPincodeTable($data);
                }

            }
        }

    }

    protected function addToPincodeTable($data)
    {
        $pincode = new Pincode();
        $pincode->fill(['pincode'  => $data[0],
                        'city'     => $data[1],
                        'statetin' => $data[2],
                        'district' => $data[3],
                       ]);
        $pincode->save();
    }
}