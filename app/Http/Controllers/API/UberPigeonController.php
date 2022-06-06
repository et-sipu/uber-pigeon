<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pigeons;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class UberPigeonController extends Controller
{
    /**
     * Checks all pigeon's availability
     */
    public function checkOrder(Request $request){
        $pigeons = Pigeons::all();

        foreach($pigeons as $key => $pigeon){
            $data = $this->check($request,$pigeon);
            if($data['status'] == 'available'){
                $result[$key] = $data;
            }
        }

        if(isset($result)){
            return [
                "status" => "success",
                "message"=> "Pigeon are available to proceed this order",
                "data" => array_values($result)
            ];
        }else{
            return [
                "status" => "reject",
                "message" => "Non of the pigeons are available to execute the order"
            ];
        }
    }

    /**
     * Check order's availability, and subsequently its cost.
     */
    private function check($request,$pigeon){
        $this->pigeon = $pigeon->name;
        $this->data = $this->standardizeData($request,$pigeon);

        /**
         * Any future expansions require to create/add new method below
        */
        try {
            return $this
                    ->downtime()
                    ->range()
                    ->speed()
                    ->cost()
                    ->result();
        }catch(Exception $e) {
            return $this->reject($e->getMessage());
        }
    }

    /**
     * Check if the order distance exceeds the assigned pigeon range
     */
    private function range(){
        if($this->data['range'] >= $this->data['distance']){
            return $this;
        }else{
            throw new Exception("Out of pigeon's range");
        }
    }

    /**
     * Check if the given deadline is doable
     */
    private function speed(){
        $eta = $this->data['distance'] / $this->data['speed'];
        $period = (Carbon::now()->diffInMinutes(Carbon::parse($this->data['deadline']),false))/60;
        $this->eta = Carbon::now()->addMinutes($eta*60)->toDateTimeString();
        
        if($period >= $eta){
            return $this;
        }elseif($period < 0){
            throw new Exception("Deadline have exceeded");
        }else{
            throw new Exception("Unable to deliver before deadline");
        }
    }

    /**
     * Calculate the total cost of the order
     */
    private function cost(){
        $this->totalCost = $this->data['distance']*$this->data['cost'];
        return $this;
    }

    /**
     * Check either the pigeon assigned is in downtime period or not
     * 
     * NOTE: FOR DEMONSTRATION PURPOSES, CHEKING FOR DOWNTIME IS HIDDEN
     *       SINCE THERE IS NO FEATURE TO CAPTURE LATEST DELIVERY DATE/TIME
     */
    private function downtime(){
        return $this;

        // $period_since_last_delivery = Carbon::parse($this->data['latest_delivery_at'])->diffInHours(Carbon::now());
        // if($period_since_last_delivery >= $this->data['downtime']){
        //     return $this;
        // }else{
        //     $message = "Pigeon still in downtime period";
        //     exit($this->reject($message));
        // }
    }

    /**
     * Display API reponse if the order cannot be executed
     */
    private function reject($message){
        $response = [
            "status" => "unavailable",
            "message" => $message
        ];

        return $response;
    }

    /**
     * Display API response if the order can be proceed
     */
    private function result(){
        $response = [
            "status" => "available",
            "pigeon" => $this->pigeon,
            "total_cost" => $this->totalCost,
            "eta" => $this->eta
        ];

        return $response;
    }

    /**
     * Compile all data into an array
     * Any new fields need to be added here
     */
    private function standardizeData($request,$pigeon){

        $data = [
            "latest_delivery_at" => $pigeon->latest_delivery_at,
            "downtime" => $pigeon->downtime,
            "range" => $pigeon->range,
            "distance" => $request->distance,
            "speed" => $pigeon->speed,
            "deadline" => $request->deadline,
            "cost" => $pigeon->cost
        ];

        return $data;
    }
}
