<?php  
/**
 * Class com as regras de criptografia padrão utilizando AES e codificação base64
 *
 * @author Deyvison Fernandes Baldoino 
 */

namespace App\Auth;

use PhpAes\Aes as AES;

class SecurityAES{
	private $aes;

	/**
	 * Contrutor da classe onde deve ser passado uma String que será a chave de criptografia
	 * @param String $chaveAES
	 */
	public function __construct($chaveAES){
		$this->aes = new AES($chaveAES);
	}

	/**
	 * Função que criptografa uma String e retorna um hash 
	 * @param String $string
	 * @return String hash
	 */
	public function criptografar($string){
        return base64_encode($this->aes->encrypt($string));
    }

    /**
	 * Função que descriptografa uma String e retorna o valor descriptografado
	 * @param String $hash
	 * @return String valor
	 */
    public function descriptografar($hash){
        return $this->aes->decrypt(base64_decode($hash));
    }
}