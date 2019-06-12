<?php
/**
 * @var \app\models\Page $page
 * @var \yii\web\View $this
 * @var \app\services\siteView\NavigationLinksGetter $navigationLinksGetter
 */

$this->title = $page->title;
$this->params['description'] = $page->description;
$this->params['keywords'] = $page->keywords;

$links = [];
foreach ($navigationLinksGetter->get(4) as $item) {
    $links[] = [
        'label' => \substr($item['title'], 0, 10),
        'url' => $item['url'],
    ];
}
$this->params['navLinks'] = $links;
?>

<?= $page->content ?>