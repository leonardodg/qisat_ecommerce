<?php
/**
 * JqueryUIHelper classe Helper para a utilização da biblioteca Jquery UI
 *
 * @link https://jqueryui.com/ Link para acesso a documentação do plugin
 * @author Deyvison Fernandes
 *
 */

namespace App\View\Helper;

class JqueryUIHelper extends JQueryBaseHelper
{
    public $helpers = ['Html'];

    /**
     * @param $elemento Identificação do elemento HTML
     * @param array $atributos Array com os atributos da função
     * @return string script pronto para a renderização
     */
    public function datePicker($elemento, $atributos = null) {
        $atributos['language'] = 'pt-BR';
        return parent::gerarScriptJquery($elemento, 'datepicker',$atributos);
    }

    /**
     * Função que deverá ser implementada contendo os scripts css, js necessários para o funcionamento
     * de um Helper
     * @return mixed
     */
    public function getScript()
    {
        $script = $this->Html->script('jquery-ui.min');
        $script .= $this->Html->script('datepicker-pt-BR');
        $script .= $this->Html->css('jquery-ui.min');

        return $script;
    }
}