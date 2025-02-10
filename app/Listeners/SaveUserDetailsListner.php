<?php

namespace App\Listeners;

use App\Events\UserSaved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\Detail;
use Illuminate\Support\Facades\Auth;

class SaveUserDetailsListner
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        Log::info("reached in listner");
        Log::info(json_encode($event));
        Log::info(Auth::id());
        $event = json_decode(json_encode($event), true);
        $middleAbbrevation =  strtoupper($event['userdata']['middlename'][0]);
        $fullname = $event['userdata']['firstname'] . " " . $middleAbbrevation . " " . $event['userdata']['lastname'];
        $gender = $event['userdata']['prefixname'] == "Mr" || $event['userdata']['prefixname'] == "Master" ? "Male" : "Female" ;
        Detail::create([
            "fullname" => $fullname,
            "gender" => $gender,
            "type" => $event['userdata']['type'],
            "icon" => $event['userdata']['photo'],
            "user_id" => Auth::id(),
        ]);
        
    }
}
