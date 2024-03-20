<?php

namespace App\Http\Controllers\Phones;

use App\Http\Controllers\Controller;
use App\Http\Requests\Phone\StoreRequest;
use App\Http\Services\MyB24;
use App\Http\Services\PhoneServices;
use App\Models\Phone;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $member_id = $request->input('member_id');

        $phone = Phone::where('member_id', $member_id)->first();

        if ($phone) {
            $automatic = $phone->automatic;
            $format = $phone->format;
        } else {
            $automatic = '';
            $format = '';
        }
        $errors = [];
        $all_data = [];
        return view('btx.index_phone', compact('member_id', 'automatic', 'format', 'errors', 'all_data'));
    }

    public function install(Request $request)
    {
        $install_result = MyB24::installApp($request);

        $result_bind_1 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMLEADADD');
        $result_bind_2 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMLEADUPDATE');

        $result_bind_3 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMCONTACTADD');
        $result_bind_4 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMCONTACTUPDATE');

        $result_bind_4 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMCOMPANYADD');
        $result_bind_5 = MyB24::bindPhoneCallB24('event.bind', 'ONCRMCOMPANYUPDATE');

//        dd($result_bind_3);
        Log::info("EVENT_BIND");
        Log::info($result_bind_3);
        Log::info($result_bind_4);

        return view('btx.install', compact("install_result"));
    }

    public function store(StoreRequest $request)
    {

        $data = $request->validated();

        $setting = Setting::where('member_id', $data['member_id'])->first();

        if ($setting) {
            $results = Phone::updateOrCreate([
                'member_id' => $data['member_id'],
            ], [
                'member_id' => $data['member_id'],
                'format' => $data['format'],
                'automatic' => $data['automatic'] ?? ''
            ]);
            $member_id = $data['member_id'];
            $format = $data['format'];
            $automatic = $data['automatic'] ?? '';
            $errors = '';
            $all_data = [];
            return view('btx.index_phone', compact('member_id', 'automatic', 'format', 'errors', 'all_data'));
        } else {
            return [];
        }

    }

    public function handler(Request $request)
    {

        $event = $request->input('event');
        $id = $request->input('data.FIELDS.ID');
        $member_id = $request->input('auth.member_id');

        $phone_settings = Phone::where('member_id', $member_id)->first();

        if (!$phone_settings) {
            return false;
        }

        $format = $phone_settings->format;
        $automatic = $phone_settings->automatic;
//        Log::info("REQUEST");
//        Log::info($request->input());


        $crm_type = '';
        if (in_array($event, ['0' => 'ONCRMLEADADD', '1' => 'ONCRMLEADUPDATE'])) {
            $crm_type = 'CRM_LEAD';
        } elseif (in_array($event, ['0' => 'ONCRMCONTACTADD', '1' => 'ONCRMCONTACTUPDATE'])) {
            $crm_type = 'CRM_CONTACT';
        } elseif (in_array($event, ['0' => 'ONCRMCOMPANYADD', '1' => 'ONCRMCOMPANYUPDATE'])) {
            $crm_type = 'CRM_COMPANY';
        }

        if (!empty($automatic) && $crm_type) {
            $get = PhoneServices::getPhoneCallB24($crm_type, $id);
            $phones = $get['result']['PHONE'];
            if (isset($get['result']['PHONE'])) {
                $new_phones = [];
                foreach ($phones as $phone) {
                    $result = PhoneServices::check_phone_format($phone['VALUE'], (string)$format);
                    $phone['VALUE'] = $result['VALUE'];
                    $new_phones[] = $phone;
                }
                $set = PhoneServices::setPhoneCallB24($crm_type, $id, $new_phones);
            }

        }

        return true;
    }

    public function phonesupdate(Request $request)
    {

        $crm_title = [
            'url' => ['CRM_LEAD' => 'lead',
                'CRM_CONTACT' => 'contact',
                'CRM_COMPANY' => 'company'],
            'title' => ['CRM_LEAD' => 'Лидах',
                'CRM_CONTACT' => 'Контактах',
                'CRM_COMPANY' => 'Компаниях']
        ];

        $req_counter = 0;
        $member_id = $request->input('member_id');
        if (!$member_id) {
            dd('FALSE1');
            return false;
        }
        $bit = Setting::where('member_id', $member_id)->first();

        if (!$bit) {
            dd('FALSE2');
            return false;
        }
        //МЕТОД ОБНОВЛЕНИЯ ТОКЕНА
        $res = MyB24::CallB24_refresh_token($member_id);
        if (!$res) {
            dd('FALSE3');
            return false;
        }
        $req_counter++;

        $site = $bit->domain;
        $errors = [];

        $phone_settings = Phone::where('member_id', $member_id)->first();

        if (!$phone_settings) {
            dd('FALSE4');
            return false;
        }

        $format = $phone_settings->format;
        $automatic = $phone_settings->automatic;

        $phone_settings->automatic = '';
        $phone_settings->save();


        $crm_array = array('CRM_COMPANY', 'CRM_LEAD', 'CRM_CONTACT');
//        $crm_array = array('CRM_CONTACT');
        $all_data = [];
        foreach ($crm_array as $crm_type) {
            sleep(2);
//ЗАПРОСЫ НА ПОЛУЧЕНИЯ НОМЕРОВ
            $results = PhoneServices::getBatchPhoneCallB24($crm_type, $member_id);

//                dd($results);
            $req_counter += $results['req_counter'];
            if (empty($results['data'])) {
                dump($results);
                continue;
            };
//                dd($req_counter);
            if (PhoneServices::check_results($results['data'])) {
                $data = [];
                $data['result'] = [];
                $data['errors'] = [];
                $data['update'] = [];

                $data_request = [];

                $result = $results['data']['result']['result'];
//dd($results);
                $result_up['result'] = [];
                $result_up['errors'] = [];
                $result_up['update'] = [];

                foreach ($result as $item50) {
                    $result_up = PhoneServices::update_item50($item50, $crm_type, $format);
                    $data['result'] = [...$data['result'], ...$result_up['result']];
                    $data['errors'] = [...$data['errors'], ...$result_up['errors']];
                    $data['update'] = [...$data['update'], ...$result_up['update']];
                }

                $all_data[$crm_type] = $data;

//            dump($data['errors'][0]['ID']);
//            dd($data['result']);

                foreach ($data['result'] as $item) {
                    $data_request[] = PhoneServices::get_request_crm($item, $crm_type);
                }
//                dd($data_request);
                $requests = array();
                $counter = 0;

                for ($i = 0; $i < count($data_request); $i++) {
                    $requests[$counter][] = $data_request[$i];
                    if ($i === 19) {
                        $counter++;
                    } elseif ($i - ($counter * 20) === 19) {
                        $counter++;
                    }
                }
                $req_counter += count($requests);

                foreach ($requests as $req50) {
//                    dd($requests);
                    $response = PhoneServices::setBatchPhoneCallB24($req50, $member_id);
                }
            }

        }


//        dd($all_data);

//        dd($member_id);
        $phone_settings_fresh = Phone::where('member_id', $member_id)->first();
        $phone_settings_fresh->automatic = $automatic;
        $phone_settings_fresh->save();
        return view('btx.index_phone', compact('member_id', 'crm_title', 'automatic', 'format', 'errors', 'all_data', 'site'));
    }
}
