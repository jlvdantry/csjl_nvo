/*
 * SUBMODAL v1.6
 * Used for displaying DHTML only popups instead of using buggy modal windows.
 *
 * By Subimage LLC
 * http://www.subimage.com
 *
 * Contributions by:
 * 	Eric Angel - tab index code
 * 	Scott - hiding/showing selects for IE users
 *	Todd Huss - inserting modal dynamically and anchor classes
 *
 * Up to date code can be found at http://submodal.googlecode.com
 */

// Popup code
function SubMod(url,w,h) {
   this._PopupMask = null;
   this._PopupContainer = null;
   this._PopFrame = null;
   this._ReturnFunc;
   this._PopupIsShown = false;
   this._DefaultPage = "cargando.php";
   this._HideSelects = false;
   this._ReturnVal = null;
   this._doc = null;
   this._boton = null;
   if (window.parent.document.getElementById('soldatos'))
   { this._doc = window.parent.document; }
   if (!this._doc) { this._doc = document; }

   this._TabIndexes = new Array();
   // Pre-defined list of tags we want to disable/enable tabbing into
   this._TabbableTags = new Array("A","BUTTON","TEXTAREA","INPUT","IFRAME");	

   // If using Mozilla or Firefox, use Tab-key trap.
   if (!document.all) {
	document.onkeypress = SubMod.keyDownHandler;
   }
	// Add the HTML to the body
	theBody = this._doc.getElementById('soldatos');
        var gurl=url.replace(/&/g,"_").replace(/\"/g,"_").replace(/=/g,"_");
	//theBody = gbody;
	popmask = document.createElement('div');
	popmask.id = 'popupMask'+gurl;
	popmask.className = 'popupMask';
	popcont = document.createElement('div');
	popcont.id = 'popupContainer'+gurl;
	popcont.className = 'popupContainer';
	popcont.innerHTML = '' +
		'<div class="popupInner" id="popupInner">' +
			'<div class="popupTitleBar" id="popupTitleBar'+gurl+'">' +
				'<div class="popupTitle" id="popupTitle'+gurl+'"></div>' +
				'<div class="popupControls" id="popupControls'+gurl+'">' +
					'<img src="img/close.gif" onclick="SubMod.hidePopWin(false,this);" id="popCloseBox'+gurl+'" />' +
				'</div>' +
			'</div>' +
			'<iframe src="'+ this._DefaultPage +'" style="width:100%;height:100%;background-color:transparent;" scrolling="auto" frameborder="0" allowtransparency="true" class="popupFrame" id="popupFrame'+gurl+'" name="popupFrame" width="100%" height="100%"></iframe>' +
		'</div>';
	theBody.appendChild(popmask);
	theBody.appendChild(popcont);
	
	this._PopupMask = this._doc.getElementById("popupMask"+gurl);
	this._PopupContainer = this._doc.getElementById("popupContainer"+gurl);
	this._PopFrame = this._doc.getElementById("popupFrame"+gurl);	
        var theHandle = this._doc.getElementById("popupTitleBar"+gurl); var theRoot = this._PopupContainer; Drag.init(theHandle, theRoot);
	
	// check to see if this is IE version 6 or lower. hide select boxes if so
	// maybe they'll fix this in version 7?
	var brsVersion = parseInt(window.navigator.appVersion.charAt(0), 10);
	//if (brsVersion <= 6 && window.navigator.userAgent.indexOf("MSIE") > -1) {
//		this._HideSelects = true;
//	}
	
	// Add onclick handlers to 'a' elements of class submodal or submodal-width-height
	var elms = document.getElementsByTagName('a');
	for (i = 0; i < elms.length; i++) {
		if (elms[i].className.indexOf("submodal") == 0) { 
			// var onclick = 'function (){showPopWin(\''+elms[i].href+'\','+width+', '+height+', null);return false;};';
			// elms[i].onclick = eval(onclick);
			elms[i].onclick = function(){
				// default width and height
				var width = 400;
				var height = 200;
				// Parse out optional width and height from className
				params = this.className.split('-');
				if (params.length == 3) {
					width = parseInt(params[1]);
					height = parseInt(params[2]);
				}
				showPopWin(this.href,width,height,null); return false;
			}
		}
	}
        //addEvent(window, "resize", SubMod.centerPopWin);
        //addEvent(window, "scroll", SubMod.centerPopWin);
        // verificar porque truena
        //window.onscroll = SubMod.centerPopWin;
}
SubMod.termina = function () { };
SubMod.prototype.regresa = function (url) { 
                   //var docx=window.parent.document;
                   //var url=document.URL.substring(document.URL.indexOf("man_menus"));
                   var url=url.replace(/&/g,"_").replace(/\"/g,"_").replace(/=/g,"_");
                   var bclose=document.getElementById("popCloseBox"+url);
                   return bclose.abbr;
          };

 /**
	* @argument width - int in pixels
	* @argument height - int in pixels
	* @argument url - url to display
	* @argument returnFunc - function to call when returning true from the window.
	* @argument showCloseBox - show the close box - default true
	*/
SubMod.prototype.showPopWin = function (url, width, height, returnFunc, showCloseBox) {
        var gurl=url.replace(/&/g,"_").replace(/\"/g,"_").replace(/=/g,"_");
        if(!width) width=450;
        if(!height) height=430;
	// show or hide the window close widget
	if (showCloseBox == null || showCloseBox == true) {
		this._doc.getElementById("popCloseBox"+gurl).style.display = "block";
	} else {
		this._doc.getElementById("popCloseBox"+gurl).style.display = "none";
	}
	this._PopupIsShown = true;
	this.disableTabIndexes();
	this._PopupMask.style.display = "block";
	this._PopupContainer.style.display = "block";
	this._PopFrame.src = url;
        var height=this._PopFrame.contentWindow.document.body.scrollHeight;
        var width=this._PopFrame.contentWindow.document.body.scrollWidth;
        this._PopupContainer.style.height=height;
	// calculate where to place the window on screen
	this.centerPopWin(width, height,gurl);
	var titleBarHeight = parseInt(this._doc.getElementById("popupTitleBar"+gurl).offsetHeight, 10);
	this._PopupContainer.style.width = width + "px";
	this._PopupContainer.style.height = (height+titleBarHeight) + "px";
	
	this.setMaskSize();

	// need to set the width of the iframe to the title bar width because of the dropshadow
	// some oddness was occuring and causing the frame to poke outside the border in IE6
	this._PopFrame.style.width = parseInt(this._doc.getElementById("popupTitleBar"+gurl).offsetWidth, 10) + "px";
	this._PopFrame.style.height = (height) + "px";
	
	// set the url
	//this._PopFrame.src = url;
	
	this._ReturnFunc = returnFunc;
	// for IE
	//if (this._HideSelects == true) {
	//	this.hideSelectBoxes();
	//}
	
	window.setTimeout("SubMod.setPopTitle();", 600);
}

SubMod.prototype.centerPopWin = function (width, height,gurlx) {
	if (this._PopupIsShown == true) {
		if (width == null || isNaN(width)) {
			width = this._PopupContainer.offsetWidth;
		}
		if (height == null) {
			height = this._PopupContainer.offsetHeight;
		}
		
		//var theBody = document.documentElement;
		//var theBody = parent.window.document.getElementsByTagName("body")[0];
		var theBody = this._doc.getElementById("soldatos");
		//theBody.style.overflow = "hidden";
		//var scTop = parseInt(getScrollTop(),10);
		var scTop = parseInt(theBody.scrollTop,10);
		var scLeft = parseInt(theBody.scrollLeft,10);
	
		this.setMaskSize();
		
		//window.status = gPopupMask.style.top + " " + gPopupMask.style.left + " " + gi++;
		
	        var titleBarHeight = parseInt(this._doc.getElementById("popupTitleBar"+gurlx).offsetHeight, 10);
		var fullHeight = getViewportHeight();
		var fullWidth = getViewportWidth();
		
		//gPopupContainer.style.top = (scTop + ((fullHeight - (height+titleBarHeight)) / 2)) ;
		//gPopupContainer.style.left =  (scLeft + ((fullWidth - width) / 2)) + "px";
		var xtop = (scTop + ((fullHeight - (height+titleBarHeight)) / 2)) ;
		var xleft =  (scLeft + ((fullWidth - width) / 2)) + "px";
                if (xtop<=0) { xtop=0 ; } ;
		this._PopupContainer.style.top = xtop ;
		this._PopupContainer.style.left =  xleft;
                if (this._PopupContainer.style.top.indexOf("px")==-1) { this._PopupContainer.style.top=this._PopupContainer.style.top+'px' }
                if (this._PopupContainer.style.left.indexOf("px")==-1) { this._PopupContainer.style.left=this._PopupContainer.style.left+'px' }
		//alert(fullWidth + " " + width + " " + gPopupContainer.style.left);
	}
}

/**
 * Sets the size of the popup mask.
 *
 */
SubMod.prototype.setMaskSize = function () {
	//var theBody = window.parent.document.getElementsByTagName("body")[0];
	var theBody = this._doc.getElementById("soldatos");
			
	var fullHeight = getViewportHeight();
	var fullWidth = getViewportWidth();
	
	// Determine what's bigger, scrollHeight or fullHeight / width
	if (fullHeight > theBody.scrollHeight) {
		popHeight = fullHeight;
	} else {
		popHeight = theBody.scrollHeight;
	}
	
	if (fullWidth > theBody.scrollWidth) {
		popWidth = fullWidth;
	} else {
		popWidth = theBody.scrollWidth;
	}
	
	this._PopupMask.style.height = popHeight + "px";
	this._PopupMask.style.width = popWidth + "px";
}

/**
 * @argument callReturnFunc - bool - determines if we call the return function specified
 * @argument returnVal - anything - return value 
 */
SubMod.hidePopWin = function (callReturnFunc,obj,url) {
        if (obj)
        { var gurl=obj.id.substr(11); }
        if (url)
        { var gurl=url; }
        //gurl=gurl.replace("&","_").replace("\"","_").replace("=","_");

        //alert('boton'+gurl);
        SubMod.termina(gurl);
	//this._PopupIsShown = false;
	//var theBody = windows.parent.document.getElementsByTagName("body")[0];
	var theBody = document.getElementById("soldatos");
	//var theBody = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
	theBody.style.overflow = "";
	//this.restoreTabIndexes();
//	if (gPopupMask == null) {
//		return;
//	}
//	gPopupMask.style.display = "none";
//	gPopupContainer.style.display = "none";
        var Pmask = document.getElementById("popupMask"+gurl);
        var Pcont = document.getElementById("popupContainer"+gurl);
	if (callReturnFunc == true && gReturnFunc != null) {
		// Set the return code to run in a timeout.
		// Was having issues using with an Ajax.Request();
		gReturnVal = window.frames["popupFrame"].returnVal;
		window.setTimeout('gReturnFunc(gReturnVal);', 1);
	}
        //	gPopFrame.src = gDefaultPage;
	// display all select boxes
	//if (this._HideSelects == true) {
	//this.displaySelectBoxes();
	//}
        theBody.removeChild(Pmask);
        theBody.removeChild(Pcont);
}

/**
 * Sets the popup title based on the title of the html document it contains.
 * Uses a timeout to keep checking until the title is valid.
 */
SubMod.setPopTitle = function () {
	return;
	if (window.frames["popupFrame"].document.title == null) {
		window.setTimeout("SubMod.setPopTitle();", 10);
	} else {
		document.getElementById("popupTitle").innerHTML = window.frames["popupFrame"].document.title;
	}
}

// Tab key trap. iff popup is shown and key was [TAB], suppress it.
// @argument e - event - keyboard event that caused this function to be called.
SubMod.keyDownHandler = function (e) {
    if (gPopupIsShown && e.keyCode == 9)  return false;
}

// For IE.  Go through predefined tags and disable tabbing into them.
SubMod.prototype.disableTabIndexes = function () {
	if (document.all) {
		var i = 0;
		for (var j = 0; j < this._TabbableTags.length; j++) {
			var tagElements = document.getElementsByTagName(this._TabbableTags[j]);
			for (var k = 0 ; k < tagElements.length; k++) {
				this._TabIndexes[i] = tagElements[k].tabIndex;
				tagElements[k].tabIndex="-1";
				i++;
			}
		}
	}
}

// For IE. Restore tab-indexes.
SubMod.prototype.restoreTabIndexes = function () {
	if (document.all) {
		var i = 0;
		for (var j = 0; j < this._TabbableTags.length; j++) {
			var tagElements = document.getElementsByTagName(this._TabbableTags[j]);
			for (var k = 0 ; k < tagElements.length; k++) {
				tagElements[k].tabIndex = this._TabIndexes[i];
				tagElements[k].tabEnabled = true;
				i++;
			}
		}
	}
}


/**
 * Hides all drop down form select boxes on the screen so they do not appear above the mask layer.
 * IE has a problem with wanted select form tags to always be the topmost z-index or layer
 *
 * Thanks for the code Scott!
 */
SubMod.prototype.hideSelectBoxes= function () {
  var x = document.getElementsByTagName("select");

  for (i=0;x && i < x.length; i++) {
    x[i].style.visibility = "hidden";
  }
}

/**
 * Makes all drop down form select boxes on the screen visible so they do not 
 * reappear after the dialog is closed.
 * 
 * IE has a problem with wanting select form tags to always be the 
 * topmost z-index or layer.
 */
SubMod.prototype.displaySelectBoxes = function () {
  var x = document.getElementsByTagName("select");
  for (i=0;x && i < x.length; i++){
    x[i].style.visibility = "visible";
  }
}
