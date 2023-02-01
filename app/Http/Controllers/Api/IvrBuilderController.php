<?php


namespace App\Http\Controllers\Api;

use App\Models\IvrBuilder;
use Illuminate\Http\Request;
use App\Ivr\IverGenerator;
use App\Models\Ivr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Ivr\IvrAction;
use App\Models\IvrNodesRetries;
use App\Models\Routing;
use App\Models\RoutingPlan;
use App\Models\TwillioNumber;
use App\Ivr\DirectCallFarwarding;
use App\Services\Reporting;
use App\Http\Resources\IvrBuilderRegisterNodeResource;
use App\Models\States;
use App\Models\IvrBuilderFilterConditions;
use App\Ivr\Tags\State;

class IvrBuilderController extends ApiController
{
    protected $response;
    protected $currentRecord;
    private $errorsList;
    private $languages;
    private $nodes;
    private $ivrBuilder;
    public function __construct()
    {
        $this->errorsList = [];
        $this->languages = [
            "da-DK",
            "de-DE",
            "en-AU",
            "en-CA",
            "en-GB",
            "en-IN",
            "en-US",
            "ca-ES",
            "es-ES",
            "es-MX",
            "fi-FI",
            "fr-CA",
            "fr-FR",
            "it-IT",
            "ja-JP",
            "ko-KR",
            "nb-NO",
            "nl-NL",
            "pl-PL",
            "pt-BR",
            "pt-PT",
            "ru-RU",
            "sv-SE",
            "zh-CN",
            "zh-HK",
            "zh-TW",
        ];
    }
    private function getErrors($errors)
    {

        foreach ($errors as $key => $error) {
            $this->errorsList[$key] = $error[0];
        }
    }
    private function playOrSayValidationRules($request)
    {
        return  [
            'no_of_reproduce' => 'required|numeric',
            'node_content_type' =>
            [
                'required',
                Rule::in(['play', 'say'])
            ],
            'sound' => Rule::requiredIf($request->node_content_type == 'play'),
            'text' => Rule::requiredIf($request->node_content_type == 'say'),
            'text_voice' => Rule::when($request->node_content_type == 'say', [
                'required',
                Rule::in(['man', 'woman']),
            ]),
            'text_language' => Rule::when($request->node_content_type == 'say', [
                'required',
                Rule::in($this->languages),
            ]),
        ];
    }


    public function createMenuValidation(Request $request)
    {
        $rulesList = [
            'timeout' => 'required|numeric',
            'no_of_retries' => 'required|numeric',
        ];
        $rulesList2 = $this->playOrSayValidationRules($request);

        $rulesList = array_merge($rulesList, $rulesList2);
        $validator = Validator::make($request->all(), $rulesList);

        // $validator = Validator::make($request->all(), [
        //     'no_of_reproduce' => 'required|numeric',
        //     'tag_name' => 'required',
        //     'node_content_type' =>
        //     [
        //         'required',
        //         Rule::in(['play', 'say'])
        //     ],
        //     'sound' => Rule::requiredIf($request->node_content_type == 'play'),
        //     'text' => Rule::requiredIf($request->node_content_type == 'say'),
        //     'text_voice' => Rule::when($request->node_content_type == 'say', [
        //         'required',
        //         Rule::in(['man', 'woman']),
        //     ]),
        //     'text_language' => Rule::when($request->node_content_type == 'say', [
        //         'required',
        //         Rule::in($this->languages),
        //     ]),
        // ]);

        if ($validator->fails()) {

            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }
    }


    public function createDialValidation(Request $request)
    {
        $messages = [
            'dial_recording_setting.required' => 'Recording Setting is Required',
            'no_of_retries.required' => 'Dial Attemps is Required',
            'timeout.required' => 'Timeout is Required',
            'dial_max_call_length.required' => 'Max Call Length is Required',
            'dial_max_recording_time.required' => 'Max Call Recording time is Required',
            'dial_routing_plan.required' => 'Routing Plan is Required',

        ];
        $rulesList = [
            'dial_recording_setting' => 'required|string',
            'no_of_retries' => 'required|numeric',
            'timeout' => 'required|numeric',
            'dial_max_call_length' => 'required|numeric',
            'dial_max_recording_time' => 'required|numeric',
            'dial_routing_plan' => 'required',
        ];

        if ($request->dial_wishper) {
            $rulesList2 = $this->playOrSayValidationRules($request);

            $rulesList = array_merge($rulesList, $rulesList2);
        }
        $validator = Validator::make($request->all(), $rulesList, $messages);

        if ($validator->fails()) {

            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }
    }


    public function createGatherValidation(Request $request)
    {
        $messages = [
            'gather_max_number_of_digits.required' => 'Max number of digits is Required',
            'gather_min_number_of_digits.required' => 'Min number of digits is Required',
            'gather_valid_digits.required' => 'Valid Digits is Required',
            'gather_finish_on_key.required' => 'Finish key is Required',
            'timeout.required' => 'Timeout is Required',
            'no_of_retries.required' => 'Numbers of retires is Required',
        ];
        $rulesList = $this->playOrSayValidationRules($request);
        $rulesList2 = [
            'tag_name' => 'required',
            'gather_max_number_of_digits' => 'required',
            'gather_min_number_of_digits' => 'required',
            'gather_valid_digits' => 'required',
            'gather_finish_on_key' => 'required',
            'gather_key_press_timeout' => 'required',
            'no_of_retries' => 'required',
            'tag_name' => 'required',
        ];
        $rulesList = array_merge($rulesList, $rulesList2);
        $validator = Validator::make($request->all(), $rulesList, $messages);

        if ($validator->fails()) {
            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }
    }


    public function createVoicemailValidation(Request $request)
    {
        $messages = [
            'voicemail_max_length.required' => 'Max Length of voicemail is Required',
            'voicemail_finish_on_key.required' => 'Finishing key for voicemail is Required',
            'voicemail_play_beep.required' => 'Play beep is Required',
            'voicemail_email_notification.required' => 'Voice email notification is Required',
            'timeout.required' => 'Timeout is Required',
        ];
        $rulesList = [];
        if ($request->voicemail_message) {
            $rulesList = $this->playOrSayValidationRules($request);
        }
        $rulesList2 = [
            'voicemail_max_length' => 'required',
            'voicemail_finish_on_key' => 'required',
            'voicemail_play_beep' => 'required|boolean',
            'voicemail_email_notification' => 'required|boolean',
            'timeout' => 'required',
            'voicemail_message' => 'required|boolean'
        ];
        $rulesList = array_merge($rulesList, $rulesList2);
        $validator = Validator::make($request->all(), $rulesList, $messages);

        if ($validator->fails()) {
            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }

        // $request->validate([
        //     'node_content_type' => Rule::requiredIf($request->voicemail_message == true),
        //     'sound' => Rule::requiredIf($request->voicemail_message == true && $request->node_content_type == 'play'),
        //     'text' => Rule::requiredIf($request->voicemail_messsage == true && $request->node_content_type == 'say')
        // ]);

        // $voicemail_booleans = ['voicemail_play_beep', 'voicemail_email_notification', 'voicemail_message'];
        // $voicemailNode = new IvrBuilder($request->all());
        // $voicemailNode->node_type = 'voicemail';
        // $voicemailNode->timeout = $request->has('timeout') ? $request->timeout : 15;
        // foreach ($voicemail_booleans as $voicemail_boolean) {
        //     $voicemailNode->$voicemail_boolean = $request->boolean($voicemail_boolean);
        // }
        // $voicemailNode->no_of_reproduce = $request->has('no_of_reproduce') ? $request->no_of_reproduce : 1;
        // if ($request->ivr_uuid) {
        //     $voicemailNode->ivr_id = Ivr::getIdByUuid($request->ivr_uuid);
        // }
        // $voicemailNode->save();
        // return $this->respond(saveRecordResponseArray($voicemailNode));
    }

    public function createHangupValidation(Request $request)
    {
        $rulesList  = [];
        if ($request->hangup_message) {

            $rulesList = $this->playOrSayValidationRules($request);
        }
        $validator = Validator::make($request->all(), $rulesList);

        if ($validator->fails()) {

            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }



        // $request->validate([
        //     'parent_uuid' => 'nullable',
        //     'node_content_type' => Rule::requiredIf($request->hangup_message == true),
        //     'sound' => Rule::requiredIf($request->hangup_message == true && $request->node_content_type == 'play'),
        //     'text' => Rule::requiredIf($request->hangup_message == true && $request->node_content_type == 'say')
        // ]);


        // $hangupNode = new IvrBuilder($request->all());

        // if ($request->parent_uuid) {
        //     $hangupNode->parent_id = IvrBuilder::getIdByUuid($request->parent_uuid);
        // }

        // $hangupNode->node_type = 'hangup';
        // $hangupNode->hangup_message = $request->boolean('hangup_message');
        // $hangupNode->no_of_reproduce = $request->has('no_of_reproduce') ? $request->no_of_reproduce : 1;

        // if ($request->ivr_uuid) {
        //     $hangupNode->ivr_id = Ivr::getIdByUuid($request->ivr_uuid);
        // }
        // $hangupNode->save();
        // return $this->respond(saveRecordResponseArray($hangupNode));
    }


    public function createPlayValidation(Request $request)
    {
        $rulesList = $this->playOrSayValidationRules($request);
        $validator = Validator::make($request->all(), $rulesList);

        if ($validator->fails()) {

            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }








        // $request->validate([
        //     'node_content_type' => 'required',
        //     'sound' => Rule::requiredIf($request->node_content_type == 'play'),
        //     'text' => Rule::requiredIf($request->node_content_type == 'say')
        // ]);

        // $playNode = new IvrBuilder($request->all());
        // $playNode->node_type = 'play';
        // $playNode->no_of_reproduce = $request->has('no_of_reproduce') ? $request->no_of_reproduce : 1;
        // if ($request->ivr_uuid) {
        //     $playNode->ivr_id = Ivr::getIdByUuid($request->ivr_uuid);
        // }
        // $playNode->save();
        // return $this->respond(saveRecordResponseArray($playNode));
    }

    public function createGotoValidation(Request $request)
    {


        $messages = [
            'goto_node_uuid.required' => 'Destination Node is Required',
        ];
        $rulesList = [
            'parent_uuid' => 'required|uuid',
            'goto_node_uuid' => 'required|uuid',
            'goto_count' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rulesList, $messages);

        if ($validator->fails()) {

            $this->getErrors($validator->errors()->getMessages());
            return $this->failValidation($this->errorsList);
        } else {
            return $this->successValidation();
        }


        // $request->validate([
        //     'parent_uuid' => 'required',
        //     'goto_node_uuid' => 'required',
        //     'goto_count' => 'required'
        // ]);
        // $gotoNode = new IvrBuilder($request->all());
        // $gotoNode->node_type = 'goto';
        // $gotoNode->goto_count = $request->has('goto_count') ? $request->goto_count : 15;
        // $gotoNode->goto_current_node = $request->has('goto_current_node') ? $request->goto_crrent_node : 0;
        // $goto_node_uuid = IvrBuilder::getIdByUuid($request->goto_node_uuid);
        // $gotoNode->goto_node =  $goto_node_uuid;
        // $gotoNode->parent_id = IvrBuilder::getIdByUuid($request->parent_uuid);
        // if ($request->ivr_uuid) {
        //     $gotoNode->ivr_id = Ivr::getIdByUuid($request->ivr_uuid);
        // }
        // $gotoNode->save();
        // return $this->respond(saveRecordResponseArray($gotoNode));
    }

    //********************EDIT ******************************* */
    // public function updateMenu(Request $request)
    // {
    //     $request->validate([
    //         'uuid' => 'required',
    //         'parent_uuid' => 'nullable',
    //         'node_content_type' => 'required',
    //         'sound' => Rule::requiredIf($request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->node_content_type == 'say'),
    //         'tag_name' => 'required'
    //     ]);
    //     $menuNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($menuNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $menuNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }
    // public function updateHangup(Request $request)
    // {

    //     $request->validate([
    //         'uuid' => 'required',
    //         'parent_uuid' => 'nullable',
    //         'node_content_type' => Rule::requiredIf($request->hangup_message == true),
    //         'sound' => Rule::requiredIf($request->hangup_message == true && $request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->hangup_message == true && $request->node_content_type == 'say')
    //     ]);

    //     $hangupNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($hangupNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $hangupNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }
    // public function updateDial(Request $request)
    // {
    //     $request->validate([
    //         'uuid' => 'required',
    //         'node_content_type' => Rule::requiredIf($request->dial_wishper == true),
    //         'sound' => Rule::requiredIf($request->dial_wishper == true && $request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->dial_wishper == true && $request->node_content_type == 'say'),
    //         'dial_routing_plan' => 'required',
    //     ]);

    //     $dialNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($dialNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $dialNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }
    // public function updateGather(Request $request)
    // {
    //     $request->validate([
    //         'uuid' => 'required',
    //         'node_content_type' => 'required',
    //         'sound' => Rule::requiredIf($request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->node_content_type == 'say'),
    //         'tag_name' => 'required'
    //     ]);
    //     $gatherNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($gatherNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $gatherNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }
    // public function updatePlay(Request $request)
    // {

    //     $request->validate([
    //         'uuid' => 'required',
    //         'node_content_type' => 'required',
    //         'sound' => Rule::requiredIf($request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->node_content_type == 'say')
    //     ]);

    //     $playNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($playNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $playNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }
    // public function updateVoicemail(Request $request)
    // {
    //     $request->validate([
    //         'uuid' => 'required',
    //         'node_content_type' => Rule::requiredIf($request->voicemail_message == true),
    //         'sound' => Rule::requiredIf($request->voicemail_message == true && $request->node_content_type == 'play'),
    //         'text' => Rule::requiredIf($request->voicemail_messsage == true && $request->node_content_type == 'say')
    //     ]);

    //     $voicemailNode = IvrBuilder::where('uuid', $request->uuid)->update($request->all());
    //     if ($voicemailNode) {
    //         return  [
    //             'status' => true,
    //             'message' => 'Created Record Successfully!',
    //             'data' => $voicemailNode
    //         ];
    //     } else {
    //         return  [
    //             'status' => false,
    //             'message' => 'Something is going wrong please try again!',
    //             'data' => []
    //         ];
    //     }
    // }

    public function ourIvr()
    {

        $detail  = TwillioNumber::getNumberDetails(request('To'));
        $ivrGenerator = new IverGenerator();

        // $obj = new State();
        // $region = $obj->isAllowRegion($detail->campaign_id);
        // if (!$region) {
        //     return  $ivrGenerator->directHangupWithMessage('Sorry This region is not allowed against this campaign.Thanks calling on cloudrep, Good Bye.!');
        // }
        // if there is no detail against twilio number
        if (!$detail) {
            return  $ivrGenerator->directHangupWithMessage('Sorry This Number is not associated with any Campaign Good Bye.');
        }
        // if campaign contain ivr routing
        if ($detail['routing'] == 'ivr') {
            $ivr = Ivr::where('id', $detail['ivr_id'])->first();
            $record = $ivr->childs->whereNull('parent_id')->first();
            return $ivrGenerator->getTwiml($record);
        } else {
            // if campaign contain standar routing
            return (new DirectCallFarwarding())->directDialing();
        }
    }

    public function ourIvrAction(Request $request)
    {
        if ($request->has('type') == 'directCall') {
            return (new DirectCallFarwarding())->directAction();
        } else {
            return (new IvrAction())->getAction();
        }
    }
    public function sendMailNotificationOfVoiceMail(Request $request)
    {
        $url = $request->RecordingUrl;
        $to_name = 'Shafeeque Ahmad';
        $to_email = getSetting('voicemail_email_id') ?? 'shafeeq.ahmed541@gmail.com';
        $data = [
            'name' => 'cloudrepai',
            'body' => 'You Can check your voicemail recording via following link',
            'url' => $url
        ];
        Mail::send('mail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Voice Mail');
        });
        $iverGenerator = new IverGenerator();

        $ivrBuilder = IvrBuilder::where('uuid', $request->uuid)->first();
        if ($ivrBuilder->success) {
            return $iverGenerator->getTwiml($ivrBuilder->success);
        } else {
            return $iverGenerator->hangupAfterRecording();
        }
        // return
        // send email notification if caller leave message
        // this is the callback request of voice mail node
        // https://www.twilio.com/docs/voice/twiml/record
    }

    public function getMenu($record)
    {

        if ($record->node_content_type == 'say') {
            $this->response->say(
                $record->text,
                [
                    'voice' => $record->text_voice,
                    'language' => $record->text_llanguauge,
                ]
            )->break_(['time' => '5000ms']);
        }

        if ($record->node_content_type == 'play') {
            $this->response->play($record->sound);
        }

        if ($record->child) {
            $this->response->pause(['length' => 5]);
            $this->getMenu($record->child);
        }
    }
    public function audioUpload(Request $request)
    {
        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $base_path = 'uploads/ivr/audio/';
            $fileSize = $request->audio->getSize();
            $fileSizeInKB = $fileSize / 1024;
            $size = intval($fileSizeInKB);
            $format = $file->getClientOriginalExtension();

            if ($format == 'mp3') {
                if ($size < 4096) {
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $location = public_path($base_path);
                    $file->move($location, $filename);
                    $url = url($base_path . $filename);
                    return $url;
                } else {
                    return  [
                        'status' => false,
                        'message' => 'Sound file size must be less then 4mb.',
                        'data' => []
                    ];
                }
            } else {
                return  [
                    'status' => false,
                    'message' => 'Please upload file in mp3 format.',
                    'data' => []
                ];
            }
        }
    }
    public function registerNode(Request $request)
    {
        $request->validate([
            'ivr_uuid' => 'required',
            'parent_uuid' => 'nullable',
            'node_type' => 'required'
        ]);
        //for register the node
        $data['ivr_id'] = Ivr::getIdByUuid($request->ivr_uuid);
        $data['node_type'] = $request->node_type;
        $parentType = '';
        $filters = '';


        //if node have parent
        if (!is_null($request->parent_uuid)) {
            $parentRecord = IvrBuilder::where('uuid', $request->parent_uuid)->first();
            //add parent_id for child node
            $data['parent_id'] = $parentRecord->id;


            //check if parent is router node
            if ($parentRecord->node_type == 'router') {
                $parentType = 'router';
                // create fitler node first
                $filter['node_type'] = 'filter';
                $filter['parent_id'] = $parentRecord->id;
                $filter['ivr_id'] = $data['ivr_id'];
                $periority = IvrBuilder::getFilterPeriority($parentRecord->id);
                $filter['priority'] = $periority;
                // $filter['type'] = $periority;
                $filterRecord = IvrBuilder::create($filter);
                // update the parent_filter_node_id in filter child
                $data['parent_filter_id'] = $filterRecord->id;
                $filters = $parentRecord->routerNodeFilters;
            } else {
                $data['type'] = $request->type;
            }
        }


        $record = IvrBuilder::create($data);
        $record->parent_type = $parentType;
        $record->filters = $filters;
        $isCreate = $record ? true : false;
        return $this->respond(saveRecordResponseArray($isCreate, new IvrBuilderRegisterNodeResource($record)));
    }
    public function removeNode(Request $request)
    {
        $request->validate([
            'uuids' => 'required',
        ]);
        $data = $request->all();


        $ivr_builder = IvrBuilder::with('filterConditions')->whereIn('uuid', $data['uuids'])->get();
        if (isset($ivr_builder[0])) {
            if ($ivr_builder[0]->filterConditions->count() > 0) {
                $condition_uuid =   $ivr_builder[0]->filterConditions->pluck('uuid');
                IvrBuilderFilterConditions::whereIn('uuid', $condition_uuid)->delete();
            }
        }

        DB::transaction(function () use ($data) {
            IvrBuilder::whereIn('uuid', $data['uuids'])->delete();
        }, 1);
        return  [
            'status' => true,
            'message' => 'Record Deleted Successfully!',
            'data' => []
        ];
    }

    public function saveIvrNodes(Request $request)
    {
        $request->validate([
            'ivr_uuid' => 'required|uuid',
            'ivr_name' => 'required',
            'nodes' =>  'nullable'
        ]);
        //initilize the object
        $this->ivrBuilder = new IvrBuilder();

        $ivr = Ivr::getRecordWithNodes('uuid', $request->ivr_uuid);
        $this->nodes = $ivr->nodes;

        Ivr::updateRecord('uuid', $request->ivr_uuid, ['name' => $request->ivr_name]);
        foreach ($request->nodes as $node) {
            if ($node) {
                $data = $this->getNodeAttributes($node);
                IvrBuilder::updateRecord('uuid', $node['nodeId'], $data);
            }
        }
        // remove soft deleted records
        IvrBuilder::where('ivr_id', $ivr->id)->whereNotNull('deleted_at')->forceDelete();
    }
    private function getNodeAttributes(array $node): array
    {

        $data = $this->ivrBuilder->getFillableAttributes($node);


        if ($node['node_type'] == 'goto') {
            $data['goto_node'] = $this->ivrBuilder::getIdByUuid($node['goto_node_uuid']);
        }
        if ($node['node_type'] == 'dial') {
            $data['dial_routing_plan'] = Routing::getIdByUuid($node['dial_routing_plan']);
        }

        $actionAttributes = $this->getNodeAction($node);

        return array_merge($data, $actionAttributes);
    }
    private function getNodeAction(array $node): array
    {
        $action = [];
        $action['on_failer'] = !empty($node['on_failer']) ?  $this->getIdByUuid($node['on_failer']) : null;
        $action['on_success'] = !empty($node['on_success']) ?  $this->getIdByUuid($node['on_success']) : null;

        //get id from uuid of nodes
        for ($i = 0; $i <= 9; $i++) {
            if (!empty($node["press_$i"])) {
                $action["press_$i"] =  $this->getIdByUuid($node["press_$i"]);
            }
        }
        return $action;
    }
    private function getIdByUuid($uuid)
    {
        return  $this->nodes->where('uuid', $uuid)->value('id');
    }
    public function dialNumberStatusCallBack(Request $request)
    {
        return (new Reporting())->storeCampaignResults();
    }
    public function reorderRouterFilters(Request $request)
    {
        $request->validate([
            'filters' => 'required|array',
        ]);

        foreach ($request->filters as $filter) {
            IvrBuilder::updateRecord('uuid', $filter['uuid'], ['priority' => $filter['priority']]);
        }
        return  [
            'status' => true,
            'message' => 'Record update Successfully!',
            'data' => []
        ];
    }

    public function getStateList()
    {
        $states = States::select('name')->get();
        return $this->respond([
            'status' => true,
            'message' => 'States fetched Successfully!',
            'states' => $states
        ]);
    }
}
