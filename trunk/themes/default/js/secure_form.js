SHA1=function(){var C=function(E,D,G,F){switch(E){case 0:return(D&G)^(~D&F);case 1:return D^G^F;case 2:return(D&G)^(D&F)^(G&F);case 3:return D^G^F}};var B=function(D,E){return(D<<E)|(D>>>(32-E))};var A=function(G){var F="",D;for(var E=7;E>=0;E--){D=(G>>>(E*4))&15;F+=D.toString(16)}return F};return{hash:function(F){var I=[1518500249,1859775393,2400959708,3395469782];F+=String.fromCharCode(128);var U=Math.ceil(F.length/4)+2;var G=Math.ceil(U/16);var H=new Array(G);for(var X=0;X<G;X++){H[X]=new Array(16);for(var V=0;V<16;V++){H[X][V]=(F.charCodeAt(X*64+V*4)<<24)|(F.charCodeAt(X*64+V*4+1)<<16)|(F.charCodeAt(X*64+V*4+2)<<8)|(F.charCodeAt(X*64+V*4+3))}}H[G-1][14]=((F.length-1)*8)/Math.pow(2,32);H[G-1][14]=Math.floor(H[G-1][14]);H[G-1][15]=((F.length-1)*8)&4294967295;var Q=1732584193;var P=4023233417;var O=2562383102;var L=271733878;var J=3285377520;var D=new Array(80);var h,g,f,Z,Y;for(var X=0;X<G;X++){for(var R=0;R<16;R++){D[R]=H[X][R]}for(var R=16;R<80;R++){D[R]=B(D[R-3]^D[R-8]^D[R-14]^D[R-16],1)}h=Q;g=P;f=O;Z=L;Y=J;for(var R=0;R<80;R++){var S=Math.floor(R/20);var E=(B(h,5)+C(S,g,f,Z)+Y+I[S]+D[R])&4294967295;Y=Z;Z=f;f=B(g,30);g=h;h=E}Q=(Q+h)&4294967295;P=(P+g)&4294967295;O=(O+f)&4294967295;L=(L+Z)&4294967295;J=(J+Y)&4294967295}return A(Q)+A(P)+A(O)+A(L)+A(J)}}}();

function secure_form(element_id)
{
  var element = s.id(element_id);
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'secured_password';
  input.value = typeof login_salt == 'undefined' ? SHA1.hash(element['username'].value.toLowerCase() + element['password'].value) : SHA1.hash(SHA1.hash(element['username'].value.toLowerCase() + element['password'].value) + login_salt);
  element['password'].value = '';

  element.appendChild(input);
}