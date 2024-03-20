<?php

namespace App\Http\Services;

use App\Models\Setting;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class PhoneServices
{

    public static function check_results($results)
    {
        if (isset($results['result']) && isset($results['result']['result_error']) && isset($results['result']['result'])) {
            if (empty($results['result']['result_error']) && !empty($results['result']['result']) && count($results['result']['result']) > 0) {
                return true;
            }
        }
        return false;
    }

    public static function update_item50($item50, $crm_type, $format)
    {
        $data = [];
        $data['update'] = [];
        $data['result'] = [];
        $data['errors'] = [];
        foreach ($item50 as $items) {
            if (empty($items['PHONE'])) continue;
            $result = PhoneServices::set_phone_format($items['PHONE'], $format);
            if (isset($result['result'])) {
                $data['result'][] = [
                    'ID' => $items['ID'],
                    'PHONE' => $result['result'],
                ];
                $data['update'] = [...$data['update'], ...$result['update']];
            }
            if (isset($result['errors'])) {
                $data['errors'][] = [
                    'ID' => $items['ID'],
                    'PHONE' => $result['errors'],
                ];
            }

        }
        return $data;
    }

    public static function get_request_crm($item, $crm_type)
    {
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
            return false;
        }

        $request = $method . "?" . http_build_query(array(
                "id" => $item['ID'],
                "fields" => [
                    "PHONE" => $item['PHONE']
                ]
            ));
        return $request;
    }

    public static function set_phone_format($items, $format)
    {
        $data = [];
        foreach ($items as $item) {
            $old_value = $item["VALUE"];
            $result = PhoneServices::check_phone_format($item["VALUE"], $format);
            if ($result['error']) {
                $data['errors'][] = $item;
            }

            if (!$result['error'] && $result['update']) {
                $data['update'][] = [
                    'VALUE_OLD' => $old_value,
                    'VALUE' => $result['VALUE'],
                ];
                $item['VALUE'] = $result['VALUE'];
                $data['result'][] = $item;
            }
        }
        return $data;
    }

    public static function check_phone_format($item, $format)
    {
        $phone = preg_replace("/[^0-9]/", '', $item ?? '');
        $phone_format = preg_replace('/^./', $format, $phone);

        if ($phone_format === $item && strlen($item) === 11) {
            return [
                'error' => false,
                'update' => false,
                'VALUE' => $phone_format,
            ];
        }

        if (strlen($phone) === 11) {
            if (substr($phone, 0, 1) === '7' || substr($phone, 0, 1) === '8') {

                return [
                    'error' => false,
                    'update' => true,
                    'VALUE' => $phone_format,
                    'VALUE_OLD' => $item,
                ];
            }
        }
        return [
            'error' => true,
            'update' => false,
            'VALUE' => $item,
        ];

    }

    public static function getPhoneCallB24($crm_type, $id)
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
                $method = "crm.contact.get";
                break;
            case "CRM_COMPANY":
                $method = "crm.company.get";
                break;
            case "CRM_CONTACT":
                $method = "crm.contact.get";
                break;
        }

        if (!$method) {
            return false;
        }

        $url = "https://" . $data->domain . "/rest/" . $method . ".json";
        $response = Http::post($url, [
            "auth" => $data->access_token,
            "id" => $id,
//            "select" => ["ID", "PHONE"],
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
        $response = Http::timeout(1)->post($url, [
            'auth' => $data->access_token,
            'id' => $id,
            'fields' => [
                "PHONE" => $phone
            ]
        ]);
        return $response->json();
    }

    //TODO PHONE GET BATCH
    //PHONE GET BATCH
    static function getBatchPhoneCallB24($crm_type, $member_id)
    {
        $req_counter = 0;

        $data = Setting::where('member_id', $member_id)->first();

        if (!$data) {
            return false;
        }

        $method = '';

        switch ($crm_type) {
            case "CRM_LEAD":
                $method = "crm.lead.list";
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

        $url = "https://" . $data->domain . "/rest/" . $method . ".json";
        $url_batch = "https://" . $data->domain . "/rest/batch.json";

//        $response = Http::post($url, [
//            "auth" => $data->access_token,
//            "select" => ["ID", "PHONE"],
//        ]);
        try {

//            $client = new Client([
//                // Base URI is used with relative requests
//                'base_uri' => "https://" . $data->domain. "/rest/",
//                // You can set any number of default request options.
////                'timeout'  => 2.0,
//            ]);
//            $response = $client->request('POST',  $method . ".json", [
//                "auth" => $data->access_token,
//                "select" => ["ID", "PHONE"],
//            ]);
            $response = Http::baseUrl("https://" . $data->domain)->acceptJson()->post("/rest/" . $method . ".json", [
                "auth" => $data->access_token,
                "select" => ["ID", "PHONE"],
            ]);
        } catch (GuzzleException $e) {
            dump($e->getRequest());
            dd($e->getResponse());
        }
        sleep(1);

        $result = $response->json();
        if (isset($result['total'])) {
            $req_counter++;
        } else {
            return [
                'data' => [],
                'error' => $result,
                'req_counter' => $req_counter
            ];
        }

        $request_arr = [];
        for ($i = 0; $i < $result['total']; $i += 49) {
            $req_counter++;
            $request_arr[] = $method . "?" . http_build_query(array(
                    "start" => $i,
                    "select" => ["ID", "PHONE"],
                ));
        }
        try {
            $response = Http::baseUrl("https://" . $data->domain)->acceptJson()->post("/rest/batch.json", [
                "auth" => $data->access_token,
                "halt" => 0,
                "cmd" =>
                    $request_arr
            ]);
        } catch (GuzzleException $e) {
            dump('error');
            dump(Psr7\Message::toString($e->getRequest()));
            dump(Psr7\Message::toString($e->getResponse()));
        }
//        $response = Http::post($url_batch, [
//            "auth" => $data->access_token,
//            "halt" => 0,
//            "cmd" =>
//                $request_arr
//        ]);


        return [
            'data' => $response->json(),
            'req_counter' => $req_counter
        ];
//        return $response->json();
    }

    //PHONE SET BATCH
    static function setBatchPhoneCallB24($item, $member_id)
    {
        $data = Setting::where('member_id', $member_id)->first();

        if (!$data) {
            return false;
        }

        sleep(2);
        $url_batch = "https://" . $data->domain . "/rest/batch.json";
//        $response = Http::post($url_batch, [
//            "auth" => $data->access_token,
//            "halt" => 0,
//            "cmd" =>
//                $item
//        ]);
        try {
            $response = Http::baseUrl("https://" . $data->domain)->acceptJson()->post("/rest/batch.json", [
                "auth" => $data->access_token,
                "halt" => 0,
                "cmd" =>
                    $item
            ]);
        } catch (GuzzleException $e) {
            dump('error');
            dump(Psr7\Message::toString($e->getRequest()));
            dump(Psr7\Message::toString($e->getResponse()));
        }

        return $response->json();
    }


}
