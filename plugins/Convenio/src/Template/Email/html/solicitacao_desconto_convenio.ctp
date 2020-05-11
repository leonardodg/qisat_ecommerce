Prezados,
<br /><br />

<b> <?= $convenioInteresse->nome?> </b> registrou interesse no convênio vinculado a
<b><?= $convenioInteresse->ecm_convenio->nome_instituicao?><b/> em <b> <?= $convenioInteresse->data_registro->format('d/m/Y H:i')?> </b>.
<br />
Para maiores detalhes <a href="<?= \Cake\Routing\Router::url('convenio/lista-interesse/'.$convenioInteresse->ecm_convenio_id, true);?>"> clique aqui </a>.