<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

class FieldsBTX
{

    public static function AddTextField(Request $request, $data = [])
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        $data =
            [
                "FIELD_NAME" => "MY_STRING",
                "EDIT_FORM_LABEL" => "Моя строка",
                "LIST_COLUMN_LABEL" => "Моя строка",
                "USER_TYPE_ID" => "string",
                "XML_ID" => "MY_STRING",
                "SETTINGS" => ["DEFAULT_VALUE" => "Привет, мир!"]
            ];
        $method = "crm.lead.userfield.add";
        $result = MyB24::CallB24($domain, $auth_id, $method, $data);
        return $result;
    }

    public static function AddListField(Request $request, $data = [])
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        $data =
            [
                "FIELD_NAME" => "MY_LIST",
                "EDIT_FORM_LABEL" => "Мой список",
                "LIST_COLUMN_LABEL" => "Мой список",
                "USER_TYPE_ID" => "enumeration",
                "LIST" => [["VALUE" => "Элемент #1"], ["VALUE" => "Элемент #2"], ["VALUE" => "Элемент #3"], ["VALUE" => "Элемент #4"], ["VALUE" => "Элемент #5"]],
                "XML_ID" => "MY_LIST",
                "SETTINGS" => ["LIST_HEIGHT" => 3]
            ];
        $method = "crm.lead.userfield.add";
        $result = MyB24::CallB24($domain, $auth_id, $method, $data);
        return $result;
    }
}
