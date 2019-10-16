<?php
switch (\Yii::$app->request->hostName) {
    case 'seafishesabout.website':
        $id = 'UA-20175183-8';
        break;
    case 'interesting-movies.website':
        $id = 'UA-20175183-9';
        break;
    case 'nuclearenergy.website':
        $id = 'UA-20175183-10';
        break;
    case 'cartoonstoknow.website':
        $id = 'UA-20175183-11';
        break;
    case 'crazy-scientists.website':
        $id = 'UA-20175183-12';
        break;
    case 'famous-musicians.website':
        $id = 'UA-20175183-13';
        break;
    case 'history-battles.website':
        $id = 'UA-20175183-14';
        break;
    case 'nature-facts.website':
        $id = 'UA-20175183-15';
        break;
    case 'world-globalization.website':
        $id = 'UA-20175183-16';
        break;
    case 'wowtoknow.com':
        $id = 'UA-20175183-17';
        break;
    case 'buildings.wowtoknow.com':
        $id = 'UA-20175183-18';
        break;
    default:
        $id = null;
}

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
