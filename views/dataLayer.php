<?php

use yii\helpers\Json;
use yii\web\View;

/**
 * @var $this View
 * @var $dataLayerItems array
 */
?>
<script>
    var dataLayer = <?= Json::encode($dataLayerItems) ?>;
</script>
