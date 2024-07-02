<?php
require_once(TEMPLATEPATH . "/admin/sponsorts.php");
?>
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
                <li class="breadcrumb-item active"><?= _("Información") ?></li>
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
                                <address><?= render_iframe_from_url(get_field('eventlocation')) ?></address>
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
            <form class="ticket-form">
                <!-- .steps--two or .steps--three -->
                <!-- <input type="hidden" name="payment_step" value="PAY"> -->
                <div class="steps">
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
                    <span class="form-title"><?= __("Información personal", 'sp') ?></span>
                    <span class="form-subtitle"><?= __("Favor completar los campos a continuación", "sp") ?></span>
                </div>
                <div class="form-input">
                    <label for="#"><?= __("IBO#", "sp") ?></label>
                    <input type="text" name="ibo" id="ibo" placeholder="<?= __("00000000", "sp") ?>" required onblur="validateIBO()">
                    <span class="error ibo-error text-danger small margin-top"></span>
                </div>
                <div class="form-input">
                    <label for="#"><?= __("Nombre completo", "sp") ?></label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-input">
                    <label for="#"><?= __("Correo electrónico", "sp") ?></label>
                    <input type="email" name="email" placeholder="<?= __("Ex.: minombre@espacio.com", "sp") ?>" required>
                </div>
                <div class="form-input">
                    <label for="#"><?= __("Confirmar Correo electrónico", "sp") ?></label>
                    <input type="email" name="email_confirmation" placeholder="<?= __("Ex.: minombre@espacio.com", "sp") ?>" required>
                </div>
                <div class="form-input">
                    <label for="#"><?= __("Selecciona tu Esmeralda / Diamante / Platino", "sp") ?></label>
                    <select name="sponsor" required>
                        <?= getSponsorsOptionGroup() ?>
                    </select>
                </div>
                <div class="form-input form-input--check">
                    <input type="checkbox" name="accept_terms_of_service" required style="width:auto !important">
                    <span><?= __("Acepto los", "sp") ?> <a href="<?= get_permalink() ?>#TERMS"><?= __("términos y condiciones", "sp") ?></a></span>
                </div>
                <?php
                $backurl = add_query_arg(array(), get_permalink());
                $actionUrl = add_query_arg(array("payment_step" => "PAY"), full_url());
                ?>
                <div class="form-actions form-actions-spaced">
                    <a href="<?= $backurl ?>" class="form-btn form-btn-link">
                        &larr; <?= __("Regresar", "sp") ?>
                    </a>
                    <a onclick="_validateForm({url: '<?= $actionUrl ?>'})" class="form-btn">
                        <?= __("Siguiente", "sp") ?>
                    </a>
                </div>
            </form>
            <?= get_template_part('template-parts/loading') ?>
            <?= show_sold_out_veil(); ?>
        </div>
    </div>
</section>
<script>
    function home_url(url) {
        return `<?= home_url() ?>${url}`;
    }
    const validateIBO = async () => {
        // showLoading();
        let responseCode = 'EXPIRED';
        let iboName = '';
        jQuery('.ibo-error').text('');
        const nameField = jQuery('#name');
        try {
            // clean the label name when searching again
            nameField.prop('disabled', true);
            // nameField.val("");
            // get the ibo number the user types
            const ibo = jQuery('#ibo').val().trim();
            // check if the ibo number is valid
            if (ibo.length < 3) {
                jQuery('.ibo-error').text("<?= __("Please Provide a valid IBO#", 'sp') ?>");
                return false;
            }
            // get the event id
            const eventID = <?= the_ID() ?>;
            // get the account name of the ibo number
            const payload = {}
            payload.ibo = ibo;
            payload.eventID = eventID;
            const response = await jQuery.post(home_url('/wp-json/wctvApi/v1/verifyIBO'), payload);
            responseCode = response.code;
            // debugger
            if (responseCode === 'IBO_NOT_FOUND') {
                jQuery('.ibo-error').text("<?= __("Please Provide a valid IBO#", 'sp') ?>");
                nameField.val("");
                return false;
            } else {
                iboName = response?.data?.account_name;
                nameField.val(response?.data?.account_name);
                nameField.removeAttr('disabled');
            }
        } catch (e) {
            console.error(e)
        } finally {
            nameField.removeAttr('disabled');
        }
        // hideLoading();
        debugger
        return iboName ? true : false;
    }


    async function _validateForm(params) {
        const response = await validateIBO();


        // Get the form element
        const form = document.querySelector('.ticket-form');

        // Check if the form is valid
        const isValid = form.checkValidity();

        if (isValid && response) {
            // The form is valid, you can submit it or perform other actions
            // console.log('Form is valid');
            goToPaymentStep(params)
        } else {
            // The form is not valid, display an error message or take appropriate action
            alert('Complete correctamente todos los campos del formulario');
        }

    }
    checkSoldOut(<?= get_the_ID() ?>)
    sessionStorage.removeItem("PAYMENT");
</script>