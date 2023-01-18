<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DropDownLabelResource;
use App\Models\DropdownLabel;
use Illuminate\Http\Request;

class DropdownLabelController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // return response()->json('you are here');
        $labels = DropdownLabel::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($labels)) {
            return $this->respond([
                'status' => false,
                'message' => 'Label Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'labels has been Fetched Successfully!',
            'data' => [
                'labels' => $labels
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $newData = new DropdownLabel();
        $newData->uuid = generateUuid();
        $newData->name = $request->name;
        $newData->save();
        if ($newData) {
            return $this->respond([
                'status' => true,
                'message' => 'Label has been Created Successfully!',
                'data' => [
                    'label' => new DropDownLabelResource($newData)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Label Not Added!',
                'data' =>  []
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($uuid)
    {
        $label = DropdownLabel::where('uuid', $uuid)->first();
        if (empty($label)) {
            return $this->respond([
                'status' => false,
                'message' => 'Label Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Label has been Fetched Successfully!',
            'data' => [
                'label' => new DropDownLabelResource($label)
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $data = DropdownLabel::where('uuid', $uuid)->first();
        $data->name = $request->name;
        $data->update();
        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Label has been Updated Successfully!',
                'data' => [
                    'label' => new DropDownLabelResource($data)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Label Not Updated!',
                'data' =>  []
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($uuid)
    {
        $label = DropdownLabel::where('uuid', $uuid)->delete();
        if ($label) {
            return $this->respond([
                'status' => true,
                'message' => 'Label has been Deleted Successfully!',
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Label Not Found',
            ]);
        }
    }
}
