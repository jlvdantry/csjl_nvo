function leearchivo ( ) {  }
leearchivo.prototype.change = function () { }
leearchivo.prototype.valor  = function () { return document.getElementById("_idar_").value; }

leearchivo.prototype.create = function ( doc ) {
        if ( doc == null ) doc = document;
        var hF = doc.getElementById("formpr");
        var headerTable = doc.createElement( "table" );
        headerTable.className = "headerTable";
        headerTable.cellSpacing = 0;
        hF.appendChild( headerTable );
        var tBody = doc.createElement( "tbody" );
        headerTable.appendChild( tBody );
        var tr = doc.createElement( "tr" );
        tBody.appendChild( tr );
        var td = doc.createElement( "td" );
        this._desc = doc.createElement( "input" );
        this._desc.className = "foco";
        this._desc.setAttribute("type", "file");
        this._desc.setAttribute("name", "ficheroin");
        this._desc.setAttribute("id", "ficheroin");
        this._desc.setAttribute("value", "jala");
        this._desc.setAttribute("size", "30");
        td.appendChild( this._desc );
        tr.appendChild( td );
        this._desc.click();
}
