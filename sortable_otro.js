var T = [], globcol = 0, globo = null;

function initable( id, types, first, func, offtop ) {
//	alert('entro en initable'+id.tagName);
try
{
 if( ( id.tagName||'' ).toLowerCase()==='table' ) { if( !id.id ) { id.id = 'table' + Math.random(); } id = id.id; }
 types = types || 0; first = first || 0; func = func || 0; offtop = offtop || 0;
 T[id] = {}; T[id].curcol = first; T[id].curdir = 'down'; T[id].rank = false; T[id].func = func; T[id].offtop = offtop;
 var a = document.getElementById( id );
 if(a) {

  var b = a.getElementsByTagName( 'thead' )[0].getElementsByTagName( 'th' ), i, j;
//  	 alert('entro en if a elementos de b'+b.length);
  for( i=0; i < b.length; i++ ) {
   if( !( offtop===false||offtop==='no' ) ) { b[i].style.position = 'relative'; }
   if( types[i] && types[i].toLowerCase().indexOf( 'rank' )===0 ) { addrank( id, i, types[i].substring( 4 ) ); T[id].rank = i; }
   else if( types[i]!=='no' && types[i]!==false ) { b[i].how = types[i]; b[i].onclick = gosortable; }
  }
  if( !( offtop===false||offtop==='no' ) ) {

  if( typeof window.onscroll!=='undefined' ) {

//	alert('stickhead1'+typeof window.onscroll+' onresize '+typeof window.onresize);   
    window.onscroll = stickhead;
//  20070524 la siguiente linea la inclui en Cambiasize   
//    window.onresize = stickhead;
   }else{
	alert('stickhead2');   	   
    window.setInterval( 'stickhead();', 2000 );
   }
  }
  if( typeof getcookie!=='undefined' && ( j = getcookie( 'sort' + id ) ) ) {
   if( Math.abs(j)!=first ) { first = Math.abs(j);
    sortable( id, first ); if( j<0 ) { sortable( id, first ); }
   }else{
    sorticon( b[first] );
   }
  }else{
   sorticon( b[first] );
  }
  globcol = first;
 }
}
catch (err)
{ alert('error initable'+err.description); }
}

function sortable( e, col ) { if( !e ) { alert( 'Argument bug!' ); return; }
// alert('entro en sortable');
 if( typeof e==='string' ) { e = document.getElementById( e ); }
 if( e && col ) { e = e.getElementsByTagName( 'thead' )[0].getElementsByTagName( 'th' )[col]; }
 var i = e, table = e, newcol = 0;
 if( !e ) { alert( arguments + '\nTable bug!' ); }
 while( ( table.tagName && table.tagName.toLowerCase()!=='table' ) ) { table = table.parentNode; }
 if( typeof col!=='undefined' ) { newcol = col; } else { while( ( i = i.previousSibling ) ) { if( i.tagName ) { newcol++; } } }
 var t = T[table.id],
  tbody = table.getElementsByTagName( 'tbody' )[0],
  newtbody = tbody.cloneNode( false ),
  rows = tbody.rows,
  newrows = [];
 i = rows.length; while (i--) {
  newrows[i] = rows[i].cloneNode( true );
 }

 var th=table.getElementsByTagName( 'thead' )[0].getElementsByTagName( 'th' )[t.curcol];
 th.className = th.className.replace( /\s?sorted/, '' );
// alert('inner'+th.innerHTML);
//20070523 aqui es donde limpia los encabezados de la tabla 
// for( i = th.firstChild; i!==null; i = i.nextSibling ) {
// 	alert('typeof'+i.src);	 
//20070523  if( /\/sort-(up|down).gif$/.test( i.src || '' ) ) { th.removeChild( i ); break; }
	th.innerHTML=th.innerHTML.replace(/\<span\>\<font face=webdings\>6\<\/font\>\<\/span\>/gi,'');
	th.innerHTML=th.innerHTML.replace(/\<span\>\<font face=webdings\>5\<\/font\>\<\/span\>/gi,'');	
//	alert('paso replace'+i.innerHTML+' i'+i);	 	
// }

 if( newcol===t.curcol ) {
  t.curdir = t.curdir !== 'up' ? 'up' : 'down';
  newrows.reverse();
 } else {
  t.curcol = newcol; globcol = newcol;
  t.curdir = 'down';
  var h = ( e.how || '' ).toLowerCase(), how = window[ 'compare' + h.replace( /^r/, '' ) ];
  newrows.sort( typeof how==='function' ? how : compare );
  if( h.charAt()==='r' ) { newrows.reverse(); }
 }
 for( i=0; i<newrows.length; i++ ) {
  newtbody.appendChild( newrows[i] );
 }
 table.replaceChild( newtbody, tbody );
 sorticon( e, t.curdir );
 if( typeof t.rank==='number' ) { sortrank( table ); }
 if( typeof setcookie!=='undefined' ) {
  setcookie( 'sort' + table.id, newcol , 0, '/' );
 }
 eval(t.func);
}

function sortcursor( s, o ) { document.body.style.cursor = s; if( o ) { o.style.cursor = s; var a = o.getElementsByTagName('a'), i = a.length; while(i--) { a[i].style.cursor = s; } } }

function sorticon( o, dir ) {
// alert('entro en sortico');		
 dir = dir || 'down';
//20070523 Modificacion para que no utilice img, sino un caracter que indique asc o desc 
//20070523 var i = document.createElement('img');
//20070523 i.setAttribute( 'hspace', '1' );
//20070523 i.setAttribute( 'vspace', '1' );
//20070523 i.style.position = 'absolute';
//20070523 i.style.width = 8;
//20070523 i.style.height = 8;
//20070523 i.src = 'img/sort-' + dir + '.gif';
//20070523 i.style.alt = dir;
//20070523 o.appendChild( i );
// alert('inner'+o.innerHTML);
 if (dir=='down')
 { o.innerHTML=o.innerHTML+"<span><font face='webdings'>6</font></span>"; }
 else
 { o.innerHTML=o.innerHTML+"<span><font face='webdings'>5</font></span>"; } 
 o.className += ' sorted';
}

function sortrank( e ) {
 if( typeof e==='string' ) { e = document.getElementById(e); }
 var i, newcols = [], col = T[e.id].rank,
  rows = e.getElementsByTagName('tbody')[0].rows;
 i = rows.length; while (i--) {
  newcols[i] = rows[i].childNodes[col].cloneNode(true);
 }
 newcols.sort( function(a,b) { return parseInt( a.firstChild.nodeValue )-parseInt( b.firstChild.nodeValue ); } );
 i = rows.length; while (i--) {
  rows[i].replaceChild( newcols[i], rows[i].childNodes[col] );
 }
}

function gosortable( e, o ) { e = e||window.event||{}; o = o||e.srcElement||e.target; globo = o; sortcursor( 'wait', o ); window.setTimeout( 'sortable( globo ); sortcursor( \'\', globo );', 10 ); }

function cell( o ) { return o.childNodes[ globcol ] || ''; }

function comp( va, vb ) { return va===vb ? 0 : ( (!va && va!==0) || (va>vb && vb) ? 1 : -1); }

function compare(a, b) {
 var va = trim(gettext(cell(a))).toLowerCase();
 var vb = trim(gettext(cell(b))).toLowerCase();
 return comp( va, vb );
}
function comparecase(a, b) {
 var c = function(o) { return gettext(cell(o)); };
 return comp( c(a), c(b) );
}
function comparedate(a, b) {
 var c = function(o) { return Date.parse( gettext(cell(o)).replace( /'/, '20' ).replace( /[\s\\\/-]/, ' ' ) ); };
 return comp( c(a), c(b) );
}
function comparehtml(a, b) {
 var c = function(o) { return cell(o).innerHTML; };
 return comp( c(a), c(b) );
}
function comparelength(a, b) {
 var c = function(o) { return gettext(cell(o)).length; };
 return comp( c(a), c(b) );
}
function comparemoney(a, b) {
 var c = function(o) { return parseFloat( '0' + gettext(cell(o)).replace( /[^\d\.]/g, '' ) ); };
 return comp( c(a), c(b) );
}
function comparename(a, b) {
 var reg = /^(d(e[lnrs]?|ella|i)|l[ae]|van(\sde[nr]?)?|von|te[nr]?|'s)\s+|o'/i;
 var va = gettext(cell(a)).replace(reg,''),
  vb = gettext(cell(b)).replace(reg,'');
 return comp( va, vb );
}
function comparenumber(a, b) {
 var c = function(o) { return parseFloat( gettext(cell(o)).replace(/,/g,'') ); };
 return comp( c(a), c(b) );
}
function comparenumberrev(a, b) {
 var c = function(o) { return parseFloat( gettext(cell(o)).replace(/,/g,'') ); };
 return comp( c(b), c(a) );
}
function comparespan(a, b) {
 var c = function(o) { return parseFloat('0'+gettext(cell(o).getElementsByTagName('span')[0])); };
 return comp( c(a), c(b) );
}
function comparetime(a, b) {
 var va = gettext(cell(a)); while( va.length<9 ) { va='0'+va; }
 var vb = gettext(cell(b)); while( vb.length<9 ) { vb='0'+vb; }
 return comp( va, vb );
}
function comparetitle(a, b) {
 return comp( cell(a).title, cell(b).title );
}
function comparetitlenumber(a, b) {
 var c = function(o) { return parseFloat( cell(o).title.replace(/,/g,'') ); };
 return comp( c(a), c(b) );
}

function stickhead() {

 var d = document, a, b, c, i, x,
//  lo modifique para tomar  d.body.scrollTop
//  y = ( typeof window.pageYOffset==='number' ? window.pageYOffset : d.documentElement && typeof d.documentElement.scrollTop==='number' ? d.documentElement.scrollTop : d.body.scrollTop );
  y = ( typeof window.pageYOffset==='number' ? window.pageYOffset :  d.body.scrollTop );
//  alert( 'antes de parse y='+y+' window.pageYOffset='+window.pageYOffset+' d.documentElement.scrollTop'+d.documentElement.scrollTop+' d.body.scrollTop'+d.body.scrollTop+' getstyle'+getstyle( document.body, 'margin-top')+ ' type of '+typeof d.documentElement.scrollTop);
 y -= parseInt( getstyle( document.body, 'margin-top' ), 10 );
//  alert( 'despuest de parse y='+y); 
//	alert('entro en stickhead pageYOffset'+window.pageYOffset+ ' scrollTop '+d.documentElement.scrollTop);	 
 for( i in T ) {

  if( T[i].offtop!==false && ( a = d.getElementById( i ) ) ) {
//	  alert('si cumplio');

   b = a; c = 0; do { c += b.offsetTop; } while ( ( b = b.offsetParent ) );
   if( ( b = a.getElementsByTagName( 'caption' )[0] ) ) { c += b.offsetHeight; }
   c -= typeof T[i].offtop==='string' ? eval( T[i].offtop ) : T[i].offtop;
   x = Math.min( a.scrollHeight - 32, Math.max( 0, y - c ) );
   b = a.getElementsByTagName( 'thead' )[0].getElementsByTagName( 'th' ); i = b.length;
//   alert('entro en for'+i+' c='+c+' y='+y);	    
//   alert('elementos de b'+i+' valor de x'+x+' sytle '+b[i-1].style.top);
//   while(i--) { b[i].style.top = x + 'px';    alert('elementos de b'+i+' name'+b[i].innerHTML+' valor de x'+x+' sytle '+b[i].style.top+' parentnode '+b[i].parentNode.nodeName);}
   while(i--) { b[i].style.top = x + 'px';}   
  }
 }
 if( typeof beweeg==='function' ) { beweeg(); }
}

function addrank( id, col, str ) {
 col = col || 0;
 var d = document,
  th = d.createElement( 'th' ),
  td = d.createElement( 'td' ),
  a = d.getElementById( id ),
  row = a.getElementsByTagName( 'thead' )[0].rows[0],
  rows = a.getElementsByTagName( 'tbody' )[0].rows,
  b = a.getElementsByTagName( 'colgroup' ), i, j,
  start = parseInt( str, 10 )===0 ? 0 : parseInt( str, 10 ) || 1;
 if( b && ( i=b[0] ) && ( j=i.childNodes[col] ) ) {
  var c = d.createElement( 'col' ); c.className = 'rank'; i.insertBefore( c, j );
 }
 th.title = ' ' + row.childNodes.length + ' columns, \u000d ' + rows.length + ' rows. ';
 th.style.textAlign = 'center';
 if( !( T[id].offtop===false||T[id].offtop==='no' ) ) { th.style.position = 'relative'; }
 th.onclick = function() { window.scrollTo(0,0); };
 th.appendChild( d.createTextNode( str.replace( new RegExp( '^[\s0]*' + start + '\s*' ), '' ) || '#' ) );
 row.insertBefore( th, row.childNodes[col] );
 td.className = 'rank';
 td.style.textAlign = 'right';
 td.style.font = 'message-box';
 i = rows.length; while (i--) {
  j = td.cloneNode(true);
  j.appendChild( d.createTextNode( ( start + i ) + '.' ) );
  rows[i].insertBefore( j, rows[i].childNodes[col] );
 }
}

if(typeof trim==='undefined') { trim = function(s) { return s.replace( /^\s+|s+$/g, '' ); };}

if(typeof getstyle==='undefined') { getstyle = function(o,prop) {
 if(o.currentStyle) {
  prop = prop.replace(/-(\w)/, function( t, a ) { return a.toUpperCase(); } );
  return o.currentStyle[prop];  // backgroundColor
 }else if(window.getComputedStyle) {
  o = window.getComputedStyle( o, '' );
  return o.getPropertyValue(prop);  // background-color
 }
};}

if( typeof gettext==='undefined' ) { gettext = function(o) {
 var s = '', x = o.firstChild;
 if( x ) {
  for( ; x!==null; x = x.nextSibling ) {
   if( x.nodeType===3 ) {
    s += x.nodeValue;
   } else if( x.nodeType===1 ) {
    s += gettext( x );
   }
  }
 }else if(o.nodeValue) {
   s += o.nodeValue;
 }
 return s;
};}
