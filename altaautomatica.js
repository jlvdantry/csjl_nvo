function altaautomatica ( ) { }
altaautomatica.prototype.change = function () { }
altaautomatica.prototype.valor  = function () { return document.getElementById("_desc_").value; }
altaautomatica.prototype.focus  = function () { return document.getElementById("_desc_").focus(); }
altaautomatica.prototype.create = function ( doc ) {
        if ( doc == null ) doc = document;

        this._document = doc;

        // create elements
        this._el = doc.createElement( "div" );
        this._el.className = "pupan";
        this._el.setAttribute('id', 'altaautomatica_');
        this._el.setAttribute('name', 'altaautomatica_');

        // header
        var div = doc.createElement( "div" );
        div.className = "header";
        this._el.appendChild( div );

        var headerTable = doc.createElement( "table" );
        headerTable.className = "headerTable";
        headerTable.cellSpacing = 0;
        div.appendChild( headerTable );

        var tBody = doc.createElement( "tbody" );
        headerTable.appendChild( tBody );

        var tr = doc.createElement( "tr" );
        tBody.appendChild( tr );
        var td = doc.createElement( "td" );
        this._desc = doc.createElement( "input" );
        this._desc.className = "foco";
        this._desc.setAttribute("type", "text");
        this._desc.setAttribute("id", "_desc_");
        this._desc.setAttribute("value", "");
        this._desc.setAttribute("size", "30");
//        this._desc.setAttribute("onblur", "");
//        this._desc.onblur = function () { dp.change(); }
        td.appendChild( this._desc );
        this._alta = doc.createElement( "button" );
        this._alta.setAttribute("type", "button");
        td.appendChild( this._alta );
        tr.appendChild( td );
        var dp = this;
        this._desc.onblur = function () { dp.change(); };
        this._desc.onkeypress = function () { return quitaenter(event); };
        this._desc.onkeyup = function () { mayusculas(this,event); };
        this._desc.focus();
        return this._el;
}

