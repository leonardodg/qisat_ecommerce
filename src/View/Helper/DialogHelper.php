<?php

namespace App\View\Helper;

use App\View\Helper\JQueryBaseHelper;
/**
 * DialogHelper classe Helper para a utilização de caixas de diálogo do bootbox
 *
 * @link http://bootboxjs.com Link para acesso a documentação do plugin
 * @author Deyvison Fernandes
 *
 */
class DialogHelper extends JQueryBaseHelper
{
    public $helpers = ['Html'];

    /**
     * Função responsável por exibir uma caixa de diálogo
     *
     * @param string $message
     * @param string $script script que será executado quando a caixa de diálogo obtiver uma resposta
     * @return string script pronto para a renderização
     */
    public function showAlert($message, $script=''){
        $scriptDialog = 'bootbox.alert("'.$message.'", function() {
                          '.$script.'
                        });';
        return $scriptDialog;
    }

    /**
     * Função responsável por exibir uma caixa de diálogo com opções de escolha
     *
     * @param string $message
     * @param string $script script que será executado quando a caixa de diálogo obtiver uma resposta
     * @return string script pronto para a renderização
     */
    public function showConfirm($message, $script=''){
        $scriptDialog = 'bootbox.confirm("'.$message.'", function(result) {
                          '.$script.'
                        });';
        return $scriptDialog;
    }

    /**
     * Função que retorna os scripts para a utilização das caixas de diálogo do script bootbox
     * @return string
     */
    public function getScript(){
        return $this->Html->script('bootbox.min.js');
    }
}