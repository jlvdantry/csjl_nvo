window.onerror = myOnError;
msgArray = new Array();
urlArray = new Array();
lnoArray = new Array();

function myOnError(msg, url, lno)
{   msgArray[msgArray.length] = msg;
   urlArray[urlArray.length] = url;
   lnoArray[lnoArray.length] = lno;
   alert('error ' + url + ' linea ' + lno + ' ' + msg);
   return true;
}

// function switchDiv()
//  this function takes the id of a div
//  and calls the other functions required
//  to show that div
//
function switchDiv(div_id)
{
  var style_sheet = getStyleObject(div_id);
  if (style_sheet)
  {
    hideAll();
    changeObjectVisibility(div_id,"visible");
  }
  else 
  {
    alert("sorry, this only works in browsers that do Dynamic HTML");
  }
}
function showAndFocus(div_id, field_to_focus)
{
  var the_div = getStyleObject(div_id);
  if (the_div != false)
  {
   changeObjectVisibility(div_id, 'visible');
    field_to_focus.focus();
  }
}


// function hideAll()
//  hides a bunch of divs
//
function hideAll()
{
   changeObjectVisibility("ez","hidden");
   changeObjectVisibility("full","hidden");
   changeObjectVisibility("superduper","hidden");
}

// function getStyleObject(string) -> returns style object
//  given a string containing the id of an object
//  the function returns the stylesheet of that object
//  or false if it can't find a stylesheet.  Handles
//  cross-browser compatibility issues.
//
function getStyleObject(objectId) {
  // checkW3C DOM, then MSIE 4, then NN 4.
  //
  if(document.getElementById && document.getElementById(objectId)) {
	return document.getElementById(objectId).style;
   }
   else if (document.all && document.all(objectId)) {  
	return document.all(objectId).style;
   } 
   else if (document.layers && document.layers[objectId]) { 
	return document.layers[objectId];
   } else {
	return false;
   }
}

function changeObjectVisibility(objectId, newVisibility) {
    // first get a reference to the cross-browser style object 
    // and make sure the object exists
    var styleObject = getStyleObject(objectId);
    if(styleObject) {
	styleObject.visibility = newVisibility;
	return true;
    } else {
	// we couldn't find the object, so we can't change its visibility
	return false;
    }
}

function buildQueryString(theFormName) {
  theForm = document.forms[theFormName];
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='') {
      qs+=(qs=='')?'?':'&'
      qs+=theForm.elements[e].name+'='+escape(theForm.elements[e].value)
      }
    }
  return qs
}

function hayalgundatotecleado(theFormName) {
//          alert('entro'+theFormName+' name '+document.forms[0].name);
  theForm = document.forms[theFormName];
//          alert('despues the form');
  var qs = '';
  for (e=0;e<theForm.elements.length;e++) {
    if (theForm.elements[e].name!='') {
//          alert('tipo'+theForm.elements[e].type+" name "+theForm.elements[e].name+" value "+theForm.elements[e].value);
       if (theForm.elements[e].value!='' && theForm.elements[e].type!='button' && theForm.elements[e].type!='hidden' && theForm.elements[e].type!='reset') {
//          alert('name'+theForm.elements[e].name+'valor'+theForm.elements[e].value+'tipo'+theForm.elements[e].type);
          qs='si';
       }
    }
  }
//  alert('sale termino '+qs);
  return qs
}

