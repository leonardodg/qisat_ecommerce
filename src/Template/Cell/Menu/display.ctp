<?= $this->Html->script('/webroot/js/jquery-ui.js') ?>
<?= $this->Html->css('/webroot/css/jquery-ui.css') ?>

<?php
$index = 0;
$plugin_atual = $this->request->plugin;
$action_atual = $this->request->here;

if ($plugin_atual == ""){
    $plugin_atual = "Home";
}

if(key_exists($plugin_atual,$menu)){
    foreach ($menu as $key => $value) {
        if($key != $plugin_atual){
            $index++;
        }else{
            break;
        }
    }
}

$textoTitle = '';
$menuHtml = '';
?>

<div id="menu-accordion-left">
    <div class="accordion-link">

        <h3 class="ui-state-default">
            <span class="ui-accordion-header-icon glyphicon glyphicon-education"></span>
            <?= $this->Html->link(__('Plataforma'), 'https://'.$linkMoodle)?>
        </h3>
    </div>
    <div id="menu-accordion">
        <?php foreach ($menu as $plugin => $links): ?>
            <?php
                $menuHtml .= '<h3>'.__($plugin).'</h3><div><ul>';
                ksort($links);

                foreach ($links as $nome => $link){
                    $menuHtml .= "<li>";
                    if($this->request->base.$link == $action_atual){
                        $menuHtml .= "<b>" . $this->Html->link(__($nome), $link) . "</b>";
                        $textoTitle = __('E-commerce QiSat').': '.__($nome);
                    }else{
                        $menuHtml .= $this->Html->link(__($nome), $link);
                    }
                    $menuHtml .= "</li>";
                }

                $menuHtml .= '</ul></div>';
            ?>
        <?php endforeach; ?>
        <?= $menuHtml?>
    </div>
</div>

<script>
    $(function(){
        $("#menu-accordion").accordion({
            heightStyle: "content",
            active: <?=$index?>,
            collapsible: true,
            activate: function( event, ui ) {
                $("#menu-accordion-left").getNiceScroll().resize();
            }
        });

        <?php if($textoTitle != ''){?>
            $("title").html("<?= $textoTitle?>");
        <?php } ?>
    });
</script>