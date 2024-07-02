<?php
/*
 * Template Name: Paypal Event Template
 * Template Post Type: post
 */
require_once(TEMPLATEPATH . "/admin/sponsorts.php");
get_header();
?>

<style>
    /*
* Prefixed by https://autoprefixer.github.io
* PostCSS: v7.0.29,
* Autoprefixer: v9.7.6
* Browsers: last 4 version
*/


    [ng\:cloak],
    [ng-cloak],
    [data-ng-cloak],
    [x-ng-cloak],
    .ng-cloak,
    .x-ng-cloak {
        display: none !important;
    }

    .site-header {
        display: none;
    }

    card--fisical-event {
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
        -webkit-box-shadow: 0px 0px 4px 2px rgb(12 12 85 / 16%);
        box-shadow: 0px 0px 4px 2px rgb(12 12 85 / 16%);
        border: none;
        border-radius: 0;
        line-height: 150%;
        position: relative;
        background: linear-gradient(45deg, #67c352, #70db2a);
        text-align: center;
        align-items: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2em;
        color: white;
    }

    @media (max-width: 576px) {
        event-card {
            -webkit-box-orient: vertical !important;
            -webkit-box-direction: normal !important;
            -ms-flex-direction: column !important;
            flex-direction: column !important;
        }

        event--details,
        .card--group--title {
            padding: 20px;
        }

        payment--voucher-body {
            overflow-x: auto;
            display: flex;
        }

        #stream iframe {
            min-height: 280px !important;
        }

        event--tabs {
            overflow-x: auto !important;
        }
    }

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
        max-width: 900px;
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
        max-width: 900px;
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
        max-width: 900px;
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
        /* padding: 20px; */
        -webkit-box-shadow: 0px 0px 4px 2px rgba(12, 12, 85, 0.16);
        box-shadow: 0px 0px 4px 2px rgba(12, 12, 85, 0.16);
        border: solid 1px rgba(11, 21, 75, 0.49);
        border-radius: 4px;
        line-height: 150%;
        position: relative;
        font-size: 14px;
    }

    .event-card--padding {
        padding: 20px;
    }

    .card-right--emails {
        background: #ebebeb;
        min-width: 280px;
    }

    card--right {
        /*    flex: 1;*/
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-box-pack: end;
        -ms-flex-pack: end;
        justify-content: flex-end;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        padding: 8px;
    }

    card--right.card-right--paypal input.form-control {
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
        display: flex;
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
        max-width: 900px;
        padding: 14px;
        font-size: .9rem;
        max-height: 90vh;
        overflow-y: auto;
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

    .event-ended,
    .event-ended-soldout {
        display: none;
    }

    .info-event-ended .event-ended {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #000000b5;
        z-index: 114;
        display: flex;
        flex-direction: column;
        color: white;
        text-align: center;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .info-event-soldout .event-ended-soldout {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: #000000b5;
        z-index: 114;
        display: flex;
        flex-direction: column;
        color: white;
        text-align: center;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    card--fisicalevent {
        display: flex;
        flex-direction: column;
    }

    card--fisicalevent>label {
        font-weight: bold;
    }

    label {}

    card--fisicalevent card--select--sponsor {
        display: flex;
    }

    card--fisicalevent card--select--sponsor>label {
        flex: 1;
    }

    card--qty.cart-qty-holder {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
    }

    card-emails {
        display: flex;
        flex-direction: column;
        border: dotted 1px #ebebeb;
        background: #ebebeb;
        margin: 10px 0;
        flex: 1;
    }

    .card-right--emails card-container {
        display: flex;
        flex-direction: column;
    }

    b.card-emails--add-email {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        cursor: pointer;
        font-size: .8em;
    }

    card-container {
        width: 100%;
    }

    card-container.card-email-container {
        display: flex;
        flex-direction: column;
        flex: 1;
        height: 100%;
    }

    form.card-add-email--form {
        background: white;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0px 0px 8px 0px #0f378e2b;
    }

    form.card-add-email--form input {
        width: 100%;
        height: 31px;
        border: none;
        margin-bottom: 10px;
        border-bottom: solid 1px #ccc;
        border-radius: 0;
        padding: 0;
        font-size: 12px;
    }

    form.card-add-email--form button {
        width: 100%;
        height: 34px;
        font-size: 12px;
    }

    card-emails email {
        display: flex;
        flex-direction: row;
        font-size: 12px;
        background: #fafafa;
        padding: 4px 10px;
        border-radius: 4px;
        line-height: 130%;
    }

    card-emails email+email {
        margin-top: 1px;
    }

    card-emails email b {
        flex: 1;
    }

    .email--delete {
        background: #dc4a4a;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        color: white;
        border-radius: 50%;
        border: none;
        font-size: 9px;
    }
</style>
<script src="//dmc1acwvwny3.cloudfront.net/atatus.js"></script>
<script type="text/javascript">
    atatus.config('dae0c8405348492190acf7406be5d03b').install();
    try {
        atatus.onBeforeErrorSend(function(error) {
            // You can ignore the error message which contains specific string
            if (error && error.message && error.message.indexOf("paypal.Buttons") !== -1) {
                return false; // Return false here to abort the send
            }
            return true;
        });
    } catch (e) {}
</script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.8.0/angular-resource.js"></script>
<script type="text/javascript">
    function __atatusNotify(e) {
        atatus.notify(e);
    }

    function DisableCopyPaste(e) {
        // Message to display
        var message = "Cntrl key/ Right Click Option disabled";
        // check mouse right click or Ctrl key press
        var kCode = event.keyCode || e.charCode;
        //FF and Safari use e.charCode, while IE use e.keyCode
        if (kCode == 17 || kCode == 2 || kCode == 91) {
            alert(message);
            return false;
        }
    }

    function getInvoiceID() {
        return [
            "<?= the_ID() ?>",
            Math.random().toString(36).substr(2, 9)
            /*,
            				Date.now()*/
        ].join("-").toUpperCase();
    }
</script>
<?php
$TYPE = get_field("type") || "EVENT";
echo $TYPE;
$MAX_QTY = get_field("fisicalmaxquantity") || 9999999;
$enableWatchFrom = get_field("useWatchFromAntifraud") || "YES";
?>
<event ng-app="wcEvent" ng-controller="eventController" ng-class="{
        'payment-modal-opened':fields.showPaymentVoucher,
        'payment-modal-opened':fields.showLoading,
    }">
    <event--header style="background-image:url(<?= the_field('event_image') ?>)">
        <event--header--info class="flex-dir-col">
            <event--logo><img src="https://s3.amazonaws.com/ezusrevent/A51E33C392B2F1490DAED41D07E7478452E81204E15B6158FC.png" width="100"></event--logo>
            <h2 class="event--title"><?= the_field("event_title"); ?></h2>
            <h6 class="event--subtitle"><?= the_field("event_date"); ?></h6>
            <button type="button" class="event--buy-tickets" ng-hide="fields.activeTab=='STREAM'" name="buyTickets" ng-click="showValidateModal()">
                <?= __("VALIDAR TICKET", "sp") ?>
            </button>
        </event--header--info>
        <event--tabs>
            <a tab ng-click="fields.activeTab=EVENT_INFORMATION" ng-class="{active:fields.activeTab==EVENT_INFORMATION}"><?= __("Información del evento", "sp") ?></a>
            <a tab ng-click="fields.activeTab=EVENT_ORGANIZER" ng-class="{active:fields.activeTab==EVENT_ORGANIZER}"><?= __("Organizadores", "sp") ?></a>
            <a tab ng-click="fields.activeTab=EVENT_SPEAKERS" ng-class="{active:fields.activeTab==EVENT_SPEAKERS}"><?= __("Oradores", "sp") ?></a>
            <a tab ng-click="fields.activeTab='STREAM'" ng-if="fields.frameContent" ng-class="{active:fields.activeTab=='STREAM'}"><?= __("Stream", "sp") ?></a>
        </event--tabs>
    </event--header>
    <event--content>
        <event--details>
            <section id="stream" ng-if="fields.activeTab=='STREAM' && fields.frameContent">

                <div ng-init="checkConn()"></div>
                <div class="embed-content" ng-bind-html="fields.frameContent"></div>
            </section>
            <section id="eventInformation" class="{{fields.activeTab!='EVENT_INFORMATION' ? 'hide' : ''}}">
                <event--cart-info ng-class="{'info-event-ended' : hidePaymentBlock, 'info-event-soldout' : isSoldOUT}">
                    <h1 class="card--group--title"><?= __("Ticket", "sp") ?></h1>
                    <?php if (get_field("isfisical")) : ?>
                        <card--fisical-event>EVENTO PRESENCIAL</card--fisical-event>
                    <?php endif; ?>
                    <event-card>
                        <card--left class="event-card--padding">
                            <card--title><?= the_field("event_title"); ?></card--title>
                            <card--date><?= the_field("event_date"); ?></card--date>
                            <card--price>US$<?= sprintf('%0.2f', get_field("event_price")) ?></card--price>

                            <br>
                            <label><?= __("Correo electrónico", "sp") ?></label>
                            <card--email--ticket>
                                <input type="text" placeholder="Tu correo electronico" ng-model="fields.email">
                            </card--email--ticket>
                            <br>
                            <label><?= __("Confirmar correo electrónico", "sp") ?></label>
                            <card--email--ticket>
                                <input type="text" placeholder="Confirmación de correo" ng-model="fields.confEmail" onKeyDown="return DisableCopyPaste(event)" onMouseDown="return DisableCopyPaste(event)">
                            </card--email--ticket>
                            <br>
                            <label><?= __("Selecciona tu Esmeralda / Diamante / Platino", "sp") ?></label>
                            <card--select--sponsor>
                                <select name="select" ng-model="fields.selectSponsor">
                                    <?= getSponsorsOptionGroup() ?>
                                </select>
                            </card--select--sponsor>
                            <!-- <br/>
                            <card--fisicalevent>
                                <label><?= __("Como desea ver este evento", "sp") ?></label>
                                <card--select--sponsor>
                                    <label><input type="radio" name="isFisicalEvent" ng-model="fields.isfisicalEvent" require value="1"> Presencial</label>
                                    <label><input type="radio" name="isFisicalEvent" ng-model="fields.isfisicalEvent" require value="0"> Virtual</label>
                                </card--select--sponsor>
                            </card--fisicalevent> -->
                            <br />
                            <label>
                                <card--input--ticket>
                                    <input type="checkbox" ng-model="fields.refundPolicy">
                                </card--input--ticket>
                                <?= __("Acepto los ", "sp") ?><a href="#refund-policy"><?= __("términos y condiciones", "sp") ?></a>
                            </label>
                        </card--left>
                        <card--right class="card-right--emails event-card--padding ng-hide" ng-show="fields.eventQty > 1">
                            <card-container ng-cloakc class="card-email-container">
                                <b>
                                    Agregar nombres de las boletas <br>
                                    <span class="small">({{fields.emails.length}} de {{fields.eventQty}})</span>
                                </b>
                                <card-emails>
                                    <email ng-repeat="(key, email) in fields.emails">
                                        <b>{{email.name}}</b>
                                        <span>{{email.email}}</span>
                                        <span ng-click="deleteEmail(key)" class="email--delete">X</span>
                                    </email>
                                </card-emails>
                                <form class="card-add-email--form" name="addEmailForm" ng-submit="addEmail()" ng-hide="fields.emails.length == fields.eventQty">
                                    <input type="text" focus-on-submit name="name" class="form-control" required="required" ng-model="name" placeholder="Nombre">
                                    <!-- <input type="email" 
                                        name="email" 
                                        class="form-control" 
                                        required="required" 
                                        ng-model="email"
                                        Placeholder="Correo"> -->
                                    <button type="submit" name="add" class="btn btn-success">Agregar</button>
                                </form>
                            </card-container>
                        </card--right>
                        <card--right class="card-right--paypal event-card--padding">
                            <card-container ng-cloak>
                                <card--qty class="cart-qty-holder">
                                    <input type="number" value="1" name="eventQty" class="form-control" min="1" max="10" ng-model="fields.eventQty">
                                    &nbsp;<br>
                                    <b>{{fields.eventQty * fields.eventPrice | currency : fields.currencyCode : 2}}</b>
                                </card--qty>
                                <br>
                                <card--paypal id="paypal-button-container">
                                    <!-- <img class="paypalBtn" src="<?= get_template_directory_uri() ?>/assets/images/paypalBtn.png"  height="45"> -->
                                </card--paypal>
                            </card-container>
                        </card--right>
                        <span class="event-ended">
                            <?= __("Evento Finalizado", 'sp') ?> <br />&nbsp;
                            <small>{{eventEndDate | date:'medium'}}</small>
                        </span>
                        <span class="event-ended-soldout">
                            <?= __("SOLD OUT", 'sp') ?>
                        </span>
                    </event-card>
                </event--cart-info>
                <br />
                <h3><?= __("Informaciones del evento", "sp") ?></h3>
                <p><?= the_field("event_information"); ?></p>
                <br />
                <h3 id="refund-policy"><?= __("Términos y condiciones", "sp") ?></h3>
                <p><?= the_field("refund_policy"); ?></p>
            </section>
            <section id="aboutOrganizer" ng-if="fields.activeTab=='EVENT_ORGANIZER'">
                <h3><?= __("Organizadores", "sp") ?></h3>
                <p><?= the_field("event_organizer"); ?></p>
            </section>
            <section id="aboutSpeakers" ng-if="fields.activeTab=='EVENT_SPEAKERS'">
                <h3><?= __("Oradores", "sp") ?></h3>
                <p><?= the_field("event_speakers"); ?></p>
            </section>
        </event--details>
    </event--content>
    <event--footer>
        <p><a class="ng-binding" href="https://www.worldcrowns.tv/support"><?= __("Ayuda", "sp") ?></a><br><?= __("World Crowns. Copyright © Todos los derechos reservados", "sp") ?></p>
    </event--footer>
    <!-- <event--location>
        <map name="">MAP</map>
    </event--location> -->
    <event--payment-voucher class="ng-hide loading-veil" ng-hide="!fields.showLoading">
        <payment--voucher-container>
            <payment--voucher-body>
                <div class="lds-roller">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
                <span>Procesando su pago espere...</span>
            </payment--voucher-body>
        </payment--voucher-container>
    </event--payment-voucher>
    <event--payment-voucher class="ng-hide" ng-hide="!fields.showPaymentVoucher">
        <payment--voucher-container>
            <voucher--close ng-click="hidePaymentVoucher()"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD/klEQVRoge3ay29VVRQG8F+10ktQoa0tOJPoQEQrc6NOfBRBrcxQnEFwwiPoFHVsHJk08e8wUQKJmOILSsVnLMXHRCUqJiYaKwTNdbD3yT7g7e095+7bNoQvObnJXWt9e52z9l57nbUP17Gy0JeRawgP4QHcgzsxijVR/hd+wff4Gh/gBH7P6ENtNPA8juFfNCte/+AodmFgiX0Hq/EizpecuojjOIwJbMIgborXYPxvAi/jvWhT2P+EQ8LDWRJsw3clB05jN9bW4FqHPZgp8X2LrVk8XQANvFka8BM8lpF/HJ+W+Cf1IDobBMebwqLdhxtzDxI5D2Beivb6XOQbhXA3MYt7cxG3wRjm4pjfRB+6wkiJcBq3dUtYAYNCim4Ka3JDXaKGNJ0+kvaDpcQanJSmWa01UyzsWWGzWy4MS7NisqrxNmlhL8WaWAxjUgIY79RotbRP7OuNX7VwUFr8HU2xl6R9ohcpti768Zng28HFlAeEUqGJR3vrVy08Ifh23iJR2SVliE5xQigxRms4NipE/v0O9fukTPpsO8VjUWl3BWemo82Xqt3MaLRp4lQFu73R5shCCkNCWX1RtQKw7FCnN1PHpsAgLuHyQn4+E4nfrUBaYARfSPvO7RV06+zYU9H+yVbC16PwcA1iOnvK3USijFcjx2uthG9H4VM1yWkfmRyRKFDMnrdaCc9F4aYuBqD1U88ViQKbI9fZVsLfonC4y0H4v+M5b4JQhTdxoZXwUhSuyjAQV06lHNOpjAGpTwBuyETcKXK2nxbENTO1rpnFXqTfp7sYoF2KrbJpLoYdrkq/5TUyG3/vr0k+KjTo7sNXeBg/l+QX8EiU3S3UdXUjMxZ/W0ZkQrjL4zWIq2x2OSJTlCjbWwkHpaJxXQXSpS4ah6Si8daFlI5G8j0ViE/XdKh8M9MV7F6INu+0U3ouKs1UIP5Ydy9WM5GjE/ThjODjznaKA/gxKj5ew7FeY7vg2w86OIY4FJXPWHnNh88F3/Z3YtCQer0HeudXZRQPeE6FQ6Gt0WheytnLiS34W/Cp8lHGpPQEctRfdTEiNOWaeKMOQUNKrSctTxP7ZqHDUnRaap8zjghlQJHrR3J41yGG8KF0HNf1gc9GKbRz6tdiVbClNOY53JGLeL00zeaF3mt/LvIS+oXsVCzsU/K8v1yBhpQAmkJDOdfpa59wlFHsE8XC7unZ+7gU9qJrv1coOqtiSKidirKjmEo5T4vboiFMr6KcaQoV6RReEfpOm4W0vSpew8Kh0Y6oMyU1PIqyY79l+gJiQOiKHxFeARb7ZOPq67LwZrpTlzeQs6uxVvio5kHhvf8uIV3fEuV/4lchlZ4VjhKm8EdGH65jxeA/l4Zhpr12p/0AAAAASUVORK5CYII=" /></voucher--close>
            <voucher--header>PayPal order</voucher--header>
            <payment--voucher-body ng-repeat="paymentResponse in fields.paymentResponse">
                <table>
                    <!-- CUSTOMER INFO -->
                    <tbody>
                        <tr>
                            <td colspan="12" style="padding: 10px;font-weight: 500;line-height: 2;font-weight: bolder;" class="bg-gray">
                                Billing Details
                                <span style="background: #6fc737;padding: 1px 14px;border-radius: 8px;color: white;float: right;font-size: .6rem;">
                                    {{paymentResponse.msg.status}}
                                </span>
                            </td>
                            <!-- <td colspan="6" style="padding: 10px;font-weight: 500;line-height: 2;font-weight: bolder;">Payment Details</td> -->
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Name</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.payee_name}}</td>
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;"><!-- Credit Card --> Auth id</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.payments_id}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Email</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.ticket_email || paymentResponse.msg.payee_email_address}}</td>
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Date of purchase</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.update_time}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding: 3px 10px; font-weight: 700;" rowspan="3">Address</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;width: 25%;" rowspan="3">
                                {{paymentResponse.msg.shipping_address_address_line_1}}
                                {{paymentResponse.msg.shipping_address_admin_area_1}}
                                {{paymentResponse.msg.shipping_address_country_code}}
                                {{paymentResponse.msg.shipping_address_postal_code}}
                            </td>
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;">Invoice No.</td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.invoice_id}}</td>
                        </tr>
                        <tr style="background: #6ec737;">
                            <!-- <td colspan="3" style=" padding: 3px 10px;font-weight: 700;"></td> -->
                            <!-- <td colspan="3" style="padding: 3px 10px;text-align: right;">{{paymentResponse.msg.shipping_address_postal_code}}</td> -->
                            <td colspan="3" style="padding: 3px 10px;font-weight: 700;"></td>
                            <td colspan="3" style="padding: 3px 10px;text-align: right;"></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td colspan="12" style="padding: 40px 10px 0 10px;font-weight: 500;" class="bg-gray">Order Items</td>
                        </tr>
                        <tr>
                            <td colspan="7" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;">Item</td>
                            <td colspan="2" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">Price</td>
                            <td colspan="1" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">Qty.</td>
                            <td colspan="2" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">
                                Total</td>
                        </tr>
                        <tr style="vertical-align: top;">
                            <td colspan="7" style=" padding: 20px 10px 0 10px;">
                                {{paymentResponse.msg.description}}
                                <br />
                                <a class="event-preview-link" ng-click="validateFromVoucher()">CLICK HERE TO WATCH YOUR STREAM</a>
                                <br> <br>
                            </td>
                            <td colspan="2" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">{{paymentResponse.msg.amoun_currency_value}}</td>
                            <td colspan="1" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">1</td>
                            <td colspan="2" style="padding: 20px 10px 0 10px;text-align: right;font-weight: 500;">{{paymentResponse.msg.amoun_currency_value}}</td>
                        </tr>
                        <tr>
                            <td colspan="6" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;">Total</td>
                            <td colspan="6" style="padding: 20px 10px 0 10px;font-weight: bolder;font-size: 14px;text-align: right;">{{paymentResponse.msg.amoun_currency_code}}$ {{paymentResponse.msg.amoun_currency_value}}</td>
                        </tr>
                    </tbody>
                </table>
            </payment--voucher-body>
            <b style="margin-top: 10px;display: block;font-size: 11px;font-weight: normal;"><b>NOTE:</b> DO NOT SHARE THIS LINK WITH ANYONE. This is a private LINK can only be used by one person at a time.</b>
        </payment--voucher-container>
    </event--payment-voucher>
    <event--payment-voucher class="ng-hide validate-payment-modal" ng-hide="!fields.showValidateModal">
        <payment--voucher-container>
            <voucher--close ng-click="hideValidatePayment()"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAAD/klEQVRoge3ay29VVRQG8F+10ktQoa0tOJPoQEQrc6NOfBRBrcxQnEFwwiPoFHVsHJk08e8wUQKJmOILSsVnLMXHRCUqJiYaKwTNdbD3yT7g7e095+7bNoQvObnJXWt9e52z9l57nbUP17Gy0JeRawgP4QHcgzsxijVR/hd+wff4Gh/gBH7P6ENtNPA8juFfNCte/+AodmFgiX0Hq/EizpecuojjOIwJbMIgborXYPxvAi/jvWhT2P+EQ8LDWRJsw3clB05jN9bW4FqHPZgp8X2LrVk8XQANvFka8BM8lpF/HJ+W+Cf1IDobBMebwqLdhxtzDxI5D2Beivb6XOQbhXA3MYt7cxG3wRjm4pjfRB+6wkiJcBq3dUtYAYNCim4Ka3JDXaKGNJ0+kvaDpcQanJSmWa01UyzsWWGzWy4MS7NisqrxNmlhL8WaWAxjUgIY79RotbRP7OuNX7VwUFr8HU2xl6R9ohcpti768Zng28HFlAeEUqGJR3vrVy08Ifh23iJR2SVliE5xQigxRms4NipE/v0O9fukTPpsO8VjUWl3BWemo82Xqt3MaLRp4lQFu73R5shCCkNCWX1RtQKw7FCnN1PHpsAgLuHyQn4+E4nfrUBaYARfSPvO7RV06+zYU9H+yVbC16PwcA1iOnvK3USijFcjx2uthG9H4VM1yWkfmRyRKFDMnrdaCc9F4aYuBqD1U88ViQKbI9fZVsLfonC4y0H4v+M5b4JQhTdxoZXwUhSuyjAQV06lHNOpjAGpTwBuyETcKXK2nxbENTO1rpnFXqTfp7sYoF2KrbJpLoYdrkq/5TUyG3/vr0k+KjTo7sNXeBg/l+QX8EiU3S3UdXUjMxZ/W0ZkQrjL4zWIq2x2OSJTlCjbWwkHpaJxXQXSpS4ah6Si8daFlI5G8j0ViE/XdKh8M9MV7F6INu+0U3ouKs1UIP5Ydy9WM5GjE/ThjODjznaKA/gxKj5ew7FeY7vg2w86OIY4FJXPWHnNh88F3/Z3YtCQer0HeudXZRQPeE6FQ6Gt0WheytnLiS34W/Cp8lHGpPQEctRfdTEiNOWaeKMOQUNKrSctTxP7ZqHDUnRaap8zjghlQJHrR3J41yGG8KF0HNf1gc9GKbRz6tdiVbClNOY53JGLeL00zeaF3mt/LvIS+oXsVCzsU/K8v1yBhpQAmkJDOdfpa59wlFHsE8XC7unZ+7gU9qJrv1coOqtiSKidirKjmEo5T4vboiFMr6KcaQoV6RReEfpOm4W0vSpew8Kh0Y6oMyU1PIqyY79l+gJiQOiKHxFeARb7ZOPq67LwZrpTlzeQs6uxVvio5kHhvf8uIV3fEuV/4lchlZ4VjhKm8EdGH65jxeA/l4Zhpr12p/0AAAAASUVORK5CYII=" /></voucher--close>
            <voucher--header>Validate</voucher--header>
            <p class="validate--code">
                Indica el <b>c&oacute;digo & electr&oacute;nico</b> y c$oacute;digo de evento para validar.
            </p>
            <payment--voucher-body>
                <form name="validaTicketForm" ng-submit="validateCode()">
                    <label class="your-email">
                        <input type="email" name="email" ng-model="fields.validaName" placeholder="Email">
                    </label>
                    <label class="ticket-code">
                        <input type="text" name="ticket" ng-model="fields.ticket" placeholder="YOUT TICKET CODE">
                    </label>
                    <button type="submit" class="validate-ticket">VALIDAR</button>
                    <!-- <button type   ="button" class="cancel-btn validate-ticket" ng-click="hideValidatePayment()">CANCELAR</button> -->
                </form>
            </payment--voucher-body>
        </payment--voucher-container>
    </event--payment-voucher>
</event>
<!-- DEV -->
<!-- <script src="https://www.paypal.com/sdk/js?client-id=ASUoLbHhh5qWjRWNvQQHJeU75ojb6ahEpDk2qU0SHhAArDW6nv_iRJwNYmgx7-3Pw_UrxbKaj8CaRjgj">  -->
<script src="https://www.paypal.com/sdk/js?client-id=AUQnWex8eSWf5Z1M2VCDqyqmX9q4NbPd1vuBPWdNccf2kKf5f5vpmRGYJLLajRkxtIc4F7SJUlM9VcGZ">
    // Required. Replace SB_CLIENT_ID with your sandbox client ID.
</script>
<script>
    document.oncontextmenu = document.body.oncontextmenu = function() {
        return false;
    }
</script>
<script type="text/javascript">
    let useWatchFromAntifraud = 'YES';
    try {
        useWatchFromAntifraud = '<?= get_field("useWatchFromAntifraud") ?>'.split(":")[1]
    } catch (e) {}
    // 		__atatusNotify(new Error("WC TEST"));
    let invoiceID = getInvoiceID();
    let postID = <?= the_ID() ?>;
    let description = '<?= esc_attr(the_field('event_title')) ?>';
    var app = angular.module('wcEvent', ['ngResource']);

    function home_url(url) {
        return `<?= home_url() ?>${url}`;
    }

    app

        .constant("EVENT_CONF", {

            EVENT_INFORMATION: "EVENT_INFORMATION",

            EVENT_ORGANIZER: "EVENT_ORGANIZER",

            EVENT_SPEAKERS: "EVENT_SPEAKERS",

            VOUCHER: "VOUCHER",

            baseUrl: "",

            registerPaypalPayment: home_url("/wp-json/wctvApi/v1/registerPayment"), // ""dmin_url('admin-ajax.php')?>"

            validateTicket: home_url("/wp-json/wctvApi/v1/validateTicket"), // ""dmin_url('admin-ajax.php')?>"
            watchFrom: home_url("/wp-json/wctvApi/v1/watchFrom"), // ""dmin_url('admin-ajax.php')?>"
            isSoldOut: home_url("/wp-json/wctvApi/v1/isSoldOut"), // ""dmin_url('admin-ajax.php')?>"

        })

        .factory("eventResource", ["$resource", "EVENT_CONF", function($resource, EVENT_CONF) {

            return $resource(EVENT_CONF.baseUrl, {}, {

                registerPaypalPayment: {

                    url: EVENT_CONF.registerPaypalPayment,

                    method: 'post',

                    isArray: true

                },

                validateTicket: {

                    url: EVENT_CONF.validateTicket,

                    method: 'post',

                    isObject: true

                },

                watchFrom: {

                    url: EVENT_CONF.watchFrom,

                    method: 'get',

                    isObject: true

                },
                isSoldOut: {

                    url: EVENT_CONF.isSoldOut,

                    method: 'get',

                    isObject: true

                }

            });

        }])

        .controller("eventController", [

            "$scope",

            "eventResource",

            "EVENT_CONF",

            "$window",

            "$sce",

            "$interval",

            function($scope, $eventResource, EVENT_CONF, $window, $sce, $interval) {

                try {
                    $scope.fields = {
                        eventPrice: Number(<?= sprintf('%0.2f', get_field("event_price")) ?> || 0),
                        eventQty: 1,
                        currencyCode: 'US$',
                        emails: []
                    };

                    $scope.fields.activeTab = EVENT_CONF.EVENT_INFORMATION;
                    $scope.EVENT_INFORMATION = EVENT_CONF.EVENT_INFORMATION;
                    $scope.EVENT_ORGANIZER = EVENT_CONF.EVENT_ORGANIZER;
                    $scope.EVENT_SPEAKERS = EVENT_CONF.EVENT_SPEAKERS;
                    $scope.VOUCHER = EVENT_CONF.VOUCHER;
                    $scope.orderData;

                    $scope.fields.showPaymentVoucher = false;
                    $scope.fields.showValidateModal = false;
                    $scope.fields.paymentBtns = false;
                    $scope.hidePaymentVoucher = hidePaymentVoucher;

                    $scope.validateCode = validateCode;
                    $scope.showValidateModal = showValidateModal;
                    $scope.hideValidatePayment = hideValidatePayment;
                    $scope.validateFromVoucher = validateFromVoucher;
                    $scope.showLoadingVeil = showLoadingVeil;
                    $scope.hideLoadingVeil = hideLoadingVeil;
                    $scope.checkConn = checkConn;
                    $scope.addEmail = addEmail;
                    $scope.deleteEmail = deleteEmail;

                    try {
                        $scope.eventEndDate = new Date('<?= the_field("end_date"); ?>');
                        $scope.currentDate = new Date();

                        $scope.hidePaymentBlock = $scope.eventEndDate < $scope.currentDate;
                        $eventResource.isSoldOut({
                            eventID: postID
                        }, function(solOutResponse) {
                            $scope.isSoldOUT = solOutResponse.status;
                        });


                        $scope.soldOutInterval = $interval(checkSoldOut, 5000);

                    } catch (e) {}

                    let t = "<?= isset($_GET["t"]) ? $_GET["t"] : false ?>";
                    let e = "<?= isset($_GET["e"]) ? $_GET["e"] : false ?>";

                    if (t && e) {
                        $scope.fields.ticket = t;
                        $scope.fields.validaName = e;
                        validateFromVoucher();
                    }


                    createOrder();
                } catch (e) {
                    __atatusNotify(e);
                }

                function deleteEmail(index) {
                    $scope.fields.emails = $scope.fields.emails.filter((item, indx) => {
                        return indx !== index;
                    })
                }

                function addEmail() {
                    if ($scope.addEmailForm.$valid) {

                        let __email = ''; // angular.copy($scope.email);
                        let __name = angular.copy($scope.name);

                        $scope.fields.emails.push({
                            name: __name,
                            email: __email
                        });
                        $scope.email = '';
                        $scope.name = '';
                        jQuery('[focus-on-submit]').focus();
                    }
                }

                function checkSoldOut() {
                    $eventResource.isSoldOut({
                        eventID: postID
                    }, function(solOutResponse) {
                        $scope.isSoldOUT = solOutResponse.status;

                        if ($scope.isSoldOUT && $scope.soldOutInterval) {
                            $interval.cancel($scope.soldOutInterval);
                        }
                    })
                }

                function createOrder() {

                    $scope.fields.paymentBtns = true;

                    let paypalBtns = (paypal && paypal.Buttons({
                        // upgradeLSAT: true,
                        // onInit is called when the button first renders
                        onError: function(err) {
                            // Show an error page here, when an error occurs

                            let e = err;
                            if (angular.isString(err) == false) {
                                e = JSON.stringify(err);
                            }
                            __atatusNotify(new Error("paypal.Buttons: " + [invoiceID, e].join("-")));
                        },
                        onInit: function(data, actions) {

                            // Listen for changes to the checkbox
                            $scope.$watch("fields", function() {
                                if ($scope.fields.selectSponsor &&
                                    $scope.fields.email &&
                                    $scope.fields.confEmail &&
                                    $scope.fields.confEmail.toLowerCase() == $scope.fields.email.toLowerCase() &&
                                    $scope.fields.refundPolicy &&
                                    $scope.isSoldOUT == false) {
                                    actions.enable();
                                } else {
                                    actions.disable();
                                }
                            }, true);
                        },

                        onClick: function() {

                            try {
                                const {
                                    confEmail,
                                    selectSponsor,
                                    refundPolicy,
                                    isSoldOUT,
                                    eventQty,
                                    emails
                                } = $scope.fields;
                                const emailsQty = emails.length;

                                if (!$scope.fields.email) {
                                    alert("Debes indicar tu correo electronico para continuar");
                                    return;
                                }

                                if (!$scope.fields.confEmail) {
                                    alert("Debes indicar tu correo electronico para continuar");
                                    return;
                                }

                                if ($scope.fields.email.toLowerCase() != $scope.fields.confEmail.toLowerCase()) {
                                    alert("El correo y la confirmacion no coincide.");
                                    return;
                                }

                                if (!$scope.fields.selectSponsor) {
                                    alert("Debes seleccionar un sponsor para continuar..");
                                    return false;
                                }

                                if (!$scope.fields.refundPolicy) {
                                    alert("Debes aceptar las políticas de privacidad para continuar.");
                                    return false;
                                }

                                if ($scope.isSoldOUT) {
                                    alert("Evento Agotado");
                                    return false;
                                }

                                if (eventQty > 1) {

                                    if (emailsQty < eventQty) {
                                        alert(`Debes especificar ${eventQty - emailsQty} nombre para continuar.`);
                                        return false;
                                    }

                                    if (emailsQty > eventQty) {
                                        alert(`Has especificado ${emailQemailsQtyty - eventQty} de mas.`);
                                        return false;
                                    }

                                }
                            } catch (e) {
                                alert(e);
                                return false;
                            }

                        },
                        // onShippingChange: function(data,actions){
                        //     return actions.resolve();
                        // },
                        createOrder: function(data, actions) {

                            if (!$scope.fields.selectSponsor ||
                                !$scope.fields.email ||
                                !$scope.fields.refundPolicy /*|| $scope.fields.isfisicalEvent == null*/ ) {
                                return false;
                            }

                            // This function sets up the details of the transaction, including the amount and line item details.
                            //invoiceID = getInvoiceID();

                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: $scope.fields.eventQty * $scope.fields.eventPrice,
                                        currency: $scope.fields.currencyCode
                                    },
                                    description: description, // Maximum length: 127.
                                    invoice_id: invoiceID
                                }]
                            });

                        },

                        onApprove: function(data, actions) {

                            $scope.orderData = data;
                            showLoadingVeil();
                            return actions.order.capture().then(function(details) {
                                try {
                                    const {
                                        payment,
                                        purchase_units
                                    } = details;
                                    let {
                                        amount,
                                        description,
                                        payee,
                                        payments,
                                        shipping
                                    } = purchase_units[0];
                                    let name = "";

                                    try {
                                        name = [details.payer.name.given_name, details.payer.name.surname].join(" ");
                                    } catch (e) {}

                                    let params = {
                                        action: 'register_paypal_payment',
                                        post_id: postID,
                                        invoice_id: invoiceID,
                                        description: description,
                                        create_time: details.create_time,
                                        update_time: details.update_time,
                                        status: details.status,
                                        amoun_currency_code: amount.currency_code,
                                        amoun_currency_value: amount.value,
                                        email_address: payee.email_address,
                                        merchant_id: payee.merchant_id,
                                        payee_email_address: payee.email_address,
                                        payee_merchant_id: payee.merchant_id,
                                        payments_amount: payments.captures[0].amount.value,
                                        payments_curency: payments.captures[0].amount.currency_code,
                                        payments_id: payments.captures[0].id,
                                        payment_status: payments.captures[0].status,
                                        payment_update_time: payments.captures[0].update_time,
                                        shipping_address_address_line_1: shipping.address.address_line_1,
                                        shipping_address_admin_area_1: shipping.address.admin_area_1,
                                        shipping_address_admin_area_2: shipping.address.admin_area_2,
                                        shipping_address_country_code: shipping.address.country_code,
                                        shipping_address_postal_code: shipping.address.postal_code,
                                        shipping_name_full_name: shipping.name.full_name,
                                        payment_contract: JSON.stringify({
                                            paymentDetails: details,
                                            orderDetails: $scope.orderData
                                        }),
                                        payment_contract_hash: "",
                                        created_at: new Date(),
                                        invoiceID: invoiceID,
                                        sponsor: $scope.fields.selectSponsor,
                                        paypal_order_id: $scope.orderData.orderID,
                                        ticket_email: $scope.fields.email,
                                        payee_name: (name.trim() || $scope.fields.email),
                                        ticketNames: $scope.fields.emails,
                                        eventQty: $scope.fields.eventQty,
                                        eventPrice: $scope.fields.eventPrice
                                        // isfisicalEvent : $scope.fields.isfisicalEvent
                                    }

                                    $eventResource.registerPaypalPayment(params, function(paymentResponse) {

                                        // This function shows a transaction success message to your buyer.

                                        // alert('Transaction completed by ' + details.payer.name.given_name);
                                        debugger
                                        if (paymentResponse.length == 1) {
                                            $scope.fields.ticket = paymentResponse[0].msg.event_ticket;
                                            $scope.fields.validaName = paymentResponse[0].msg.ticket_email;
                                            showPaymentVoucher(paymentResponse);
                                        } else {
                                            showPaymentVoucher(paymentResponse);
                                        }
                                        hideLoadingVeil();
                                    }, function(error) {
                                        debugger
                                        hideLoadingVeil();
                                        alert("Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820");
                                        __atatusNotify(new Error("registerPaymentError: " + JSON.stringify(error)));
                                    });

                                } catch (e) {
                                    hideLoadingVeil();
                                    __atatusNotify(e);
                                }



                            }, function(error) {
                                hideLoadingVeil();
                                __atatusNotify(error);
                                alert("PAYPAL ERROR: no fue posible procesar su pago debido a que paypal retornó un error, intente nuevamente");

                            });

                        }

                    }).render('#paypal-button-container'));

                    console.dir(paypalBtns);

                    return paypalBtns;

                }

                function validateFromVoucher() {

                    $scope.fields.showValidateModal = false;

                    $scope.fields.showPaymentVoucher = false;

                    validateCode();

                }

                function showPaymentVoucher(paymentResponse) {

                    $scope.fields.showValidateModal = false;

                    $scope.fields.showPaymentVoucher = true;

                    $scope.fields.paymentResponse = paymentResponse;

                }

                function hidePaymentVoucher() {

                    $scope.fields.showPaymentVoucher = false;

                    $window.location.reload();

                }

                function showValidateModal() {

                    $scope.fields.showPaymentVoucher = false;

                    $scope.fields.showValidateModal = true;

                }

                function hideValidatePayment() {

                    $scope.fields.showValidateModal = false;

                }

                function validateCode() {



                    if (!$scope.fields.ticket || !$scope.fields.validaName) {

                        alert("Email and Ticket Code are required");

                        return;

                    }



                    $eventResource.validateTicket({

                        code: $scope.fields.ticket,

                        email: $scope.fields.validaName,

                        eventId: "<?= the_ID() ?>"

                    }, function(onSuccess) {

                        hideValidatePayment();

                        $scope.fields.ticketIdValid = true;

                        $scope.fields.activeTab = 'STREAM';

                        $scope.ticketValidated = onSuccess;
                        $scope.fields.frameContent = $sce.trustAsHtml(onSuccess.source);

                        reloadAudioPlayer();

                    }, function(error) {

                        alert("ERROR VALIDANDO CREDENCIALES PARA STREAM");

                    });

                }

                function reloadAudioPlayer() {
                    try {
                        setTimeout(function() {
                            __CI_AUDIOIGNITER_MANUAL_INIT__(document.getElementsByClassName('audioigniter-root')[0])
                        }, 300);
                    } catch (e) {}
                }

                function checkConn() {
                    if (useWatchFromAntifraud == "NO") {
                        return false;
                    }
                    $scope.checkConnInterval = $interval(() => {
                        $eventResource.watchFrom({
                                tickedCodeID: $scope.ticketValidated.ticket.id,
                                tickedCode: $scope.ticketValidated.ticket.last_event_ticket_code
                            },
                            angular.noop,
                            function(onError) {
                                try {
                                    if (onError.data.code == "MISS_MATCH_IP") {
                                        alert("Otro dispositivo se ha conectado con este numero de ticket. Cierre esta ventada si no desea seguir mirando en este dispositivo.");
                                        $window.location = $window.location.href.split("?")[0];
                                        $interval.cancel($scope.checkConnInterval);
                                    }
                                } catch (e) {}
                            })
                    }, 60000);
                }

                function showLoadingVeil() {
                    $scope.fields.showLoading = true;
                }

                function hideLoadingVeil() {
                    $scope.fields.showLoading = false;
                }

            }

        ]);
</script>

<?php get_footer(); ?>