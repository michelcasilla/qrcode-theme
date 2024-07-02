/*** {
	price : number,
	eventId,
	statementDescriptor,
	invoiceId
}*/
const stripe = Stripe(wc_vars.STRIPE_PUBLISHABLE_KEY);

function home_url(url) {
	const base = window.location.origin;
	return `${base}/${url}`;
}

async function initialize(formID, params) {

	const response = await fetch(home_url("/wp-json/wctvApi/v1/stripe/createPaymentID"), {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify({
			amount: Number(params.amount || 0),
			eventId: params.eventId,
			statementDescriptor: params.statementDescriptor,
			invoiceId: params.invoiceId,
			ibo: params.ibo,
		}),
	});

	const { data } = await response.json();
	const { client_secret: clientSecret, id } = data;

	PAYMENT_ID = id;

	const appearance = { theme: 'stripe' };
	elements = stripe.elements({ appearance, clientSecret });
	const paymentElement = elements.create("payment");
	paymentElement.mount(formID);
	return data;
}

const updatePaymentIntent = async (params, config) => {

	params.tickets = params.emails.length;
	params.emails.forEach((ticket, index) => {
		params[`email_${index}`] = ticket.name
	});
	const fields = { ...config, ...params };
	delete params.emails;
	let metadata = [];

	Object.keys(params).forEach(prop => {
		let value = params[prop];
		console.log(prop);
		if (prop == "selectSponsor") {
			value = (value || "").replace('&', '-');
		}
		metadata.push(`metadata[${prop}]=${value}`);
	});

	metadata = metadata.join("&");

	const updatePayment = {
		paymentId: config.id,
		amount: Number(fields.eventQty) * Number(fields.amount),
		currency: 'usd',
		statementDescriptor: fields.statementDescriptor,
		receipt_email: fields.email,
		setup_future_usage: false,
		extraFields: metadata,
		invoiceId: fields.invoiceID,
		eventId: fields.eventId,
		ibo: fields.ibo,
	};
	const updatePaymentResponse = await fetch(home_url("/wp-json/wctvApi/v1/stripe/updatePaymentIntent"), {
		method: "POST",
		body: JSON.stringify(updatePayment),
		headers: {
			'Content-Type': 'application/json'
		},
	});
	return updatePaymentResponse.json();
}

const STRIPE_handleSubmit = async (e, return_url) => {
	e.preventDefault();
	const confirmPaymentResponse = await stripe.confirmPayment({
		elements,
		confirmParams: {
			return_url: (return_url || location.href),
		},
	});
	try {
		if (confirmPaymentResponse.error) {
			alert(confirmPaymentResponse.error.message);
		}
	} catch (e) { }
	return confirmPaymentResponse;
}

const STRIPE_handleSuccess = async (params, callback) => {
	const currentUrl = new URL(location.href);
	const paymentId = currentUrl.searchParams.get('payment_intent');
	let paymentIntentResponse = await fetch(home_url("/wp-json/wctvApi/v1/stripe/retrivePaymentIntentInfos"), {
		method: "POST",
		body: JSON.stringify({ paymentId }),
		headers: {
			'Content-Type': 'application/json'
		},
	});
	paymentIntentResponse = await paymentIntentResponse.json();
	if (paymentIntentResponse.data.status === "succeeded") {
		const invoice = { ...params, ...paymentIntentResponse.data, ...{ description: params.description } }
		stripePaymentSuccess(invoice, callback);
	} else {
		throw paymentIntentResponse.data.status
	}
}

const stripePaymentSuccess = async (details, callback) => {
	try {
		const $scope = details;
		const { id, charges: { data }, amount, created, currency, metadata } = details;
		let createdAt = new Date(created * 1000);
		metadata.emails = [];
		for (let i = 0; i < Number(metadata.eventQty); i++) {
			const ticket = { name: metadata[`email_${i}`], email: '' }
			metadata.emails.push(ticket);
		}
		metadata.showValidateModal = false;
		$scope.fields = metadata;
		const charge = data[0];
		const { billing_details } = charge;
		const name = [billing_details.name, billing_details.phone].join(" ");
		const email = (charge.receipt_email || $scope.fields.email);
		const status = details.status == 'succeeded' ? 'COMPLETED' : details.status.toUpperCase()
		const __id = String(id).toUpperCase();
		const parsedAmount = Number(amount);
		let params = {
			ibo: metadata.ibo,
			action: 'register_stripe_payment',
			post_id: details.EVENTID,
			invoice_id: metadata.invoiceID,
			description: details.description,
			create_time: createdAt,
			update_time: createdAt,
			status: status,
			amoun_currency_code: String(currency).toUpperCase(),
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
			payment_contract: JSON.stringify({ paymentDetails: details.details, orderDetails: $scope.orderData }),
			payment_contract_hash: "",
			created_at: new Date(),
			invoiceID: metadata.invoiceID,
			sponsor: metadata.selectSponsor,
			paypal_order_id: __id,
			ticket_email: email,
			payee_name: (name.trim() ? name : email),
			ticketNames: metadata.emails,
			eventQty: Number(metadata.eventQty),
			eventPrice: Number(metadata.amount)
		}

		let paymentResponse = await fetch(home_url("/wp-json/wctvApi/v1/registerPayment"), {
			method: "POST",
			body: JSON.stringify(params),
			headers: {
				'Content-Type': 'application/json'
			},
		});
		paymentResponse = await paymentResponse.json();

		if (Array.isArray(paymentResponse)) {
			// $scope.fields.ticket = paymentResponse[0].msg.event_ticket;
			// $scope.fields.validaName = paymentResponse[0].msg.ticket_email;
			if (callback) {
				const invoice = { details, ...$scope, ...$scope.fields, ...{ tickets: paymentResponse } };
				callback(invoice);
			}
		} else {
			alert(`Ha ocurrido un error registrando su pago en el sistema, contact al centro de soporte. ayuda@wordcrowns.com | WhatsApp 1-754-236-0820"`);
		}

	} catch (e) {
		alert(e);
	}
}