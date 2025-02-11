<?php
/**
 * @var \app\models\Page $page
 * @var \yii\web\View $this
 * @var \app\services\siteView\NavigationLinksGetter $navigationLinksGetter
 * @var SAPE_context $sapeContext
 * @var SAPE_client $sapeClient
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

<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope"
         id="post-<?= $page->id ?>"
         class="post type-post status-publish format-standard has-post-thumbnail hentry category-uncategorized">

    <header class="entry-header">
        <h1 class="entry-title"><?= $page->title ?></h1>
        <?php if ($page->publish_date) { ?>
            <div class="entry-meta">
                <span
                    class="posted-on">Posted on <?= (new \DateTime($page->publish_date))->format('F d, Y') ?></span>
            </div>
        <?php } ?>
    </header><!-- .entry-header -->

    <?php if (!empty($page->images)) { ?>
        <div class="post-thumbnail">
            <img src="<?= $page->images[0]->getSourceUrl() ?>"
                 class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                 alt="<?= $page->title ?>">
        </div>
    <?php } ?>
    <!-- .post-thumbnail -->

    <div class="entry-content">
        <?php
            $indents = \app\helpers\TextSplitter::splitByDot($page->content);
            $text = $indents[0] ?? '';
            echo $sapeContext->replace_in_text_segment($text);

            $links = $sapeClient->return_links();
            if ($links) {
                echo "<br>{$links}";
            }
        ?>
        <br>
        <?php if (!\in_array(\Yii::$app->request->hostName, \Yii::$app->params['adsenseSites'], true)) { ?>
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle"
                 style="display:block; text-align:center;"
                 data-ad-layout="in-article"
                 data-ad-format="fluid"
                 data-ad-client="ca-pub-8283920148845070"
                 data-ad-slot="1618811611"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        <?php } ?>
        <br>
        <?php if (\count($page->pageLinks) > 0) { ?>
            <ul>
                <?php foreach ($page->pageLinks as $pageLink) { ?>
                    <li><?= $pageLink->getLink() ?></li>
                <?php } ?>
            </ul>
        <?php } ?>

        <?php
            $text = $indents[1] ?? '';
            echo $sapeContext->replace_in_text_segment($text);
        ?>
    </div>
    <div class="another-content">
        <?php //echo $page->seo_content ?>
    </div>

    <nav class="navigation post-navigation" role="navigation">
        <h2 class="screen-reader-text">Post navigation</h2>
        <div class="nav-links">
            <?php if ($prevPage = $page->getPrevPage()) { ?>
                <div class="nav-previous">
                    <a href="<?= \yii\helpers\Url::to(['page/index', 'url' => $prevPage->url]) ?>"
                       rel="prev"><span>Previous article</span> <?= $prevPage->title ?></a>
                </div>
            <?php } ?>
            <?php if ($nextPage = $page->getNextPage()) { ?>
                <div class="nav-previous">
                    <a href="<?= \yii\helpers\Url::to(['page/index', 'url' => $nextPage->url]) ?>"
                       rel="next"><span>Next article</span> <?= $nextPage->title ?></a>
                </div>
            <?php } ?>
        </div>
    </nav>
</article>
<div class="buzznews-infinite-scrolling-post"></div>
