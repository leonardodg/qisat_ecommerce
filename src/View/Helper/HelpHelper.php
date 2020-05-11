<?php
/**
 * HelpHelper classe Helper para a utilização de caixas de diálogo com paginação
 *
 * @author Deyvison Fernandes
 */

namespace App\View\Helper;


use Cake\View\Helper;

class HelpHelper extends Helper
{
    public $helpers = ['Bootpag', 'Jquery'];

    /**
     * Função responsável por exibir uma caixa de diálogo com mensagens paginadas
     *
     * @param string $elClick elemento HTML para efetuar a função de click
     * @param array|string $mensagem array de mensagens por página
     * @param string $titulo título para a caixa de diálogo
     * @return string script pronto para a renderização
     */
    public function helpClick($elClick, $mensagem, $titulo = null){
        $elComponent = $elClick;
        $msg = is_array($mensagem) ? $mensagem[0]:$mensagem;
        $titulo = is_null($titulo) ? __('Ajuda') : $titulo;
        $titulo = '<span class=\"glyphicon glyphicon-question-sign\"></span> '.$titulo;

        if($elComponent[0] == '#' || $elComponent[0] == '.')
            $elComponent = substr($elComponent, 1);

        $textAlert = '<div id=\"'.$elComponent.'-content\">'.addslashes($msg).'</div><div id=\"'.$elComponent.'-page-selection\"></div>';
        $scriptAlert = 'bootbox.alert({title: "'.$titulo.'", message: "'.$textAlert.'"});';

        if(is_array($mensagem) && count($mensagem) > 1) {
            $script = '';
            foreach ($mensagem as $key => $value) {
                $script .= 'case ' . ($key + 1) . ':
                            mensagem = "' . addslashes($value) . '";
                            break;';
            }

            $script = 'var mensagem = "";
                   switch(page){' . $script . '}
                   $("#' . $elComponent . '-content").html(mensagem);';

            $scriptAlert .= $this->Bootpag->pagination('#' . $elComponent . '-page-selection', $script, ['total' => count($mensagem)]);
        }
        $scriptAlert = $this->Jquery->get($elClick)->event('click', $scriptAlert);

        return $scriptAlert;
    }

    /**
     * Função responsável por retornar um HTML com o ícone de help
     *
     * @param string $idElemento id do elemento HTML
     * @return string HTML para a renderização
     */
    public function iconeHelp($idElemento){
        return '<span class="glyphicon glyphicon-question-sign" id="'.$idElemento.'" title="'.__('O que é isso?').'"></span>';
    }

}