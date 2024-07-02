
function home_url(url) {
	const base = window.location.origin;
	return `${base}/${url}`;
}

const antifraudCheck = async (tickedCodeID, tickedCode, interval = 6000)=>{
	const intervalInstance = setInterval(async () => {
		let response = await fetch(home_url("/wp-json/wctvApi/v1/watchFrom?")+ new URLSearchParams({tickedCodeID, tickedCode}));
		response = await response.json()
		const {code = ''} = response;
		if(code == 'MISS_MATCH_IP'){
			alert("Otro dispositivo se ha conectado con este numero de ticket. Cierre esta ventada si no desea seguir mirando en este dispositivo.");
			location.href = location.href.split("?")[0];
			clearInterval(intervalInstance);
		}
	}, interval);
}