function do_search(submit) {

  var searchText = document.getElementById('query');
  var searchForm = document.getElementById('search_form');

  if (searchText.value == "") {
    searchText.focus();
    alert('Please enter search text');
    if (submit)
      return false;
  } else {
    var searchValue = searchText.value.replace(/[^A-Za-z0-9 +-]/g, "");
    searchValue = searchText.value.replace(/[ ]/g, "-");
    document.location = searchForm.action + searchValue.toLowerCase() + '/';
    return false;
  }

}

function SetImageWidth() {
  var ImageMaxWidth = 700;

  screenshotImage = document.getElementById("ScreenshotImage");
 
  if (screenshotImage) {
      if (screenshotImage.width > ImageMaxWidth) {
        screenshotImage.width = ImageMaxWidth;
      }
  }
}

/* top menu code */

var menu_speed = 15;
var menu_initialized = false;
var menu_current = new Array();
var menu_hide_timeout = 0;
var menu_anim_timeout = 0;

var menu_anim = new Array();
function menu_anim_step(){
    menu_anim_timeout = window.setTimeout("menu_anim_step();", 10);
    for (var i=0; i<menu_anim.length; i++)
    {
        if (menu_anim[i]==null)continue;
        menu_anim[i].height += menu_anim[i].step;
        var h = menu_anim[i].height;
        var n = menu_anim[i].node;
        if (menu_anim[i].step<0)
        {
            if (menu_anim[i].height<=menu_anim[i].limit){
                h = menu_anim[i].limit;
                menu_anim[i] = null;
            }
        }
        else {
            if (menu_anim[i].height>=menu_anim[i].limit){
                h = menu_anim[i].limit;
                menu_anim[i] = null;
            }
        }
        n.style.height = h+"px";
        if (h==0)
        {
            n.style.height = "1px";
            n.style.visibility = 'hidden';
        }
        else {
            n.style.visibility = 'visible';
        }
    }
}
menu_anim_step();

function menu_render(id, items){
    var div = document.createElement("div");
    var html = "<table celpadding='0' cellspacing='0' border='0' width='100%'>";
    for (var j=0; j<items.items.length; j++)
    {
        if (items.items[j]['sub']){
            html += '<tr><td onmouseover="this.className=\'over\'; menu_over(this, \''+id+':'+j+'\');" onmouseout="this.className=\'\'; menu_out(this, \''+id+':'+j+'\');" onclick="menu_click(\''+id+':'+j+'\');menu_out(this, \''+id+':'+j+'\');">&nbsp;&nbsp;&nbsp;'+items.items[j].title+'</td></tr>';
            items.items[j].node = menu_render(id+':'+j, items.items[j]['sub']);
            items.items[j].parent = div;
        }
        else {
            html += '<tr><td onmouseover="this.className=\'over\'; menu_over(this, \''+id+':'+j+'\');" onmouseout="this.className=\'\'; menu_out(this, \''+id+':'+j+'\');" onclick="menu_click(\''+id+':'+j+'\');menu_out(this, \''+id+':'+j+'\');">&nbsp;&nbsp;&nbsp;'+items.items[j].title+'</td></tr>';
        }
    }
    html += '</table>';
    if (items.className)
    {
        div.className = "menu "+items.className;
    }
    else {
        div.className = "menu";
    }
    div.style.position = 'absolute';
    div.style.overflow = 'hidden';
    div.style.left = '250px';
    div.style.top = '150px';
    div.style.visibility = 'hidden';
    if (id.indexOf(':')==-1)
        div.style.height = '1px';
    div.innerHTML = html;
    document.body.appendChild(div);
    return div;
}

function menu_init(){
	//alert('entro menu_init');
    if (menu_initialized)return;
    menu_initialized = true;

    if (!menu_data)return;

    for (var i in menu_data)
    {
        menu_data[i].node = menu_render(i, menu_data[i]);
    }
}

function menu_node_pos(n){
    if (n.offsetParent)
    {
        var pos = menu_node_pos(n.offsetParent);
        pos.x+=n.offsetLeft;
        pos.y+=n.offsetTop;
        pos.w+=n.offsetWidth;
        pos.h+=n.offsetHeight;
        return pos;
    }
    return {x:n.offsetLeft, y:n.offsetTop, w:n.offsetWidth, h:n.offsetHeight};
}

function menu_click(id){
    var cur = menu_data;
    var tmp = id.split(/:/);
    var path = new Array();
    for (var i=0;i<tmp.length;i++)
    {
        if (!cur||!cur[tmp[i]]){
            break;
        }
        path[i] = cur[tmp[i]];
        if (!cur[tmp[i]].items)
        {
            if (cur[tmp[i]].sub)
                cur = cur[tmp[i]].sub.items;
            else
                break;
        }
        else
            cur = cur[tmp[i]].items;
    }
    if (path.length==0)return;
    var item = path[path.length-1];
    
    if (item.url)
    {
	    if (item.title=='Salir')
	    	{	salir();	}
	    else if (item.title=='Cerrar')
	    	{	menu_out(this, id);	}
	    else
	    	{	window.open(item.url,'pantallas');	}
    }
}

/*
* funcion que muestra el cotenido del menu
**/
function menu_over(n, id){
	//alert (n.value+' '+id);
	//alert (menu_initialized);
    if (!menu_initialized)return;
	//alert ('menuok');
    var menu_item = null;
    var cur = menu_data;
    var tmp = id.split(/:/);
    var path = new Array();
    //alert ('termino for');
    for (var i=0;i<tmp.length;i++)
    {
	    //alert ('entro en for '+i);
        if (!cur||!cur[tmp[i]]){
	        //alert ('freno');
            break;
        }
        path[i] = cur[tmp[i]];
        cur = cur[tmp[i]].items;
    }
	//alert ('paso for');
    window.clearTimeout(menu_hide_timeout);

    for (var i=path.length; i<menu_current.length; i++ )
    {
        if (menu_current[i]==null)break;
        if (menu_current[i].node){
            if (i==0)
                menu_hide_first(menu_current[i]);
            else
                menu_current[i].node.style.visibility = 'hidden';
        }
        menu_current[i] = null;
    }

    var i = path.length-1;
    while (i>=0)
    {
        if (menu_current[i])
        {
            if (menu_current[i]==path[i])break;
            if (menu_current[i].node){
                if (i==0)
                    menu_hide_first(menu_current[i]);
                else
                    menu_current[i].node.style.visibility = 'hidden';
            }
            menu_current[i] = null;
        }
        i--;
    }
    i++;

    if (i==path.length)return;
    while (i<path.length)
    {
        if (i==path.length-1 && n && path[i].node)
        {
            var pos = menu_node_pos(n);
            //alert (pos.x+','+pos.y+','+pos.w+','+pos.h);
            if (path[i].offsetLeft)
            {
                pos.x+=path[i].offsetLeft;
            }
            if (i==0)
            {
                path[i].node.style.left = pos.x+'px';
                path[i].node.style.top = pos.y+25+'px';
            }
            else {
                path[i].node.style.left = pos.x+n.offsetWidth-1+'px';
                path[i].node.style.top = pos.y+'px';
            }
        }
        if (path[i].node){
            if (i==0)
                menu_show_first(path[i]);
            else
                path[i].node.style.visibility = 'visible';
                path[i].node.style.width = 170+'pt';
        }
        menu_current[i] = path[i];
        i++;
    }
}

function menu_out(n, id){
//	alert('salio');
    if (!menu_initialized)return;
    window.clearTimeout(menu_hide_timeout);
    menu_hide_timeout = window.setTimeout("menu_hide();", 300);
}

function menu_hide(){
	//alert ('menuhide');
    for (var i=0; i<menu_current.length; i++ )
    {
        if (menu_current[i]==null)break;
        if (menu_current[i].node)
        {
            if (i==0)
                menu_hide_first(menu_current[i]);
            else
                menu_current[i].node.style.visibility = 'hidden';
        }
        menu_current[i] = null;
    }
}

function menu_hide_first(n){
    var anim = null;
    var pos = menu_anim.length;
    for (var i=0; i<menu_anim.length; i++)
    {
        if (!menu_anim[i])pos = i;
        if (menu_anim[i]&&menu_anim[i].node==n.node)
        {
            anim = menu_anim[i];
            break;
        }
    }
    if (anim==null)
    {
        anim = {node: n.node};
        menu_anim[pos] = anim;
    }
    anim.height = n.node.offsetHeight;
    n.node.style.height = anim.height+"px";
    anim.limit = 0;
    anim.step = -(menu_speed*3);
}
function menu_show_first(n){
	//alert ('entro menu_show_first');
    var anim = null;
    var pos = menu_anim.length;
    for (var i=0; i<menu_anim.length; i++)
    {
        if (!menu_anim[i])pos = i;
        if (menu_anim[i]&&menu_anim[i].node==n.node)
        {
            anim = menu_anim[i];
            break;
        }
    }
    if (anim==null)
    {
        anim = {node: n.node};
        menu_anim[pos] = anim;
    }
    anim.height = n.node.offsetHeight;
    n.node.style.height = anim.height+"px";
    anim.limit = n.node.scrollHeight+10;
    anim.step = menu_speed;
}

