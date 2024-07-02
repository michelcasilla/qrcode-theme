<?php include_once(TEMPLATEPATH."/admin/models/VTW_Ticket_Code.php"); ?>
<?php require_once(TEMPLATEPATH."/admin/sponsorts.php"); ?>
<?php
	$ticket = esc_sql($_GET['t']);
	$email = esc_sql($_GET['e']);
	$eventId = get_the_ID();

	if(is_leadership_program()){
		$ticketInfo = TicketCode::getLastEventTicketCodeAny($ticket, $email, $eventId);
	}else{
		$ticketInfo = TicketCode::getLastEventTicketCode($ticket, $email, $eventId);
	}
	$isFisical = get_field("isfisical");
	$enableWatchFrom = explode(":", get_field("useWatchFromAntifraud"));
	$isEnableWatchFrom = $enableWatchFrom[0];

	if($ticketInfo){
		TicketCode::updateLastTicketCodeIP($ticketInfo->last_event_ticket_code, $ticketInfo->id, get_the_user_ip());
	}
?>
<?php if($ticketInfo): ?>
	<section class="content">
		<div class="container">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb--default">
					<li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
					<li class="breadcrumb-item" aria-current="page">
						<a href="<?= get_permalink() ?>">
							<?= get_field('event_title') ?>
						</a>
					</li>
					<li class="breadcrumb-item active"><?= _("Información") ?></li>
				</ol>
			</nav>
			<div class="single single-inline">
				<?=get_post_country_image(get_field("country"), "single-caption__flag")?>
				<h2 class="single-caption__subtitle"><?=get_field('event_title')?></h2>		
			</div>
			<div class="ticket">
				<?php if($isFisical): ?>
					<!-- <div class="fisical">ES UN EVENTO FISICO</div> -->
				<?php endif; ?>
				<?=wp_kses_post(the_field("event_embed_source")) ?>
			</div>
		</div>
	</section>
	<?php if($isEnableWatchFrom == "1"): ?>
		<script>
			const ticket = <?=json_encode($ticketInfo)?>;
			const {post_id, last_event_ticket_code} = ticket;
			antifraudCheck(post_id, last_event_ticket_code);
		</script>
	<?php endif; ?>
<?php else: ?>
	<section class="content">
		<div class="container">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb breadcrumb--default">
					<li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?=_("Home")?></a></li>
					<li class="breadcrumb-item" aria-current="page">
						<a href="<?= get_permalink() ?>">
							<?= get_field('event_title') ?>
						</a>
					</li>
					<li class="breadcrumb-item active"><?= _("Información") ?></li>
				</ol>
			</nav>
			<div class="single single-inline invalid-ticket-provided">
				<i class="bi bi-ticket-perforated"></i>
				<h3><?=_('El ticket no es válido')?>&nbsp; <?=$eventId?></h3>	
			</div>
		</div>
	</section>
<?php endif; ?>