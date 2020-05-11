<?php
/**
 * PaginatorHelper classe Helper para a inserir funções referentes ao Helper Paginator
 *
 * @author Deyvison Fernandes Baldoino
 *
 */

namespace App\View\Helper;


class PaginatorHelper extends \Cake\View\Helper\PaginatorHelper
{

    public $helpers = ['Url', 'Number', 'Html', 'Jquery'];

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';

    /**
     * Função reponsável por manipular as requisições de paginação via ajax
     *
     * @param string $elementPaginator elemento onde o evento será identificado
     * @param string $elementReplace elemento onde será inserido o retorno da requisição
     * @param string $formSerialize formulário para serialização e envio pela requisição
     * @param string $method metodo de envio post ou get
     * @return string script pronto para a renderização
     */
    public function ajaxPagination($elementPaginator, $elementReplace, $formSerialize = null, $method = PaginatorHelper::METHOD_POST){

        $script = " event.preventDefault();
                    var url = $(this).attr('href');";

        if(!is_null($formSerialize))
            $script .= "var formData = $('".$formSerialize."').serializeArray();";

        $script .= "$.".$method."(url, formData, function(data) {
                        $('".$elementReplace."').html(data);
                    });";

        $script = $this->Jquery->get($elementPaginator)
                ->event('click', $script);

        return $script;
    }
}