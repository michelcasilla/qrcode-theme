
function home_url(url) {
	const base = window.location.origin;
	return `${base}/${url}`;
}

const processWithAzul = async (paymentData, callback) => {
	let currentUrl = new URL(location.href);
	currentUrl = `${currentUrl.origin}${currentUrl.pathname}`;
	const data = {
		// eventId: postID,
		// email: $scope.fields.email,
		// eventQty: $scope.fields.eventQty,
		// confEmail: $scope.fields.confEmail,
		// refundPolicy: $scope.fields.refundPolicy,
		// emails: $scope.fields.emails,
		// invoiceId: invoiceID,
		// currency: $scope.fields.currencyCode,
		// amount: $scope.fields.eventPrice,
		// selectSponsor: $scope.fields.selectSponsor,
		approveUrl: `${currentUrl}`,
		cancelUrl: `${currentUrl}`,
		declineUrl: `${currentUrl}`,
		...paymentData
	}
	const response = await AZUL_createPayment(data);
	setTimeout(()=>{
		const azulFormContainer = document.createElement('div');
		azulFormContainer.innerHTML = response.data.trim();
		const azulForm = azulFormContainer.firstChild;
		document.body.appendChild(azulForm);
		azulForm.submit();
		if(callback){ callback() }
	}, 2000)
		
}

async function AZUL_handleSuccess(params, callback) {
	try{
		const currentUrl = new URL(location.href);
		const paymentId = currentUrl.searchParams.get('payment_intent');
		if (paymentId) {
			const paymentIntentResponse = await AZUL_retrivePaymentIntentInfos(paymentId);
			if (paymentIntentResponse.data.status === "succeeded") {
				AZUL_paymentSuccess({...params, ...paymentIntentResponse.data}, callback);
			} else {
				throw `Azul payment fail with status: ${paymentIntentResponse.data.status}`;
			}
		}
	}catch(e){
		alert(e);
	}
}

async function AZUL_paymentSuccess(details, callback) {
	try {
		const {
			id,
			charges: { data },
			amount,
			created,
			currency,
			metadata,
			description
		} = details;
		let createdAt = data.DateTime; //new Date(Number(data.DateTime) * 1000);

		metadata.showValidateModal = false;
		const $scope = {};
		$scope.fields = metadata;
		$scope.orderData = metadata;
		const paymentInfo = data;
		// showLoadingVeil();
		const name = [paymentInfo.email, paymentInfo.phone].join(" ");
		const email = (metadata.email || $scope.fields.email);
		const status = details.status == 'succeeded' ? 'COMPLETED' : details.status.toUpperCase()
		const __id = String(paymentInfo.AzulOrderId).toUpperCase();
		const customer = (paymentInfo.customer || paymentInfo.CardNumber || __id);
		let params = {
			action: 'register_azul_payment',
			post_id: details.eventId,
			invoice_id: metadata.invoiceId,
			description: description,
			create_time: createdAt,
			update_time: createdAt,
			status: status,
			amoun_currency_code: String(currency).toUpperCase(),
			amoun_currency_value: amount,
			email_address: email,
			merchant_id: customer,
			payee_email_address: email,
			payee_merchant_id: customer,
			payments_amount: amount,
			payments_curency: currency,
			payments_id: __id,
			payment_status: status,
			payment_update_time: createdAt,
			shipping_address_address_line_1: "",
			shipping_address_admin_area_1: "",
			shipping_address_admin_area_2: "",
			shipping_address_country_code: "",
			shipping_address_postal_code: "",
			shipping_name_full_name: "",
			payment_contract: JSON.stringify({
				paymentDetails: details,
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
			eventQty: metadata.eventQty,
			eventPrice: metadata.price
			// isfisicalEvent : metadata.isfisicalEvent
		}

		const paymentResponse = await AZUL_registerPaypalPayment(params);
		
		if(Array.isArray(paymentResponse)){
			$scope.fields.ticket = paymentResponse[0].msg.event_ticket;
			$scope.fields.validaName = paymentResponse[0].msg.ticket_email;
			if(callback){
				const invoice = {details, ...$scope, ...$scope.fields, ...$scope.orderData, ...{tickets : paymentResponse}};
				callback(invoice);
			}
		}else{
			alert(`Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820"`);
		}
	} catch (e) {
		alert(`Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820"`);
	}
}

const AZUL_createPayment = async (data) =>{
	const response = await fetch(home_url("/wp-json/wctvApi/v1/azul/create_payment"),{
		method: "POST",
		body : JSON.stringify(data),
		headers: {
			'Content-Type': 'application/json'
		},
	});
	return response.json();
}

const AZUL_retrivePaymentIntentInfos = async (paymentId) =>{
	const response = await fetch(home_url("/wp-json/wctvApi/v1/azul/retrive_payment_intent_infos"),{
		method: "POST",
		body : JSON.stringify({paymentId : paymentId}),
		headers: {
			'Content-Type': 'application/json'
		},
	});
	return response.json();
}

const AZUL_registerPaypalPayment = async (params) =>{
	const response = await fetch(home_url("/wp-json/wctvApi/v1/registerPayment"),{
		method: "POST",
		body : JSON.stringify(params),
		headers: {
			'Content-Type': 'application/json'
		},
	});
	return response.json();
}