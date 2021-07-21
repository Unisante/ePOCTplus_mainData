<?php


namespace App\Services;

use App\Device;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\DeviceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class DeviceService {



    public function add($validatedRequest): Device{
        $device = new Device($validatedRequest);
        //Set Parameters for Passport Client Creation
        $userID = Auth::user()->id;
        $clientName = $device->name;
         //Redirect URL is the callback set for either reader or hub devices
        $redirectURL = $this->getRedirectURL($device->type);
        //The following parameters make sure the client can only use the secure PKCE authorization flow
        $provider = null;
        $personalAccess = false;
        $password = false;
        $confidential = false;
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
        $device->save();
        return $device;
    }


    public function update($validatedRequest,Device $device): Device{
        //Update the device and then update the OAuth client (only name and device type matter here)
        $device->fill($validatedRequest)->save();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        $redirectURL = $this->getRedirectURL($device->type);
        $clientRepository->update($client,$validated['name'],$redirectURL);
        return $device;
    }


    public function remove(Device $device){
        //Remove the device and Revoke the associated OAuth client
        $id = $device->id;
        $device->delete();
        $clientRepository = app('Laravel\Passport\ClientRepository');
        $client = $clientRepository->findForUser($device->oauth_client_id,Auth::user()->id);
        $clientRepository->delete($client);
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
        return $client;
    }

    /**
     * Returns the Redirect URL associated with a specific device
     */
    private function getRedirectURL($deviceType){
        $redirectURL = "";
        switch ($deviceType){
            case "hub":
                $redirectURL = Config::get('medal.authentication.hub_callback_url');
                break;
            case "reader":
                $redirectURL = Config::get('medal.authentication.reader_callback_url');
                break;
        }
        return $redirectURL;
    }
}