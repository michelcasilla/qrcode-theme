<?php
$is_rejected = false;
if (isset($_REQUEST['IsoCode']) && $_REQUEST['IsoCode'] !== '00') {
    $is_rejected = true;
    $have_error = $_REQUEST['ResponseMessage'];
}

$freeProccesed = false;
if (isset($_GET['applyForFree'])) {
    $freeProccesed = $_GET['applyForFree'];
}
?>
<section class="content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb--default">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?= _("Home") ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="<?= get_permalink() ?>">
                        <?= get_field('event_title') ?>
                    </a>
                </li>
                <li class="breadcrumb-item"><?= _("Información") ?></li>
            </ol>
        </nav>

        <div class="ticket">
            <div class="ticket-info" style="background-image: url('<?= get_field("event_image") ?>')">
                <div class="ticket-caption">
                    <span class="ticket-caption-label">
                        <?= the_field("event_speakers") ?>
                    </span>
                    <div class="ticket-caption-info">
                        <div class="square-caption square-caption--ticket">
                            <!-- <img class="single-caption__flag" src="./img/flag-eng.svg" alt="EN"> -->
                            <h2 class="square-caption__title square-caption__title--ticket"><?= get_field('event_title') ?></h2>
                            <!-- <h3 class="square-caption__subtitle square-caption__subtitle--ticket">Septiembre 2022</h3> -->
                            <div class="square-caption__address square-caption__address--ticket">
                                <span><?= get_field('event_date') ?></span>
                                <address><?= get_field('eventlocation') ?></address>
                            </div>
                            <div class="square-action--ticket">
                                <span>
                                    <?php if (get_field('country') == "DO") : ?>
                                        RD$
                                    <?php elseif (get_field('country') == "US") : ?>
                                        US$
                                    <?php endif; ?>
                                    <?= sprintf('%0.2f', get_field("event_price")) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket-form">
                <!-- .steps--two or .steps--three -->
                <div class="steps steps--three">
                    <div class="steps-block">
                        <span>1</span>
                        <small><?= __("Información", 'sp') ?></small>
                    </div>
                    <div class="steps-block">
                        <span>2</span>
                        <small><?= __("Pago", 'sp') ?></small>
                    </div>
                    <div class="steps-block">
                        <span>3</span>
                        <small><?= __("Factura", "sp") ?></small>
                    </div>
                </div>

                <!-- BEGIN BILL -->
                <?php if ($is_rejected) : ?>
                    <div class="bill text-danger text-center payment-failed">
                        <i class="bi bi-credit-card" style="font-size: 4em !important;"></i>
                        <h2><?= __("Error completando transacción", 'sp') ?></h2>
                        <h5><?= $have_error ?></h5>
                    </div>
                    <?php
                    $backurl = add_query_arg(array(), get_permalink());
                    $actionUrl = add_query_arg(array(
                        "payment_step" => "PAY",
                        "rejected" => null,
                        "error" => null,
                        "bucketRef" => null
                    ), full_url());
                    ?>
                    <a class="link" href="<?= $actionUrl ?>">
                        <?= __("Regresar a Pagos", "sp") ?>
                    </a>
                <?php else : ?>
                    <div class="bill">
                        <div class="bill-title">
                            <h4><?= __("FACTURA", "sp") ?></h4>
                        </div>
                        <div class="bill-item-wrapper bill-item-data">
                            <div class="bill-item">
                                <span><?= __("Detalles", "sp") ?></span>
                                <span data-invoice-status>XXXXXXXXXX</span>
                            </div>
                            <div class="bill-item">
                                <span><?= __("Auth ID", "sp") ?></span>
                                <span data-invoice-auth>XXXXXXXXXXXX</span>
                            </div>
                            <div class="bill-item">
                                <span><?= __("Fecha de compra", "sp") ?></span>
                                <span data-invoice-date>XXXX-XX-XX</span>
                            </div>
                            <div class="bill-item">
                                <span><?= __("Número de Factura", "sp") ?></span>
                                <span data-invoice-number>XXXXXXXXX</span>
                            </div>
                            <div class="bill-item">
                                <span><?= __("Cantidad de boletas", "sp") ?></span>
                                <span data-invoice-amount>Xx - XXX XX.00</span>
                            </div>
                        </div>
                        <div data-invoice-tickets>
                            <div class="bill-item-wrapper bill-item-user">
                                <div class="bill-item">
                                    <span>Boleta X</span>
                                    <span>XXXXXX</span>
                                </div>
                                <div class="bill-item">
                                    <span><?= __("Producto", "sp") ?></span>
                                    <span>XXXXXXXXXXXXXXX</span>
                                </div>
                                <div class="bill-qr">
                                    XXXXXX
                                </div>
                            </div>
                        </div>
                        <div class="bill-actions-wrapper">
                            <div class="bill-actions">
                                <span><?= __("Tu pago", "sp") ?></span>
                                <div><big data-invoice-total>XX</big><small>.00</small> <span data-invoice-currency>XXX</span></div>
                            </div>
                            <div class="bill-type">
                                <!-- add class .bill-type-label--stream for stream button
                                    <a href="#" class="bill-type-label bill-type-label--stream">VER STREAM</a> -->
                                <?php if (get_field("isfisical")) : ?>
                                    <a href="#" class="bill-type-label" data-invoice-event_type>PRESENCIAL</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
                <a class="link" href="<?= get_permalink() ?>">
                    <?= __("Regresar al inicio", "sp") ?>
                </a>
            </div>
            <?= get_template_part('template-parts/loading') ?>
        </div>
    </div>
</section>
<script>

    const handler = async () => {

        const GATE = "<?= $freeProccesed ? "FREE_PROCCESED" : get_field("payment_gate") ?>";
        const EVENTID = <?= the_ID() ?>;
        const CURRENT_URL = "<?= full_url() ?>";
        const PRICE = <?= get_field("event_price") ?>;
        const description = '<?= esc_attr(the_field('event_title')) ?>';
        showLoading();
        debugger
        switch (GATE) {
            case "AZUL":
                try {
                    await AZUL_handleSuccess({
                        EVENTID,
                        description
                    }, DrawVoucher);
                } catch (e) {
                    // debugger
                    // alert(e);
                    // location.href = "<?= get_permalink() ?>";
                }
                break;
            case "STRIPE":
                try {
                    const result = await STRIPE_handleSuccess({
                        EVENTID,
                        description
                    }, DrawVoucher);
                } catch (e) {
                    alert(e);
                    location.href = "<?= get_permalink() ?>";
                }
                break;
            case "FREE_PROCCESED":
                try {
                    let invoice = sessionStorage.getItem("INVOICE");
                    invoice = JSON.parse(invoice);
                    invoice.amount = 0;
                    invoice.price = 0;
                    DrawVoucher(invoice);
                } catch (e) {
                    alert(e);
                    location.href = "<?= get_permalink() ?>";
                }
                break;
            case "PAYPAL":
                try {
                    const paymentIntentResponse = JSON.parse(sessionStorage.getItem('PAYMENT'));
                    const result = await PAYPAL_handleSuccess({
                        EVENTID,
                        paymentIntentResponse
                    }, DrawVoucher);
                } catch (e) {
                    alert("NO PAYMENT DATA FOUND");
                    location.href = "<?= get_permalink() ?>";
                }
                break;
            default:
                alert("NOT_GATE_CONFIGURED");
                break;
        }
        hideLoading();
    }


    handler();
</script>