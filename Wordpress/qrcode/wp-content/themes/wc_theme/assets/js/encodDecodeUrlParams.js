const encodeBase64UrlParams = (url, data, keyName = 'content')=>{
	const endUrl = new URL(url);
	const search_params = endUrl.searchParams;
	const encodedData = btoa(JSON.stringify(data));
	search_params.set(keyName, encodedData);
	endUrl.search = search_params.toString();
	return endUrl;
}

const decodeBase64UrlParams = (url, keyName = 'content')=>{
	const endUrl = new URL(url);
	const search_params = endUrl.searchParams;
	let paramsInfo = {};
	try{
		paramsInfo = search_params.get(keyName);
		paramsInfo = atob(paramsInfo);
		paramsInfo = JSON.parse(paramsInfo);
	}catch(e){}
	return paramsInfo;
}