<?php

namespace App\Ivr;

use App\Models\IvrBuilder;
use App\Http\Resources\RouterNodeFilterResource;

class IvrNodesResponse
{

    private $response;
    private $parentRecord;
    private $record;
    private $nodes;
    private $node;
    private $data;
    private $ivrBuilder;
    private $formData;
    private $ivr;
    private $nodeList;

    public function __construct()
    {
        $this->node['id'] = '';
        $this->node['parentId'] = '';
        $this->node['type'] = '';
        $this->node['nodeComponent'] = "demo-node";
        $this->node['isOpen'] = true;
        $this->node['isActiveGoTo'] = false;
        $this->node['destinationNodeId'] = '';
        $this->node['gotoSourceNodeId'] = [];
        $this->node['blinkHeader'] = false;
        $this->node['parentLabel'] = '';
        $this->node['formData'] = [];
        $this->node['formErrors'] = [];
        $this->node['ivrUuid'] = '';
        $this->node['node_type'] = '';
        $this->nodeList = [];

        $this->ivrBuilder = new IvrBuilder();
    }


    public function getResponse($record)
    {
        $this->record = $record;
        if (method_exists(self::class, $this->record->node_type)) {
            call_user_func_array([$this, $this->record->node_type], [$this->record]);
        }
        return $this->response;
    }

    public function getNodes($ivr)
    {
        $this->ivr = $ivr;
        $this->nodes = $ivr->nodes;
        foreach ($this->nodes as $record) {
            $this->record  = $record;

            if (method_exists(self::class, $this->record->node_type)) {
                call_user_func_array([$this, $this->record->node_type], [$this->record]);
            }
        }
        // dd($this->nodeList);
        return $this->nodeList;
    }
    private function getParentNode()
    {
        $type = '';
        $parent = '';
        // get the parent record
        $parentRecord = $this->nodes->where('id', $this->record->parent_id)->first();
        if ($parentRecord) {
            //get the parent type
            if ($parentRecord->node_type == 'router') {
                //the git the parent filter record
                $filter = $this->nodes->where('id', $this->record->parent_filter_id)->first();
                // set the priority to type
                $type = $filter->priority;
            } else {
                //if parent is not a router node then the type will contain (1 to 9/success/fail)
                $type = $this->record->type;
            }
            $parent = $parentRecord->node_type;
        }

        //if the parent type is router node
        $this->node['type'] = $type;
        $this->node['parent_type'] = $parent;
    }
    private function getParentFillterUuid()
    {
        if ($this->record->parent_filter_id) {
            $parentRecord = $this->nodes->where('id', $this->record->parent_filter_id)->first();
            return $parentRecord->uuid;
        } else {
            return null;
        }
    }
    private function updateNode($type)
    {
        $this->getParentNode();
        $this->formData = $this->getFormData($type);
        $this->formData['nodeId'] = $this->record->uuid;
        $this->node['id'] = $this->record->uuid;
        $this->node['parentId'] = $this->getParentUuidById();
        $this->node['formData'] = $this->formData;
        $this->node['ivrUuid'] = $this->ivr->uuid;
        $this->node['node_type'] = $this->record->node_type;
        $this->node['data'] = $this->getData();
        $this->node['destinationNodeId'] = $type == 'goto' ? $this->getUuidById($this->record->goto_node) : "";
        $this->node['gotoSourceNodeId'] = json_decode($this->record->goto_source_node_uuid);
        $this->node['parent_fillter_uuid'] = $this->getParentFillterUuid();
        array_push($this->nodeList, $this->node);
    }

    private function dial()
    {
        $this->updateNode('dial');
    }
    private function gather()
    {
        $this->updateNode('gather');
    }
    private function goto()
    {
        $this->updateNode('goto');
    }
    private function hangup()
    {
        $this->updateNode('hangup');
    }
    private function menu()
    {
        $this->updateNode('menu');
    }
    private function play()
    {
        $this->updateNode('play');
    }
    private function voicemail()
    {
        $this->updateNode('voicemail');
    }
    private function router()
    {
        $this->updateNode('router');
    }

    private function getFormData($type)
    {
        $formData = [];
        $attributes = $this->ivrBuilder->getNodeAttributes($type);

        foreach ($attributes as $attribute) {
            $formData[$attribute] = $this->record[$attribute];
        }

        if ($type == 'goto') {
            $formData['goto_node_uuid'] = $this->getUuidById($this->record->goto_node);
            $formData['parent_uuid'] = $this->getParentUuidById();
        }
        if ($type == 'router') {
            $this->node['filters'] =  RouterNodeFilterResource::collection($this->record->routerNodeFilters);
        }
        return $formData;
    }

    public function getData()
    {
        $this->data = [];
        $this->data['description'] = $this->getTitle();
        $this->data['text'] = $this->getTitle();
        $this->data['title'] = $this->getTitle();
        return $this->data;
    }
    private function getTitle()
    {
        return $this->record->node_type == 'goto' ? 'Go To' : ucfirst($this->record->node_type);
    }
    private function getUuidById($id)
    {
        if (is_null($id)) {
            return "";
        } else {
            return $this->nodes->where('id', $id)->value('uuid');
        }
    }
    private function getParentUuidById()
    {
        if (is_null($this->record->parent_id)) {
            return -1;
        } else {
            return $this->nodes->where('id', $this->record->parent_id)->value('uuid');
        }
    }
}
