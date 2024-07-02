const validateStepInfoForm = () => {
	const name = document.querySelectorAll('[name="name"]')[0].value.trim();
	const email = document.querySelectorAll('[name="email"]')[0].value.trim();
	const emailConfirmation = document.querySelectorAll('[name="email_confirmation"]')[0].value.trim();
	const sponsor = document.querySelectorAll('[name="sponsor"]')[0].value.trim();
	const acceptTermsOfService = document.querySelectorAll('[name="accept_terms_of_service"]')[0].checked;
	const ibo = document.querySelectorAll('[name="ibo"]')[0].value.trim();
	const errors = [];

	if (ibo == "") { errors.push('IBO es requerido') }
	if (name == "") { errors.push('Nombre es requerido') }
	if (email == "") { errors.push('Correo es requerido') }
	if (emailConfirmation == "") { errors.push('Confirmation de correo es requerido') }
	if (!validateEmail(email)) { errors.push('El correo no es válido') }
	if (sponsor == "") { errors.push('Sponsor es requerido') }
	if (!acceptTermsOfService) {
		errors.push('Aceptar los terminos y condiciones es required')
	}
	if (email !== emailConfirmation) { errors.push('Correo y Confirmation no son identicos.') }

	if (errors.length) {
		const msg = errors.join('\n');
		alert(msg)
		return { errors };
	}

	return { name, email, emailConfirmation, sponsor, ibo, acceptTermsOfService, errors };

}

function validateEmail(email) {
	debugger
	// Regular expression for a valid email address
	const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

	// Test the email against the regex pattern
	return emailRegex.test(email);
}

const validateStepPaymentForm = () => {
	const ticketFields = document.querySelectorAll('[data-ticket-name]');
	const qtyTicketsField = document.querySelectorAll('[name="qtyticket"]')[0];
	const qtyTickets = parseFloat(qtyTicketsField.value);
	const errors = [];
	const tickets = [];

	(ticketFields || []).forEach((tikcetField, index) => {
		const name = tikcetField.value.trim();
		const match = tickets.find(x => (x == name));
		if (name == "") {
			errors.push(`Debes indicar el nombre para el ticket ${index + 1}`)
		}

		if (match) {
			errors.push(`Ticket ${index + 1} nombre duplicado ${name}`);
		}

		if (!match) { tickets.push(name) };
	});

	if (errors.length) {
		const msg = errors.join("\n");
		alert(msg);
	}
	return { tickets, qtyTickets, errors };

}

const goToPaymentStep = (params) => {
	const toStore = validateStepInfoForm();
	const { errors = [] } = toStore;
	if (!errors.length) {
		location.href = encodeBase64UrlParams(params.url, toStore).toString();
	}
}