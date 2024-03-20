<?php

namespace App\Http\Services;

use App\Models\Field;
use App\Models\Rule;

class ApiServices
{

    static public function ruleAdd($request, $type)
    {

        if($type == 1){

            $field_id = $request->input('field_id');
            $rule_type = $request->input('rule_type');
            $show = $request->input('show');

            if($rule_type === 1){
                $rule = [];
                $rule['id'] = 1;
                $rule['rule'] = 0;
                $rule['text'] = $request->input('text');

                $field = Rule::create([
                    'field_id' => $field_id,
                    'rule' => json_encode($rule),
                    'rule_type' => $rule_type,
                    'show' => $show,
                ]);
            }
        }elseif ($type == 2){
            $data_all = [];
            return json_encode($request->input());
        }


        return $field;
    }

    static public function fileldAdd($data){

//        $data['FIELD_NAME'] = $request->input('FIELD_NAME');
//        $data['CRM_TYPE'] = $request->input('CRM_TYPE');
//        $data['EDIT_FORM_LABEL'] = $request->input('EDIT_FORM_LABEL');
//        $data['LIST_COLUMN_LABEL'] = $request->input('LIST_COLUMN_LABEL');
//        $data['USER_TYPE_ID'] = $request->input('USER_TYPE_ID');
//        $data['XML_ID'] = $request->input('XML_ID');
//        $data['LIST'] = $request->input('LIST');
//        $data['SETTINGS'] = $request->input('SETTINGS');


            $field = Field::firstOrCreate([
                'FIELD_NAME' => $data['FIELD_NAME'],
                'CRM_TYPE' => $data['CRM_TYPE'],
            ], $data);

        return $field;
    }
}
