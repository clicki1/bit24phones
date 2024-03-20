<?php

namespace App\Http\Services;

use App\Models\Field;
use App\Models\Rule;

class Services
{
    public static function getLeads($request, $type = 'all')
    {
        $data = [];
        $data = json_decode($request->input('PLACEMENT_OPTIONS'), true);
        if ($type == 'id') {
            return $data['ENTITY_VALUE_ID'];
        }
        return $data;
    }

    //лиды
    public static function refreshCRM_LEADfields($request)
    {
        $result = MyB24::getCallB24($request, 'crm.lead.fields');
        $data = [];
        // dd($result['result']);
        foreach ($result['result'] as $k => $value) {
            if ($value['isReadOnly'] == true) continue;
            $data[$k]['TITLE'] = $k;
            $data[$k]['BTX_TITLE'] = $value['title'] ?? $value['formLabel'];
            $data[$k]['CRM_TYPE'] = "CRM_LEADS";
            $data[$k]['type'] = $value['type'];
            $data[$k]['isRequired'] = $value['isRequired'];
            $data[$k]['isReadOnly'] = false;
            $data[$k]['isImmutable'] = $value['isImmutable'];
            $data[$k]['isMultiple'] = $value['isMultiple'];
            $data[$k]['isDynamic'] = $value['isDynamic'];
            $data[$k]['settings'] = json_encode($value['settings'] ?? []);
            $data[$k]['listLabel'] = $value['listLabel'] ?? '';
            $data[$k]['filterLabel'] = $value['filterLabel'] ?? '';
            $data[$k]['formLabel'] = $value['formLabel'] ?? '';
        }

        foreach ($data as $res) {
            // dd($res);
            $field = Field::firstOrCreate([
                'TITLE' => $res['TITLE'],
                'CRM_TYPE' => $res['CRM_TYPE'],
            ], $res);
        }
        $fields = Field::all();

        return $fields;
    }

    //сделки
    public static function refreshCRM_DEALfields($request)
    {
        $result = MyB24::getCallB24($request, 'crm.deal.fields');
        $data = [];
//        dd($result['result']);
        foreach ($result['result'] as $k => $value) {
            if ($value['isReadOnly'] == true) continue;
            $data[$k]['TITLE'] = $k;
            $data[$k]['BTX_TITLE'] = $value['title'] ?? $value['formLabel'];
            $data[$k]['CRM_TYPE'] = "CRM_DEAL";
            $data[$k]['type'] = $value['type'];
            $data[$k]['isRequired'] = $value['isRequired'];
            $data[$k]['isReadOnly'] = false;
            $data[$k]['isImmutable'] = $value['isImmutable'];
            $data[$k]['isMultiple'] = $value['isMultiple'];
            $data[$k]['isDynamic'] = $value['isDynamic'];
            $data[$k]['settings'] = json_encode($value['settings'] ?? []);
            $data[$k]['listLabel'] = $value['listLabel'] ?? '';
            $data[$k]['filterLabel'] = $value['filterLabel'] ?? '';
            $data[$k]['formLabel'] = $value['formLabel'] ?? '';
        }

        foreach ($data as $res) {
            $field = Field::firstOrCreate([
                'TITLE' => $res['TITLE'],
                'CRM_TYPE' => $res['CRM_TYPE'],
            ], $res);
        }

        $fields = Field::all();

        return $fields;
    }

    //контакты
    public static function refreshCRM_CONTACTfields($request)
    {
        $result = MyB24::getCallB24($request, 'crm.contact.fields');
        $data = [];
//        dd($result['result']);
        foreach ($result['result'] as $k => $value) {
            if ($value['isReadOnly'] == true) continue;
            $data[$k]['TITLE'] = $k;
            $data[$k]['BTX_TITLE'] = $value['title'] ?? $value['formLabel'];
            $data[$k]['CRM_TYPE'] = "CRM_CONTACT";
            $data[$k]['type'] = $value['type'];
            $data[$k]['isRequired'] = $value['isRequired'];
            $data[$k]['isReadOnly'] = false;
            $data[$k]['isImmutable'] = $value['isImmutable'];
            $data[$k]['isMultiple'] = $value['isMultiple'];
            $data[$k]['isDynamic'] = $value['isDynamic'];
            $data[$k]['settings'] = json_encode($value['settings'] ?? []);
            $data[$k]['listLabel'] = $value['listLabel'] ?? '';
            $data[$k]['filterLabel'] = $value['filterLabel'] ?? '';
            $data[$k]['formLabel'] = $value['formLabel'] ?? '';
        }

        foreach ($data as $res) {
            $field = Field::firstOrCreate([
                'TITLE' => $res['TITLE'],
                'CRM_TYPE' => $res['CRM_TYPE'],
            ], $res);
        }

        $fields = Field::all();

        return $fields;
    }

//компании
    public static function refreshCRM_COMPANYfields($request)
    {
        $result = MyB24::getCallB24($request, 'crm.company.fields');
        $data = [];
//        dd($result['result']);
        foreach ($result['result'] as $k => $value) {
            if ($value['isReadOnly'] == true) continue;
            $data[$k]['TITLE'] = $k;
            $data[$k]['BTX_TITLE'] = $value['title'] ?? $value['formLabel'];
            $data[$k]['CRM_TYPE'] = "CRM_COMPANY";
            $data[$k]['type'] = $value['type'];
            $data[$k]['isRequired'] = $value['isRequired'];
            $data[$k]['isReadOnly'] = false;
            $data[$k]['isImmutable'] = $value['isImmutable'];
            $data[$k]['isMultiple'] = $value['isMultiple'];
            $data[$k]['isDynamic'] = $value['isDynamic'];
            $data[$k]['settings'] = json_encode($value['settings'] ?? []);
            $data[$k]['listLabel'] = $value['listLabel'] ?? '';
            $data[$k]['filterLabel'] = $value['filterLabel'] ?? '';
            $data[$k]['formLabel'] = $value['formLabel'] ?? '';
        }

        foreach ($data as $res) {
            $field = Field::firstOrCreate([
                'TITLE' => $res['TITLE'],
                'CRM_TYPE' => $res['CRM_TYPE'],
            ], $res);
        }

        $fields = Field::all();

        return $fields;
    }

//CRM_QUOTE предложения
    public static function refreshCRM_QUOTEfields($request)
    {
        $result = MyB24::getCallB24($request, 'crm.quote.fields');
        $data = [];
        foreach ($result['result'] as $k => $value) {
            if ($value['isReadOnly'] == true) continue;
            $data[$k]['TITLE'] = $k;
            $data[$k]['BTX_TITLE'] = $value['title'] ?? $value['formLabel'];
            $data[$k]['CRM_TYPE'] = "CRM_QUOTE";
            $data[$k]['type'] = $value['type'];
            $data[$k]['isRequired'] = $value['isRequired'];
            $data[$k]['isReadOnly'] = false;
            $data[$k]['isImmutable'] = $value['isImmutable'];
            $data[$k]['isMultiple'] = $value['isMultiple'];
            $data[$k]['isDynamic'] = $value['isDynamic'];
            $data[$k]['settings'] = json_encode($value['settings'] ?? []);
            $data[$k]['listLabel'] = $value['listLabel'] ?? '';
            $data[$k]['filterLabel'] = $value['filterLabel'] ?? '';
            $data[$k]['formLabel'] = $value['formLabel'] ?? '';
        }

        foreach ($data as $res) {
            $field = Field::firstOrCreate([
                'TITLE' => $res['TITLE'],
                'CRM_TYPE' => $res['CRM_TYPE'],
            ], $res);
        }

        $fields = Field::all();

        return $fields;
    }


    public static function checkLeadsFields($data)
    {
        $rules = Rule::where('CRM_TYPE', $data['CRM_TYPE'])
            ->where('member_id', $data['member_id'])->get() ?? [];
        $result = [];

        if (count($rules) == 0) {
            return 'Rules empty';
        }
        unset($data['CRM_TYPE']);
        unset($data['member_id']);
        foreach ($rules as $rule) {
            if ($rule->rule_type == 1) {
                $result[] = Services::checkFieldsOne($data, $rule);
            } elseif ($rule->rule_type == 2) {
                $result[] = Services::checkFieldsTwo($data, $rule);
            } elseif ($rule->rule_type == 3) {
                $result[] = Services::checkFieldsThree($data, $rule);
            }
        }
        return $result;
    }

    public static function checkFields($data, $rule, $rule_field)
    {
        $result = [];

        if (isset($data[$rule_field['id']])) {
            $field = Field::find($rule_field['id']);
            // 0 => 'РАВНО',
            if ($rule_field['rule'] == 0) {
                if ($field->MULTIPLE == 1) {
                    foreach ($data[$rule_field['id']] as $data_mult) {
                        if ($data_mult == $rule_field['text']) {
                            $value = $data[$rule_field['id']];
                            $result[$rule_field['id']] = [
                                'show' => $rule->show,
                                'check' => 1,
                                'value' => $value
                            ];
                            $result['check_rule_field'] = 1;
                            break;
                        }
                    }
                } else {
                    if ($data[$rule_field['id']] == $rule_field['text']) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                }
            }
//        1 => 'НЕ РАВНО',
            if ($rule_field['rule'] == 1) {
                if ($field->MULTIPLE == 1) {
                    foreach ($data[$rule_field['id']] as $data_mult) {
                        if ($data_mult !== $rule_field['text']) {
                            $value = $data[$rule_field['id']];
                            $result[$rule_field['id']] = [
                                'show' => $rule->show,
                                'check' => 1,
                                'value' => $value
                            ];
                            $result['check_rule_field'] = 1;
                            break;
                        }
                    }
                } else {
                    if ($data[$rule_field['id']] !== $rule_field['text']) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                }

            }
            //        2 => 'НЕ ЗАПОЛНЕНО',
            if ($rule_field['rule'] == 2) {
                if ($field->MULTIPLE == 1) {
                    if (count($data[$rule_field['id']]) < 1) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                } else {
                    if (empty($data[$rule_field['id']])) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                }
            }
            //        3 => 'ЗАПОЛНЕНО',
            if ($rule_field['rule'] == 3) {
                if ($field->MULTIPLE == 1) {
                    if (count($data[$rule_field['id']]) > 0) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                } else {
                    if (!empty($data[$rule_field['id']])) {
                        $value = $data[$rule_field['id']];
                        $result[$rule_field['id']] = [
                            'show' => $rule->show,
                            'check' => 1,
                            'value' => $value
                        ];
                        $result['check_rule_field'] = 1;
                    }
                }

            }
            //        4 => 'СОДЕРЖИТ',
            if ($rule_field['rule'] == 4) {
                if ((strpos($data[$rule_field['id']], $rule_field['text']) !== false)) {
                    $value = $data[$rule_field['id']];
                    $result[$rule_field['id']] = [
                        'show' => $rule->show,
                        'check' => 1,
                        'value' => $value
                    ];
                    $result['check_rule_field'] = 1;
                }
            }
            //       5 => 'НЕ СОДЕРЖИТ',
            if ($rule_field['rule'] == 5) {
                if ((strpos($data[$rule_field['id']], $rule_field['text']) === false)) {
                    $value = $data[$rule_field['id']];
                    $result[$rule_field['id']] = [
                        'show' => $rule->show,
                        'check' => 1,
                        'value' => $value
                    ];
                    $result['check_rule_field'] = 1;
                }
            }

        };

        return $result;
    }

    public static function checkFieldsOne($data, $rule)
    {

        $rule_fields = json_decode($rule->rule, true);
        $rule_field = null;
        foreach ($rule_fields as $value) {
            $rule_field = $value;
        }

        $result = Services::checkFields($data, $rule, $rule_field);

        if (isset($result['check_rule_field'])) {
            $data_fresh[$rule->field_id] = [
                'show' => $rule->show,
            ];
        } else {
            $data_fresh[$rule->field_id] = [
                'show' => ($rule->show == 1) ? 0 : 1,
            ];
        };

        return $data_fresh;
    }

    public static function checkFieldsTwo($data, $rule)
    {
        $data_fresh = [];
        $rule_fields = json_decode($rule->rule, true);

        foreach ($rule_fields as $rule_field) {

            $result = Services::checkFields($data, $rule, $rule_field);

            if (isset($result['check_rule_field'])) {
                $data_fresh[$rule->field_id] = [
                    'show' => $rule->show,
                ];
                break;
            };

            $data_fresh[$rule->field_id] = [
                'show' => ($rule->show == 1) ? 0 : 1,
            ];
        }

        return $data_fresh;

    }

    public static function checkFieldsThree($data, $rule)
    {
        $data_fresh = [];
        $rule_fields = json_decode($rule->rule, true);
        $check_cont = count($rule_fields);
        foreach ($rule_fields as $rule_field) {

            $result = Services::checkFields($data, $rule, $rule_field);

            if (!count($result)) {
                $data_fresh[$rule->field_id] = [
                    'show' => ($rule->show == 1) ? 0 : 1,
                ];
                break;
            } else {
                $data_fresh[$rule->field_id] = [
                    'show' => $rule->show,
                ];
            }

        }

        return $data_fresh;
    }

    public static function rules_fields($id_num = null)
    {
        $rules_fields = [
            'РАВНО' => '0',
            'НЕ РАВНО' => '1',
            'НЕ ЗАПОЛНЕНО' => '2',
            'ЗАПОЛНЕНО' => '3',
            'СОДЕРЖИТ' => '4',
            'НЕ СОДЕРЖИТ' => '5',
        ];

        if ($id_num === null) {
            return $rules_fields;
        }

        return array_search($id_num, $rules_fields);
    }

    public static function type_fields($type_field = null)
    {
        $type = [
            '0' => 'string',
            '1' => 'enumeration',
        ];

        if (!empty($type_field)) {
            return in_array($type_field, $type) ? true : false;
        }

        return $type;
    }

    public static function rule_type($type_rule = null)
    {
        $type = [
            'Разные значения полей управляют разными полями' => '1',
            'Разные значения полей управляют одними полями' => '2',
            'Совокупность значений управляет одними полями' => '3',
        ];

        if (!empty($type_rule)) {
            return in_array($type_rule, $type) ? array_search($type_rule, $type) : array_search($type_rule, $type);
        }

        return $type;
    }
}
