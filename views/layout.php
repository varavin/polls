<?php
/**
 * @var string $content
 * @var string $pageTitle
 * @var string $siteRootURL
 * @var \Polls\App $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <link href="<?= $siteRootURL ?>/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,400i,700" media="all">
    <title>XIAG test task</title>
    <meta name="robots" content="noindex,nofollow" />
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0" />
    <link rel="shortcut icon" href="<?= $siteRootURL ?>/images/favicon.ico" type="image/x-icon">
</head>
<body>
<div class="page">
    <div class="page__header">
        <div class="page__logo">
            <a href="https://www.xiag.ch" target="_blank">
                <img src="<?= $siteRootURL ?>/images/page-logo.png" alt="XIAG AG">
            </a>
        </div>
        <div class="page__task-name">
            <?= $pageTitle ?>
        </div>
    </div>
    <div class="page__image">
        <div class="page__task-title">
            <?= $pageTitle ?>
        </div>
    </div>
    <div class="page__content page__content--padding">
        <?= $content ?>
    </div>
</div>
<script src="<?= $siteRootURL ?>/js/functions.js"></script>
<script>
    window.jsConfig = {
        siteRootURL: '<?= $siteRootURL ?>',
        components: <?= json_encode($this->getJsComponents()); ?>
    }
</script>
<?php foreach ($this->getJsComponents() as $component): ?>
    <script src="<?= $siteRootURL ?>/js/components/<?= $component ?>.js"></script>
<?php endforeach; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = null;
        <?php foreach ($this->getJsComponents() as $component): ?>
            var jsComponent<?= $component ?> = null;
            if (elems = document.getElementsByClassName('jsComponent<?= $component ?>')) {
                [].forEach.call(elems, function(el){
                    var jsComponent<?= $component ?> = new <?= $component ?>(el);
                });
            }
        <?php endforeach; ?>
    });
</script>
</body>
</html>