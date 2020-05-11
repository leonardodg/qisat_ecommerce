<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Network;

use Cake\ORM\TableRegistry;
use Carrinho\Model\Entity\EcmCarrinho;
use RuntimeException;

/**
 * This class is a wrapper for the native PHP session functions. It provides
 * several defaults for the most common session configuration
 * via external handlers and helps with using session in cli without any warnings.
 *
 * Sessions can be created from the defaults using `Session::create()` or you can get
 * an instance of a new session by just instantiating this class and passing the complete
 * options you want to use.
 *
 * When specific options are omitted, this class will take its defaults from the configuration
 * values from the `session.*` directives in php.ini. This class will also alter such
 * directives when configuration values are provided.
 */
class Session extends \Cake\Network\Session
{

    /**
     * Starts the Session.
     *
     * @return bool True if session was started
     * @throws \RuntimeException if the session was already started
     */
    public function start()
    {
        if ($this->_started) {
            return true;
        }

        if ($this->_isCLI) {
            $_SESSION = [];
            return $this->_started = true;
        }

        if (session_status() === \PHP_SESSION_ACTIVE) {
            throw new RuntimeException('Session was already started');
        }

        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            return;
        }

        if (!session_start()) {
            throw new RuntimeException('Could not start the session');
        }

        $this->_started = true;

        if ($this->_timedOut()) {

            $ecmCarrinho = $this->read('carrinho');
            if(!is_null($ecmCarrinho)) {
                if(!is_null($ecmCarrinho->id)) {
                    $ecmCarrinhoModel = TableRegistry::get('Carrinho.EcmCarrinho');
                    $ecmCarrinho->set('status', EcmCarrinho::STATUS_CANCELADO);
                    $ecmCarrinhoModel->save($ecmCarrinho);
                }
            }

            $this->destroy();
            return $this->start();
        }

        return $this->_started;
    }

}
