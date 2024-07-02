const updateNames = (qtyticket) => {
	const tickets = document.querySelectorAll("[data-ticket]");
	let totalTickets = tickets.length;

	while (totalTickets != qtyticket) {

		if (totalTickets > qtyticket) {
			const last = tickets[totalTickets - 1];
			last.remove();
		}

		if (tickets.length < qtyticket) {
			const ticketId = totalTickets;
			const label = document.createElement('label');
			label.setAttribute('for', "");
			label.innerText = `Nombre Acceso ${totalTickets + 1}`;

			const input = document.createElement('input');
			input.setAttribute('placeholder', "Nombre completo");
			input.setAttribute('data-ticket-name', ticketId);
			input.setAttribute('type', "text");

			const div = document.createElement('div');
			div.setAttribute("data-ticket", "");
			div.classList.add('form-input');

			div.appendChild(label)
			div.appendChild(input)

			const ticketsName = document.querySelectorAll("[ticket-names]")[0];
			ticketsName.append(div);
		}
		totalTickets = document.querySelectorAll("[data-ticket]").length;
	}
}

const updateAmount = (unitAmount, $freeTickets) => {
	const qtyticket = document.querySelectorAll('[name="qtyticket"]')[0];
	const subTotal = document.querySelectorAll('[data-subtotal]')[0];
	// const unitAmount = <?=get_field("event_price")?>;

	// MIN quantity 1
	if (qtyticket.value < 1) { qtyticket.value = 1; }
	if (qtyticket.value > 10) { qtyticket.value = 10; }

	const free = (Number(unitAmount) * Number($freeTickets ?? 0));
	let total = (Number(unitAmount) * Number(qtyticket.value)) - free;
	if (total < 0) {
		total = 0;
	}

	const settings = { style: "currency", currency: "USD" };
	subTotal.innerText = Intl.NumberFormat('en-US', settings).format(total);

	updateNames(Number(qtyticket.value));
}