<?php
/**
 * JqueryBootpagHelper classe Helper para a utilização de paginação com Jquery
 *
 * @link http://botmonster.com/jquery-bootpag Link para acesso a documentação do plugin
 * @author Deyvison Fernandes
 */

namespace App\View\Helper;


class JqueryBootpagHelper extends JQueryBaseHelper
{
    public $helpers = ['Html'];

    /**
     * Função responsável por criar uma paginação com Jquery
     *
     * @param string $elemento elemento HTML para efetuar a função de click
     * @param string $script script que será executado quando alternar entre as páginas
     * @param array $atributos Array com os atributos da função
     * @return string script pronto para a renderização
     */
    public function pagination($elemento, $script,$atributos = null) {
        $scriptJquery = parent::gerarScriptJquery($elemento, 'bootpag',$atributos);
        $scriptJquery = substr($scriptJquery, 0, -1);

        $scriptJquery .= '.on("page", function(event, page){'.$script.'});';

        return $scriptJquery;
    }

    /**
     * Função que deverá ser implementada contendo os scripts css, js necessários para o funcionamento
     * de um Helper
     * @return mixed
     */
    public function getScript(){
        return $this->Html->script('jquery.bootpag.min.js');
    }
}