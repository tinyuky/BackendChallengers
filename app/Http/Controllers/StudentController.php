<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Students;
use App\Grades;
use App\Classes;
use File;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function handle(Request $request){
        $file = $request->file('File');
        $filename = uniqid();
        Storage::disk('public_uploads')->put($filename, File::get($file));
        $data = Excel::load(Storage::disk('public_uploads')->getDriver()->getAdapter()->getPathPrefix().$filename, function($reader) {
        })->get();
        $students = [];
        $err = 0;
        foreach($data as $row ){
            $erstt = '';
            $new = [];
            $new['STT'] = $row['tt'];
            $new['Student_id'] = $row['ma_sv'];
            $new['Name'] = $row['ho_ten_sinh_vien'].$row['ten'];
            $new['Dob'] = $row['ngay_sinh'];
            $new['Class'] = $row['lop'];
            $new['Grade'] = $row['khoi'];
            $new['Gender'] = $row['phai'];
            $new['Pob'] = $row['noi_sinh'];
            $new['Code1'] = $row['ma_khoa'];
            $new['Code2'] = $row['ma_nganh'];
            $new['Note'] = $row['ghi_chu'];
            
            $findst = Students::where('student_id',$row['ma_sv'])->first();
            $findcl = Classes::where('name',$row['lop'])->first();
            $findgr = Grades::where('id',$findcl->grade_id)->first();

            if(!empty($findst)){
                $err += 1;
                $erstt .= 'Mã sinh viên đã tồn tại-';
            }
            if(empty($findcl)){
                $err += 1;
                $erstt .= 'Lớp không tồn tại-';
            }
            if(empty($findgr)){
                $err += 1;
                $erstt .= 'Khối không tồn tại-';
            }
            if($findgr->name != $row['khoi']){
                $err += 1;
                $erstt .= 'Khối và lớp không quan hệ-';
            }
            $new['Error'] = explode('-',$erstt);
            $students = $new;
        }

        $rs = [];
        $rs['ErrorCount'] = $err;
        $rs['File'] = $filename;
        if($err>0){
            $rs['ErrorCount'] = '';
            Storage::delete(Storage::disk('public_uploads')->getDriver()->getAdapter()->getPathPrefix().$filename);
        }
        $rs['Students'] = $students;
        return response()->json($rs);
    }

    public function import($filename){
        $data = Excel::load(Storage::disk('public_uploads')->getDriver()->getAdapter()->getPathPrefix().$filename, function($reader) {
        })->get();
        foreach($data as $row ){
            $new = new Students();
            $new->student_id = $row['ma_sv'];
            $new->name = $row['ho_ten_sinh_vien'].$row['ten'];
            $new->dob = $row['ngay_sinh'];
            $findcl = Classes::where('name',$row['lop'])->first();
            $new->class_id = $findcl->id;
            $new->gender = $row['phai'];
            $new->pob= $row['noi_sinh'];
            $new->code1 = $row['ma_khoa'];
            $new->code2 = $row['ma_nganh'];
            $new->node = $row['ghi_chu'];
            $new->status = 1;
            $new->save();
        }
        Storage::delete(Storage::disk('public_uploads')->getDriver()->getAdapter()->getPathPrefix().$filename);
        return response()->json('Add success');
    }

    public function export(){

    }
}
