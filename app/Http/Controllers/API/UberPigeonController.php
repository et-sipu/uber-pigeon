<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Pigeons;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UberPigeonController extends Controller
{
    /**
     * Check order's availability, and subsequently its cost.
     */
    public function checkOrder(Request $request){
        $this->data = $this->standardizeData($request);

        /**
         * Any future expansions require to create/add new method below
         */
        return $this
        ->downtime()
        ->range()
        ->speed()
        ->cost()
        ->result();
    }

    /**
     * Check if the order distance exceeds the assigned pigeon range
     */
    private function range(){
        if($this->data['range'] >= $this->data['distance']){
            return $this;
        }else{
            $message = "Out of pigeon's range";
            exit($this->reject($message));
        }
    }

    /**
     * Check if the given deadline is doable
     */
    private function speed(){
        $eta = $this->data['distance'] / $this->data['speed'];
        $period = (Carbon::now()->diffInMinutes(Carbon::parse($this->data['deadline']),false))/60;
        $this->eta = Carbon::now()->addHour($eta)->toDateTimeString();
        
        if($period >= $eta){
            return $this;
        }elseif($period < 0){
            $message = "Deadline have exceeded";
            exit($this->reject($message));
        }else{
            $message = "Unable to deliver before deadline";
            exit($this->reject($message));
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
            "status" => "reject",
            "message" => $message
        ];

        header('Content-Type : application/json; charset=UTF-8');
        return json_encode($response);
    }

    /**
     * Display API response if the order can be proceed
     */
    private function result(){
        $response = [
            "status" => "success",
            "message" => "Order can be proceed",
            "total_cost" => $this->totalCost,
            "eta" => $this->eta
        ];

        return response()->json($response);
    }

    /**
     * Compile all data into an array
     * Any new fields need to be added here
     */
    private function standardizeData($request){
        $pigeon = Pigeons::getPigeon($request->pigeon);

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
