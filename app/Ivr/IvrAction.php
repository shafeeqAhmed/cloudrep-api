<?php

namespace App\Ivr;

use App\Ivr\IverGenerator;
use App\Models\IvrBuilder;
use App\Models\IvrNodesRetries;
use App\Models\Routing;
use Illuminate\Support\Str;

class IvrAction
{
    private $ivr;
    private $record;
    private $response;

    public function __construct()
    {
        $this->ivr = new IverGenerator;
    }
    public function getAction()
    {

        $this->record = IvrBuilder::getRecordWithAllRelationship('uuid', request('uuid'));
        if (method_exists(self::class, $this->record->node_type)) {
            call_user_func_array([$this, $this->record->node_type], [$this->record]);
        }
        return $this->response;
    }
    // private function isAllowRegion(){
    //     $this->record
    // }
    private function menu()
    {
        $digits = request('Digits');
        $no = getNumber($digits);

        //check relationship 0 to 9 fail, or success
        if ($this->record[$no]) {
            $this->response = $this->ivr->getTwiml($this->record[$no]);
        } else {

            //check no of attempt default is 1
            if (request()->has('attempt')) {

                $attempt =  request('attempt');
                //check wrong attempt less than the max limit
                if ($attempt < $this->record->no_of_retries) {

                    //increase attempt until reached max limit
                    $this->ivr->increaseAttempt($attempt);

                    // add warning on invalid input
                    $this->ivr->warningOnInvalidInput();
                    //reproduce menu on wrong attempt
                    $this->response = $this->ivr->getTwiml($this->record);
                } else {
                    // if max limit of attempt axceded then hanup
                    $this->response = $this->ivr->hangupOnInvalidInput();
                }
            }
        }
    }
    private function dial()
    {
        if (request('attempt') < $this->record->no_of_retries) {
            //if call is completed in first attempt we do not add retries again
            if (request('DialCallStatus') == 'completed') {
                return $this->response = $this->ivr->simpleHanup();
            }
            $this->ivr->increaseDialAttempt(request('attempt'));
            $this->response = $this->ivr->getTwiml($this->record);
        } else {
            //move to the next number
            IvrNodesRetries::increament(request('CallSid'), 'dial', $this->record->uuid);
            // get total numbers of retries
            $tries = IvrNodesRetries::getCount(request('CallSid'), $this->record->uuid);
            //get total count of numbers aganist routing plan / targets
            $numbers = Routing::getRoutingForAction($this->record->dial_routing_plan);

            if ($tries < count($numbers)) {
                $this->ivr->dialAttempt = 1;
                $this->response = $this->ivr->getTwiml($this->record);
            } else {
                //failer case
                if ($this->record['fail']) {
                    $this->response = $this->ivr->getTwiml($this->record['fail']);
                } else {
                    $this->response = $this->ivr->simpleHanup();
                }
            }
        }
    }
    private function gather()
    {
        $isValidDigits = Str::containsAll($this->record->gather_valid_digits, str_split(request('Digits')));
        $checkDigitLength = $this->checkGatherDigitsLength();

        // dd($isValidDigits, $checkDigitLength);
        //check relationship 0 to 9 fail, or success
        // dd($this->record['success']);
        if ($isValidDigits && $checkDigitLength) {
            if ($this->record['success']) {
                $this->response = $this->ivr->getTwiml($this->record['success']);
            } else {
                $this->response = $this->ivr->simpleHanup();
            }
        } else {

            //check no of attempt default is 1
            if (request()->has('attempt')) {
                $attempt =  request('attempt');
                //check wrong attempt less than the max limit
                if ($attempt < $this->record->no_of_retries) {

                    //increase attempt until reached max limit
                    $this->ivr->increaseGatherAttempt($attempt);

                    // add warning on invalid input
                    $this->ivr->warningOnInvalidInput();
                    //reproduce menu on wrong attempt
                    $this->response = $this->ivr->getTwiml($this->record);
                } else {
                    // if max limit of attempt axceded then hanup
                    $this->response = $this->ivr->hangupOnInvalidInput();
                }
            }
        }
    }
    private function hangup()
    {
        $this->response = $this->ivr->getTwiml($this->record);
    }
    private function play()
    {
        $this->response = $this->ivr->getTwiml($this->record);
    }
    private function voicemail()
    {
        $this->response = $this->ivr->getTwiml($this->record);
    }
    private function router()
    {
        $this->response = $this->ivr->getTwiml($this->record);
    }
    private function checkGatherDigitsLength()
    {
        $minDigits = $this->record->gather_min_number_of_digits;
        $maxDigits = $this->record->gather_max_number_of_digits;
        $digitsLength = strlen(request('Digits'));

        return $digitsLength >=  $minDigits && $digitsLength <= $maxDigits;
    }
}
