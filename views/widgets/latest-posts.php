<?php
/**
 * @var \app\models\Page[] $pages
 */

if (empty($pages)) {
    return;
}
?>

<section id="buzznews_sidebar_gridview_section-1"
         class=" widget widget_buzznews_sidebar_gridview_section">
    <div class="buzznews-newsfeed sidebar-gridpost-widget">
        <div class="buzznews-newsfeed-outer-wrapper">
            <div class="buzznews-newsfeed-inner-wrapper">
                <div class="buzznews-trendingnews-right">
                    <div class="buzznews-header-title">
                        <h5>Latest Posts</h5>
                    </div>
                    <?php /*<div class="buzznews-postlist-arrow">
                            <span class="buzznews-postlist-ajax arrow-left"
                                  catId="all"
                                  postCount="8"
                                  postPaginate="1"
                                  postLastPaginate="2"
                                  postDisplayStyle="SidebarGridLayout"><a
                                        href="javascript:void(0)"><i
                                            class="fa fa-angle-left"
                                            aria-hidden="true"></i></a></span>
                            <span class="pagination-current">1/2</span>
                            <span class="buzznews-postlist-ajax arrow-right" catId="all"
                                  postCount="8" postPaginate="2" postLastPaginate="2"
                                  postDisplayStyle="SidebarGridLayout"><a href="javascript:void(0)"><i
                                            class="fa fa-angle-right"
                                            aria-hidden="true"></i></a></span>
                        </div>*/ ?>

                    <div class="buzznews-trendingnews-right-top">
                        <div class="middle-bottom">
                            <div class="row">
                                <!--Loop-->
                                <?php foreach ($pages as $page) { ?>
                                    <div class="col-lg-12 col-md-12 buzznews-matchheight-article">
                                        <div class="middle-bottom-wrapper">
                                            <div class="middle-bottom-wrapper-image">
                                                <a href="<?= \yii\helpers\Url::to([
                                                    'page/index',
                                                    'url' => $page->url,
                                                ]) ?>">
                                                    <img src="<?= !empty($page->images) ? $page->images[0]->getSourceUrl() : '/images/blank-images.jpg' ?>"
                                                         class="attachment-buzznews-postlist size-buzznews-postlist wp-post-image"
                                                         alt="<?= $page->title ?>">
                                                </a>
                                            </div>
                                            <div class="buzznews-article-content">
                                                <div class="desert-eating">
                                                    <a href="<?= \yii\helpers\Url::to(['page/index', 'url' => $page->url]) ?>"
                                                       rel="bookmark"><?= $page->title ?></a></div>
                                                <div class="image">
                                                    <p class="short-description"><?= \substr($page->content, 0, 100) ?>
                                                        [&hellip;]</p>
                                                    <?php /*
                                                    <div class="post-author d-none">
                                                        <a rel="bookmark"
                                                           href="http://demo.spiderbuzz.com/buzznews/pro-demo1/author/buzznews/"><img
                                                               alt=''
                                                               src='http://0.gravatar.com/avatar/019a36493aaaa0c0c163af01a86e63fc?s=25&#038;d=mm&#038;r=g'
                                                               class='avatar avatar-25 photo img-circle'
                                                               height='25' width='25'/></a>
                                                    </div> */ ?>
                                                    <?php if ($page->publish_date) { ?>
                                                        <span><?= $page->publish_date ?></span>
                                                    <?php } ?>
                                                    <?php /* VIEWS!!!!
                                                    <div class="pull-right">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             width="19" height="15"
                                                             viewBox="0 0 19 15">
                                                            <path fill="none" fill-rule="evenodd"
                                                                  stroke="#D1D1D1"
                                                                  stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M1 7.045S4.023 1 9.312 1c5.29 0 8.312 6.045 8.312 6.045S14.6 13.09 9.312 13.09C4.022 13.09 1 7.045 1 7.045zm8.29 2.447c1.39 0 2.519-1.225 2.519-2.735 0-1.51-1.128-2.735-2.52-2.735-1.391 0-2.52 1.225-2.52 2.735 0 1.51 1.129 2.735 2.52 2.735z"/>
                                                        </svg>
                                                        <span> 138</span>
                                                    </div>
                                                    */ ?>
                                                </div>
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
        </div>
    </div>
</section>

