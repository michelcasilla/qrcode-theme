<?php

include_once("EventPayment.php");
include_once("tables/EventPaymentListTable.php");
include_once("notification.class.php");

$eventPaymentInstance;

// $isAdmin = current_user_can('administrator');
// $isSuperUser = current_user_can('wc_super_user');
// $isRedeemer = current_user_can('wc_redem_tickets');

// Register settings using the Settings API
add_action('admin_menu', 'subscriptions_admin');

function subscriptions_admin()
{

    add_submenu_page(
        'paymentlist',
        __('Subscriptions'),
        __('Subscriptions'),
        'wc_subscriptions',
        'subscriptionspage',
        'showSubscriptions'
    );
}


function showSubscriptions()
{

    global $wpdb;

    $events = $wpdb->get_results("SELECT ep.description, ep.post_id 
        FROM {$wpdb->prefix}event_payment ep
        LEFT JOIN {$wpdb->prefix}postmeta pm ON pm.post_id = ep.id
        WHERE pm.meta_key = 'type' AND pm.meta_value = 'EVENT'
        GROUP BY description, post_id 
        ORDER BY description ASC");
?>
    <style>
        .ticket-redeame {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .ticket-redeame-field {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 80vw;
        }

        .ticket-redeame-field input {
            font-size: 16px;
            padding: 8px;
            border-radius: 8px;
            border: solid 1px #666;
            width: 100%;
            height: 70px;
        }

        .ticket-redeame-field button.btn-submit {
            height: 70px
        }

        .ticket-redeame-list {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 50vw;
            margin: auto;
            clear: both;
        }

        .ticket-redeame-list .ticket {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;

        }

        .ticket--eventname {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
        }

        .ticket--code {
            min-width: 150px;
            text-align: right;
        }

        .ticket--available {
            padding: 4px;
            background: green;
            color: white;
            border-radius: 4px;
        }

        .redeem-ticket-message {
            font-size: 2em;
            line-height: 130%;
            border-radius: 8px;
            background: white;
            text-align: center;
            display: none;
        }

        .redeem-ticket-message .code {
            font-size: 12px;
            text-align: left;
            padding: 10px;
        }

        .redeem-ticket-message .msg {
            padding: 40px;
            border-bottom: solid 1px #ccc;
            font-size: 2.5rem;
            line-height: 120%;
        }

        .redeem-ticket-message.danger,
        .redeem-ticket-message.success {
            display: flex;
            flex-direction: column;
        }

        .redeem-ticket-message.danger {
            background: red;
            color: white;
        }

        .redeem-ticket-message.success {
            background: green;
            color: white;
        }

        .event-dropdown {
            min-width: 40%;
            margin-right: 2px;
        }

        .event-dropdown.search-active .field-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0px 5px 9px #0a155f75;
            border: solid 1px #666;
            overflow: hidden;
        }

        .event-dropdown.search-active .field-container input,
        .event-dropdown.search-active .field-container input[type=text]:focus {
            border: none;
            border-color: transparent;
            box-shadow: none;
            outline: 2px solid transparent;
        }


        .event-dropdown.search-active .field-container #event-dropdown-list {
            display: flex;
            flex-direction: column;
        }

        .event-dropdown.search-active button {
            padding: 10px 14px;
            text-align: left;
            border: none;
            background: white;
            border-top: dotted 1px #ebebeb;
            min-height: 50px;
        }

        #event-dropdown-list {
            display: none;
        }

        .event-dropdown.search-active #event-dropdown-list {
            display: initial;
            cursor: pointer;
            max-height: 40vh;
            overflow: auto;
        }

        .filter-option {
            display: flex;
        }

        .filter-option-detail {
            flex: 1;
        }

        .filter-option-id {
            min-width: 60px;
        }

        span.select2-selection.select2-selection--single {
            font-size: 1.5em;
            text-align: center;
            max-width: 100%;
            height: 70px;
            border-radius: 10px;
        }

        span#select2-ticketEventTpm-container {
            font-size: 1em;
            text-align: center;
            max-width: 100%;
            border-radius: 10px;
            line-height: 68px;
        }

        span.select2-selection__arrow {
            height: 70px !important;
        }

        span.select2.select2-container.select2-container--default {
            max-width: 100%;
            min-width: 100%;
        }

        span.state-id {
            width: 50px !important;
            display: block;
            float: left;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <form class="redemeForm" class="wrap" onsubmit="verifySubcription()">
        <h2><?= _('Subscriptions') ?></h2>
        <div class="ticket-redeame">
            <div class="ticket-redeame-field">
                <!-- <div class="event-dropdown">
                    <div class="field-container">
                        <select class="ticketEventTpm" name="state" id="ticketEventTpm">
                            <?php foreach ($events as $event) {
                                $visible = true; // get_post_meta($event->post_id, 'hide_from_report', true);
                            ?>
                                <?php if ($visible == "Visible") : ?>
                                    <option value="<?= $event->post_id ?>"><?= $event->description ?></option>
                                <?php endif; ?>
                            <?php } ?>
                        </select>
                    </div>
                </div> -->
                <input type="text" id="ticketCode" width="400" style="font-size: 1.5em;text-align: center" placeholder="IBO">
                <button type="submit" class="btn-submit" onclick="verifySubcription()">&nbsp;&nbsp;&nbsp;<?= __("BUSCAR", "sp") ?>&nbsp;&nbsp;&nbsp;</button>
            </div>
            <br>
            <br>
            <br>
            <div class="redeem-ticket-message">
                <div class="msg"></div>
                <div class="code"></div>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        function home_url(url) {
            return `<?= home_url() ?>${url}`;
        }

        jQuery("#ticketCode").val("").focus();
        jQuery(".redemeForm").on("submit", verifySubcription);
        hideSearchList();

        var timer, calltimeout;

        function formatState(state) {
            var $state = jQuery(
                '<span><span class="state-id">' + state.id + '</span>' + state.text + '</span>'
            );
            return $state;
        };

        function matchCustom(params, data) {

            // If there are no search terms, return all of the data
            if (jQuery.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object
            const searchStr = (data.id + ' - ' + data.text)
            if (searchStr.indexOf(params.term) > -1) {
                var modifiedData = jQuery.extend({}, data, true);
                // modifiedData.text += ' (matched)';

                // You can return modified objects from here
                // This includes matching the `children` how you want in nested data sets
                return modifiedData;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

        jQuery(document).ready(function() {
            jQuery('.ticketEventTpm').select2({
                templateResult: formatState,
                matcher: matchCustom
            });
        });

        function onSuccess(response) {
            try {
                jQuery('.redeem-ticket-message .msg').html(response.msg);
                jQuery('.redeem-ticket-message .code').html(response.code);
            } catch (e) {
                jQuery('.redeem-ticket-message .msg').html(response);
            }
            jQuery('.redeem-ticket-message').removeClass("danger").addClass("success");
            jQuery("#ticketCode").val("").focus();
            cleanAfter5Seconds();
        }

        function onError(error) {
            try {
                jQuery('.redeem-ticket-message .msg').html(error.msg)
                jQuery('.redeem-ticket-message .code').html(error.code)
            } catch (e) {
                jQuery('.redeem-ticket-message .msg').html(error)
            }
            jQuery('.redeem-ticket-message').removeClass("success").addClass("danger");
            jQuery("#ticketCode").val("").focus();
            cleanAfter5Seconds()
        }

        function verifySubcription() {
            // if form already call, stop and create a new one
            if (calltimeout) {
                window.clearTimeout(calltimeout);
                calltimeout = null
            }
            // form is being triggered to times
            calltimeout = setTimeout(function() {
                if (timer) {
                    window.clearTimeout(timer);
                    timer = null
                }
                jQuery('.redeem-ticket-message .msg').html("");
                jQuery('.redeem-ticket-message .code').html("");
                jQuery('.redeem-ticket-message').removeClass("success danger");
                let url = home_url("/wp-json/wctvApi/v1/verifyIBO");
                const code = jQuery("#ticketCode").val();
                const eventId = 0; // jQuery('#ticketEventTpm').val();
                if (!code) {
                    return
                }
                jQuery.post(url, {
                        ibo: code,
                        eventId: eventId
                    })
                    .success(function(response) {
                        if (response.code !== "VALID") {
                            onError(response);
                        } else {
                            onSuccess(response);
                        }
                    })
                    .error(onError);
            }, 300)
            return false;
        }

        function cleanAfter5Seconds() {
            timer = setTimeout(function() {
                jQuery('.redeem-ticket-message .msg').html("");
                jQuery('.redeem-ticket-message .code').html("");
                jQuery('.redeem-ticket-message').removeClass("success danger");
            }, 20000);
        }

        function hideSearchList() {
            setTimeout(() => {
                jQuery('.event-dropdown').removeClass('search-active');
            }, 100);
        }

        function showEventList() {
            jQuery('.event-dropdown').addClass('search-active');
        }

        function searchOnEventList(event) {
            let filterValue;
            const value = jQuery('#ticketEventTpm').val().trim().toLowerCase();
            jQuery('[data-filter="event"]').each((index, element) => {
                const catchedElement = jQuery(element);
                filterValue = catchedElement.data('filterValue').trim().toLowerCase();
                if (filterValue.indexOf(value) > -1 || value == '') {
                    catchedElement.show();
                } else {
                    catchedElement.hide();
                }
            });
            if (value == '') {
                jQuery('#ticketEvent').val('');
            }
        }

        function selectSearch(__eventId, __title) {
            jQuery('#ticketEvent').val(__eventId);
            jQuery('#ticketEventTpm').val(__title);
        }
    </script>
<?php
}
