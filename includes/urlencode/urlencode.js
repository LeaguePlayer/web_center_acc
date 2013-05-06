function urlencode( text ) {
	var trans = [];
	for ( var i = 0x410; i <= 0x44F; i++ ) trans[i] = i-0x350;
	trans[0x401] = 0xA8;
	trans[0x451] = 0xB8;
	var ret = [];
	for (var i=0; i<text.length; i++) {
		var n = text.charCodeAt(i);
		if(typeof trans[n] != 'undefined') n = trans[n];
		if(n <= 0xFF) ret.push(n);
	}
	return escape(String.fromCharCode.apply(null,ret));
}