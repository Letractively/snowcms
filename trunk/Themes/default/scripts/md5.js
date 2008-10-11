function md5_password() {
  handle = document.getElementById('password');
  hash = document.getElementById('pass_hash');
  hash.value = hex_md5(handle.value);
  handle.value = '';
}


function hex_md5(L){var J=Array(),P=(1<<8)-1,R=L.length*8,V=1732584193,U=-271733879,T=-1732584194,S=271733878;for(var Q=0;Q<R;Q+=8){J[Q>>5]|=(L.charCodeAt(Q/8)&P)<<(Q%32)}J[R>>5]|=128<<((R)%32);J[(((R+64)>>>9)<<4)+14]=R;function C(e,Y,X,W,d,c){var Z=O(O(Y,e),O(W,c));return O(O(Z<<d)|(Z>>>(32-d)),X)}function B(Y,X,g,f,W,e,Z){return C((X&g)|((~X)&f),Y,X,W,e,Z)}function H(Y,X,g,f,W,e,Z){return C((X&f)|(g&(~f)),Y,X,W,e,Z)}function N(Y,X,g,f,W,e,Z){return C(X^g^f,Y,X,W,e,Z)}function A(Y,X,g,f,W,e,Z){return C(g^(X|(~f)),Y,X,W,e,Z)}function O(W,Y){var X=(W&65535)+(Y&65535);return((W>>16)+(Y>>16)+(X>>16)<<16)|(X&65535)}for(var Q=0;Q<J.length;Q+=16){var G=V,F=U,E=T,D=S;V=B(V,U,T,S,J[Q+0],7,-680876936);S=B(S,V,U,T,J[Q+1],12,-389564586);T=B(T,S,V,U,J[Q+2],17,606105819);U=B(U,T,S,V,J[Q+3],22,-1044525330);V=B(V,U,T,S,J[Q+4],7,-176418897);S=B(S,V,U,T,J[Q+5],12,1200080426);T=B(T,S,V,U,J[Q+6],17,-1473231341);U=B(U,T,S,V,J[Q+7],22,-45705983);V=B(V,U,T,S,J[Q+8],7,1770035416);S=B(S,V,U,T,J[Q+9],12,-1958414417);T=B(T,S,V,U,J[Q+10],17,-42063);U=B(U,T,S,V,J[Q+11],22,-1990404162);V=B(V,U,T,S,J[Q+12],7,1804603682);S=B(S,V,U,T,J[Q+13],12,-40341101);T=B(T,S,V,U,J[Q+14],17,-1502002290);U=B(U,T,S,V,J[Q+15],22,1236535329);V=H(V,U,T,S,J[Q+1],5,-165796510);S=H(S,V,U,T,J[Q+6],9,-1069501632);T=H(T,S,V,U,J[Q+11],14,643717713);U=H(U,T,S,V,J[Q+0],20,-373897302);V=H(V,U,T,S,J[Q+5],5,-701558691);S=H(S,V,U,T,J[Q+10],9,38016083);T=H(T,S,V,U,J[Q+15],14,-660478335);U=H(U,T,S,V,J[Q+4],20,-405537848);V=H(V,U,T,S,J[Q+9],5,568446438);S=H(S,V,U,T,J[Q+14],9,-1019803690);T=H(T,S,V,U,J[Q+3],14,-187363961);U=H(U,T,S,V,J[Q+8],20,1163531501);V=H(V,U,T,S,J[Q+13],5,-1444681467);S=H(S,V,U,T,J[Q+2],9,-51403784);T=H(T,S,V,U,J[Q+7],14,1735328473);U=H(U,T,S,V,J[Q+12],20,-1926607734);V=N(V,U,T,S,J[Q+5],4,-378558);S=N(S,V,U,T,J[Q+8],11,-2022574463);T=N(T,S,V,U,J[Q+11],16,1839030562);U=N(U,T,S,V,J[Q+14],23,-35309556);V=N(V,U,T,S,J[Q+1],4,-1530992060);S=N(S,V,U,T,J[Q+4],11,1272893353);T=N(T,S,V,U,J[Q+7],16,-155497632);U=N(U,T,S,V,J[Q+10],23,-1094730640);V=N(V,U,T,S,J[Q+13],4,681279174);S=N(S,V,U,T,J[Q+0],11,-358537222);T=N(T,S,V,U,J[Q+3],16,-722521979);U=N(U,T,S,V,J[Q+6],23,76029189);V=N(V,U,T,S,J[Q+9],4,-640364487);S=N(S,V,U,T,J[Q+12],11,-421815835);T=N(T,S,V,U,J[Q+15],16,530742520);U=N(U,T,S,V,J[Q+2],23,-995338651);V=A(V,U,T,S,J[Q+0],6,-198630844);S=A(S,V,U,T,J[Q+7],10,1126891415);T=A(T,S,V,U,J[Q+14],15,-1416354905);U=A(U,T,S,V,J[Q+5],21,-57434055);V=A(V,U,T,S,J[Q+12],6,1700485571);S=A(S,V,U,T,J[Q+3],10,-1894986606);T=A(T,S,V,U,J[Q+10],15,-1051523);U=A(U,T,S,V,J[Q+1],21,-2054922799);V=A(V,U,T,S,J[Q+8],6,1873313359);S=A(S,V,U,T,J[Q+15],10,-30611744);T=A(T,S,V,U,J[Q+6],15,-1560198380);U=A(U,T,S,V,J[Q+13],21,1309151649);V=A(V,U,T,S,J[Q+4],6,-145523070);S=A(S,V,U,T,J[Q+11],10,-1120210379);T=A(T,S,V,U,J[Q+2],15,718787259);U=A(U,T,S,V,J[Q+9],21,-343485551);V=O(V,G);U=O(U,F);T=O(T,E);S=O(S,D)}var K=Array(V,U,T,S),I="0123456789abcdef",M="";for(var Q=0;Q<K.length*4;Q++){M+=I.charAt((K[Q>>2]>>((Q%4)*8+4))&15)+I.charAt((K[Q>>2]>>((Q%4)*8))&15)}return M} 