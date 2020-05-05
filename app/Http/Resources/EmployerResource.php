<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
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
            'id'                =>  $this->id,
            'name'              =>  $this->name,
            'designation'       =>  $this->designation,
            'business_name'     =>  $this->business_name,
            'city'              =>  $this->city,
            'state'             =>  $this->state,
            'country'           =>  $this->country,
            'industry'          =>  $this->industry,
            'phone'             =>  $this->phone,
            'messenger_type'    =>  $this->messenger_type,
            'messenger_id'      =>  $this->messenger_id,
            'email'             =>  $this->email,
            'status'            =>  $this->status,
            'requisition'       =>  $this->requisition,
            'fees'              =>  $this->fees,
            'user'              =>  $this->current_user,
            'notes'             =>  NoteResource::collection($this->notes),
            'created_at'        =>  $this->created_at,
            'updated_at'        =>  $this->updated_at,
            'can_edit'          =>  $this->canEdit(),
            'can_delete'        =>  $this->canDelete(),
            'can_assign'        =>  $this->canAssign(),
            'can_comment'       =>  $this->canComment(),
        ];
    }
}
