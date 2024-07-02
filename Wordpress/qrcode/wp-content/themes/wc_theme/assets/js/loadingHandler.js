
const showLoading = ()=>{
	const loadingElement = document.querySelectorAll('.loading')[0];
	loadingElement.style.display = "flex";
}
const hideLoading = (timeout = 600)=>{
	setTimeout(()=>{
		const loadingElement = document.querySelectorAll('.loading')[0];
		loadingElement.style.display = "none";
	}, timeout);
}