<section class="content">
	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb breadcrumb--default">
				<li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
				<li class="breadcrumb-item active" aria-current="page"><?=get_field('event_title')?></li>
			</ol>
		</nav>
		<div class="single-proceeding">
		<div class="container">
			<div class="single-actions">
				<?php $useCountDown = get_field("use_countdown"); ?>
				<?php if($useCountDown && get_field("event_date")): ?>
					<script>
						
						const interval = 1000;
						const intervalInstance = setInterval(function(){
							const eventTime = new Date("<?=get_field("event_date")?>").getTime(); // Timestamp - Sun, 21 Apr 2013 13:00:00 GMT
							const currentTime = new Date().getTime(); // Timestamp - Sun, 21 Apr 2013 12:30:00 GMT
							const isExpired = (eventTime < currentTime)
							const diffTime = eventTime - currentTime;
							let duration = moment.duration(diffTime, 'milliseconds');
							// duration = moment.duration(duration - interval, 'milliseconds');
							const month = duration.months();
							const d = duration.days();
							const h = duration.hours();
							const m = duration.minutes();
							const s = duration.seconds();

							document.querySelectorAll('[data-countdown-month]')[0].innerText = month < 0 ? 0 : month
							document.querySelectorAll('[data-countdown-days]')[0].innerText = d < 0 ? 0 : d
							document.querySelectorAll('[data-countdown-hours]')[0].innerText = h < 0 ? 0 : h;
							document.querySelectorAll('[data-countdown-mins]')[0].innerText = m < 0 ? 0 : m;
							document.querySelectorAll('[data-countdown-segs]')[0].innerText = s < 0 ? 0 : s;

							if((month < 0) && (d < 0) && (h < 0) && (m < 0) && (s < 0) ){
								clearInterval(intervalInstance);
							}
						}, interval);
					</script>
					<div class="single-countdown">
						<div class="all"></div>
						<div class="single-countdown__item">
							<span data-countdown-month>0</span>
							<small><?=__("MESES", "sp")?></small>
						</div>
						<div class="single-countdown__item">
							<span data-countdown-days>0</span>
							<small><?=__("DIAS", "sp")?></small>
						</div>
						<div class="single-countdown__item">
							<span data-countdown-hours>0</span>
							<small><?=__("HORAS", "sp")?></small>
						</div>
						<div class="single-countdown__item">
							<span data-countdown-mins>0</span>
							<small><?=__("MIN", "sp")?></small>
						</div>
						<div class="single-countdown__item">
							<span data-countdown-segs>0</span>
							<small><?=__("SEG", "sp")?></small>
						</div>
					</div>
				<?php endif; ?>
				<?php
					$actionUrl = add_query_arg(
						array( "payment_step" => "INFO" ),
						get_permalink()
					);
				?>
				<div class="single-button">
					<span>
					<?php if(get_field('country') == "DO"):?>
						RD$
					<?php elseif(get_field('country') == "US"): ?>
						US$
					<?php endif; ?>
					<?=sprintf('%0.2f', get_field("event_price"))?></span>
					<a href="<?=$actionUrl?>"><?=__("COMPRAR", "sp")?></a>
				</div>
			</div>
		</div>
	</div>
		<div class="single reverse-order-mobile">
			<div class="single-caption">
				<?=get_post_country_image(get_field("country"), "single-caption__flag")?>
				<!-- <h2 class="single-caption__subtitle">Seminario Miami - Noviembre 2022</h2> -->
				<h1 class="single-caption__title"><?=get_field('event_title')?></h1>
				<div class="single-caption__place">
					<?=the_field("event_information")?>
				</div>
				<div class="single-caption__place">
					<?=the_field("event_organizer")?>
				</div>
				<div class="single-caption__place">
					<?=the_field("refund_policy")?>
				</div>
				<div class="single-caption__place">
					<?=render_iframe_from_url(the_field("eventlocation"))?>
				</div>
			</div>
			<div class="single-image">
				<?php if(get_field("event_image")): ?>
					<img class="single-image__item" src="<?=get_field("event_image")?>" alt="#">
				<?php endif; ?>
				<?php if(get_field('event_speakers')): ?>
					<div class="single-image__name">
						<!-- <small><?=__("Orador invitado:", "sp")?></small> -->
						<!-- <span><?=the_field("event_speakers")?></span> -->
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>