function altaadjuntara ( ) { this._tt_=null; }
altaadjuntara.prototype.change = function () { }
altaadjuntara.prototype.valor  = function () { return document.getElementById("_idar_").value; }
altaadjuntara.prototype.dclick  = function () { 
                  if (isIE==false)
                     { 
                          this._desc.click(); 
                     }
              }
altaadjuntara.prototype.checaftp = function () { 
    try {
               var _this = this;
               if (this._avance_.value.indexOf('Transmitio')>=0 ||
                   this._avance_.value.indexOf('Error')>=0) 
               {
                  //_this.change();
                  clearTimeout(this._tt_);
                  this._tt_=null;
                  return;
               }
               else
               {   
                   this._avance_.value+='.';
               }
               if (this._tt_==null)
               { 
                        this._tt_=setTimeout(function() { _this.checaftp()},1000); 
               }
          } catch (err) { alert('error en checaftp ='+err.description); }
    }

altaadjuntara.prototype.create = function ( doc ) {
        if ( doc == null ) doc = document;

        this._document = doc;

        // create elements
        this._el = doc.createElement( "div" );
        this._el.className = "pupan";
        this._el.setAttribute('id', 'altaadjuntara_');
        this._el.setAttribute('name', 'altaadjuntara_');

        // header
        var div = doc.createElement( "div" );
        div.className = "header";
        this._el.appendChild( div );

        this.hF = doc.createElement( "form" );
        this.hF.className = "headerTable";
        this.hF.cellSpacing = 0;
        this.hF.setAttribute("target","iframeUpload");
        this.hF.setAttribute("enctype","multipart/form-data");
        this.hF.setAttribute("name","_subearchivo_");
        this.hF.setAttribute("id","_subearchivo_");
        this.hF.setAttribute("action","altaadjuntaran.php");
        this.hF.setAttribute("method","post");
        this.hF.setAttribute("accept-charset","utf-8");
        if (isIE==true)
        this.hF.setAttribute("src", "javascript:'<script>window.onload=function(){document.write(\\'<script>document.domain=\\\""+doc.domain+"\\\";<\\\\/script>\\');document.close();};<\/script>'");
        //this.hF.setAttribute("document.domain", "forapi.dyndns-work.com");
        div.appendChild( this.hF );

        var headerTable = doc.createElement( "table" );
        headerTable.className = "headerTable";
        headerTable.cellSpacing = 0;
        this.hF.appendChild( headerTable );

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
        this._desc.setAttribute("value", "Teclee aqui una nueva opcion");
        this._desc.setAttribute("size", "30");
//        if (isIE==false)
//         this._desc.setAttribute("style", "display:none"); 
        td.appendChild( this._desc );
        this._avance_ = doc.createElement( "input" );
        this._avance_.setAttribute("name", "_avance_");
        this._avance_.setAttribute("id", "_avance_");
        this._avance_.setAttribute("value", "Muestra Avance");
        this._avance_.setAttribute("size", "30");
        this._avance_.setAttribute("readonly", "true");
        this._avance_.className="lee";
        _avance_=this._avance_;
        td.appendChild( this._avance_ );

        this._iar = doc.createElement( "input" );
        this._iar.setAttribute("name", "_idar_");
        this._iar.setAttribute("id", "_idar_");
        this._iar.setAttribute("value", "");
        this._iar.setAttribute("size", "30");
        this._iar.setAttribute("style", "display:none");
        td.appendChild( this._iar );

        tr.appendChild( td );
        var hFu = doc.createElement( "iframe" );
        hFu.cellSpacing = 0;
        hFu.setAttribute("id","iframeUpload");
        hFu.setAttribute("name","iframeUpload");
        //hFu.setAttribute("src","<html><body><p>Hello<\/p><\/body><\/html>");
        if (isIE==true)
        hFu.setAttribute("src", "javascript:'<script>window.onload=function(){document.write(\\'<script>document.domain=\\\""+doc.domain+"\\\";<\\\\/script>\\');document.close();};<\/script>'");
        hFu.setAttribute("style", "display:none");
        this.hF.appendChild( hFu );
        var dp = this;
        //this._desc.onblur = function () { dp.change(); };
        this._iar.onclick = function () { 
                             dp.change(); };
        this._desc.onchange = function () { 
                                try { 
                             //   _avance_.setAttribute("value", "Inicio transmision");
                                document.getElementById('_avance_').value="Inicio transmision";
                                dp.checaftp();
                                //document.getElementById('_subearchivo_').submit(); }
                                dp.hF.submit(); }
                                catch (err) { alert('error _desc onchange'+err.description); }
                                };
//        this._desc.click();
        return this._el;
}
