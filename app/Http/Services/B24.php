<?php

namespace App\Http\Services;


use GuzzleHttp\Client;

/**
 *
 * Простой класс для работы с REST-API Битрикс24
 *
 */

class B24 extends Client
{

    protected $conftoken = null;
    public $arrToallDealList = array();
    protected $reqMethod = '';
    protected $reqParam = array();


    /**
     * Установка URL-адреса токена во время инициализации объекта,
     * для последующего использования, также вызываем родительский конструктор
     */

    function __construct($token) {
        parent::__construct();
        $this->conftoken = $token;
    }



    /**
     *
     * Выполнение простого метода, не списочного
     * @param array $params Параметры для запроса
     * @param string $method Название метода
     * @return array Ассоциативный массив
     * Пример использования : $obj->oneMethod(array('ID'=>245),'crm.lead.get');
     *
     */

    public function oneMethod(array $params, string $method): mixed
    {
        $init = $this->conftoken;
        if($init) {
            $response = $this->request('POST', '$this->conftoken/$method.json',['json'=>$params],['http_errors' => false]);
            return json_decode($response->getBody(),true);
        }else{
            return false;
        }
    }


    /**
     *
     * Заполнение массива для метода listMethod()
     * если результат запроса составляет более 50 элементов.
     * Вызывается методом listMethod()
     * $arrtoalldeallist свойство заполняется в цикле.
     * Происходит проверка на наличие ключа 'next' в $arrResult
     * Если такой ключ есть, то вызывается метод listMethod.
     * Ему передаются параметры, которые хранятся в свойствах $reqmethod и $reqparam
     * В противном случае возвращается свойство arrToallDealList
     * Между запросами существует интервал в 1 секунду
     * @param array $arrResult Результат метода listMethod()
     * @return array Ассоциативный массив
     *
     */

    public function arrCombine(array $arrResult): array
    {
        foreach($arrResult['result'] as $key => $value) {
            $this->arrToallDealList[] = $value;
        }
        if(array_key_exists('next', $arrResult)) {
            sleep(1);
            $this->listMethod($this->reqParam,$this->reqMethod);
        }
        return $this->arrToallDealList;
    }

    /**
     *
     * Для выполнения запросов к списочным методам.
     * Полученный результат отправляется в метод arrCombine()
     * Как параметр
     * Метод вернет все результаты в одном массиве
     * Сохраняет параметры запроса в свойстве $reqParam
     * Сохраняет имя метода в свойстве $reqMethod
     * @param array $params Параметры для запроса
     * @param string $method Метод для запроса
     * @return bool|array Ассоциативный массив с всеми результатами
     * Пример использования: $obj->listMethod(array('SELECT' => array('ID', 'TITLE'),'FILTER' => array('>OPPORTUNITY' => '50000')),'crm.deal.list');
     *
     */

    public function listMethod(array $params,string $method): bool|array
    {
        $init = $this->conftoken;
        if($init) {
            $response = $this->request('POST', '$this->conftoken/$method.json',['json'=>$params],['http_errors' => false]);
            $result = json_decode($response->getBody(),true);

            if(array_key_exists('next', $result)) {

                $params['start'] = $result['next'];
                $this->reqParam = $params;
                $this->reqMethod = $method;

            }
            return $this->arrCombine($result);
        }else {
            return false;
        }
    }

}
