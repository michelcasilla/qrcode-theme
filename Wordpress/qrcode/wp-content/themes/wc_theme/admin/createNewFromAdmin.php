<?php
require_once(TEMPLATEPATH . "/admin/sponsorts.php");

function createNewFromAdmin()
{
    $id     = esc_sql($_REQUEST['id']);
    $postID = esc_sql($_REQUEST['eventID']);
    $userID = isset($_REQUEST['userID']) ? esc_sql($_REQUEST['userID']) : false;
    $currency = get_event_currency($postID);

    if ($id) {
        $pending = EventPaymentCatch::getById($id);
        $payment = unserialize($pending->payload);
        $payloadJson = json_encode($payment);
    } else {
        $pending = new EventPaymentCatch();
        $payment = new EventPayment();
        $payloadJson = new stdClass();

        if ($userID) {
            $user = get_user_by('ID', (int) $userID);
            $payloadJson = json_encode($user);
            $payment->payee_name = "";
            $payment->ticket_email = "";
            $payment->invoice_id = ""; // $postID."-".uniqid();
            $payment->payments_id = "";
            $payment->sponsor = "";
        }
    }
?>


    <style>
        event {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            max-width: 100vw;
            overflow-x: hidden;
            overflow-y: auto;
        }

        event--header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            min-height: 45vh;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            color: white;
            background-image: url(https://d2poexpdc5y9vj.cloudfront.net/themes/3.0/bg/abstract19.jpg);
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
        }

        event--header:after {
            content: " ";
            background: rgba(5, 7, 64, 0.63);
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: 0;
        }

        event--content {
            width: 100%;
            margin: auto;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            /*    max-height: 90%;*/
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .padding {
            padding: 20px;
        }

        .flex-dir-col {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        event--header--info {
            text-align: center;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            z-index: 1;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .event--title {
            margin: 0;
            font-size: 2rem;
            font-weight: 100;
        }

        event--tabs {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            background: #eaeaea;
            color: black;
            width: 100%;
            font-size: 14px;
            border-radius: 4px 4px 0px 0px;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            max-height: 50px;
            z-index: 1;
            overflow: hidden;
        }

        event--tabs [tab] {
            padding: 14px 20px;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            text-align: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        event--tabs [tab]:hover,
        event--tabs [tab].active {
            background: white;
            cursor: pointer;
        }

        h3 {
            font-weight: 100
        }

        .event--buy-tickets {
            padding: 14px 30px;
            border: 0;
            border-radius: 4px;
            background: #67c352;
            color: white;
        }

        p {
            line-height: 150%;
        }

        event--cart-info {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            width: 100%;

            margin: auto;
            height: auto
        }

        card--left {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        card--price {
            font-size: 1.2rem;
            font-weight: bold;
        }


        event-card {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            padding: 20px;
            -webkit-box-shadow: 0px 0px 4px 2px rgba(12, 12, 85, 0.16);
            box-shadow: 0px 0px 4px 2px rgba(12, 12, 85, 0.16);
            border: solid 1px rgba(11, 21, 75, 0.49);
            border-radius: 4px;
            line-height: 150%
        }

        card--right {
            /*    flex: 1;*/
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            padding: 8px;
            flex-direction: column;
            flex: 1;
        }

        card--right input.form-control {
            max-width: 100px;
        }

        card--title {
            font-weight: bold;
        }

        card--date {
            font-size: 12px;
        }

        .card--group--title {
            font-weight: 100;
        }

        event--location {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            background: #ebebeb;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            max-height: 30vh;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .form-control {
            padding: 10px;
            font-size: 16px;
            border: solid 1px #ccc;
        }

        .paypalBtn {
            height: 40px;
            ;
        }

        .loading-veil payment--voucher-container {
            width: 200px;
            min-height: 110px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            box-shadow: none;
            color: white;
        }

        .loading-veil payment--voucher-body {
            text-align: center;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        event--payment-voucher {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            z-index: 9999;
            min-width: 100vw;
            min-height: 100vh;
            display: -webkit-box;
            display: -ms-flexbox;
            display: none;
        }

        event--payment-voucher:after {
            content: " ";
            background: #0a024078;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
            z-index: 0;
        }

        payment--voucher-container {
            width: 80%;
            min-height: 600px;
            background: white;
            margin: auto;
            border-radius: 14px;
            -webkit-box-shadow: 0px 0px 6px 10px rgb(2 2 70 / 19%);
            box-shadow: 0px 0px 6px 10px rgb(2 2 70 / 19%);
            z-index: 1;
            position: relative;
            padding: 14px;
            font-size: .9rem;
        }

        voucher--close {
            position: absolute;
            right: -14px;
            top: -14px;
            background: white;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            -webkit-box-shadow: 0px 0px 6px 4px #10035647;
            box-shadow: 0px 0px 6px 4px #10035647;
            cursor: pointer;
        }

        card--select--sponsor,
        card--email--ticket {
            width: 280px;
            margin-top: 10px;
        }

        card--right input.form-control[disabled] {
            background: #ebebeb;
            border: solid 1px #ccc;
        }

        payment--voucher-body table tbody>tr:nth-child(odd)>td,
        payment--voucher-body table tbody>tr:nth-child(odd)>th {
            background: inherit;
        }

        payment--voucher-body table .bg-gray {
            background: #ebebeb !important;
        }

        .event-preview-link {
            display: inline-block;
            font-size: 1rem;
            text-decoration: none;
            padding: 4px 10px;
            background: #6fc737;
            color: white;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 12px;
        }

        voucher--header {
            font-size: 1.2rem;
            margin-bottom: 14px;
            display: block;
        }

        .payment-modal-opened event--header,
        .payment-modal-opened event--content {
            -webkit-filter: blur(2px);
            filter: blur(2px);
        }

        event--details {
            padding: 20px;
            width: 100%;
        }

        .validate-payment-modal payment--voucher-container {
            width: 360px;
            min-height: 300px;
        }

        .validate-payment-modal payment--voucher-body form {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .validate-payment-modal payment--voucher-body form>* {
            margin-bottom: 14px;
        }

        button.cancel-btn.validate-ticket {
            border: solid 1px #ccc;
            color: #ccc;
        }

        button.cancel-btn.validate-ticket:hover {
            background: white;
        }

        #stream iframe {
            max-width: 100%;
            width: 100%;
            height: auto;
            min-height: 540px;
        }

        input,
        select {
            font-family: 'Helvetica', 'Arial', sans-serif;
        }

        event--footer {
            display: flex;
            align-items: center;
            text-align: center;
            justify-content: center;
            padding: 40px 0;
        }

        .hide {
            display: none;
        }

        .lds-roller {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-roller div {
            animation: lds-roller 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            transform-origin: 40px 40px;
        }

        .lds-roller div:after {
            content: " ";
            display: block;
            position: absolute;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #fff;
            margin: -4px 0 0 -4px;
        }

        .lds-roller div:nth-child(1) {
            animation-delay: -0.036s;
        }

        .lds-roller div:nth-child(1):after {
            top: 63px;
            left: 63px;
        }

        .lds-roller div:nth-child(2) {
            animation-delay: -0.072s;
        }

        .lds-roller div:nth-child(2):after {
            top: 68px;
            left: 56px;
        }

        .lds-roller div:nth-child(3) {
            animation-delay: -0.108s;
        }

        .lds-roller div:nth-child(3):after {
            top: 71px;
            left: 48px;
        }

        .lds-roller div:nth-child(4) {
            animation-delay: -0.144s;
        }

        .lds-roller div:nth-child(4):after {
            top: 72px;
            left: 40px;
        }

        .lds-roller div:nth-child(5) {
            animation-delay: -0.18s;
        }

        .lds-roller div:nth-child(5):after {
            top: 71px;
            left: 32px;
        }

        .lds-roller div:nth-child(6) {
            animation-delay: -0.216s;
        }

        .lds-roller div:nth-child(6):after {
            top: 68px;
            left: 24px;
        }

        .lds-roller div:nth-child(7) {
            animation-delay: -0.252s;
        }

        .lds-roller div:nth-child(7):after {
            top: 63px;
            left: 17px;
        }

        .lds-roller div:nth-child(8) {
            animation-delay: -0.288s;
        }

        .lds-roller div:nth-child(8):after {
            top: 56px;
            left: 12px;
        }

        @keyframes lds-roller {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        event input,
        event select {
            width: 100%;
            max-width: 100%;
        }
    </style>
    <event>
        <event--content>
            <event--cart-info>
                <h1 class="card--group--title">
                    <?php if ($id) : ?>
                        <a onclick="openPendingNotifications()" class="button-secondary">Back</a> &nbsp;
                    <?php endif; ?>
                    <?= __("Creación manual de ticket para evento {$postID}", "sp") ?>
                </h1>
                <event-card>
                    <card--left>
                        <h2><?= the_field("event_title", $postID); ?></h2>
                        <h4><?= the_field("event_date", $postID); ?></h4>
                        <card--price><?= $currency ?><?= sprintf('%0.2f', get_field("event_price", $postID)) ?></card--price>
                    </card--left>
                    <card--right>
                        <label><?= __("IBO", "sp") ?></label>
                        <card--email--ticket>
                            <input type="text" placeholder="IBO" value="<?= $payment->payee_name ?>" name="cPayee_ibo" id="cPayee_ibo">
                            <input type="hidden" value="0" name="apply_for_free" id="apply_for_free">
                        </card--email--ticket>
                        <br>
                        <label><?= __("Name", "sp") ?></label>
                        <card--email--ticket>
                            <input type="text" placeholder="Nombre" value="<?= $payment->payee_name ?>" name="cPayee_name" id="cPayee_name">
                            <input type="hidden" value="<?= $postID ?>" name="cPostID" id="cPostID">
                        </card--email--ticket>
                        <br>
                        <label><?= __("Correo electrónico", "sp") ?></label>
                        <card--email--ticket>
                            <input type="text" placeholder="Tu correo electronico" value="<?= $payment->ticket_email ?>" name="email" id="email">
                            <input type="hidden" value="<?= $postID ?>" name="cPostID" id="cPostID">
                        </card--email--ticket>
                        <br>
                        <label><?= __("ID Factura", "sp") ?></label>
                        <card--email--ticket>
                            <input type="text" value="<?= $payment->invoice_id ?>" name="invoiceID" id="invoiceID">
                        </card--email--ticket>
                        <br>
                        <label><?= __("Referencia de pago", "sp") ?></label>
                        <card--email--ticket>
                            <input type="text" value="<?= $payment->payments_id ?>" name="paymentref" id="paymentref">
                        </card--email--ticket>
                        <br>
                        <label><?= __("Selecciona tu Esmeralda / Diamante / Platinos", "sp") ?></label>
                        <card--select--sponsor>
                            <select name="sponsor" id="creatorSponsor">
                                <?= getSponsorsOptionGroup($payment->sponsor) ?>
                            </select>
                        </card--select--sponsor>
                        <label><?= __("Moneda", "sp") ?></label>
                        <card--select--sponsor>
                            <select name="currency" id="currency">
                                <option value="USD" <?= $currency == 'USD' ? 'selected="true"' : '' ?>>USD</option>
                                <option value="RD$" <?= $currency == 'RD$' ? 'selected="true"' : '' ?>>RD$</option>
                            </select>
                        </card--select--sponsor>
                    </card--right>
                    <card--right>
                        <button class="button-primary" onclick="sendPaymentEntry()">Registrar entrada de pago</button> <br>
                        <?php if ($id) : ?>
                            <button class="button-secondary" onclick="openPendingNotifications()">Cancelar</button>
                        <?php endif; ?>
                    </card--right>
                </event-card>
                <div class="updated notice">
                    <p>Su entrada de pago se registrado correctamente y fue enviado al correo provisto.</p>
                </div>
                <div class="error notice">
                    <p>No se pudo registrar su pago debido a un error.</p>
                </div>
            </event--cart-info>
        </event--content>
        <?php if ($id) : ?>
            <h4>LOG DE INFORMACION OBTENIDA</h4>
            <pre><?php print_r($payment); ?></pre>
        <?php endif; ?>
    </event>
    <script type="text/javascript">
        jQuery(function() {
            jQuery('.updated.notice').hide();
            jQuery('.error.notice').hide();

            jQuery('#cPayee_ibo').on('blur', async function() {
                const value = jQuery(this).val();
                await verifyIBO(value);
            });
        });

        function home_url(url) {
            return `<?= home_url() ?>${url}`;
        }

        async function verifyIBO(ibo) {
            if (!ibo.trim()) return;
            const params = {
                ibo,
                eventId: <?= $postID ?>
            };
            jQuery('#cPayee_name').attr('disabled', true);
            const response = await jQuery.post(home_url("/wp-json/wctvApi/v1/verifyIBO"), params);
            const {
                code = "EXPIRED",
                    data: {
                        account_name = ''
                    }
            } = response;

            jQuery('#apply_for_free').val(code === 'VALID' ? 1 : 0);
            jQuery('#cPayee_name').val(account_name);
            jQuery('#cPayee_name').attr('disabled', false);

        }

        function sendPaymentEntry() {

            let email = jQuery('#email').val();
            let sponsor = jQuery("#creatorSponsor").val();
            let currency = jQuery("#currency").val();
            let postId = jQuery("#cPostID").val();
            let paymentref = jQuery("#paymentref").val();
            let invoiceID = jQuery("#invoiceID").val();
            let payee_name = jQuery("#cPayee_name").val();
            let payee_ibo = jQuery("#cPayee_ibo").val();
            let apply_for_free = jQuery("#apply_for_free").val();

            if (email.trim() == "") {
                jQuery('.updated.notice').hide();
                jQuery('.error.notice').html("<p>Debe indicar el correo electrónico</p>").show();
                return false;
            }

            if (sponsor.trim() == "") {
                jQuery('.updated.notice').hide();
                jQuery('.error.notice').html("<p>Debe indicar el correo Sponsor</p>").show();
                return false;
            }

            let p = {
                email: email,
                sponsor: sponsor,
                eventId: postId,
                paymentref: paymentref,
                ibo: payee_ibo,
                invoice_id: invoiceID,
                paypload: JSON.stringify(<?= $payloadJson ?>),
                catchID: <?= $id ?>,
                payee_name: payee_name,
                currency,
                apply_for_free
            }

            jQuery.post(home_url("/wp-json/wctvApi/v1/registerPaymentAdmin"), p)
                .success(function(response) {
                    jQuery('.error.notice').hide();
                    jQuery('.updated.notice').show();
                }).error(function(error) {
                    jQuery('.updated.notice').hide();
                    jQuery('.error.notice').html("<p>No se pudo registrar su pago debido a un error</p><br/> <div>" + JSON.stringify(error) + "</div>").show();
                });
        }
    </script>


<?php } ?>