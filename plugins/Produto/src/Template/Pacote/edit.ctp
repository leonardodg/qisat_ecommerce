<?= $this->element('Produto.addProduto') ?>
<?= $this->element('Produto.addProdutoEnrolPeriod') ?>
<?= $this->element('Produto.editTipoProduto') ?>
<?= $this->element('Imagem.Imagem', ['imagens' => $ecmProduto['ecm_imagem']]) ?>
<?= $this->element('Produto.addProdutoCurso') ?>
<?= $this->element('Produto.addProdutoSubmit') ?>
