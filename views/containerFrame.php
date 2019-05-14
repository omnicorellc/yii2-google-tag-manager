<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var $this View
 * @var $frameSrc string
 * @var $tagManagerId string
 * @var $tagManagerPrefix string
 */
if (empty($tagManagerId)) {
    return;
}
$containerId = $tagManagerPrefix . $tagManagerId;
?>
<noscript>
    <iframe src="<?= $frameSrc ?>?id=<?= Html::encode($containerId) ?>"
            height="0"
            width="0"
            style="display:none;visibility:hidden">
    </iframe>
</noscript>
