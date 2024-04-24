<?php

namespace Adplay\Business\Requests;

use Adplay\Handler\Request;

class BidRequest extends Request {


    public function __construct()
    {
        parent::__construct();
        
        $messages = $this->validation();

        if(!empty($messages)){
            return responseJSON([
                'status'=>false,
                'errors'=>$messages
            ],400);
        }
    }

    private function validation(){

        $data = $this->getData();

        $messages = [];

        if(empty($data['device']['geo']['country'])){
            $messages['country'] = 'Country is required';
        }

        if(empty($data['device']['os'])){
            $messages['os'] = 'OS is required';
        }

        if(!empty($data['imp'])){
            foreach($data['imp'] as $imp){
                if(!array_key_exists('bidfloor',$imp)){
                    $messages['bidfloor'] = "bidfloor is missing for id {$imp['id']}";
                    break;
                }
            }
        }

        return $messages;
    }

    public function all()
    {
        $data = $this->getData();

        return [
            'id'=>$data['id'],
            'imp'=>$data['imp'],
            'device'=>$data['device']
        ]; 
    }
}