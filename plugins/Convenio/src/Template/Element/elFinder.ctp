<!-- jQuery and jQuery UI (REQUIRED) -->
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<!-- elFinder CSS (REQUIRED) -->
<?= $this->Html->css('elfinder.min.css') ?>
<?= $this->Html->css('theme.css') ?>
<!-- elFinder JS (REQUIRED) -->
<?= $this->Html->script('elfinder.min.js') ?>

<!-- elFinder initialization (REQUIRED) -->
<script type="text/javascript" charset="utf-8">
    // Documentation for client options:
    // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
    $(document).ready(function() {
        $('#elfinder').elfinder({
            url : '<?= $this->request->here ?>',  // connector URL (REQUIRED)
            uiOptions : {
                toolbar : [
                    ['upload']
                ]
            },
            contextmenu : {
                cwd    : ['upload']
            }
        });
    });
</script>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
