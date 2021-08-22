<?php

namespace App\Http\Resources;



use App\Bank_account;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Accounts extends JsonResource
{

    public function toArray($request)
    {
        if ($this->account_type==1){
            $type='Savings';
        }
        elseif ($this->account_type==2){
            $type='Current';
        }
        else{
            $type='join';
        }
        return [
            "id"=> $this->id,
            'bank'=>@$this->bank,
            'store_id'=>@$this->store_id,
            'account_name'=>@$this->account_name,
            'account_no'=>@$this->account_no,
            'branch'=>@$this->branch,
            'account_type'=>@$type,
            'swift_code'=>@$this->swift_code,
            'route_no'=>@$this->route_no,
        ];
    }
}
