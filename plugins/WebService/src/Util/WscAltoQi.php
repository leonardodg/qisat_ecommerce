<?php
namespace WebService\Util;

use Cake\Network\Http\Client;

class WscAltoQi
{

    const URL_BASE = 'http://api.altoqi.com.br'; //'http://api.altoqi.com.br'
    const ORIGIN = 'teste.altoqi.com.br'; // 'local-ecommerce.qisat.com.br'
    const LOGIN = '209517-8';
    const PASSWORD = 'ENE642';
    static protected $token = '';

    /*
    // LOGIN INTEGRADO
    public static function login($username, $password)
    {
        $url = self::URL_BASE . '/auth';
        $config = array( 'Content-Type' => 'application/x-www-form-urlencoded', 'type' => 'json' );

        if(!is_null($username) && !is_null($password)){
            $dados = array( 'codigo' => $username, 'senha' => $password );
            $http = new Client();
            $response = $http->post( $url, $dados, $config );
            $result = $response->json;
            $token = $result['meta']['token'];

            if($response->isOk() && $token){
                $user = (object) $result['data'][0];
                $name = explode(' ',$user->nome);
                $user->first_name = count($name) > 2 ? $name[0].' '.$name[1] :  $name[0];
                $user->last_name = $name[ count($name) - 1 ];
                $user->token = $token;
                $aq_user = base64_encode(json_encode($user));
                $aq_token = base64_encode($token);
                unset($user->token);

                return (object) [ "usuario" => $user, "token" => $token, "aq_user" => $aq_user, "aq_token" => $aq_token ];
            }
        }
        return false;
    }*/

    public static function credential($username = null, $password = null)
    {
        $url = self::URL_BASE . '/auth';
        $config = array( 'Content-Type' => 'application/x-www-form-urlencoded', 'type' => 'json' , 'headers' => [ 'Origin' =>  self::ORIGIN ]);

        if(!is_null($username) && !is_null($password))
            $dados = array( 'codigo' => $username, 'senha' => $password );
        else
            $dados = array( 'codigo' => self::LOGIN, 'senha' => self::PASSWORD );

        $http = new Client();
        $response = $http->post( $url, $dados, $config );

        if($response->isOk()){
            $result = $response->json;
            self::$token = $result['meta']['token'];
            return true;
        }
        
        return false;
    }

    public static function userListProduts($chave)
    {
        if($chave){
            $codigo = self::getDigito($chave);
            if(empty(self::$token))
                self::credential();

            if(!empty(self::$token)){
                $url = self::URL_BASE . '/usuario/'.$codigo.'/produtos';
                $config = array( 'Content-Type' => 'application/x-www-form-urlencoded', 'type' => 'json','headers' => [ 'Origin' => self::ORIGIN, 'Authorization' => self::$token ]);

                $http = new Client();
                $response = $http->get( $url, [], $config );

                if($response->isOk()){
                    $result = $response->json;
                    return $result['data'];
                }
            }
        }

        return false;
    }

    /**
        Função para buscar atendimentos do TOPComercial para um usuário 
        @paramts $chave = Numero de Identificação da AltoQi

        @return ArrayList or False;
    */
    public static function userListCalls($chave)
    {
        if($chave){
            $codigo = self::getDigito($chave);
            if(empty(self::$token))
                self::credential();

            if(!empty(self::$token)){
                $dados = array( 'codigo' => $codigo );

                $url = self::URL_BASE . '/atendimento';
                $config = array( 'Content-Type' => 'application/x-www-form-urlencoded', 'type' => 'json','headers' => [ 'Origin' =>  self::ORIGIN, 'Authorization' => self::$token ]);


                $http = new Client();
                $response = $http->get( $url, $dados, $config );

                if($response->isOk()){ // || $response->code == 404
                    $result = $response->json;
                    return $result['data'][0];
                }
            }
        }

        return false;
    }

    private static function getDigito($chave){
        $chave = str_pad($chave, 6 , "0", STR_PAD_LEFT);
        $d1 = (int)($chave[0]);
        $d2 = (int)($chave[1]);
        $d3 = (int)($chave[2]);
        $d4 = (int)($chave[3]);
        $d5 = (int)($chave[4]);
        $d6 = (int)($chave[5]);
        $multiplica = (($d1 * 5) + ($d2 * 6) + ($d3 * 7) + ($d4 * 3) + ($d5 * 2) + ($d6 * 4));
        $resto = $multiplica % 11;
        return ($resto == 10) ? $chave.'-1' : $chave.'-'.$resto;
    }

    public static function send($url, $dados = [], $get = true)
    {
        if(empty(self::$token))
            self::credential();

        if(!empty(self::$token)){
            $url = self::URL_BASE . '/'. $url;
            $config = array( 'Content-Type' => 'application/x-www-form-urlencoded', 'type' => 'json','headers' => [ 'Origin' =>  self::ORIGIN, 'Authorization' => self::$token ]);

            $http = new Client();
            if($get)
                $response = $http->get( $url, $dados, $config );
            else
                $response = $http->post( $url, $dados, $config );

            return $response->isOk();
        }

        return false;
    }
}