var svgNS = "http://www.w3.org/2000/svg",
xlinkNS = "http://www.w3.org/1999/xlink";
var createSVGElement = function(o) {
  for (var p in o) {
    var value = o[p];
    switch(p) {
      case "element" : var element = document.createElementNS(svgNS, o.element);
      break;
      case "textNode" : element.appendChild(document.createTextNode(value));
      break;
      case "appendTo" : value.appendChild(element);
      break;
      default : element.setAttributeNS((p == "xlink:href") ? 
                xlinkNS : null, p, value);
    }
  }
  return element;
};
