let PAYPAL_BTNs;
let paypalButtonsRef;
var paypalActions;

function home_url(url) {
	const base = window.location.origin;
	return `${base}/${url}`;
}

const processWithPaypal = async (dataCallback, validationCallback, callback)=>{
	PAYPAL_BTNs = (paypal && paypal.Buttons({
		onError: (err) => {
			alert(err);
		},
		onRender: function () {
			paypalButtonsRef = this;
		},
		onInit: (data, actions) => {
			actions.enable();
			document.querySelectorAll('.pay-btn-paypal')[0].remove();
		},
		onClick: function(data, actions) {
			if(!validationCallback()){
				return actions.reject();
			}else{
				return actions.resolve();
			}
		},		
		createOrder: (data, actions)=>{
			const paymentData = dataCallback();
			const totalAmount = (Number(paymentData.amount)*Number(paymentData.eventQty)); // $scope.fields.eventQty*$scope.fields.eventPrice
			const currencyCode = 'US$';
			const description = paymentData.statementDescriptor;
			const invoiceID = paymentData.invoiceId;
			return actions.order.create({
				purchase_units:[{
					amount: {
						value: totalAmount,
						currency : currencyCode
					},
					description  : description, // Maximum length: 127.
					invoice_id  : invoiceID
				}]
			});

		},

		onApprove: function(orderData, actions) {

			return actions.order.capture().then(async (details)=>{
				try{
					const paymentData = dataCallback();
					const {payment, purchase_units, status} = details;
					let {amount, description, payee, payments, shipping} = purchase_units[0];
					 let name = "";
					
					try{
						name = [details.payer.name.given_name, details.payer.name.surname].join(" ");
					}catch(e){}
					
					let params = {
						action: 'register_paypal_payment',
						post_id: paymentData.eventId,
						invoice_id : paymentData.invoiceId,
						description : paymentData.statementDescriptor,
						create_time : details.create_time,
						update_time : details.update_time,
						status : details.status,
						amoun_currency_code : amount.currency_code,
						amoun_currency_value : amount.value,
						email_address : payee.email_address,
						merchant_id : payee.merchant_id,
						payee_email_address : payee.email_address,
						payee_merchant_id : payee.merchant_id,
						payments_amount : payments.captures[0].amount.value,
						payments_curency : payments.captures[0].amount.currency_code,
						payments_id : payments.captures[0].id,
						payment_status : payments.captures[0].status,
						payment_update_time : payments.captures[0].update_time,
						shipping_address_address_line_1 : shipping.address.address_line_1,
						shipping_address_admin_area_1 : shipping.address.admin_area_1,
						shipping_address_admin_area_2 : shipping.address.admin_area_2,
						shipping_address_country_code : shipping.address.country_code,
						shipping_address_postal_code : shipping.address.postal_code,
						shipping_name_full_name : shipping.name.full_name,
						payment_contract : JSON.stringify({paymentDetails : details, orderDetails : orderData, paymentData}),
						payment_contract_hash : "",
						created_at : new Date(),
						invoiceID : paymentData.invoiceId,
						sponsor : paymentData.selectSponsor,
						paypal_order_id : orderData.orderID,
						ticket_email : paymentData.email,
						payee_name : (name.trim() || paymentData.email),
						ticketNames : paymentData.emails,
						eventQty : paymentData.eventQty,
						eventPrice : paymentData.amount
						// isfisicalEvent : $scope.fields.isfisicalEvent
					}

					let paymentResponse = await fetch(home_url("/wp-json/wctvApi/v1/registerPayment"),{
						method: "POST",
						body : JSON.stringify(params),
						headers: {
							'Content-Type': 'application/json'
						},
					});
					paymentResponse = await paymentResponse.json();
					if(Array.isArray(paymentResponse)){
						if(callback){
							const invoice = {...{data: details}, details, status, ...{id : params.payments_id}, ...paymentData, ...{tickets : paymentResponse}};
							callback(invoice);
						}
					}else{
						alert(`Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820"`);
					}
				}catch(e){
					alert(e);
				}
			},function(error){
				alert("PAYPAL ERROR: no fue posible procesar su pago debido a que paypal retornó un error, intente nuevamente");
			});
		}
	}).render('#paypal-button-container'));

	return PAYPAL_BTNs;

}

const PAYPAL_handleSuccess = async (params, callback)=> {
	const { paymentIntentResponse : invoice } = params;
	if(invoice.data.status.toLowerCase() === "completed"){
		callback(invoice);
	}else{
		throw invoice.data.status
	}
}