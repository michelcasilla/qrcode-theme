function home_url(url) {
    const base = window.location.origin;
    return `${base}/${url}`;
}
var app = angular.module('wcEvent', ['ngResource']);
app.constant("EVENT_CONF", {
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

    .factory("eventResource", ["$resource", "EVENT_CONF", function ($resource, EVENT_CONF) {
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
    .controller("StripeEventController", [
        "$scope",
        "eventResource",
        "EVENT_CONF",
        "$window",
        "$sce",
        "$interval",
        function ($scope, $eventResource, EVENT_CONF, $window, $sce, $interval) {

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
                    $scope.eventEndDate = new Date('<?=the_field("end_date"); ?>');
                    $scope.currentDate = new Date();

                    $scope.hidePaymentBlock = $scope.eventEndDate < $scope.currentDate;
                    $eventResource.isSoldOut({ eventID: postID }, function (solOutResponse) {
                        $scope.isSoldOUT = solOutResponse.status;
                    });


                    $scope.soldOutInterval = $interval(checkSoldOut, 5000);

                } catch (e) { }

                let t = "<?=isset($_GET["t"]) ? $_GET["t"] : false?>";
                let e = "<?=isset($_GET["e"]) ? $_GET["e"] : false?>";

                if (t && e) {
                    $scope.fields.ticket = t;
                    $scope.fields.validaName = e;
                    validateFromVoucher();
                }

                $scope.$watch("fields", function () {
                    if ($scope.fields.selectSponsor
                        && $scope.fields.email
                        && $scope.fields.confEmail
                        && $scope.fields.confEmail.toLowerCase() == $scope.fields.email.toLowerCase()
                        && $scope.fields.refundPolicy
                        && $scope.isSoldOUT == false) {
                        document.querySelector("#submit").disabled = false;
                    } else {
                        document.querySelector("#submit").disabled = true;
                    }
                }, true);

                // createOrder();
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

                    $scope.fields.emails.push({ name: __name, email: __email });
                    $scope.email = '';
                    $scope.name = '';
                    jQuery('[focus-on-submit]').focus();
                }
            }

            function checkSoldOut() {
                $eventResource.isSoldOut({ eventID: postID }, function (solOutResponse) {
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
                    onError: function (err) {
                        // Show an error page here, when an error occurs

                        let e = err;
                        if (angular.isString(err) == false) {
                            e = JSON.stringify(err);
                        }
                        __atatusNotify(new Error("paypal.Buttons: " + [invoiceID, e].join("-")));
                    },
                    onInit: function (data, actions) {

                        // Listen for changes to the checkbox
                        $scope.$watch("fields", function () {

                            if ($scope.fields.selectSponsor
                                && $scope.fields.email
                                && $scope.fields.confEmail
                                && $scope.fields.confEmail.toLowerCase() == $scope.fields.email.toLowerCase()
                                && $scope.fields.refundPolicy
                                && $scope.isSoldOUT == false) {
                                document.querySelector("#submit").disabled = false;
                            } else {
                                document.querySelector("#submit").disabled = true;
                            }
                        }, true);
                    },

                    onClick: function () {
                        try {
                            const { confEmail, selectSponsor, refundPolicy, isSoldOUT, eventQty, emails } = $scope.fields;
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
                    createOrder: function (data, actions) {

                        if (!$scope.fields.selectSponsor
                            || !$scope.fields.email
                            || !$scope.fields.refundPolicy /*|| $scope.fields.isfisicalEvent == null*/) {
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

                    onApprove: function (data, actions) {

                        $scope.orderData = data;
                        showLoadingVeil();
                        return actions.order.capture().then(function (details) {
                            try {
                                const { payment, purchase_units } = details;
                                let { amount, description, payee, payments, shipping } = purchase_units[0];
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
                                    payment_contract: JSON.stringify({ paymentDetails: details, orderDetails: $scope.orderData }),
                                    payment_contract_hash: "",
                                    created_at: new Date(),
                                    invoiceID: invoiceID,
                                    sponsor: $scope.fields.selectSponsor,
                                    paypal_order_id: $scope.orderData.orderID,
                                    ticket_email: $scope.fields.email,
                                    payee_name: [details.payer.name.given_name, details.payer.name.surname].join(" "),
                                    ticketNames: $scope.fields.emails,
                                    eventQty: $scope.fields.eventQty,
                                    eventPrice: $scope.fields.eventPrice
                                    // isfisicalEvent : $scope.fields.isfisicalEvent
                                }

                                $eventResource.registerPaypalPayment(params, function (paymentResponse) {

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
                                }, function (error) {
                                    debugger
                                    hideLoadingVeil();
                                    alert("Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820");
                                    __atatusNotify(new Error("registerPaymentError: " + JSON.stringify(error)));
                                });

                            } catch (e) {
                                hideLoadingVeil();
                                __atatusNotify(e);
                            }



                        }, function (error) {
                            hideLoadingVeil();
                            __atatusNotify(error);
                            alert("PAYPAL ERROR: no fue posible procesar su pago debido a que paypal retornó un error, intente nuevamente");

                        });

                    }

                }).render('#paypal-button-container'));

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

                    eventId: "<?=the_ID()?>"

                }, function (onSuccess) {

                    hideValidatePayment();

                    $scope.fields.ticketIdValid = true;

                    $scope.fields.activeTab = 'STREAM';

                    $scope.ticketValidated = onSuccess;
                    $scope.fields.frameContent = $sce.trustAsHtml(onSuccess.source);

                    reloadAudioPlayer();

                }, function (error) {

                    alert("ERROR VALIDANDO CREDENCIALES PARA STREAM");

                });

            }

            function reloadAudioPlayer() {
                try {
                    setTimeout(function () {
                        __CI_AUDIOIGNITER_MANUAL_INIT__(document.getElementsByClassName('audioigniter-root')[0])
                    }, 300);
                } catch (e) { }
            }

            function checkConn() {
                if (useWatchFromAntifraud == "NO") { return false; }
                $scope.checkConnInterval = $interval(() => {
                    $eventResource.watchFrom({
                        tickedCodeID: $scope.ticketValidated.ticket.id,
                        tickedCode: $scope.ticketValidated.ticket.last_event_ticket_code
                    },
                        angular.noop,
                        function (onError) {
                            try {
                                if (onError.data.code == "MISS_MATCH_IP") {
                                    alert("Otro dispositivo se ha conectado con este numero de ticket. Cierre esta ventada si no desea seguir mirando en este dispositivo.");
                                    $window.location = $window.location.href.split("?")[0];
                                    $interval.cancel($scope.checkConnInterval);
                                }
                            } catch (e) { }
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