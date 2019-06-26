<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
/** @var \app\models\Page $page */
$page = \Yii::$app->params['page'];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $this->params['description'] ?? '' ?>">
    <meta name="keywords" content="<?= $this->params['keywords'] ?? '' ?>">

    <script type="text/javascript">
        window._wpemojiSettings = {
            "baseUrl": "https:\/\/s.w.org\/images\/core\/emoji\/12.0.0-1\/72x72\/",
            "ext": ".png",
            "svgUrl": "https:\/\/s.w.org\/images\/core\/emoji\/12.0.0-1\/svg\/",
            "svgExt": ".svg",
            "source": {"concatemoji": "http:\/\/demo.spiderbuzz.com\/buzznews\/pro-demo1\/wp-includes\/js\/wp-emoji-release.min.js?ver=5.2.1"}
        };
        !function (a, b, c) {
            function d(a, b) {
                var c = String.fromCharCode;
                l.clearRect(0, 0, k.width, k.height), l.fillText(c.apply(this, a), 0, 0);
                var d = k.toDataURL();
                l.clearRect(0, 0, k.width, k.height), l.fillText(c.apply(this, b), 0, 0);
                var e = k.toDataURL();
                return d === e
            }

            function e(a) {
                var b;
                if (!l || !l.fillText) return !1;
                switch (l.textBaseline = "top", l.font = "600 32px Arial", a) {
                    case"flag":
                        return !(b = d([55356, 56826, 55356, 56819], [55356, 56826, 8203, 55356, 56819])) && (b = d([55356, 57332, 56128, 56423, 56128, 56418, 56128, 56421, 56128, 56430, 56128, 56423, 56128, 56447], [55356, 57332, 8203, 56128, 56423, 8203, 56128, 56418, 8203, 56128, 56421, 8203, 56128, 56430, 8203, 56128, 56423, 8203, 56128, 56447]), !b);
                    case"emoji":
                        return b = d([55357, 56424, 55356, 57342, 8205, 55358, 56605, 8205, 55357, 56424, 55356, 57340], [55357, 56424, 55356, 57342, 8203, 55358, 56605, 8203, 55357, 56424, 55356, 57340]), !b
                }
                return !1
            }

            function f(a) {
                var c = b.createElement("script");
                c.src = a, c.defer = c.type = "text/javascript", b.getElementsByTagName("head")[0].appendChild(c)
            }

            var g, h, i, j, k = b.createElement("canvas"), l = k.getContext && k.getContext("2d");
            for (j = Array("flag", "emoji"), c.supports = {
                everything: !0,
                everythingExceptFlag: !0
            }, i = 0; i < j.length; i++) c.supports[j[i]] = e(j[i]), c.supports.everything = c.supports.everything && c.supports[j[i]], "flag" !== j[i] && (c.supports.everythingExceptFlag = c.supports.everythingExceptFlag && c.supports[j[i]]);
            c.supports.everythingExceptFlag = c.supports.everythingExceptFlag && !c.supports.flag, c.DOMReady = !1, c.readyCallback = function () {
                c.DOMReady = !0
            }, c.supports.everything || (h = function () {
                c.readyCallback()
            }, b.addEventListener ? (b.addEventListener("DOMContentLoaded", h, !1), a.addEventListener("load", h, !1)) : (a.attachEvent("onload", h), b.attachEvent("onreadystatechange", function () {
                "complete" === b.readyState && c.readyCallback()
            })), g = c.source || {}, g.concatemoji ? f(g.concatemoji) : g.wpemoji && g.twemoji && (f(g.twemoji), f(g.wpemoji)))
        }(window, document, window._wpemojiSettings);
    </script>
    <style type="text/css">
        img.wp-smiley,
        img.emoji {
            display: inline !important;
            border: none !important;
            box-shadow: none !important;
            height: 1em !important;
            width: 1em !important;
            margin: 0 .07em !important;
            vertical-align: -0.1em !important;
            background: none !important;
            padding: 0 !important;
        }
    </style>
    <link rel='stylesheet' id='buzznews-google-fonts-Montserrat-css'
          href='//fonts.googleapis.com/css?family=Montserrat%3A200%2C300%2C400%2C500%2C600%2C700%2C800&#038;ver=5.2.1'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='buzznews-google-fonts-Merriweather-css'
          href='//fonts.googleapis.com/css?family=Merriweather%3A200%2C300%2C400%2C500%2C600%2C700%2C800&#038;ver=5.2.1'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='buzznews-google-fonts-Poppins-css'
          href='//fonts.googleapis.com/css?family=Poppins%3A200%2C300%2C400%2C500%2C600%2C700%2C800&#038;ver=5.2.1'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='buzznews-google-fonts-Josefin Sans-css'
          href='//fonts.googleapis.com/css?family=Josefin+Sans%3A200%2C300%2C400%2C500%2C600%2C700%2C800&#038;ver=5.2.1'
          type='text/css' media='all'/>
    <?php /*<link rel='stylesheet' id='slick-css'
          href='http://demo.spiderbuzz.com/buzznews/pro-demo1/wp-content/themes/buzznews-pro//assets/library/slick/slick.css?ver=1.0.0'
          type='text/css' media=''/>
    <link rel='stylesheet' id='slick-theme-css'
          href='http://demo.spiderbuzz.com/buzznews/pro-demo1/wp-content/themes/buzznews-pro//assets/library/slick/slick-theme.css?ver=1.0.0'
          type='text/css' media=''/> */ ?>
    <link rel='stylesheet' id='bootstrap-css'
          href='/buzznews/css/bootstrap.css?ver=1.0.0'
          type='text/css' media=''/>
    <link rel='stylesheet' id='font-awesome-css'
          href='/buzznews/font-awesome.css?ver=1.0.0'
          type='text/css' media=''/>
    <link rel='stylesheet' id='buzznews-color-css'
          href='/buzznews/css/color.css?ver=1.0.0'
          type='text/css' media=''/>
    <link rel='stylesheet' id='buzznews-style-css'
          href='/buzznews/css/style.css?ver=5.2.1'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='buzznews-custom-css'
          href='/buzznews/css/buzznews-custom.css?ver=5.2.1'
          type='text/css' media='all'/>
    <style id='buzznews-custom-inline-css' type='text/css'>

        h1, h2, h3, h4, h5, h6, h4 a, h3 a, h2 a, h1 a, h5 a, h6 a, a,
        .right-bottom-single-section .image-details a,
        footer.sb-bottom-footer h2.widget-title span,
        .banner_content .banner_item-v1 .item h1,
        .widget h2.widget-title,
        .site-main h2.entry-title,
        span.label-info {
            font-family: Merriweather;
        }

        body, p, span,
        .slider-category .colorful-cat a,
        .buzznews-footer-widget .widget li a,
        section.widget ul li a,
        article.buzznews-article .snippets p,
        span.label-info ul.post-categories li,
        .buzznews-newsfeed .middle-details p {
            font-family: Lato;
            font-size: 14px;
        }

        .banner_content .banner_item-v1 .item h1,
        h1 {
            font-size: 32px;
            font-weight: 600;
        }

        h2,
        .widget h2.widget-title,
        .site-main h2.entry-title {
            font-size: 20px;
            font-weight: 500;
        }

        h3 {
            font-size: 20px;
            font-weight: 600;
        }

        h4 {
            font-size: 20px;
            font-weight: 600;
        }

        h5 {
            font-size: 18px;
            font-weight: 400;
        }

        h6 {
            font-size: 16px;
            font-weight: 400;
        }

    </style>
    <link rel='stylesheet' id='wp-block-library-css' href='/buzznews/css/style.min.css?ver=5.2.1'
          type='text/css' media='all'/>
    <link rel='stylesheet' id='google-fontsMerriweather-css'
          href='//fonts.googleapis.com/css?family=Merriweather:300,400,700' type='text/css' media='all'/>
    <link rel='stylesheet' id='google-fontsLato-css' href='//fonts.googleapis.com/css?family=Lato:300,400,700'
          type='text/css' media='all'/>
    <script type='text/javascript'
            src='/buzznews/js/jquery.js?ver=1.12.4-wp'></script>
    <script type='text/javascript'
            src='/buzznews/js/jquery-migrate.min.js?ver=1.4.1'></script>
    <link rel="canonical" href="<?= \Yii::$app->request->hostName ?>">
    <style type="text/css">
        .site-title a,
        .site-description {
            color: #333333;
        }
    </style>

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) . ' - ' . \ucwords($page->site->search_word) ?></title>
    <?php $this->head() ?>
</head>
<body class="post-template-default single single-post postid-146 single-format-standard buzznews-right-sidebar buzznews-header-one buzznews-theme-boxlayot">
<?php $this->beginBody() ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content">Skip to content</a>

    <div id="content" class="site-content">
        <header class="sb-header ">
            <div class="sb-header-logo">
                <div class="container">
                    <strong class="sb-logo">
                        <div class="site-branding">
                            <h1 class="site-title">
                                <a href="/" rel="home"><?= $page->site->search_word ?></a>
                            </h1>
                            <p class="site-description">Just another <?= $page->site->search_word ?> site</p>
                        </div><!-- .site-branding -->
                    </strong>
                </div>
            </div>
            <!--HERE GOOGLE ADS-->
        </header>
        <?php /*
        <div class="sb-mobile-menu">
            <div class="screen">
                <header>
                    <a class="target-burger">
                        <ul class="buns">
                            <li class="bun"></li>
                            <li class="bun"></li>
                        </ul>
                        <!--buns-->
                    </a>
                    <!--target-burger-->
                </header>
                <nav class="main-nav" role="navigation">
                    <ul id="primary-menu" class="navbar-nav mr-auto">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-has-children menu-item-461">
                            <a href="http://demo.spiderbuzz.com/buzznews/pro-demo1">Homepage</a>
                            <ul class="sub-menu buzznews-sidenav-dropdown">
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-465">
                                    <a href="http://demo.spiderbuzz.com/buzznews/pro-demo1">Homepage 1</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-462"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo2">Homepage 2</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-463"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo3">Homepage 3</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-464"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo4">Homeapge 4</a></li>
                            </ul>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-450"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/featured/">Featured</a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-451"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/">Lifestyle</a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-452"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/travel/">Travel</a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-453"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/business/">Business</a>
                        </li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-454"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/health-fitness/">Health
                                &#038; Fitness</a></li>
                        <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-455"><a
                                    href="http://demo.spiderbuzz.com/buzznews/pro-demo1/category/lifestyle/recipes/">Recipes</a>
                        </li>
                        <li class="menu-item menu-item-type-post_type menu-item-object-page current_page_parent menu-item-has-children menu-item-467">
                            <a href="http://demo.spiderbuzz.com/buzznews/pro-demo1/blog/">Blog</a>
                            <ul class="sub-menu buzznews-sidenav-dropdown">
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-468"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo1/2017/12/10/health-star-ratings-kellogg-reveals-the-cereal/">Right
                                        Sidebar</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-470"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo1/2017/12/10/how-to-drive-growth-through-customer-support/">Left
                                        Sidebar</a></li>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-472"><a
                                            href="http://demo.spiderbuzz.com/buzznews/pro-demo1/2017/12/10/show-hn-appsites-beautiful-websites-for-mobile/">No
                                        Sidebar</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="header-search-form small-screen">
                    <form role="search" method="get" class="search-form"
                          action="http://demo.spiderbuzz.com/buzznews/pro-demo1/">
                        <label>
                            <span class="screen-reader-text">Search for:</span>
                            <input type="search" class="search-field" placeholder="Search &hellip;" value="" name="s"/>
                        </label>
                        <input type="submit" class="search-submit" value="Search"/>
                    </form>
                </div>
                <!--main-nav-->

            </div>
        </div>
        */ ?>

        <!-- ht-banner -->
        <div class="sb-wrapper">
            <div class="sb-main-container-wrapper">
                <div class="container">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main">
                            <div class="buzznews-trendingnews">
                                <div class="buzznews-trendingnews-outer-wrapper">
                                    <div class="buzznews-trendingnews-inner-wrapper">
                                        <div class="buzznews-trendingnews-left">
                                            <article itemtype="https://schema.org/CreativeWork" itemscope="itemscope"
                                                     id="post-<?= $page->id ?>"
                                                     class="post type-post status-publish format-standard has-post-thumbnail hentry category-uncategorized">

                                                <header class="entry-header">
                                                    <h1 class="entry-title"><?= $page->title ?></h1>
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
                                                    <?= $content ?>
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
                                            <!--HERE GOOGLE ADS-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </main><!-- #main -->

                    </div><!-- #primary -->

                    <aside id="secondary" class="widget-area buzznews-sidebar-sticky right-sidebar-section">
                        <?= \app\widgets\LatestPostsWidget::widget() ?>
                    </aside><!-- #secondary -->
                </div>
            </div>
            <div class="content-area container">
                <?= \app\widgets\RelatedPostsWidget::widget() ?>
            </div>
            <section class="widget widget_media_image">
                <!--HERE GOOGLE ADS-->
            </section>
        </div>

        <footer id="colophon" class="site-footer sb-bottom-footer">
            <div class="container">
                <?= \app\widgets\FooterMenuWidget::widget() ?>
                <div class="sb-footer-copyright">
                    <div class="site-info">
                        <a target="_blank" class="right" href="/">
                            <?= $page->site->search_word ?> <?= date('Y') ?>
                        </a>
                    </div>
                </div>
            </div>
        </footer><!-- #colophon -->

    </div><!-- #content -->
</div><!-- #page -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
