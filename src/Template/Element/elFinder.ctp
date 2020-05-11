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
            url : '<?= $this->request->here ?>'  // connector URL (REQUIRED)
            // , lang: 'ru'                    // language (OPTIONAL)
        });
    });
</script>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>
