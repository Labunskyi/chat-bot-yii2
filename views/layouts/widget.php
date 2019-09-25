<?php

use app\assets\WidgetAsset;
WidgetAsset::register( $this ); ?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Widget joinchat</title>
    <?php $this->head() ?>
</head>
<body>
    <?= $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

