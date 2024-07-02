<?php

/**
 * /*
 * Template Name: Programs Page Template
 * Template Post Type: Page
 */

 get_template_part('template-parts/headers/header-page'); ?>
<?php $postList = get_posts(array(
    'numberposts' => 1000,
    'order' => 'DESC',
    'orderby' => 'ID',
    'post_type' => 'post',
    "category_name" => "leadership-program",
    'post_status' => 'publish'
)); ?>
<section class="content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb--default">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=_("Programa de Liderazgo")?></li>
            </ol>
        </nav>
        <h1 class="title"><?=_("Programa de Liderazgo")?></h1>
        <div class="block-wrapper">
            <div class="row">
            <?php foreach($postList as $index => $post): ?>
                <div class="col-md-<?=$index == 0 ? "6" : "3"?>">
                    <a href="<?=get_permalink($post)?>" class="block block-background" style="background-image: url(<?=get_field("event_image",  $post->ID)?>)">
                        <h3 class="square-caption__subtitle"><?=get_field('event_title', $post->ID)?></h3>
                        <span class="block__icon"></span>
                        <span class="block__price">
                        <?php if(get_field('country', $post->ID) == "DO"):?>
                            RD$
                        <?php elseif(get_field('country', $post->ID) == "US"): ?>
                            US$
                        <?php endif; ?>
                            <?=sprintf('%0.2f', get_field("event_price", $post->ID))?>
                        </span>
                    </a>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php get_template_part('template-parts/footers/footer-page'); ?>