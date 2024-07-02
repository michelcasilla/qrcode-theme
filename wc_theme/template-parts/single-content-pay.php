<?php
$post = get_post();
$eventId = $post->ID;
$content = query_url_content();
$gate = new AmwayGateWay();

$freePayments = VTW_Event_Payment::getFreePaymentsByIbo($content->ibo, $eventId);
$remainingFree = FREE_PAYMENT_PER_EVENT - $freePayments->total_tickets;
$iboInfo = $gate->verifyIBO($content->ibo, $eventId);

$approveUrl = add_query_arg(
    array("payment_step" => "INVOICE"),
    full_url()
);

$eventApplyForFree = get_field('apply_for_trial');
$APPLY_FOR_FREE = $eventApplyForFree == true && ($remainingFree > 0 && $iboInfo->code === "VALID");
$GATE = get_field("payment_gate");
?>
<script>
    const PRICE = <?= get_field("event_price") ?>;
    const $freeTickets = <?= $APPLY_FOR_FREE ? 1 : 0 ?>;
    const description = "<?= the_title() ?>";
    let stripeInitialized;

    function home_url(url) {
        return `<?= home_url() ?>${url}`;
    }

    const paypalProcess = () => {
        updateAmount(PRICE, $freeTickets);
        doPayment()
    }

    const azulProcess = () => {
        updateAmount(PRICE, $freeTickets);
        doPayment()
    }

    function updatePriceTicketQTY() {
        updateAmount(PRICE, $freeTickets);
        hideStripePrimaryPayment();
    }


    const doPayment = async (event) => {
        const currency = ("DO" == "<?= get_field('country') ?>") ? "RD$" : "US$";
        const GATE = "<?= $GATE ?>";
        const EVENTID = <?= $eventId ?>;
        const CURRENT_URL = "<?= full_url() ?>";
        const cancelUrl = "<?= full_url() ?>";
        const {
            tickets,
            qtyTickets,
            errors
        } = validateStepPaymentForm();
        if (errors.length) {
            return;
        }
        const infos = decodeBase64UrlParams(location.href);
        let approveUrl = encodeBase64UrlParams("<?= $approveUrl ?>", {
            ...infos,
            tickets,
            qtyTickets
        });
        const paymentData = {
            eventId: EVENTID,
            email: infos.email,
            confEmail: infos.emailConfirmation,
            refundPolicy: infos.acceptTermsOfService,
            eventQty: qtyTickets,
            emails: tickets.map(x => {
                return {
                    name: x,
                    email: x
                }
            }),
            invoiceId: getInvoiceID(EVENTID),
            currency: currency,
            amount: PRICE,
            selectSponsor: infos.sponsor,
            ibo: infos.ibo,
            statementDescriptor: description.replace(/\W/gi, ' ').replace('  ', '')
        }

        if ($freeTickets > 0) {
            handleFreeTierPayment(paymentData, approveUrl, DrawVoucher);
            return false;
        }

        switch (GATE) {
            case "AZUL":
                showLoading();
                paymentData.approveUrl = approveUrl;
                paymentData.cancelUrl = cancelUrl;
                paymentData.declineUrl = approveUrl;
                await processWithAzul(paymentData, () => {
                    hideLoading();
                });
                break;
            case "STRIPE":
                showLoading();
                showStripePrimaryPayment();
                setTimeout(async () => {
                    if (!stripeInitialized) {
                        stripeInitialized = await initialize("#payment-element", paymentData);
                    }
                    paymentData.currency = currency;

                    const updateStatus = await updatePaymentIntent(paymentData, stripeInitialized);
                }, 100);
                hideLoading();
                break;
            case "PAYPAL":
                const onSuccess = (data) => {
                    hideLoading();
                    sessionStorage.setItem('PAYMENT', JSON.stringify(data));
                    location.href = approveUrl;
                };

                const validation = () => {
                    const {
                        errors
                    } = validateStepPaymentForm()
                    return errors.length ? false : true; // if error the the validation fails
                }

                const getData = () => {
                    return paymentData;
                };

                await processWithPaypal(getData, validation, onSuccess);
                break;
        }
    }

    const showStripePrimaryPayment = () => {
        const primaryButtons = document.querySelectorAll('[do-payment-button]')[0];
        const secondaryButtons = document.querySelectorAll('[secondary-payment-button]')[0];
        const stripeContainer = document.querySelectorAll('#payment-element')[0];
        stripeContainer.style.display = "block";
        primaryButtons.style.display = "block";
        secondaryButtons.style.display = "none";
    }

    const hideStripePrimaryPayment = () => {
        try {
            const primaryButtons = document.querySelectorAll('[do-payment-button]')[0];
            const secondaryButtons = document.querySelectorAll('[secondary-payment-button]')[0];
            const stripeContainer = document.querySelectorAll('#payment-element')[0];
            stripeContainer.style.display = "none";
            primaryButtons.style.display = "none";
            secondaryButtons.style.display = "block";
        } catch (e) {}
    }

    function getInvoiceID(eventID) {
        return [
            eventID,
            Math.random().toString(36).substr(2, 9)
        ].join("-").toUpperCase();
    }

    async function STRIPESubmitHandler(event, approveUrl) {
        await doPayment()
        await STRIPE_handleSubmit(event, approveUrl);
    }

    function createDetails(paymentData) {
        const currency = paymentData.currency;
        const tickets = paymentData.emails;
        const eventId = paymentData.eventId;
        const amount = paymentData.amount;
        const eventQty = paymentData.eventQty;
        const invoiceId = paymentData.invoiceId;
        const description = paymentData.statementDescriptor;

        return {
            id: "<?= wp_generate_uuid4() ?>",
            amount: amount,
            created: new Date(),
            currency: currency,
            status: "succeeded",
            description: description,
            ibo: "<?= $content->ibo ?>",
            apply_for_free: $freeTickets ? true : false,
            EVENTID: eventId,
            charges: {
                data: [{
                    customer: "<?= $content->name ?>",
                    receipt_email: "<?= $content->email ?>",
                    status: "succeeded",
                    create: new Date(),
                    amount: {
                        currency: currency,
                        value: 1000
                    },
                    billing_details: {
                        name: "<?= $content->name ?>",
                        phone: "NA",
                        email: "<?= $content->email ?>",
                        address: {
                            city: "NA",
                            state: "NA",
                            line1: "NA",
                            line2: "NA",
                            country: "<?= $iboInfo->data->contry_code ?>",
                            postal_code: "NA"
                        }
                    }
                }]
            },
            tickets,
            metadata: {
                eventQty: eventQty,
                invoiceID: invoiceId,
                selectSponsor: "<?= $content->sponsor ?>",
                amount: amount,
                ...(tickets.reduce((acc, x, i) => {
                    acc[`email_${i}`] = x.email;
                    return acc;
                }, {}))
            }
        };
    }

    async function handleFreeTierPayment(paymentData, approveUrl, callback) {
        showLoading();
        try {
            const details = createDetails(paymentData);
            const $scope = details;
            const {
                id,
                charges: {
                    data
                },
                amount,
                created,
                currency,
                metadata
            } = details;
            let createdAt = new Date(created * 1000);
            metadata.emails = [];
            for (let i = 0; i < Number(metadata.eventQty); i++) {
                const ticket = {
                    name: metadata[`email_${i}`],
                    email: ''
                }
                metadata.emails.push(ticket);
            }
            metadata.showValidateModal = false;
            $scope.fields = metadata;
            const charge = data[0];
            const {
                billing_details
            } = charge;
            const name = [billing_details.name, billing_details.phone].join(" ");
            const email = (charge.receipt_email || $scope.fields.email);
            const status = details.status == 'succeeded' ? 'COMPLETED' : details.status.toUpperCase()
            const __id = String(id).toUpperCase();
            const parsedAmount = Number(amount);
            let params = {
                amount: amount,
                price: amount,
                apply_for_free: details.apply_for_free,
                ibo: details.ibo,
                action: 'register_stripe_payment',
                post_id: details.EVENTID,
                invoice_id: metadata.invoiceID,
                invoiceId: metadata.invoiceID,
                description: details.description,
                create_time: createdAt,
                update_time: createdAt,
                status: status,
                amoun_currency_code: String(currency).toUpperCase(),
                currency: String(currency).toUpperCase(),
                amoun_currency_value: parsedAmount,
                email_address: email,
                merchant_id: (charge.customer || __id),
                payee_email_address: (billing_details.email || metadata.email),
                payee_merchant_id: (charge.customer || __id),
                payments_amount: parsedAmount,
                payments_curency: charge.amount.currency,
                payments_id: __id,
                payment_status: charge.status,
                payment_update_time: charge.created,
                shipping_address_address_line_1: `city:${(billing_details.address.city || "")},state:${(billing_details.address.state || "")},`,
                shipping_address_admin_area_1: billing_details.address.line1,
                shipping_address_admin_area_2: billing_details.address.line2,
                shipping_address_country_code: billing_details.address.country,
                shipping_address_postal_code: billing_details.address.postal_code,
                shipping_name_full_name: "",
                payment_contract: JSON.stringify({
                    paymentDetails: details.details,
                    orderDetails: $scope.orderData
                }),
                payment_contract_hash: "",
                created_at: new Date(),
                invoiceID: metadata.invoiceID,
                sponsor: metadata.selectSponsor,
                paypal_order_id: __id,
                ticket_email: email,
                payee_name: (name.trim() ? name : email),
                ticketNames: metadata.emails,
                eventQty: Number(metadata.eventQty),
                eventPrice: Number(metadata.amount),
                details: {
                    ...details,
                    charges: {
                        data: {
                            AzulOrderId: details.id,
                            status: "succeeded",
                        }
                    }
                },

            }

            let paymentResponse = await fetch(home_url("/wp-json/wctvApi/v1/registerPayment"), {
                method: "POST",
                body: JSON.stringify(params),
                headers: {
                    'Content-Type': 'application/json'
                },
            });
            paymentResponse = await paymentResponse.json();
            const INVOICE = {
                ...params,
                ...paymentResponse
            };
            INVOICE.tickets = paymentResponse;
            sessionStorage.setItem("INVOICE", JSON.stringify(INVOICE));
            if (Array.isArray(paymentResponse)) {
                const localApprobeUrl = "<?= add_query_arg(array(
                                                "IsoCode" => "00",
                                                "paymentId" => "00",
                                                "applyForFree" => $APPLY_FOR_FREE,
                                            ), $approveUrl) ?>";
                window.location = (localApprobeUrl || location.href);
            } else {
                alert(`Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820"`);
            }

        } catch (e) {
            alert(e);
        }
        hideLoading();
    }

    sessionStorage.removeItem("PAYMENT");
</script>

<section class="content">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb--default">
                <li class="breadcrumb-item"><a href="/"><i class="bi bi-house"></i><?= _("Home") ?></a></li>
                <li class="breadcrumb-item" aria-current="page">
                    <a href="<?= get_permalink() ?>">
                        <?= get_field('event_title') ?>
                    </a>
                </li>
                <li class="breadcrumb-item active"><?= _("Pago") ?></li>
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
                <div class="steps steps--two">
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
                <?= show_buy_after_sold_out_msg(); ?>
                <div class="form-titles">
                    <span class="form-title"><?= __("Información de pago", "sp") ?></span>
                    <span class="form-subtitle"><?= __("Favor completar los campos a continuación", 'sp') ?></span>
                </div>
                <div class="form-input form-input--col">
                    <div>
                        <label for="#"><?= __("Cantidad", "sp") ?></label>
                        <input type="number" name="qtyticket" value="1" min="1" <?= $APPLY_FOR_FREE ? 'max="' . $remainingFree . '"' : '' ?> onchange="updatePriceTicketQTY()">
                    </div>
                    <div>
                        <span class="form-input__label" data-subtotal>
                            $<?= sprintf('%0.2f', get_field("event_price")) ?>
                        </span>
                    </div>
                </div>
                <div ticket-names>
                    <div class="form-input" data-ticket>
                        <label for="#"><?= __("Nombre Acceso 1", "sp") ?></label>
                        <input type="text" placeholder="Nombre completo" data-ticket-name onchange="updatePriceTicketQTY()">
                    </div>
                </div>
                <?php if ($APPLY_FOR_FREE) : ?>
                    <div class="form-actions apply-for-free-box apply-for-free-box-<?= $APPLY_FOR_FREE ? "-show" : "-hide" ?>">
                        <h1>¡Tu evento está dentro de un período cortesía!</h1>
                        <p>
                            <b>¡Hola <?= $iboInfo->data->account_name ?>!</b>
                            Nos complace informarte que tu evento actual se encuentra dentro de un período de cortesía,
                            por lo que no se requiere ningún pago en este momento.
                            Queremos que disfrutes al máximo de tu experiencia con nosotros. <br> <br>
                            <i class="small">Máximo <?= $remainingFree ?> tickets de cortesía en esta compra</i>
                        </p>
                    </div>
                <?php endif; ?>
                <?php
                $backurl = add_query_arg(
                    array("payment_step" => "INFO"),
                    full_url()
                );
                ?>
                <?php if ($GATE == "STRIPE") : ?>
                    <br>
                    <form id="payment-form">
                        <div id="payment-element"></div>
                        <div class="form-actions form-actions-spaced">
                            <a href="<?= $backurl ?>" class="form-btn form-btn-link">
                                &larr; <?= __("Regresar", "sp") ?>
                            </a>
                            <button id="submit" class="form-btn" do-payment-button style="display:none" onclick="STRIPESubmitHandler(event, '<?= $approveUrl ?>')">
                                <div class="spinner hidden" id="spinner"></div>
                                <span id="button-text"><?= __("Pay now", 'sp') ?></span>
                            </button>
                            <a onclick="doPayment()" class="form-btn" secondary-payment-button>
                                <?= __("Continuar", "sp") ?>
                            </a>
                        </div>
                        <div id="payment-message" class="hidden"></div>
                    </form>
                <?php endif; ?>
                <?php if ($GATE == "AZUL") : ?>
                    <div class="form-actions form-actions-spaced">
                        <a href="<?= $backurl ?>" class="form-btn form-btn-link">
                            &larr; <?= __("Regresar", "sp") ?>
                        </a>
                        <a onclick="azulProcess()" class="form-btn">
                            <?= __("Pagar", "sp") ?>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($GATE == "PAYPAL") : ?>
                    <!-- DEV -->
                    <br>
                    <!-- <script src="https://www.paypal.com/sdk/js?client-id=ASUoLbHhh5qWjRWNvQQHJeU75ojb6ahEpDk2qU0SHhAArDW6nv_iRJwNYmgx7-3Pw_UrxbKaj8CaRjgj"></script> -->
                    <!-- PROD -->
                    <script src="https://www.paypal.com/sdk/js?client-id=AUQnWex8eSWf5Z1M2VCDqyqmX9q4NbPd1vuBPWdNccf2kKf5f5vpmRGYJLLajRkxtIc4F7SJUlM9VcGZ"></script>
                    <div id="paypal-button-container"></div>
                    <div class="form-actions form-actions-spaced">
                        <a href="<?= $backurl ?>" class="form-btn form-btn-link">
                            &larr; <?= __("Regresar", "sp") ?>
                        </a>
                        <a onclick="paypalProcess()" class="form-btn pay-btn-paypal">
                            <?= __("Continue", "sp") ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <?= get_template_part('template-parts/loading') ?>
            <?= show_sold_out_veil(); ?>
        </div>
    </div>
</section>
<script>
    checkSoldOut(<?= get_the_ID() ?>)
</script>