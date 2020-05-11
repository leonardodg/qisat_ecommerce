<?php

namespace App\Auth;

use AES\SecurityAES;
use Cake\Auth\AbstractPasswordHasher;

class AESPasswordHasher extends AbstractPasswordHasher
{
    public static $AES_CIPHER = 'dMOqr2ZsT1r7cCYD';
    private $aes;

    /**
     * Constructor
     *
     * @param array $config Array of config.
     */
    public function __construct(array $config = [])
    {
        $this->config($config);
        $this->aes = new SecurityAES(self::$AES_CIPHER);
    }

    /**
     * Generates password hash.
     *
     * @param string|array $password Plain text password to hash or array of data
     *   required to generate password hash.
     * @return string Password hash
     */
    public function hash($password)
    {
        return $this->aes->criptografar($password);
    }

    /**
     * Check hash. Generate hash from user provided password string or data array
     * and check against existing hash.
     *
     * @param string|array $password Plain text password to hash or data array.
     * @param string $hashedPassword Existing hashed password.
     * @return bool True if hashes match else false.
     */
    public function check($password, $hashedPassword)
    {
        return $this->aes->criptografar($password) == $hashedPassword;
    }

    public function decrypt($password){
        return $this->aes->descriptografar($password);
    }
}