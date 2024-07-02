<?php

/**
 * /*
 * Template Name: Home Page Template
 * Template Post Type: Page
 */

 get_template_part('template-parts/headers/header-page'); ?>

<section class="content content--center">
	<div class="container">
		<div class="featured">
			<div class="featured__info">
				<h2 class="featured__subtitle"><?=get_field("subtitle")?></h2>
				<h1 class="featured__title"><?=get_field("title")?></h1>
				<p class="featured__caption"><?=get_field("description")?></p>
				<?php if(get_field("past_embed_content")): ?>
					<br>
					<a id="play-video" class="past-events form-btn" href="#" data-bs-toggle="modal" data-bs-target="#exampleModalPast">
						<?=__("CONFERENCIAS PASADAS", "sp")?>
					</a>
					<br>
				<?php endif; ?>
			</div>
			<div class="featured__play">
				<a id="play-video" class="video-play-button" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal">
					<span></span>
				</a>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body">
						<?=get_field("embed_content")?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="exampleModalPast" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body">
						<?=get_field("past_embed_content")?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_template_part('template-parts/footers/footer-page'); ?>