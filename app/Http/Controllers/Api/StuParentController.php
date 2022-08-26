<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStuParentRequest;
use App\Models\StuParent;
use Illuminate\Http\Request;

class StuParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stuParent = StuParent::all();
        if ($stuParent->count() > 0) {
            return response()->json([
                "status" => true,
                "message" => "All Parents details",
                "data" => $stuParent
            ]);
        }
        return response()->json([
            "status" => true,
            "message" => "No data found",
            "data" => $stuParent
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStuParentRequest $request)
    {
        $stuParent = StuParent::create($request->all());
        return response()->json([
            "status" => true,
            "message" => "Parents details added successfully.",
            "data" => $stuParent
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StuParent  $stuParent
     * @return \Illuminate\Http\Response
     */
    public function show(StuParent $stuParent)
    {
        return response()->json([
            "status" => true,
            "message" => "Requested parents details found.",
            "data" => $stuParent
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StuParent  $stuParent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StuParent $stuParent)
    {
        $stuParent->update($request->all());
        return response()->json([
            "status" => true,
            "message" => "Parents details updated successfully.",
            "data" => $stuParent
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StuParent  $stuParent
     * @return \Illuminate\Http\Response
     */
    public function destroy(StuParent $stuParent)
    {
        $stuParent->delete();
        return response()->json([
            "status" => true,
            "message" => "Parents details deleted successfully.",
            "data" => $stuParent
        ]);
    }
}
