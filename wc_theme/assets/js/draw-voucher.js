const DrawVoucher = (invoice) => {
	const statusElement = document.querySelectorAll('[data-invoice-status]')[0];
	const authElement = document.querySelectorAll('[data-invoice-auth]')[0];
	const dateElement = document.querySelectorAll('[data-invoice-date]')[0];
	const invoiceNumberElement = document.querySelectorAll('[data-invoice-number]')[0];
	const amountElement = document.querySelectorAll('[data-invoice-amount]')[0];
	const ticketsWrapper = document.querySelectorAll('[data-invoice-tickets]')[0];
	const totalElement = document.querySelectorAll('[data-invoice-total]')[0];
	const currencyElement = document.querySelectorAll('[data-invoice-currency]')[0];
	const eventTypeElement = document.querySelectorAll('[data-invoice-event_type]')[0];
	console.log(invoice);

	const {
		id,
		details: { charges: { data: { AzulOrderId }, status: azulStatus } },
		tickets,
		status,
		price,
		eventQty,
		amount
	} = invoice;
	debugger
	const _price = Number(price) || Number(amount);
	statusElement.innerText = String(status || azulStatus).toUpperCase();
	authElement.innerText = String(AzulOrderId || id).toUpperCase();
	dateElement.innerText = moment().format('YYYY-MM-DD');
	invoiceNumberElement.innerText = invoice.invoiceId;
	amountElement.innerText = `${eventQty}x - ${invoice.currency} ${_price * Number(eventQty)}.00`;
	totalElement.innerText = (_price * Number(eventQty));
	currencyElement.innerText = invoice.currency;

	// for each ticket add a new entry
	// ticketsWrapper
	let ticketList = '';
	const ticketSize = 80;
	debugger
	(tickets || []).forEach((ticket, index) => {
		ticketList += `
		<div class="bill-item-wrapper bill-item-user">
			<div class="bill-item">
				<span>Acceso ${index + 1}</span>
				<span>${ticket.msg.event_ticket}</span>
			</div>
			<div class="bill-item">
				<span>Producto</span>
				<span>${ticket.msg.description}</span>
			</div>
			<div class="bill-qr">
				<img src="/qr/generate.php?chs=${ticketSize}x${ticketSize}&cht=qr&chl=${ticket.msg.event_ticket}&chld=L|1&choe=UTF-8" width="${ticketSize}" height="${ticketSize}" />
			</div>
		</div>
	`
	});

	ticketsWrapper.innerHTML = ticketList;
}