<?php
/**
 * @var \app\models\Page[] $pages
 */
?>

<!------ Latest News full Section -------->
<div class="buzznews-newsfeed buzznews-related-post">
    <div class="buzznews-newsfeed-outer-wrapper">
        <div class="buzznews-newsfeed-inner-wrapper">
            <div class="buzznews-header-title">
                <h5>RELATED ARTICLES</h5>
            </div>
            <div class="middle-bottom">
                <div class="row">
                    <!--Loop-->
                    <?php foreach ($pages as $page) { ?>
                    <div class="col-lg-3 col-md-3 buzznews-matchheight-article">
                        <div class="middle-bottom-wrapper">
                            <?php if (!empty($page->images)) { ?>
                            <div class="middle-bottom-wrapper-image">
                                <img src="<?= $page->images[0]->getSourceUrl() ?>"
                                     class="attachment-buzznews-postlist size-buzznews-postlist wp-post-image"
                                     alt="<?= $page->title ?>">
                            </div>
                            <?php } ?>
                            <?php /*
                            <div class="slider-category">
                                <div class="colorful-cat"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/travel/"
                                            rel="category tag">Travel</a>&nbsp;
                                </div>
                            </div>*/ ?>
                            <div class="buzznews-article-content">
                                <div class="desert-eating">
                                    <a href="<?= \yii\helpers\Url::to(['page/index', 'url' => $page->url]) ?>"
                                       rel="bookmark"><?= $page->title ?></a>
                                </div>
                                <?php /*<div class="image">
                                    <div class="post-author d-none"><a rel="bookmark"
                                                                       href="http://demo.spiderbuzz.com/buzznews/pro-demo1/author/buzznews/"><img
                                                    alt=''
                                                    src='http://0.gravatar.com/avatar/019a36493aaaa0c0c163af01a86e63fc?s=25&#038;d=mm&#038;r=g'
                                                    class='avatar avatar-25 photo img-circle'
                                                    height='25'
                                                    width='25'/></a>
                                    </div>
                                    <span>2 years ago</span>
                                    <div class="pull-right">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="15"
                                             viewBox="0 0 19 15">
                                            <path fill="none" fill-rule="evenodd" stroke="#D1D1D1"
                                                  stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M1 7.045S4.023 1 9.312 1c5.29 0 8.312 6.045 8.312 6.045S14.6 13.09 9.312 13.09C4.022 13.09 1 7.045 1 7.045zm8.29 2.447c1.39 0 2.519-1.225 2.519-2.735 0-1.51-1.128-2.735-2.52-2.735-1.391 0-2.52 1.225-2.52 2.735 0 1.51 1.129 2.735 2.52 2.735z"/>
                                        </svg>
                                        <span> 138</span>
                                    </div>
                                </div>*/ ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <!--/Loop-->
                </div>
            </div>
        </div>
    </div>
</div>
