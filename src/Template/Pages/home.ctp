<div class="ecmCarrinho col-md-12">
    <h2>Bem vindo ao E-Commerce QiSat</h2>
    <h4><?= __('Páginas Favoritadas')?></h4>
    Nesse local aparece uma lista com as páginas que você favoritou.<br/>
    Para salvar suas páginas utilize o ícone <i class="glyphicon glyphicon-star" style="color:#bcbcbc"></i> no topo da
    página que você está acessando.<br />
    <b>Atenção:</b> Os favoritos são armazenados em seu navegador, se os dados de histórico forem excluídos
    os favoritos serão perdidos.
    <div id="menuLista" style="margin-bottom: 20px"></div>
</div>
<script>
    var h3 = $('#menuLista').find('h3');
    h3.css('margin-top', '12px');
    h3.css('margin-bottom', '0px');
</script>
