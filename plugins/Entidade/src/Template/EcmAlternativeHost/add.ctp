<?= $this->Html->script('/webroot/js/jquery.multi-select.js') ?>
<?= $this->Html->script('/webroot/js/jquery.quicksearch.js') ?>
<?= $this->Html->css('/webroot/css/multi-select.css') ?>
<div class="ecmAlternativeHost col-md-12">
    <?= $this->Form->create($ecmAlternativeHost) ?>
    <fieldset>
        <legend><?= __('Add Ecm Alternative Host') ?></legend>
        <?php
            echo $this->Form->input('host');
            echo $this->Form->input('shortname');
            echo $this->Form->input('fullname');
            echo $this->Form->input('path');
            echo $this->Form->input('email');
            echo $this->Form->input('googleanalytics', ['label' => 'Google analytics']);
            echo $this->Form->input('codigoorigemaltoqi', ['label' => 'Código de origem AltoQi']);
            echo $this->element('Imagem.Imagem');
            echo $this->Form->input('ecm_promocao._ids', ['options' => $ecmPromocao, 'label' => 'Promoções']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $(function() {
        $("#ecm-promocao-ids").multiSelect({
            selectableHeader: "<input type='text' class='search-input' autocomplete='off'>",
            selectionHeader: "<input type='text' class='search-input' autocomplete='off'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';
                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });
                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    });
</script>