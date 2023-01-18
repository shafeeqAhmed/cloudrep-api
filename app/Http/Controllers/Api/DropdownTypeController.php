<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\DropDownTypeResource;
use App\Models\DropdownType;
use Illuminate\Http\Request;

class DropdownTypeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        // return response()->json('you are here');
        $types = DropdownType::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);
        if (empty($types)) {
            return $this->respond([
                'status' => false,
                'message' => 'Type Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Types has been Fetched Successfully!',
            'data' => [
                'types' => $types
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
        $newData = new DropdownType();
        $newData->uuid = generateUuid();
        $newData->name = $request->name;
        $newData->save();
        if ($newData) {
            return $this->respond([
                'status' => true,
                'message' => 'Type has been Created Successfully!',
                'data' => [
                    'Type' => new DropDownTypeResource($newData)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Type Not Added!',
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

    public function show($id)
    {
        $type = DropdownType::where('uuid', $id)->first();
        if (empty($type)) {
            return $this->respond([
                'status' => false,
                'message' => 'Drop Down Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Drop Down has been Fetched Successfully!',
            'data' => [
                'type' => new DropDownTypeResource($type)
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
        $data = DropdownType::where('uuid', $uuid)->first();
        $data->name = $request->name;
        $data->update();
        if ($data) {
            return $this->respond([
                'status' => true,
                'message' => 'Type has been Updated Successfully!',
                'data' => [
                    'Type' => new DropDownTypeResource($data)
                ],
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Type Not Updated!',
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
        $type = DropdownType::where('uuid', $uuid)->delete();
        if ($type) {
            return $this->respond([
                'status' => true,
                'message' => 'Type has been Deleted Successfully!',
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Type Not Found',
            ]);
        }
    }
}
