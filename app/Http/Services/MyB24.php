<?php

namespace App\Http\Services;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MyB24
{
    /**
     * Can overridden this method to change the data storage location.
     *
     * @return array setting for getAppSettings()
     */

    public static function getSettingData()
    {
        $return = [];

        $return['C_REST_CLIENT_ID'] = env("C_REST_CLIENT_ID");
        $return['C_REST_CLIENT_SECRET'] = env("C_REST_CLIENT_SECRET");

        return $return;
    }

    /**
     * @return boolean
     * @var $isInstall  boolean true if install app by installApp()
     * @var $arSettings array settings application
     */

    private static function setAppSettings($arSettings, $isInstall = false)
    {
        $return = false;
        if (is_array($arSettings)) {
            $oldData = static::getAppSettings();
            if ($isInstall != true && !empty($oldData) && is_array($oldData)) {
                $arSettings = array_merge($oldData, $arSettings);
            }
            $return = static::setSettingData($arSettings);
        }
        return $return;
    }

    /**
     * Can overridden this method to change the data storage location.
     *
     * @return boolean is successes save data for setSettingData()
     * @var $arSettings array settings application
     */

    protected static function setSettingData($arSettings)
    {
        return (boolean)file_put_contents(__DIR__ . '/settings.json', static::wrapData($arSettings));
    }

    /**
     * @return string json_encode with encoding
     * @var $debag boolean
     *
     * @var $data mixed
     */
    protected static function wrapData($data, $debag = false)
    {
        if (defined(env('C_REST_CURRENT_ENCODING'))) {
            $data = static::changeEncoding($data, true);
        }
        $return = json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);


        return $return;
    }

    /**
     * @return mixed setting application for query
     */

    public static function getAppSettings()
    {
        if (defined(env("C_REST_WEB_HOOK_URL")) && !empty(env("C_REST_WEB_HOOK_URL"))) {
            $arData = [
                'client_endpoint' => env("C_REST_WEB_HOOK_URL"),
                'is_web_hook' => 'Y'
            ];
            $isCurrData = true;
        } else {
            $arData = static::getSettingData();
            $isCurrData = false;
            if (
                !empty($arData['access_token']) &&
                !empty($arData['domain']) &&
                !empty($arData['refresh_token']) &&
                !empty($arData['application_token']) &&
                !empty($arData['client_endpoint'])
            ) {
                $isCurrData = true;
            }
        }

        return ($isCurrData) ? $arData : false;
    }

    static function CallB24($domain, $auth_id, $method, $data)
    {

        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            'fields' => $data
        ]);

        return $response->json();
    }


    static function getCallB24(Request $request, $method, $select, $type = 'json')
    {
//        return $params;
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        //  $method = 'crm.company.list';
        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            'select' => $select
        ]);

        if ($type == 'array') {
            return $response->body();
        }

        return $response->json();
    }

    static function getLeadCallB24(Request $request, $method, $id)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            'ID' => $id
        ]);

        return $response->json();
    }

    static function getCallB24config(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        //  $method = 'crm.company.list';
        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            "scope" => "P",
            "data" =>
                [
                    [
                        "name" => "main",
                        "title" => "Общие сведения 1",
                        "type" => "section",
                        "elements" =>
                            [
                                ["name" => "TITLE"],
                                ["name" => "STATUS_ID"],
                                ["name" => "NAME"],
                                ["name" => "BIRTHDATE"],
                                ["name" => "POST"],
                                ["name" => "PHONE"],
                                ["name" => "EMAIL"]
                            ]
                    ],
                    [
                        "name" => "additional",
                        "title" => "Дополнительно",
                        "type" => "section",
                        "elements" =>
                            [
                                ["name" => "SOURCE_ID"],
                                ["name" => "SOURCE_DESCRIPTION"],
                                ["name" => "OPENED"],
                                ["name" => "ASSIGNED_BY_ID"],
                                ["name" => "ASSIGNED_BY_ID"],
                                ["name" => "OBSERVER"],
                                ["name" => "COMMENTS"]
                            ]
                    ],
                ],
        ]);


        return $response->json();
    }

    static function getCallB24configReset(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        //  $method = 'crm.company.list';
        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            "scope" => "P",
        ]);


        return $response->json();
    }

    static function setCallB24(Request $request, $method, $data)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        //  $method = 'crm.company.list';

        $params = [];
        $params['id'] = $data[''];

        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            "id" => $data,
            'fields' => [
                'TITLE' => '12312312312aaaa',
            ]
        ]);

        return $response->json();
    }

    static function setLeadsCallB24(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            "id" => $request->input('LEADS.ID'),
            'fields' => [
                'TITLE' => $request->input('LEADS.TITLE'),
            ]
        ]);

        return $response->json();
    }

    static function setLeadsCallB24_test(Request $request, $method)
    {
        $domain = $request->input('auth.domain');
        $auth_id = $request->input('auth.access_token');
        $id = $request->input('data.FIELDS.ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $auth_id,
            "id" => $id,
            'fields' => [
                'TITLE' => "New Test ---*** - " . $id,
                "NAME" => "NAME"
            ]
        ]);

        return $response->json();
    }

    static function placementCallB24upd(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';

        $response = Http::post($url, [
            'auth' => $auth_id,
            'USER_TYPE_ID' => 'bitb24_custom_type',
            'HANDLER' => "https://bitb24.ru/placement/",
            'TITLE' => 'bitb24',
            'OPTIONS' => [
                'height' => 600,
            ]
        ]);

        return $response;

    }

    static function placementCallB24_delete(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');


        $url = 'https://' . $domain . '/rest/' . $method . '.json';


        $response = Http::post($url,
            [
                'auth' => $auth_id,
                'USER_TYPE_ID' => 'bitb24_custom_type_1',
            ]);
        return $response;
    }

    static function placementCallB24(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';


        $response = Http::post($url,
            [
                'auth' => $auth_id,
                'USER_TYPE_ID' => 'bitb24_custom_type',
                'HANDLER' => "https://bitb24.ru/laravel/placement/",
                'TITLE' => 'bitb24',
                'OPTIONS' => [
                    'height' => 600,
                ]
            ]);
        return $response;

    }

    static function placementCallB24_list(Request $request, $method)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';


        $response = Http::post($url,
            [
                'auth' => $auth_id,
//                'USER_TYPE_ID' => 'bitb24_custom_type1'
            ]);
        return $response;

    }

    static function bindCallB24(Request $request, $method, $event)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $domain . '/rest/' . $method . '.json';

        $response = Http::post($url,
            [
                'auth' => $auth_id,
                'EVENT' => $event,
                'HANDLER' => "https://bitb24.ru/laravel/handler/",
                'EVENT_TYPE' => 'online'
            ]);

        return $response;
    }

    static function bindPhoneCallB24($method, $event)
    {

        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {
            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }

//        $domain = $request->input('DOMAIN');
//        $auth_id = $request->input('AUTH_ID');

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
                'EVENT' => $event,
                'HANDLER' => "https://bitb24.ru/laravel/phones/handler",
                'EVENT_TYPE' => 'online'
            ]);

        return $response;
    }

    public static function installApp(Request $request)
    {
        $domain = $request->input('DOMAIN');
        $auth_id = $request->input('AUTH_ID');
        $plasement = $request->input('PLACEMENT');
        $auth_exp = $request->input('AUTH_EXPIRES');
        $app_sid = $request->input('APP_SID');
        $refresh_id = $request->input('REFRESH_ID');
        $event = $request->input('event');
        $member_id = $request->input('member_id');

        $result = [
            'rest_only' => true,
            'install' => false
        ];

        if ($event == 'ONAPPINSTALL' && !empty($auth_id)) {
            $result['install'] = static::setAppSettings($auth_id, true);
        } elseif ($plasement == 'DEFAULT') {
            $result['rest_only'] = false;
            $result['install'] = static::setAppSettings(
                [
                    'access_token' => htmlspecialchars($auth_id),
                    'expires_in' => htmlspecialchars($auth_exp),
                    'application_token' => htmlspecialchars($app_sid),
                    'refresh_token' => htmlspecialchars($refresh_id),
                    'domain' => htmlspecialchars($domain),
                    'client_endpoint' => 'https://' . htmlspecialchars($domain) . '/rest/',
                ],
                true
            );

            Setting::updateOrCreate([
                'member_id' => htmlspecialchars($member_id)
            ], [
                'member_id' => htmlspecialchars($member_id),
                'access_token' => htmlspecialchars($auth_id),
                'expires_in' => htmlspecialchars($auth_exp),
                'application_token' => htmlspecialchars($app_sid),
                'refresh_token' => htmlspecialchars($refresh_id),
                'domain' => htmlspecialchars($domain),
                'client_endpoint' => 'https://' . htmlspecialchars($domain) . '/rest/',
            ]);
        }

//        static::setLog(
//            [
//                'request' => $request->all(),
//                'result' => $result
//            ],
//            'installApp'
//        );
        return $result;
    }

    static function CallB24_field_text_add(Request $request, $crm_type, $add = [])
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.add";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.add";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.add";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.add";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.add";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data['AUTH_ID'],
            'fields' => [
                "FIELD_NAME" => $add["FIELD_NAME"],
                "EDIT_FORM_LABEL" => $add["EDIT_FORM_LABEL"],
                "LIST_COLUMN_LABEL" => $add["LIST_COLUMN_LABEL"],
                "USER_TYPE_ID" => $add["USER_TYPE_ID"],
                "XML_ID" => $add["XML_ID"],
//                "SETTINGS" => ["DEFAULT_VALUE" => "Привет, мир!"],
            ]
        ]);

        return $response->json();
    }

    static function CallB24_refresh_token($member_id)
    {
        $member = Setting::where('member_id', $member_id)->first();

        if (!$member) {
            return false;
        }

        $url = 'https://oauth.bitrix.info/oauth/token/';
        $response = Http::get($url, [
            'grant_type' => 'refresh_token',
            'client_id' => env('C_REST_CLIENT_ID'),
            'client_secret' => env('C_REST_CLIENT_SECRET'),
            'refresh_token' => $member->refresh_token,
        ]);

        $result = $response->json();

        if (!isset($result['access_token'])) {
            return false;
        }

        unset($result['expires']);
        unset($result['scope']);
        unset($result['server_endpoint']);
        unset($result['status']);
        unset($result['user_id']);
        $result['domain'] = $member->domain;
        $result['client_endpoint'] = $member->client_endpoint;
//        dd($result);
        $member->update($result);
        return true;
    }

    static function CallB24_field_text_add_new($crm_type, $add)
    {
        $res = MyB24::CallB24_refresh_token($add["member_id"]);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $add['member_id'])->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.add";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.add";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.add";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.add";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.add";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            'fields' => [
                "FIELD_NAME" => $add["FIELD_NAME"],
                "EDIT_FORM_LABEL" => $add["EDIT_FORM_LABEL"],
                "LIST_COLUMN_LABEL" => $add["LIST_COLUMN_LABEL"],
                "USER_TYPE_ID" => $add["USER_TYPE_ID"],
                "XML_ID" => $add["XML_ID"],
//                "SETTINGS" => ["DEFAULT_VALUE" => "Привет, мир!"],
            ]
        ]);

        return $response->json();
    }

    static function CallB24_field_enumeration_add(Request $request, $crm_type, $add = [])
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.add";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.add";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.add";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.add";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.add";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data['AUTH_ID'],
            'fields' => [
                "FIELD_NAME" => $add["FIELD_NAME"],
                "EDIT_FORM_LABEL" => $add["EDIT_FORM_LABEL"],
                "LIST_COLUMN_LABEL" => $add["LIST_COLUMN_LABEL"],
                "USER_TYPE_ID" => $add["USER_TYPE_ID"],
                "XML_ID" => $add["XML_ID"],
                "LIST" => $add["LIST"],
                "SETTINGS" => ["LIST_HEIGHT" => "3"],
            ]
        ]);

        return $response->json();
    }

    static function CallB24_field_enumeration_add_new($crm_type, $add = [])
    {

        $data = Setting::where('member_id', $add['member_id'])->first();

        if (!$data) {
            return false;
        }

        $res = MyB24::CallB24_refresh_token($add["member_id"]);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $add['member_id'])->first();

        $multiple = ($add['MULTIPLE']) ? "Y" : "N";

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.add";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.add";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.add";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.add";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.add";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            'fields' => [
                "FIELD_NAME" => $add["FIELD_NAME"],
                "EDIT_FORM_LABEL" => $add["EDIT_FORM_LABEL"],
                "LIST_COLUMN_LABEL" => $add["LIST_COLUMN_LABEL"],
                "USER_TYPE_ID" => $add["USER_TYPE_ID"],
                "XML_ID" => $add["XML_ID"],
                "LIST" => json_decode($add['LIST']),
                "SETTINGS" => ["LIST_HEIGHT" => "3", "DISPLAY" => "DIALOG"],
                "MULTIPLE" => $multiple
            ]
        ]);

        return $response->json();
    }

    static function CallB24_field_text_upd(Request $request, $crm_type, $XML_ID, $update)
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.update";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.update";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.update";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.update";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.update";
                break;
        }
        if (!$method) {
            return false;
        }

        $usr_field_list = MyB24::CallB24_field_list($request, $crm_type, $XML_ID);

        if (!isset($usr_field_list['result'][0]['ID'])) {
            return false;
        }

        $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data['AUTH_ID'],
            "id" => $usr_field_list['result'][0]['ID'],
            "fields" =>
                [
                    "EDIT_FORM_LABEL" => $update['EDIT_FORM_LABEL'],
                    "LIST_COLUMN_LABEL" => $update['LIST_COLUMN_LABEL']
                ]
        ]);

        return $response->json();
    }

    static function CallB24_field_text_upd_new($crm_type, $update)
    {
        $data = Setting::where('member_id', $update['member_id'])->first();

        if (!$data) {
            return false;
        }
        $res = MyB24::CallB24_refresh_token($update["member_id"]);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $update['member_id'])->first();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.update";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.update";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.update";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.update";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.update";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            "id" => $update['BTX_ID'],
            "fields" =>
                [
                    "EDIT_FORM_LABEL" => $update['EDIT_FORM_LABEL'],
                    "LIST_COLUMN_LABEL" => $update['LIST_COLUMN_LABEL']
                ]
        ]);

        return $response->json();
    }

    static function CallB24_field_enumeration_upd_new($crm_type, $update, $ID_del)
    {
        $data = Setting::where('member_id', $update['member_id'])->first();

        if (!$data) {
            return false;
        }

        $multiple = ($update['MULTIPLE']) ? "Y" : "N";

        $del_field = MyB24::CallB24_field_del_new($crm_type, $update['member_id'], $ID_del);

        $data = Setting::where('member_id', $update['member_id'])->first();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.add";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.add";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.add";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.add";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.add";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            'fields' => [
                "FIELD_NAME" => $update["FIELD_NAME"],
                "EDIT_FORM_LABEL" => $update["EDIT_FORM_LABEL"],
                "LIST_COLUMN_LABEL" => $update["LIST_COLUMN_LABEL"],
                "USER_TYPE_ID" => $update["USER_TYPE_ID"],
                "XML_ID" => $update["XML_ID"],
                "LIST" => json_decode($update['LIST']),
                "SETTINGS" => ["LIST_HEIGHT" => "3", "DISPLAY" => "DIALOG", "SHOW_NO_VALUE" => "Y"],
                "MULTIPLE" => $multiple
            ]
        ]);

        return $response->json();
    }

    static function CallB24_field_enumeration_upd(Request $request, $crm_type, $XML_ID, $update)
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.update";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.update";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.update";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.update";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.update";
                break;
        }
        if (!$method) {
            return false;
        }

        $usr_field_list = MyB24::CallB24_field_list($request, $crm_type, $XML_ID);

        if (!isset($usr_field_list['result'][0]['ID'])) {
            return false;
        }

        $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data['AUTH_ID'],
            "id" => $usr_field_list['result'][0]['ID'],
            "fields" =>
                [
                    "EDIT_FORM_LABEL" => $update['EDIT_FORM_LABEL'],
                    "LIST_COLUMN_LABEL" => $update['LIST_COLUMN_LABEL'],
                    "LIST" => $update["LIST"],
                ]
        ]);

        return $response->json();
    }

    static function CallB24_field_list(Request $request, $crm_type, $XML_ID = null)
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.list";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.list";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.list";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.list";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.list";
                break;
        }
        if (!$method) {
            return false;
        }

        if ($XML_ID) {
            $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
            $response = Http::post($url, [
                'auth' => $data['AUTH_ID'],
                'filter' => [
                    'XML_ID' => $XML_ID
                ]
            ]);

        } else {
            $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
            $response = Http::post($url, [
                'auth' => $data['AUTH_ID'],
            ]);
        }


        return $response->json();
    }

    static function CallB24_field_list_new($crm_type, $member_id, $ID)
    {
        $res = MyB24::CallB24_refresh_token($member_id);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $member_id)->first();

        if (!$data) {
            return false;
        }


        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.get";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.get";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.get";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.get";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.get";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            'ID' => $ID
        ]);


        return $response->json();
    }

    static function CallB24_field_del(Request $request, $crm_type, $XML_ID)
    {
        $data = $request->input();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.delete";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.delete";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.delete";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.delete";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.delete";
                break;
        }
        if (!$method) {
            return false;
        }

        $usr_field_list = MyB24::CallB24_field_list($request, $crm_type, $XML_ID);

        if (!isset($usr_field_list['result'][0]['ID'])) {
            return false;
        }

        $url = 'https://' . $data['DOMAIN'] . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data['AUTH_ID'],
            "id" => $usr_field_list['result'][0]['ID'],
        ]);

        return $response->json();
    }

    static function CallB24_field_del_new($crm_type, $member_id, $ID)
    {
        $res = MyB24::CallB24_refresh_token($member_id);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $member_id)->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.userfield.delete";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.userfield.delete";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.userfield.delete";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.userfield.delete";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.userfield.delete";
                break;
        }

        if (!$method) {
            return false;
        }

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            "id" => $ID,
        ]);

        return $response->json();
    }

    static function CallB24_upd_values($crm_type, $update)
    {
        $data = Setting::where('member_id', $update['member_id'])->first();

        if (!$data) {
            return false;
        }
        $res = MyB24::CallB24_refresh_token($update["member_id"]);

        if (!$res) {
            return false;
        }

        $data = Setting::where('member_id', $update['member_id'])->first();

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.update";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.update";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.update";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.update";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.update";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            "id" => $update['ENTITY_VALUE_ID'],
            "fields" => $update['values_data']
        ]);

        return $response->json();
    }

    static function CallB24_get_crm($crm_type, $res)
    {
        $data = Setting::where('member_id', $res['member_id'])->first();

        if (!$data) {
            return false;
        }
        $res = MyB24::CallB24_refresh_token($res["member_id"]);

        if (!$res) {
            return false;
        }
        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.get";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.get";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.get";
                break;
            case "CRM_DEAL":
                $method = "crm.deal.get";
                break;
            case "CRM_QUOTE":
                $method = "crm.quote.get";
                break;
        }
        if (!$method) {
            return false;
        }


        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            "id" => '2',
        ]);

        return $response->json();
    }

    //TODO PHONE GET
    //PHONE
    static function getPhoneCallB24($crm_type)
    {
        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {
            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.contact.list";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.list";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.list";
                break;
        }

        if (!$method) {
            return false;
        }

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            "select" => ["ID", "PHONE"],
        ]);

        return $response->json();
    }


    //TODO PHONE UPDATE
    //PHONE UPDATE
    static function setPhoneCallB24($crm_type, $id, $phone)
    {
//        return $phone;
        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {

            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.contact.update";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.update";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.update";
                break;
        }

        if (!$method) {
            dump(1);
            return false;
        }

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $response = Http::post($url, [
            'auth' => $data->access_token,
            'id' => $id,
            'fields' => [
                "PHONE" => [$phone]
            ]
        ]);
        return $response->json();
    }

    //TODO PHONE GET BATCH
    //PHONE GET BATCH
    static function getBatchPhoneCallB24($crm_type)
    {
        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {
            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.contact.list";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.list";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.list";
                break;
        }

        if (!$method) {
            return false;
        }

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $url_batch = 'https://' . $data->domain . '/rest/batch.json';

        $response = Http::post($url, [
            'auth' => $data->access_token,
            "select" => ["ID", "PHONE"],
        ]);

        $result = $response->json();

        $request_arr = [];
        for ($i = 0; $i < $result['total']; $i += 50) {
            $request_arr[] = 'crm.contact.list?' . http_build_query(array(
                    "start" => $i,
                    "select" => ["ID", "PHONE"],
                ));
        }

        $response = Http::timeout(-1)->post($url_batch, [
            'auth' => $data->access_token,
            "halt" => 0,
            "cmd" =>
                $request_arr
        ]);
//        $response = Http::post($url_batch, [
//            'auth' => $data->access_token,
//            "halt" => 0,
//            "cmd" => [
//                'crm.contact.list?' . http_build_query(array(
//                    "start" => 0,
//                )),
//                'crm.contact.list?' . http_build_query(array(
//                    "start" => 50,
//                )),
//            ]
//        ]);

        return $response->json();
    }
//TODO PHONE SET BATCH
    //PHONE SET BATCH
    static function setBatchPhoneCallB24($item)
    {
        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {
            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }


        $url_batch = 'https://' . $data->domain . '/rest/batch.json';
        $response = Http::post($url_batch, [
            'auth' => $data->access_token,
            "halt" => 0,
            "cmd" =>
                $item
        ]);

        return $response->json();
    }


    //TODO PHONE GET BATCH
    //PHONE GET BATCH
    static function setBatch50PhoneCallB24($crm_type)
    {
        $res = MyB24::CallB24_refresh_token('e06846e3d3560fffef5142c3fff0a8f6');

        if (!$res) {
            return false;
        }
        $data = Setting::where('member_id', 'e06846e3d3560fffef5142c3fff0a8f6')->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.contact.list";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.list";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.list";
                break;
        }

        if (!$method) {
            return false;
        }

        $url = 'https://' . $data->domain . '/rest/' . $method . '.json';
        $url_batch = 'https://' . $data->domain . '/rest/batch.json';

        $response = Http::post($url, [
            'auth' => $data->access_token,
            "select" => ["ID", "PHONE"],
        ]);

        $result = $response->json();

        $request_arr = [];
        for ($i = 0; $i < $result['total']; $i += 50) {
            $request_arr[] = 'crm.contact.list?' . http_build_query(array(
                    "start" => $i,
                    "select" => ["ID", "PHONE"],
                ));
        }

        $response = Http::timeout(-1)->post($url_batch, [
            'auth' => $data->access_token,
            "halt" => 0,
            "cmd" =>
                $request_arr
        ]);
//        $response = Http::post($url_batch, [
//            'auth' => $data->access_token,
//            "halt" => 0,
//            "cmd" => [
//                'crm.contact.list?' . http_build_query(array(
//                    "start" => 0,
//                )),
//                'crm.contact.list?' . http_build_query(array(
//                    "start" => 50,
//                )),
//            ]
//        ]);

        return $response->json();
    }


}
