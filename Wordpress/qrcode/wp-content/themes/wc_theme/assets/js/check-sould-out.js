
function home_url(url) {
	const base = window.location.origin;
	return `${base}/${url}`;
}

const verify = async (eventID, intervalInstance)=>{
	const soldOutElement = document.querySelectorAll('.soldout')[0];
	let soldOut = await fetch(home_url("/wp-json/wctvApi/v1/isSoldOut?") + new URLSearchParams({ eventID }));
	soldOut = await soldOut.json();
	if(soldOut.status){
		soldOutElement.style.display = "flex";
		if(intervalInstance){
			clearInterval(intervalInstance);
		}
	}else{
		soldOutElement.style.display = "none";
	}
}

const checkSoldOut = async (eventID, interval = 5000)=>{
	const intervalInstance = setInterval(async ()=> verify(eventID, intervalInstance), interval);
	verify(eventID, intervalInstance);
}