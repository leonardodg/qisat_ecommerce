<div style="padding:20px 0px 8px 20px; line-height:125%; color:#015da2; font-size:14px;text-align:left; font-weight:bolder;">
    QiSat | Preenchimento de Termo de Adesão
</div>
<br /><br />

Prezados,<br/><br/>
<b> <?= $convenio->nome_responsavel?> </b> preencheu o Termo de Adesão do
Projeto QiSat Rede Educacional em <b> <?= $convenio->data_registro->format('d/m/Y H:i')?> </b>. <br />
Para maiores detalhes <a href="<?= \Cake\Routing\Router::url('convenio/ecm-convenio/edit/'.$convenio->id, true);?>"> clique aqui </a>.