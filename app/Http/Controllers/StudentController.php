<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Students;
use App\Grades;
use App\Classes;
use Excel;
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
        $count = 0;
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
            if(!empty($findst)){
                $count += 1;
                $erstt .= 'Mã sinh viên đã tồn tại';
            }
            else{
                $findcl = Classes::where('name',$row['lop'])->first();
                if(empty($findcl)){
                    $count += 1;
                    $erstt .= 'Lớp không tồn tại';
                }
                else{
                    $findgr = Grades::where('name',$row['khoi'])->first();
                    if(empty($findgr)){
                        $count += 1;
                        $erstt .= 'Khối không tồn tại';
                    }
                    elseif($findgr->id != $findcl->grade_id){
                        $count += 1;
                        $erstt .= 'Khối và lớp không quan hệ';
                    }
                }
            }

            $new['Error'] = $erstt;
            $students = $new;
        }

        $rs = [];
        $rs['ErrorCount'] = $count;
        $rs['File'] = $filename;
        if($count > 0){
            $rs['File'] = '';
            Storage::disk('public_uploads')->delete($filename);
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
            $new->dob = date('Y-m-d', strtotime($row['ngay_sinh']));
            $findcl = Classes::where('name',$row['lop'])->first();
            $new->class_id = $findcl->id;
            // $new->gender = $row['phai'];
            $new->pob= $row['noi_sinh'];
            $new->code1 = $row['ma_khoa'];
            $new->code2 = $row['ma_nganh'];
            $new->note = $row['ghi_chu'];
            if(empty($row['ghi_chu'])){
                $new->note = '';
            }
            $new->status = 1;
            $new->save();
        }
        Storage::disk('public_uploads')->delete($filename);
        return response()->json('Add success');
    }

    public function export(){
        
    }
}
