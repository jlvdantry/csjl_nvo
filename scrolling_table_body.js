
// +----------------------+
// | Scrolling Table Body |  (C) 2005 by Derek Anderson   [ http://kered.org | public at kered dot org ]
// +----------------------+

function initAllScrollingTableBodies() {
	var all = document.getElementsByTagName("TABLE");
//	alert('formas antes'+document.forms.length)	
	for( var i=0; i<all.length; ++i ) {
		if( all[i].className=="scrolling_table_body" ) {
			initScrollingTableBody( all[i] );
			if( all.length && all[i].id=="scrolling_table_body_generated" ) ++i;
		}
	}
	var all = document.getElementsByTagName("TABLE");
//	alert('formas despuest'+document.forms.length)
}
function initScrollingTableBody( stb ) {

	// this is our table - init it
	stb.border = "0";
	stb.cellSpacing = "0";

	// can we get these automatically?
	var scrollBarWidth = stb.childNodes[0].tagName=="THEAD" ? 19 : 17; // (IE needs 19, mozilla needs 17)
	var newHeaderFudgeWidth = 2;
//	var newHeaderFudgeWidth = 30;
	// get our header nodes, information regarding, and init
	var oHeader = document.createElement("table");
	var oTHead = getFirstChildNodeOfType( stb, "THEAD" );
	var oTR = getFirstChildNodeOfType( oTHead, "TR" );
	var tHeadHeight = oTR.clientHeight;
	oHeader.border = "0";
	oHeader.cellSpacing = "0";
	oHeader.className = "scrolling_table_body";
	oHeader.id = "scrolling_table_body_generated";
	oHeader.style.border = "1px solid black";
	oHeader.style.height = "";
//	alert('header height'+oHeader.style.height);
	oHeader.style.position = "absolute";
	// not needed, as we've moved the new header to be inline with the top of the scroll bar
	// oHeader.style.margin = "-"+ (tHeadHeight) +"px 0px 0px 0px";

	// build div parent node, and shrink its width to the correct size
	var stbp = document.createElement("div");
//	alert('despues de div'+document.forms.length);
	stb.parentNode.insertBefore( stbp, stb );
//	alert('despues de insertbefore'+document.forms.length);	
	stbp.className = "scrolling_table_body";
	stbp.style.overflow = "auto";
	stbp.style["overflow-x"] = "hidden";
	stbp.style["overflow-y"] = "scroll";
	stbp.style.width = stb.clientWidth + scrollBarWidth;
	stbp.style.height = stb.style.height;
	stb.style.height = "";
//	stb.name='vientos';
//	stbp.name='vientos1';
//	alert('si0='+document.getElementById("formpr").name+' stbp'+stbp.name);
	stbp.appendChild(stb);
//	document.forms[1].name='vientos';
//	alert('si1='+document.getElementById("vientos").name);	
//	alert('despues de appendChild'+document.forms.length+'name1'+document.forms[1].name+'name0'+document.forms[0].name);		
		
	// build span parent node, and shrink its width to the correct size
	var stbpp = document.createElement("span");
//	alert('despues de span'+document.forms.length)
	stbp.parentNode.insertBefore( stbpp, stbp );
	stbpp.className = "scrolling_table_body";
	stbpp.style.display = "block";
	// not needed, as we've moved the new header to be inline with the top of the scroll bar
	// stbpp.style.margin = tHeadHeight +"px 0px 0px 0px";
	stbpp.style.width = stbp.clientWidth;
	stbpp.appendChild(stbp);

	// append a spacer TD to our header, to cover the scroll bar top
	// not needed, as we've moved the new header to be inline with the top of the scroll bar
	// var oTD = document.createElement("td");
	// oTD.innerHTML = "&nbsp;";
	// oTR.appendChild( oTD );
	
	// move our header node to its proper location
	stbp.parentNode.insertBefore( oHeader, stbp );
	oHeader.appendChild(oTHead.cloneNode(true));
	// oHeader.appendChild(oTHead);

	resizeScrollingTableBodyHeader( stb, oHeader );
	oHeader.style.width = stb.offsetWidth + newHeaderFudgeWidth +"px";
//	alert('header height 1'+oHeader.style.height);	

}
function resizeScrollingTableBodyHeader( oT, oH ) {

	// can we get these automatically?
	var tdPadding = 6; // must match: table.scrollingTableBody TD { padding; }

	var oTtr = getFirstChildNodeOfType( oT, "TR" );
	var oHtr = getFirstChildNodeOfType( oH, "TR" );
	var i=0; var j=0;
	while( i<oTtr.childNodes.length && j<oHtr.childNodes.length ) {
		while( i<oTtr.childNodes.length && oTtr.childNodes[i].tagName!="TD" ) ++i;
		while( j<oHtr.childNodes.length && oHtr.childNodes[j].tagName!="TD" ) ++j;
		if( i<oTtr.childNodes.length && j<oHtr.childNodes.length && 
			oHtr.childNodes[j].clientWidth != oTtr.childNodes[i].clientWidth ) {
			oHtr.childNodes[j].style.width = oTtr.childNodes[i].clientWidth-(2*tdPadding) +"px";
//			oHtr.childNodes[j].style.width = oTtr.childNodes[i].clientWidth;
					}
//		alert( "id="+oHtr.childNodes[j].id + " " + oHtr.childNodes[j].clientWidth + ", id=" + oTtr.childNodes[i].id + " " + oTtr.childNodes[i].clientWidth );
		++i; ++j;
	}
}
function getFirstChildNodeOfType( oNode, type ) {
	// search breadth-first
	for( var i=0; i<oNode.childNodes.length; ++i ) {
		if( oNode.childNodes[i].tagName==type ) {
			return oNode.childNodes[i];
		}
	}
	for( var i=0; i<oNode.childNodes.length; ++i ) {
		if( oNode.childNodes[i].hasChildNodes() ) {
			var oChildNode = getFirstChildNodeOfType( oNode.childNodes[i], type );
			if( oChildNode!=null ) return oChildNode;
		}
	}
	return null;
}
