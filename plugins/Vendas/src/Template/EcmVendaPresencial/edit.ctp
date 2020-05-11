<div class="ecmVendaPresencial col-md-12">
    <?= $this->Form->create($ecmVendaPresencial) ?>
    <fieldset>
        <legend><?= __('Editar Reserva - Curso Presencial') ?></legend>
        <h3>
            <?= $ecmCursoPresencialTurma->ecm_produto->nome ?> -
            <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->nome ?> -
            <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->mdl_cidade->mdl_estado->uf ?> -
            <?= $ecmCursoPresencialTurma->ecm_curso_presencial_data[0]->ecm_curso_presencial_local->nome ?>
        </h3>
        <?= $this->element('carrinho_andamento',['carrinho' => $ecmCarrinho]);?>
        <?php
            echo $this->Form->input('vagas_total', ['label' => 'Máximo de vagas disponíveis', 'required' => 'true',
                'value' => $ecmCursoPresencialTurma->vagas_total]);
            echo $this->Form->input('vagas_preenchidas', ['label' => 'Vagas Preenchidas', 'disabled' => 'disabled',
                'value' => $ecmCursoPresencialTurma->vagas_preenchidas]);
            echo $this->Form->input('quantidade_reserva', ['label' => 'Numero de Vagas', 'required' => 'true']);
            echo $this->Form->input('nome', ['label' => 'Nome do cliente para reserva']);
            echo $this->Form->input('pedido', ['label' => 'Número do pedido registrado no CRM', 'type' => 'number']);
            echo $this->Form->input('email', ['label' => 'E-mail', 'required' => 'true']);
        ?>
    </fieldset>
    <fieldset>
        <?= $this->Form->button('Vender', ['name' => 'status', 'value' => 'Vendido']) ?>
        <?= $this->Form->button('Reservar', ['name' => 'status', 'value' => 'Reservado']) ?>
        <?= $this->Form->button('Cancelar', ['type' => 'button', 'onclick' => 'window.history.back();']) ?>
    </fieldset>
    <?= $this->Form->end() ?>
</div>
