<?php
$id = \Yii::$app->params['googleAnalyticsCodes'][\Yii::$app->request->hostName] ?? null;
if ($id === null) {
    return;
}
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $id ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?= $id ?>');
</script>
