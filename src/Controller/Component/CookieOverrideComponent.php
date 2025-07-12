<?php
/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 04/11/2016
 * Time: 11:04
 */

namespace App\Controller\Component;


use App\Auth\SecurityAES;
use Cake\Controller\Component\CookieComponent;
use Cake\I18n\Time;

class CookieOverrideComponent extends CookieComponent
{

    protected function _write($name, $value)
    {

        // echo $this->getKey();
        // die;

        $aes = new SecurityAES($this->getKey());

        $config = $this->configKey($name);
        $expires = new Time($config['expires']);

        $this->_response->cookie([
            'name' => $name,
            'value' => $aes->criptografar(json_encode($value)),
            'expire' => $expires->format('U'),
            'path' => $config['path'],
            'domain' => $config['domain'],
            'secure' => $config['secure'],
            'httpOnly' => $config['httpOnly']
        ]);
    }

    public function read($key = null)
    {
        $this->_load($key);
        $value = null;

        if(array_key_exists($key, $this->_values))
            $value = $this->_values[$key];
        return $value;
    }

    private function getKey(){
        return mb_substr(hash('sha256', $this->_getCookieEncryptionKey()), 0, 32, '8bit');
    }

    protected function _load($key)
    {
        $parts = explode('.', $key);
        $first = array_shift($parts);
        if (isset($this->_loaded[$first])) {
            return;
        }
        if (!isset($this->request->cookies[$first])) {
            return;
        }
        $cookie = $this->request->cookies[$first];
        $this->_loaded[$first] = true;
        $this->_values[$first] = $this->_decrypt($cookie);
    }

    protected function _decrypt($values, $mode = null)
    {
        $aes = new SecurityAES($this->getKey());
        $decrypted = $aes->descriptografar($values);
        $decrypted = (array)json_decode($decrypted);
        return $decrypted;
    }



}