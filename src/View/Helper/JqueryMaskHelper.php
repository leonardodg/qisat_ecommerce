<?php
/**
 * JqueryMask classe Helper para a utilização da biblioteca Jquery Mask
 *
 * @link https://github.com/igorescobar/jQuery-Mask-Plugin Link para acesso a documentação do plugin
 * @author Deyvison Fernandes
 *
 */

namespace App\View\Helper;


class JqueryMaskHelper extends JQueryBaseHelper
{
    public $helpers = ['Html'];
    /**
     * @param $elemento Identificação do elemento HTML
     * @param array $atributos Array com os atributos da função
     * @return string script pronto para a renderização
     */
    public function mask($elemento, $atributos = null) {
        return parent::gerarScriptJquery($elemento, 'mask',$atributos);
    }

    /**
     * Função para remover uma máscara de um elemento
     * @param $elemento Identificação do elemento HTML
     * @return string script pronto para a renderização
     */
    public function unmask($elemento) {
        return parent::gerarScriptJquery($elemento, 'unmask');
    }

    /**
     * Função para a utilização de máscara de telefone
     *
     * @param $elemento Identificação do elemento HTML
     * @return string script pronto para a renderização
     */
    public function maskTelefone($elemento){

        $script = '$("'.$elemento.'").focusout(function(){
						var phone, element;
						element = $(this);
						element.unmask();
						phone = element.val().replace(/\D/g, "");

						if(phone.length > 10) {
							element.mask("(99) 99999-999#", {placeholder: "(__) _____-____"});
						} else {
							element.mask("(99) 9999-9999#", {placeholder: "(__) _____-____"});
						}
					}).trigger("focusout");';

        return $script;
    }

    /**
     * Função que deverá ser implementada contendo os scripts css, js necessários para o funcionamento
     * de um Helper
     * @return mixed
     */
    public function getScript()
    {
        return $this->Html->script('jquery.mask.min');
    }
}