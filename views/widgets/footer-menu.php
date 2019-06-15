<?php
/**
 * @var array $links
 */
?>

<div class="buzznews-footer-widget">
    <div class="row">
        <?php foreach ($links as $linksColumns) { ?>
            <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                <section id="categories-4" class="widget widget_categories">
                    <?php /*<h2 class="widget-title">World</h2>*/ ?>
                    <ul>
                        <?php foreach ($linksColumns as $link) { ?>
                        <li class="cat-item cat-item-8"><a href="<?= $link['url'] ?>"><?= $link['title'] ?></a></li>
                        <?php } ?>
                    </ul>
                </section>
            </div>
        <?php } ?>
    </div>
</div><!-- .site-info -->
