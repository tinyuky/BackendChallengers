<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
use App\Http\Resources\Classes as ClassesResource;
class ClassesController extends Controller
{
    public function add(Request $request){
        $db = new Classes();
        $db->name = $request->input('Name');
        $db->grade_id = $request->input('GradeId');
        $db->save();
        return response()->json(['message'=>'Add Success'], 200);
    }
    public function update(Request $request){
        $db = Classes::find($request['id']);
        $db->name = $request->input('Name');
        $db->grade_id = $request->input('GradeId');
        $db->save();
        return response()->json(['message'=>'Update Success'], 200);
    }
    public function get($id){
        return new ClassesResource(Classes::find($id));
    }
    public function getall(){
        return ClassesResource::collection(Classes::all());
    }
}
