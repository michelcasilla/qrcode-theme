<?php

/**
 * /*
 * Template Name: Events Page Template
 * Template Post Type: Page
 */

 get_template_part('template-parts/headers/header-page'); ?>
 <?php $conventions = get_posts(array(
    'numberposts' => 1000,
    'order' => 'ASC',
    'orderby' => 'ID',
    'post_type' => 'post',
    "category_name" => "conventions",
    'post_status' => 'publish'
)); ?>
<section class="content">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb--default">
				    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=_("Convenciones")?></li>
                </ol>
            </nav>
            <!-- BEGIN CONVENCIONES -->
            <h1 class="title"><?=_("Convenciones")?></h1>
            <div class="square-wrapper">
                <div class="row">
                <?php foreach($conventions as $index => $convention): ?>
                    <div class="col-md-6 mb-4">
                        <div class="square">
                            <div class="square-image">
                                <?=get_post_country_image(get_field("country", $convention->ID)); ?>
                                <img class="image--item" src="<?=get_field("event_image",  $convention->ID)?>" alt="#">
                            </div>
                            <div class="square-caption">
                                <h2 class="square-caption__title"><?=get_field('event_title', $convention->ID)?></h2>
                                <div class="square-caption__address">
                                    <span><?=get_field("event_date", $convention->ID)?></span>
                                    <address><?=get_field("eventlocation", $convention->ID)?></address>
                                </div>
                                <div class="square-action">
                                    <span>
                                    <?php if(get_field('country', $convention->ID) == "DO"):?>
                                        RD$
                                    <?php elseif(get_field('country', $convention->ID) == "US"): ?>
                                        US$
                                    <?php endif; ?>
                                    <?=sprintf('%0.2f', get_field("event_price", $convention->ID))?></span>
                                    <a href="<?=get_permalink($convention)?>"><?=_("COMPRAR BOLETA")?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <?php $events = get_posts(array(
                'numberposts' => 1000,
                'order' => 'ASC',
                'orderby' => 'ID',
                "category_name" => "events"
            )); ?>
            <!-- BEGIN EVENTOS -->
            <h1 class="title">Eventos</h1>
            <div class="square-wrapper">
                <div class="row">
                <?php foreach($events as $index => $event): ?>
                    <div class="col-md-6 mb-4">
                        <div class="square">
                            <div class="square-image">
                                <?=get_post_country_image(get_field("country", $event->ID)); ?>
                                <img class="image--item" src="<?=get_field("event_image",  $event->ID)?>" alt="">
                            </div>
                            <div class="square-caption">
                                <h2 class="square-caption__title"><?=get_field('event_title', $event->ID)?></h2>
                                <div class="square-caption__address">
                                    <span><?=get_field("event_date", $event->ID)?></span>
                                    <address><?=get_field("eventlocation", $event->ID)?></address>
                                </div>
                                <div class="square-action">
                                    <span>
                                    <?php if(get_field('country', $event->ID) == "DO"):?>
                                        RD$
                                    <?php elseif(get_field('country', $event->ID) == "US"): ?>
                                        US$
                                    <?php endif; ?>
                                        <?=sprintf('%0.2f', get_field("event_price", $event->ID))?>
                                    </span>
                                    <a href="<?=get_permalink($event)?>"><?=_("COMPRAR BOLETA")?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php get_template_part('template-parts/footers/footer-page'); ?>