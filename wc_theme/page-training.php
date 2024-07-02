<?php

/**
 * /*
 * Template Name: Events Training Template
 * Template Post Type: Page
 */

 get_template_part('template-parts/headers/header-page'); ?>
    <section class="content">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb--default">
				    <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=_("Entrenamientos")?></li>
                </ol>
            </nav>
            <!-- BEGIN CONVENCIONES -->
            <h1 class="title"><?=_("Entrenamientos")?></h1>
            <div class="square-wrapper">
                <div class="row">
                    <script src="https://js.boxcast.com/libs/iframeResizer-3.5.2.min.js"></script><script>(function(p,c,q){document.write('<iframe width="100%" id="'+p+c+'" src="https://boxcast.tv/view-embed/'+c+'?'+q+'" frameBorder="0" scrolling="auto" allowfullscreen="true" allow="autoplay; fullscreen"></iframe>');iFrameResize({}, '#'+p+c);})('boxcast-iframe-', 'g6ogdbi5o0zq526ajmw8', 'showTitle=1&showDescription=1&showHighlights=1&showRelated=1&defaultVideo=next&market=smb&showCountdown=1&showDocuments=1&showIndex=1&showDonations=0&showChat=1&layout=playlist-to-right&hostname='+encodeURIComponent(location.hostname));</script>
                </div>
            </div>
        </div>
    </section>
<?php get_template_part('template-parts/footers/footer-page'); ?>