<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var $this View
 * @var $scriptSrc string
 * @var $tagManagerId string
 * @var $tagManagerPrefix string
 */
if (empty($tagManagerId)) {
    return;
}
$containerId = $tagManagerPrefix . $tagManagerId;
?>
<script>
    (function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start':
                new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            '<?= $scriptSrc ?>?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', '<?= Html::encode($tagManagerId) ?>');
</script>