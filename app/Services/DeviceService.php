<?php


namespace App\Services;

use App\Http\Resources\MedicalStaffsAPI;
use App\MedicalStaff;
use App\MedicalStaffRole;
use Exception;
use App\Device;
use App\HealthFacility;
use Illuminate\Support\Collection;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class DeviceService {

    public function add($validatedRequest): Device{
        $validatedRequest = $this->updateRedirect($validatedRequest);
        $device = new Device($validatedRequest);
        //Set Parameters for Passport Client Creation
        $userID = Auth::user()->id;
        $clientName = $device->name;
         //Redirect URL is the callback set for either reader or hub devices
        $redirectURL = $device->redirect;        //The following parameters make sure the client can only use the secure PKCE authorization flow
        $provider = null;
        $personalAccess = false;
        $password = false;
        $confidential = $this->getConfidentialFlag($device->type);
        //Get Passport's client repository using the app parameters and create the client using the parameters
        $clientRepository = app('Laravel\Passport\ClientRepository');
        //Create the client using all necessary parameters
        $client = $clientRepository->create(
            $userID,
            $clientName,
            $redirectURL,
            $provider,
            $personalAccess,
            $password,
            $confidential
        );
        //Update the device information and link it to the client ID
        $device->user_id = Auth::user()->id;
        $device->oauth_client_id = $client->id;
        $device->oauth_client_secret = $client->secret;
        $device->save();
        return $device;
    }


    public function update($validatedRequest,Device $device): Device{
        //Update the device and then update the OAuth client (only name and device type matter here)
        $device->fill($validatedRequest)->save();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        if($client !== null){
            $redirectURL = $device->redirect;
            $clientRepository->update($client,$validatedRequest['name'],$redirectURL);
        }
        return $device;
    }


    public function remove(Device $device){
        //Remove the device and Revoke the associated OAuth client
        $id = $device->id;
        $device->delete();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        if($client !== null){
            $clientRepository->delete($client);
        }
        return $id;
    }

    /**
     * Returns the Device that made a request with a token
     */
    public function getDeviceFromAuthRequest($request): Device {
        //Get Token from request
        $bearerToken=$request->bearerToken();
        //Parse Token using Library
        $parsedJwt = (new Parser())->parse($bearerToken);
        //Check if Client ID is located in the header or in the claim field
        if ($parsedJwt->hasHeader('jti')) {
            $tokenId = $parsedJwt->getHeader('jti');
        } elseif ($parsedJwt->hasClaim('jti')) {
            $tokenId = $parsedJwt->getClaim('jti');
        } else {
            Log::error('Invalid JWT token, Unable to find JTI header');
            return null;
        }
        //Fetch the client and then the corresponding device associated
        $client = Token::find($tokenId)->client;
        $device = Device::where('oauth_client_id',$client->id)->first();
        return $device;
    }

    /**
     * Returns an array with the pin code and hub IP for the device (intended for reader devices)
     */
    public function getHealthFacilityInfo(Device $device){
        $healthFacility = HealthFacility::where('id',$device->health_facility_id)->first();
        if ($healthFacility == null) {
            throw new Exception("Device is not associated with any Health Facilities");
        }

        $medicalStaffs = MedicalStaff::where('health_facility_id', $healthFacility->id)->get();

        $AllMedicalStaffRoleLabel = new Collection();
        MedicalStaffRole::all()->each(function($medicalStaffRole) use (&$AllMedicalStaffRoleLabel){
            $AllMedicalStaffRoleLabel->add($medicalStaffRole->label);
        });

        return array(
            "id" => $healthFacility->id,
            "name" => $healthFacility->name,
            "created_at" => $healthFacility->created_at,
            "updated_at" => $healthFacility->updated_at,
            "local_data_ip" => $healthFacility->local_data_ip,
            "main_data_ip" => Config::get('medal-data.global.ip'),
            "architecture" => $healthFacility->architecture,
            "pin_code" => $healthFacility->pin_code,
            "latitude" => $healthFacility->lat,
            "longitude" => $healthFacility->long,
            "country" => $healthFacility->country,
            "area" => $healthFacility->area,
            "study_id" => Config::get('medal-data.global.study_id'),
            "medical_staffs" => MedicalStaffsAPI::collection($medicalStaffs),
            "medical_staff_roles" => $AllMedicalStaffRoleLabel,
        );
    }

    /**
     * Updates the given device model with system information uploaded by the device itself
     */
    public function storeDeviceInfo(Device $device,$validatedDeviceInfoRequest){
        error_log($device->name);
        foreach($validatedDeviceInfoRequest as $key => $value){
            error_log($key . $value);
        }
        $device->fill($validatedDeviceInfoRequest)->save();
    }

    /**
     * Returns the Redirect URL associated with a specific device
     */
    private function updateRedirect($validatedRequest){
        if($validatedRequest['type'] == "reader"){
            $validatedRequest['redirect'] = Config::get('medal.authentication.reader_callback_url');
        }
        return $validatedRequest;
    }
    /**
     * Returns the type of Grant for the device (hub->confidential->client-credentials reader->non-confidential->pkce)
     */
    private function getConfidentialFlag($deviceType){
        $confidential = false;
        switch($deviceType){
            case "hub":
                $confidential = false;
                break;
            case "reader":
                $confidential = false;
                break;
        }
        return $confidential;
    }

}
