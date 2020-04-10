<?php

use yii\helpers\Json;
use yii\web\View;

/**
 * @var $this View
 * @var $googleAnalyticId string
 */
?>

<script defer src="https://www.googletagmanager.com/gtag/js?id=<?= $googleAnalyticId ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?= $googleAnalyticId ?>');
</script>
