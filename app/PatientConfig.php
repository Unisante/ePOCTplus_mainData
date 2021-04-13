<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Version;
class PatientConfig extends Model
{
  protected $table = 'patient_configs';
  protected $guarded = [];

  /**
  * Create or get patient configuration
  * @params $config
  * @params $version_id
  * @return $config_data
  */
  public static function getOrCreate($version){

    $dataConfig=self::fetchConfig($version->medal_c_id);
    $configurations=json_encode($dataConfig);
    $config_fetch=PatientConfig::firstOrCreate(
      [
        "version_id"=>$version->id,
      ],
      [
        "config"=>$configurations
      ]
    );
    $config_data=json_decode($config_fetch->config);
    return $config_data;
  }

  public static function fetchConfig($version_id){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://medalc.unisante.ch/api/v1/versions/medal_data_config?version_id='.$version_id,
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
      $config_data = json_decode($response, true);
      $config_array=[];
      collect($config_data)->each(function($item, $key)use(&$config_array){
        if($key != 'study_id'){
          $item=(int)$item;
        }
        $config_array[$key]=$item;
      });
      return $config_array;
    }
  }
  /**
  * get patient configuration
  * @params $version_id
  * @return $config
  */
  // public static function getConfig($version_id){
  //   $config = PatientConfig::where('version_id',$version_id)->first();
  //   $config=json_decode($config->config);
  //   return $config;
  // }
}
