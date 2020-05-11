<?php

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * JQueryBaseHelper classe abstrata para o desenvolvimento de helpers com utilização do JQuery
 *
 * @author Deyvison Fernandes
 *
 */
abstract class JQueryBaseHelper extends Helper
{
    /**
     * Função que deverá ser implementada contendo os scripts css, js necessários para o funcionamento
     * de um Helper
     * @return mixed
     */
    public abstract function getScript();

    /**
     * Função responsável por gerar scripts JQuery
     *
     * @deprecated Utilizar função gerarScriptJquery, essa função utiliza a função converteArrayParaParametroJs
     *             para converter seus parâmetros
     *
     * @param $elemento Identificação do elemento HTML
     * @param $function Nome da função JQuery
     * @param array  $atributos Array com os atributos da função
     * @return string Retorna o script gerado
     */
    protected function gerarScritp($elemento, $function, array $atributos = null){
        $scripts = '';

        if (is_array ( $elemento )) {
            foreach ( $elemento as $valor ) {
                $scripts .= '$("' . $valor . '").'.$function.'(';

                if (! is_null ( $atributos )) {
                    if (array_key_exists ( $valor, $atributos )) {
                        $scripts .= '{';
                        $scripts .= $this->lerAtributos ( $atributos [$valor] );
                        $scripts .= '}';
                    }elseif(array_key_exists ( 'all', $atributos )){
                        $scripts .= '{';
                        $scripts .= $this->lerAtributos ( $atributos ['all'] );
                        $scripts .= '}';
                    }
                }
                $scripts .= ');';
            }
        } else {
            $scripts = '$("' . $elemento . '").'.$function.'(';
            if (! is_null ( $atributos )) {
                $scripts .= '{';
                $scripts .= $this->lerAtributos ( $atributos );
                $scripts .= '}';
            }
            $scripts .= ');';
        }

        return $scripts;
    }

    /**
     * Função responsável por converter os atributos do array para o formato de atributos do JQuery
     *
     * @deprecated Utilizar função converteArrayParaParametroJs, sua leitura de atributos tem uma melhor eficiência para
     *             definir parâmetros de função e conversão de array em json
     *
     * @param array $atributos
     * @return string Retorna os atributos convertidos
     */
    protected function lerAtributos(array $atributos) {
        $scriptAtributos = '';
        foreach ( $atributos as $chave => $atributo ) {
            $scriptAtributos .= $chave . ':' . $atributo . ',';
        }

        $scriptAtributos = substr ( $scriptAtributos, 0, strlen ( $scriptAtributos ) - 1 );

        return $scriptAtributos;
    }

    /**
     * Função responsável por gerar scripts JQuery
     *
     * @param array|string $elemento Identificação do elemento HTML
     * @param $funcao Nome da função JQuery
     * @param array  $parametros Array com os atributos da função
     * @return string Retorna o script gerado
     */
    protected function gerarScriptJquery($elemento, $funcao, $parametros = []){
        $script = '';
        if(is_array($elemento)){
            foreach($elemento as $el){
                $param = '';
                if(array_key_exists($el, $parametros)){
                    $param = $this->converteArrayParaParametroJs($parametros[$el]);
                }elseif(array_key_exists('all', $parametros)){
                    $param = $this->converteArrayParaParametroJs($parametros['all']);
                }elseif(count($parametros) > 0){
                    current($parametros);
                    if(is_int(key($parametros))) {
                        $param = $this->converteArrayParaParametroJs($parametros);
                    }
                }

                $script .= '$("' . $el . '").' . $funcao . '(' . $param . ');';
            }
        }else {
            $param = count($parametros) > 0 ? $this->converteArrayParaParametroJs($parametros) : '';
            $script = '$("' . $elemento . '").' . $funcao . '(' . $param . ');';
        }

        return $script;
    }

    /**
     * Função responsável por converter os atributos do array para o formato de atributos do JQuery
     *
     * @param array $parametros
     * @return string Retorna os parametros convertidos
     */
    protected function converteArrayParaParametroJs($parametros){
        $param = '';
        $fecharParametros = false;
        foreach ($parametros as $key => $parametro) {
            if(!is_int($key)){
                if(!$fecharParametros){
                    $param .= '{';
                }

                $param .= $key.':';
                $fecharParametros = true;
            }elseif($fecharParametros){
                $param .= '}';
                $fecharParametros = false;
            }

            if(is_string($parametro) && substr($parametro, 0, 8) == 'function'){
                $param .= $parametro;
            }elseif(is_array($parametro)){
                $param .= json_encode($parametro);
            }elseif(is_string($parametro)){
                $param .= '"'.$parametro.'"';
            }else{
                $param .= (string) $parametro;
            }

            $param .= ',';
        }

        $param = substr($param, 0, strlen($param) - 1);

        if($fecharParametros){
            $param .= '}';
        }

        return $param;
    }

}