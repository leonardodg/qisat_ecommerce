<?php

/**
 * Created by PhpStorm.
 * User: deyvison.pereira
 * Date: 13/09/2016
 * Time: 11:13
 */

namespace WebService\View\Helper;

use App\View\Helper\JQueryBaseHelper;

class CidadeHelper extends JQueryBaseHelper
{
    public $helpers = ['Jquery'];

    public function changeCidades($selectEstado, $elementoRetorno){

        $script = "
                       var idEstado = $(this).val();

                       if(idEstado.trim() != ''){
                           var options = '<option value=\"\">".__('Carregando...')."</option>';
                           $('".$elementoRetorno."').html(options).attr(\"disabled\", \"disabled\");

                           $.get('".\Cake\Routing\Router::url(['plugin' => 'WebService', 'controller' => 'wsc-cidade', 'action' => 'listar'])."/'+idEstado,
                                function(data){
                                    var cidades = data.retorno.cidade;
                                    //console.log(cidades);

                                    var options = '<option value=\"\">".__('Selecione')."</option>';
                                    cidades.forEach(function(entry) {
                                        options += '<option value=\"'+entry.id+'\">'+entry.nome+'</option>';
                                    });

                                    $('".$elementoRetorno."').html(options).removeAttr('disabled');

                                }, 'json'
                           );
                       }else{
                           var options = '<option value=\"\">".__('Selecione um Estado')."</option>';
                           $('".$elementoRetorno."').html(options).attr(\"disabled\", \"disabled\");
                       }
                  ";

        $script = $this->Jquery->get($selectEstado)->event('change', $script);

        return $this->Jquery->domReady($script);
    }

    /**
     * Função que deverá ser implementada contendo os scripts css, js necessários para o funcionamento
     * de um Helper
     * @return mixed
     */
    public function getScript()
    {
        return '';
    }
}