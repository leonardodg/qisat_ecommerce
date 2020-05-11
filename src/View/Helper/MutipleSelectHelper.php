<?php
namespace App\View\Helper;

/**
* MutipleSelectHelper classe Helper para a utilização do multiselect Jquery
*
* @link http://wenzhixin.net.cn/p/multiple-select/docs/ Link para acesso a documentação do plugin
* @author Deyvison Fernandes
*
*/
class MutipleSelectHelper extends JQueryBaseHelper
{
    public $helpers = ['Html'];

    /**
     * @param $elemento Identificação do elemento HTML
     * @param array $atributos Array com os atributos da função
     * @return string script pronto para a renderização
     */
    public function multipleSelect($elemento, array $atributos = array())
    {
        return parent::gerarScritp($elemento, 'multipleSelect', $atributos);
    }

    /**
     * Função que retorna os scripts css e js para a utilização do multiselect JQuery
     * @return string
     */
    public function getScript()
    {
        $scripts = $this->Html->script('multiple-select.js');
        $scripts .= $this->Html->css('multiple-select.css');

        return $scripts;
    }
}