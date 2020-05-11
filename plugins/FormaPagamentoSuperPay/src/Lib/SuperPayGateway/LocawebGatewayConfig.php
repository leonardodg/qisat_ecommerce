<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 16/06/2016
 * Time: 12:17
 */

namespace FormaPagamentoSuperPay\Lib\SuperPayGateway;


class LocawebGatewayConfig
{
    const DEFAULT_ENV = "production";
    const DEFAULT_TOKEN = "";

    static protected $environment = self::DEFAULT_ENV;
    static protected $token = self::DEFAULT_TOKEN;

    private function __construct(){}

    static public function getToken(){
        return self::$token;
    }

    static public function setToken($value){
        if(self::$token == self::DEFAULT_TOKEN)
            self::$token = $value;
    }

    static public function getEnvironment(){
        return self::$environment;
    }

    static public function setEnvironment($value){
        if(self::$environment == self::DEFAULT_ENV)
            self::$environment = $value;
    }
}