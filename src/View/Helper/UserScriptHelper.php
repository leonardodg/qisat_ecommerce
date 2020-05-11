<?php
/**
 * UserAjaxHelper classe Helper para a reutilização de scripts vinculados aos usuários
 *
 * @author Deyvison Fernandes Baldoino
 *
 */

namespace App\View\Helper;


class UserScriptHelper extends JQueryBaseHelper
{
    public $helpers = ['Jquery'];

    /**
     * Função específica para a busca de usuários via ajax para inserção em  selects e controle de multiselects
     *
     * @param string $campoBusca parâmetro responsável por efetuar a busca ao pressionar uma tecla
     * @param string $elementoRetorno elemento onde será retornado os options do ajax
     * @param string $elementoMultiselect elemento para inserção dos elementos selecionados no $elementoRetorno
     * @return string script
     */
    public function selectUserAjax($campoBusca, $elementoRetorno, $elementoMultiselect){
        $scriptBusca = '

                        var delay = (function(){
                          var timer = 0;
                          return function(callback, ms){
                            clearTimeout (timer);
                            timer = setTimeout(callback, ms);
                          };
                        })();

                        var options = \'<option value="">'.__('Carregando...').'</option>\';
                        $("'.$elementoRetorno.'").html(options).attr("disabled", "disabled");
                        var val = $(this).val();

                        var notId = new Array();
                        var cont = 0;
                        $("'.$elementoMultiselect.'").find("option").each(function(){
                            notId[cont++] = val;
                        });

                        delay(function(){

                                $.post("'.\Cake\Routing\Router::url(['plugin' => false, 'controller' => 'usuario', 'action' => 'listaUsuarioJson']).'"
                                        ,{nome : val, "not-id": JSON.stringify(notId)},function( data ) {

                                    data = JSON.parse(data);

                                    var options = \'<option value="">'.__('Selecione').'</option>\';
                                    data.forEach(function(entry) {
                                        options += \'<option value="\'+entry.id+\'">\'+entry.id+\' - \'+entry.firstname+\' \'+entry.lastname+\'</option>\';
                                    });
                                    $("'.$elementoRetorno.'").html(options).removeAttr("disabled");
                                });
                        }, 1500 );
                        ';

        $scriptBusca = $this->Jquery->get($campoBusca)->event('keyup', $scriptBusca);

        $scriptSelect = 'var valueOptions = $(this).val();
                         var textOptions = $(this).text();
                         var optionSelecionado = $(this).find("option").filter(":selected");
						 var textOptions = optionSelecionado.text();
						 optionSelecionado.remove();

                         var opt = $("<option/>", {
                            value: valueOptions,
                            text: textOptions
                         });
                         opt.prop("selected", true);

                         $("'.$elementoMultiselect.'").append(opt).multipleSelect("refresh");';

        $scriptSelect = $this->Jquery->get($elementoRetorno)->event('change', $scriptSelect);
        $titleBusca = '$("'.$campoBusca.'").attr("title","'.__('Para efetuar a consulta digite pelo menos 3 caracteres').'");';

        return $this->Jquery->domReady($scriptBusca.$scriptSelect.$titleBusca);
    }

    /**
     * Função específica para a busca de usuários via ajax para inserção em  selects
     *
     * @param string $campoBusca parâmetro responsável por efetuar a busca ao pressionar uma tecla
     * @param string $elementoRetorno elemento onde será retornado os options do ajax
     * @return string script
     */
    public function selectOptionsUserAjax($campoBusca, $elementoRetorno){
        $scriptBusca = 'if($(this).val().length >= 3){
                            var options = \'<option value="">'.__('Carregando...').'</option>\';
                            $("'.$elementoRetorno.'").html(options).attr("disabled", "disabled");
                            executeDefaultAjax = false;

                            $.post("'.\Cake\Routing\Router::url(['plugin' => false, 'controller' => 'usuario', 'action' => 'listaUsuarioJson']).'"
                                    ,{nome : $(this).val()},function( data ) {

                                data = JSON.parse(data);

                                var options = \'<option value="">'.__('Selecione').'</option>\';
                                data.forEach(function(entry) {
                                    options += \'<option value="\'+entry.id+\'">\'+entry.firstname+\' \'+entry.lastname+\' - \'+entry.idnumber+\'</option>\';
                                });
                                $("'.$elementoRetorno.'").html(options).removeAttr("disabled");
                            });
                        }';

        $scriptBusca = $this->Jquery->get($campoBusca)->event('focusout', $scriptBusca);
        $titleBusca = '$("'.$campoBusca.'").attr("title","'.__('Para efetuar a consulta digite pelo menos 3 caracteres').'");';

        return $this->Jquery->domReady($scriptBusca.$titleBusca);
    }

    public function getScript()
    {
        return '';
    }
}