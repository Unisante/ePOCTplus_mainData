<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HealthFacility extends Model
{
  protected $guarded = [];
  // fetch the the facility information

  public static function fetchHealthFacility($group_id = null){
    if(!$group_id){
      $facility_doesnt_exist=HealthFacility::where('group_id',$group_id)->doesntExist();
      if($facility_doesnt_exist){
        // setting headers for when we secure this part of quering from medal c
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //   'Header-Key: Header-Value',
        //   'Header-Key-2: Header-Value-2'
        // ));
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://liwi-test.wavelab.top/api/v1/versions/'.$group_id,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return response()->json([
            "error"=>json_decode($err, true)
          ]);
        } else {
          $health_facilty = json_decode($response, true);
          self::store($health_facilty);
        }
      }
    }
  }
  public static function store($healthFacilityInfo){
    HealthFacility::firstOrCreate(
      [
        'facility_name'=>$healthFacilityInfo['facility_name']
      ],
      [
        'group_id'=>$healthFacilityInfo['group_id'],
        'long'=>$healthFacilityInfo['long'],
        'lat'=>$healthFacilityInfo['lat'],
        'hf_mode'=>$healthFacilityInfo['hf_mode']
      ]
    );
  }
}
