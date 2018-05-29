<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Students extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'StudentId' => $this->student_id,
            'Name' => $this->name,
            'Gender'=> $this->gender,
            'Dob'=> $this->dob,
            'Pob'=> $this->pob,
            'Code1'=> $this->code1,
            'Code2'=> $this->code2,
            'Note'=> $this->note,
            'Status'=> $this->status,
            'ClassId'=> $this->class_id,
        ];
    }
}
