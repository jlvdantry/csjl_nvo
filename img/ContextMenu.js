
     var WebFX_PopUp="";
     var  WebFX_PopUpcss="";
ContextMenu.intializeContextMenu=function()
{
try {  // firefox
	document.body.insertAdjacentHTML("BeforeEnd", '<iframe scrolling="no" class="WebFX-ContextMenu" marginwidth="0" marginheight="0" frameborder="1" style="position:absolute;display:none;z-index:50000000;" name="WebFX_PopUp" id="WebFX_PopUp" ></iframe>');
        WebFX_PopUp    = document.getElementById("WebFX_PopUp").contentWindow;
	WebFX_PopUpcss = document.getElementById("WebFX_PopUp");
	document.body.attachEvent("onmousedown",function(){WebFX_PopUpcss.style.display="none"});
	WebFX_PopUpcss.onfocus  = function(){WebFX_PopUpcss.style.display="inline";;};
	WebFX_PopUpcss.onblur  = function(){WebFX_PopUpcss.style.display="none";;};
	document.body.attachEvent("onblur",function(){WebFX_PopUpcss.style.display="none";})
} catch (err) { alert("error ContextMenu.intializeContextMenu="+err) }
}

function ContextSeperator(){}

function ContextMenu(){}

ContextMenu.showPopup=function(x,y)
	{
		WebFX_PopUpcss.style.display = "block";
	}

ContextMenu.display=function(popupoptions)
	{
//        alert('entro showpoup');
	    var eobj,cmdx,cmdy;
    	eobj = window.event;
//firefox	x    = eobj.x;
//firefox	y    = eobj.y
        if (!e) var e = window.event;
	if (e.pageX || e.pageY) 	{
		posx = e.pageX;
		posy = e.pageY;
	}
	else if (e.clientX || e.clientY) 	{
		posx = e.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = e.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}
       //posx = eobj.clientX;
       //posy = eobj.clientY;

	

/*
	not really sure why I had to pass window here
	it appears that an iframe inside a frames page
	will think that its parent is the frameset as
	opposed to the page it was created in...
	*/
        ContextMenu.showPopup(posx,posy);
	ContextMenu.populatePopup(popupoptions,window)
	ContextMenu.fixSize();
	ContextMenu.fixPos(posx,posy);
        eobj.cancelBubble = true;
        eobj.returnValue  = false;
	}

 ContextMenu.getScrollTop=function()
  {
//	  alert('document.body.scrollTop='+document.body.scrollTop+' window.pageXOffset='+window.pageXOffset);
   	//return document.body.scrollTop;
	//window.pageXOffset and window.pageYOffset for moz
        return 0;
 }

   ContextMenu.getScrollLeft=function()
   {
     	return document.body.scrollLeft;
 }

  ContextMenu.fixPos=function(x,y)
  {
  	var docheight,docwidth,dh,dw;	
	docheight = document.body.clientHeight;
	docwidth  = document.body.clientWidth;
	dh = (WebFX_PopUpcss.offsetHeight+y) - docheight;
	dw = (WebFX_PopUpcss.offsetWidth+x)  - docwidth;
	if(dw>0)
	{
	WebFX_PopUpcss.style.left = (x - dw) + ContextMenu.getScrollLeft() + "px";		
	}
	else
	{
	WebFX_PopUpcss.style.left = x + ContextMenu.getScrollLeft() + "px";
        }
	if(dh>0)
	{
		WebFX_PopUpcss.style.top = (y - dh) + ContextMenu.getScrollTop() + "px"
	}
	else
	{
		WebFX_PopUpcss.style.top  = y + ContextMenu.getScrollTop() + "px";
	}
}

	ContextMenu.fixSize=function()
	{
		try
		{
			var body,h,w;
                        h=0; w=0;
			WebFX_PopUpcss.style.width = "10%";
			WebFX_PopUpcss.style.height = "10%";
		        body = WebFX_PopUp.document.body;
/*
                        alert ('\nXbody.scrollHeight='+body.scrollHeight+
                               '\nbody.scrollWidth='+body.scrollWidth+
                               '\nWebFX_PopUpcss.offsetHeight='+WebFX_PopUpcss.offsetHeight+
                               '\nWebFX_PopUpcss.offsetWidth='+WebFX_PopUpcss.offsetWidth+
                               '\nbody.clientHeight='+body.clientHeight+
                               '\nbody.clientWidth='+body.clientWidth+
                               '\nh='+h+
                               '\nw='+w);
*/
	// check offsetHeight twice... fixes a bug where scrollHeight is not valid because the visual state is undefined
			var dummy = WebFX_PopUpcss.offsetHeight + " dummy";
			h = body.scrollHeight + WebFX_PopUpcss.offsetHeight - body.clientHeight;
			w = body.scrollWidth + WebFX_PopUpcss.offsetWidth - body.clientWidth;
			WebFX_PopUpcss.style.height = h + "px";
			WebFX_PopUpcss.style.width = w + "px";
/*
                        alert ('\n2body.scrollHeight='+body.scrollHeight+
                               '\nbody.scrollWidth='+body.scrollWidth+
                               '\nWebFX_PopUpcss.offsetHeight='+WebFX_PopUpcss.offsetHeight+
                               '\nWebFX_PopUpcss.offsetWidth='+WebFX_PopUpcss.offsetWidth+
                               '\nbody.clientHeight='+body.clientHeight+
                               '\nbody.clientWidth='+body.clientWidth+
                               '\nh='+h+
                               '\nw='+w);
*/
		}
		catch (err)
		{
			alert('error fixsize '+err);
		}
//		alert("body"+body+" body.clientHeight"+body.clientHeight+" WebFX_PopUpcss.offsetHeight="+WebFX_PopUpcss.offsetHeight);
	//use document.height for moz
	}
ContextMenu.populatePopup=function(arr,win)
{
       try {  // firefox
		var alen,i,tmpobj,doc,height,htmstr;
		alen = arr.length;
		doc  = WebFX_PopUp.document;
		doc.body.innerHTML  = "";
		if (doc.getElementsByTagName("LINK").length == 0) {
			doc.open();
			doc.write('<html><head><link rel="StyleSheet" type="text/css" href="WebFX-ContextMenu.css"></head><body></body></html>');
			doc.close();
		}
		doc.body.className  = "WebFX-ContextMenu-Body" ;
		for(i=0;i<alen;i++)
		{
			if(arr[i].constructor==ContextItem)
			{
				tmpobj=doc.createElement("div");
				tmpobj.noWrap = true;
				tmpobj.className = "WebFX-ContextMenu-Item";
				if(arr[i].disabled)
				{
					htmstr  = '<span class="WebFX-ContextMenu-DisabledContainer"><span class="WebFX-ContextMenu-DisabledContainer">'
					htmstr += arr[i].text+'</span></span>'
					tmpobj.innerHTML = htmstr
					tmpobj.className = "WebFX-ContextMenu-Disabled";
					tmpobj.onmouseover = function(){this.className="WebFX-ContextMenu-Disabled-Over"}
					tmpobj.onmouseout  = function(){this.className="WebFX-ContextMenu-Disabled"}
				}
				else
				{
					tmpobj.innerHTML = arr[i].text;
					tmpobj.onclick = (function (f)
					{	
					 return function () {
							win.WebFX_PopUpcss.style.display='none'
					    		if (typeof(f)=="function"){ f(); }
					 					};
					})(arr[i].action);
					tmpobj.onmouseover = function(){this.className="WebFX-ContextMenu-Over"}
					tmpobj.onmouseout  = function(){this.className="WebFX-ContextMenu-Item"}
				}
				doc.body.appendChild(tmpobj);
			}
			else
			{
				doc.body.appendChild(doc.createElement("div")).className = "WebFX-ContextMenu-Separator";
			}
		}
//		alert('doc.body.innerHTML='+doc.body.innerHTML);
//firefox		doc.body.className  = "WebFX-ContextMenu-Body" ;
//firefox hay que checar este punto		doc.body.onselectstart = function(){return false;}
	} 
catch (err) { alert('error =ContextMenu.populatePopup'+err) }
}

function ContextItem(str,fnc,disabled)
{
	this.text     = str;
	this.action   = fnc; 
	this.disabled = disabled || false;
}

