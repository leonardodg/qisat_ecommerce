<?php

namespace App\Network;

use Cake\Core\Configure;

class Request extends \Cake\Network\Request
{

    /**
     * Wrapper method to create a new request from PHP superglobals.
     *
     * Uses the $_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $_ENV and php://input data to construct
     * the request.
     *
     * @return \Cake\Network\Request
     */
    public static function createFromGlobals()
    {
        list($base, $webroot) = static::_base();
        $sessionConfig = (array)Configure::read('Session') + [
                'defaults' => 'php',
                'cookiePath' => $webroot
            ];

        $config = [
            'query' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
            'cookies' => $_COOKIE,
            'environment' => $_SERVER + $_ENV,
            'base' => $base,
            'webroot' => $webroot,
            'session' => Session::create($sessionConfig)
        ];
        $config['url'] = static::_url($config);
        return new static($config);
    }

}
