/**
 * Created by yajon_000 on 30/12/2014.
 */

var cLang = Class.create(
    {
        _langs: ['de','en','fr','tr','pt','es','it'],
        _tester: false,
        _names: {de:'Deutsch',
            en:'English',
            fr:'Fran&#231;ais',
            tr:'T&#252;rk&#231;e',
            pt:'portugu&#233;s',
            es:'Espa&#241;ol',
            it:'Italiano'},

        c: function(){ return $$('language'); },

        initialize: function()
        {
            this._current = $$$('meta[name=language]')[0].getAttribute('content');

            if(this.c())
                this.c().observe('click', this.select.bind(this));
        },

        select: function()
        {
            this.c().select('.lang').invoke('remove');

            var c = this.c().up();
            var other = '';
            if(!this._current || this._current=="")
                this._current = 'es';

            this._langs.without(this._current).each(function(l){ if((l!='pt' && l!='it') || this._tester) other += '<a href="language/'+l+'">'+this._names[l]+'</a>'; }.bind(this));

            c.insert('<div class="lang" style="display:none">'+other+'<a href="javascript:void(0)" onclick="Lang.close()" style="font-weight:bold;background:#347dce">'+this._names[this._current]+'</a></div>');
            c.down('.lang').clonePosition(this.c(),{setTop:false,setHeight:false,setWidth:false,offsetLeft:-13});
            c.down('.lang').appear({to:.95,duration:.8}).blindDown({duration:.7}); //.morph('height:'+(c.down('.lang').getHeight()-10)+'px;margin-top:-'+(c.down('.lang').getHeight()-20)+'px');
        },

        close: function()
        {
            this.c().up().down('.lang').blindUp({duration:.4}).fade({duration:.4});
        }
    });

var cUser = Class.create(
    {
        initialize: function()
        {
            if($$('nlogin'))
                $$('nlogin').observe('click', Login.open.bind(Login));
        }
    });

var cEvent = Class.create(
    {
        initialize: function(that)
        {
            that.addMethods({addEvent:this.register, fireEvent:this.fire});
        },

        register: function(listener, event)
        {
            this.Events[listener] = event;
        },

        fire: function(listener)
        {
            if(this.Events[listener])
                this.Events[listener]();
        }
    });

var cPage = Class.create(
    {
        _c: null,
        _n: null,
        Events: null,

        initialize: function(file, event)
        {
            this._c		= $$('content').down('div.center').setOpacity(.7);

            this.Events = Object.extend({
                onInsert:		null,
                onRecover:		null,
                afterRecover:	null
            }, event || { });

            new Ajax.Request(file, {parameters:'inline=true',onComplete:this.insert.bind(this)});
        },

        insert: function(t)
        {
            $$('content').setStyle({overflow:'hidden'});
            this.hide();

            this.fire('onInsert');
            this._c.insert({after:t.responseText});

            this._n = this._c.next().absolutize();
            this.addButton();

            this._n.setStyle({left:document.viewport.getWidth()+'px'});
            this._n.morph('left:'+((document.viewport.getWidth()-this._n.getWidth())/2)+'px', {duration:.5,afterFinish:function(){ $$('content').setStyle({overflow:'visible'}); this._n.setStyle({position:'static',height:'auto'}); }.bind(this)});
        },

        addEvent: function(listener, event)
        {
            this.Events[listener] = event;
        },

        fire: function(listener)
        {
            if(this.Events[listener])
                this.Events[listener]();
        },

        addButton: function()
        {
            this._n.insert({bottom:'<div style="margin:25px 0 0 0;width:100px" id="pBack">&larr; <span class="a">atr&#225;s</span></div>'});
            $$('pBack').observe('click', this.recover.bind(this));
        },

        hide: function()
        {
            this._c.absolutize().fade().setStyle({top:'30px'}).morph('left:-'+this._c.getWidth()+'px', {duration:.5});
        },

        recover: function()
        {
            this.fire('onRecover');
            $$('content').setStyle({overflow:'hidden'});

            this._c.morph('left:'+((document.viewport.getWidth()-this._n.getWidth())/2)+'px', {duration:1,afterFinish:function(){
                $$('content').setStyle({overflow:'visible'});
                this._c.setStyle({position:'static'});
                location.href = location.href.substr(0, location.href.indexOf('#'))+'#';
                this.fire('afterRecover');
            }.bind(this)}).appear();
            this._n.fade().absolutize().morph('left:'+document.viewport.getWidth()+'px');
        }
    });
var cUploader = Class.create(
    {
        overlay: false,
        confirmed: false,
        prompted: false,
        overlayAfterFinish: false,

        initialize: function(id)
        {
            uploadServer = 'http://fra-7m18-stor08.uploaded.net/';

            if(!$$('user_id'))
                return this.noUser(id);


            //YAHOO.widget.Uploader.SWFURL = uploadServer+'misc/uploader.swf';

            //crossdomain.xml exists on all storage nodes
            //serve swf from current domain, target storage node is set within upload method through uploadServer var
            YAHOO.widget.Uploader.SWFURL = 'misc/uploader.swf';

            this.uploader = new YAHOO.widget.Uploader(id);

            this.uploader.addListener('contentReady', function(){ this.uploader.setAllowMultipleFiles(true); }.bind(this));
            this.uploader.addListener('fileSelect', this.onFileSelect.bind(this));
            this.uploader.addListener('uploadProgress', this.onUploadProgress.bind(this));
            this.uploader.addListener('uploadCompleteData', this.onUploadComplete.bind(this));
            this.uploader.addListener('uploadError', this.onUploadError.bind(this));

            this._IDs 		= {};
            this._waiting	= [];
            this._uploading = 0;
            this._complete 	= 0;
        },

        noUser: function(id){
            $$(id).setStyle('cursor:pointer').observe('click', this.regShow.bind(this));
        },

        regShow: function(){
            this.ol({overlay:true,fixed:true,html:'<div style="margin:5px 20px" class="aC">'
            +'	<h2 style="margin-bottom:5px">Registrese para transferir datos, por favor</h2>'
            +'	<span style="display:none;line-height:18px">Um Dateien hochzuladen, wird ein kostenloser uploaded-Account benötigt.<br />'
            +'	Wenn Sie bereits einen Account besitzen, <span class="a" onclick="$$(\'ol\').fade();Login.open()">melden Sie sich an</span>.</span>'
            +'	<div class="inputbig" id="regfree"><form action="io/register/free" style="margin:50px auto 0">'
            +'		<input type="text" placeholder="eMail-Adresse" class="plain" name="mail" />'
            +'		<button class="big" type="submit">registrar</button>'
            +'	</form></div>'
            +'	<small id="regstatus" class="cL" style="display:block;margin-top:13px">La registraci&#243;n y el uso de una cuenta uploaded Free es <b>completamente gratis</b>.<br />'
            +'		Sus datos personales de acceso ser&#225;n enviados despu&#233;s de introducir Su correo electr&#243;nico.</small>'
            +'</div>'});
            $$('ol').setStyle('width:850px;height:240px');
            $$('ol').down('form').observe('submit', this.regFree.bind(this));
            $$$('body')[0].setStyle('overflow-x:hidden');
        },

        regFree: function(e)
        {
            e.stop();
            var c	 = $$('ol').down('form');
            var mail = c.down('input').value;

            if(!mail)
                return c.down('input').focus();

            c.stopObserving();
            c.down('button').update('<img src="img/l/buttonBig.gif" alt="..." />');

            c.request({onComplete:function(t){
                if(t.responseText)
                {
                    var t = t.responseText.evalJSON();

                    c.down('button').update('registrar');
                    c.observe('submit', this.regFree.bind(this));

                    $$('regstatus').blindUp({duration:.1,afterFinish:function(){
                        $$('regstatus').addClassName('error').update('<big>'+t.err+'</big>').blindDown({duration:.5});
                    }.bind(this)});
                    return c.down('input').select();
                }

                $$('regstatus').fade({duration:.2,to:.01});
                c.morph('margin-left:-'+($$('ol').getWidth()+200)+'px').fade({to:.01});
                c.insert({after:'<h2 style="margin-top:-50px">Gracias por Su registraci&#243;n!<br /><small>Sus datos de acceso ser&#225;n enviados a Su correo electr&#243;nico </small></h2>'});
                c.next('h2').morph('left:0px');
            }.bind(this)});
        },

        onFileSelect: function(entries)
        {
            var e;

            if(!uploadServer)
                return alert('Por el momento, nuestras capacidades estan completamente usadas a causa de grandes demandas.\nPor favor, intentelo de nuevo en algunos minutos.');

            for(var i=0; i<Object.keys(entries.fileList).length; i++)
            {
                e = entries.fileList['file'+i];
                e.editKey = EditKey ? EditKey : generate(6);

                if(e.size < 1)
                    continue;

                if(e.size >= 1073741824){
                    Uploader.ol({"html":'<span class="cB" style="position:relative;left:5px;">'+('El tama&#241;o del dato escogido excede %max.\nSolo usuarios Premium pueden subir datos mayores de %max.').gsub('%max', '1,00 GB')+'</span>',"style":'width:410px;height:90px;'});
                    setTimeout(function(){Uploader.overlay=false;Uploader.observeOl();}.bind(this),4000);
                    continue;
                }

                if(this._IDs[e.id])
                    continue;
                else
                    this._IDs[e.id] = e;

                if(e.size < (10485760)){
                    this.initUpload(e);
                }else{
                    this.checkDuplicity(e);
                }
            }
        },

        isIntro: function(){
            if($$('intro') != null)
                return true;
            else
                return false;
        },

        escape: function(h) {
            var esc, div = document.createElement("div");
            div.appendChild(document.createTextNode(h));
            esc = div.innerHTML;
            delete div;
            return esc;
        },

        initUpload: function(file, fake)
        {
            if($$('uploads') == null)
                $$('head').insert({before:'<div style="background:#5CA9FB;padding-top:20px;box-shadow:0 -4px 7px #4ea2fc inset"><div id="uploads" class="center"></div></div>'});

            var c = $$('uploads');
            var html = '<div id="'+file.id+'" class="uploadWeb editKey_'+file.editKey+'">'+
                '	<div class="bg"></div>' +
                '	<div class="mask"></div>' +
                '	<h1><span></span></h1>' +
                '	<p><span>' + this.escape(file.name) + '</span>' +
                '	<small><span class="cancel a" onclick="Uploader.cancelUpload(\''+file.id+'\')">cancelar</span> &nbsp;&middot;&nbsp; <span class="size"></span> &nbsp;&middot;&nbsp; <span class="kbs" id="null'+(new Date().getTime())+'">iniciados Uploads&hellip;</span> &nbsp;&middot;&nbsp; <span class="rem"></span></small></p>' +
                '</div>';
            if(this.isIntro())
                c.insert({top:html});
            else
                c.insert({bottom:html});
            c.down('#'+file.id).setOpacity(.1).appear({duration:.7});

            if(!this.isIntro())
                this.fitHeight();

            $$(file.id).hide().blindDown({duration:.5}).appear({duration:.4});

            if(!fake)
                this.startUpload(file);
        },

        fitHeight: function(){
            var old = parseInt($$('content').getStyle('min-height'));
            var cnt = $$$('.uploadWeb').length;
            var mHeight = old - 75 - (cnt==1 ? 20 : 0);
            $$('content').morph('min-height:'+mHeight+'px', {duration:.5});
        },

        startUpload: function(file)
        {
            if(this._uploading > 1)
                return this._waiting.push(file);

            // freeze starttime
            $$(file.id).down('span.kbs').id = new Date().getTime();

            this._uploading++;
            this.setTransferStatus();

            if(this.isIntro())
                oStart.startUpload();

            var access = ($$('user_id')&&$$('user_pw')?'&id='+$$('user_id').value+'&pw='+$$('user_pw').value:'&id=1&pw=f4c154895961b4d3c408268f6af1421f30af1901');

            // thats "THE LINE"!
            this.uploader.upload(file.id, uploadServer+'upload?admincode='+file.editKey+access+(($$('cfolder'))?'&folder='+$$('cfolder').value:''), 'POST');
        },

        cancelUpload: function(fileID)
        {
            if(!confirm('&#191;Cancelar Upload?'))
                return false;

            this._uploading--;
            this.setTransferStatus();
            this.uploader.cancel(fileID);

            var d = 2;
            var c = $$(fileID);

            c.fade({duration:d});
            c.down('h1').morph('width:0px', {duration:d});
            c.down('h1 span').fade({duration:d-(.3)});
            c.blindUp({duration:d,afterFinish:placeFooter});

            setTimeout(function(){
                if(this.isIntro()) return;

                var visible = false;
                $$$('.uploadWeb').each(function(u){ if(u.visible()) visible = true; }.bind(this));
                if(!visible) $$('uploads').up().blindUp({duration:.3,afterFinish:function(){$$('uploads').up().remove()}});
            }.bind(this), (d*1000)+50);

            if(this._waiting.length > 0)
                this.startUpload(this._waiting.shift());
        },

        precheck: function(file){
            if(file.size < (10485760)){
                new Ajax.Request('io/upload/precheck', {parameters:Object.toQueryString(file),onComplete:function(t){
                    if(t.responseText && t.responseText == '_forbidden_'){
                        alert('Â¡El dato \"'+file.name+'\" lo conocemos como hirente al derecho autoral!\nNo es permitido subir este dato.');
                    }else{
                        this.checkDuplicity(file);
                    }
                }.bind(this) });
            }else{
                this.checkDuplicity(file);
            }
        },

        checkDuplicity: function(file)
        {
            switch(file.name.substr(file.name.lastIndexOf('.')+1))
            {
                case 'rar':
                case 'txt':
                    return this.initUpload(file);
                    break;
            }

            new Ajax.Request('io/upload/duplicity', {parameters:Object.toQueryString(file),onComplete:function(t){
                if(!t.responseText)
                    return this.initUpload(file);
                else{
                    if(t.responseText == '_forbidden_'){
                        return alert('Â¡El dato \"'+file.name+'\" lo conocemos como hirente al derecho autoral!\nNo es permitido subir este dato.');
                    }
                    this.initUpload(file, true);
                    setTimeout(function(){ this.onUploadComplete({data:t.responseText,id:file.id}); }.bind(this), 500);
                }
            }.bind(this)});
        },

        onUploadProgress: function(obj)
        {
            var c		= $$(obj.id);
            var percent	= ((obj.bytesLoaded / obj.bytesTotal)*100).round();
            var kbs		= (obj.bytesLoaded / (new Date().getTime() - parseInt(c.down('span.kbs').id)));
            var rem		= new Date(((obj.bytesTotal-obj.bytesLoaded) / kbs));

            rem	= {hr:rem.getUTCHours(), min:rem.getUTCMinutes(), sec:rem.getUTCSeconds()};

            rem	= (rem.hr ? rem.hr+' h. ' : '') +
            (rem.min ? rem.min+' Min. ' : '') +
            (rem.sec ? rem.sec+' seg. ' : '');

            if(rem == '')
                rem = '<b>pocos segundos&hellip;</b>';
            else
                rem = rem.strip().substr(0, rem.strip().length-1)+'.';

            if(parseInt(percent)==100)
            {
                if(c.down('h1 span').innerHTML.trim() == '100%')
                    return;

                c.down('h1 span').update('100%&nbsp;');
                c.down('h1').setStyle({background:'url(../img/e/progress.gif) 0 0'}).morph('width:960px', {duration:.4}).done = true;;
                c.down('small').update('<b>procesando&hellip;</b>');
                return;
            }

            c.down('h1 span').update('&nbsp;'+percent+'%&nbsp;');
            c.down('h1').morph('width:'+((obj.bytesLoaded / obj.bytesTotal)*960).round()+'px', {duration:.4});
            c.down('span.size').update(((obj.bytesLoaded/1024/1024).toFixed(2))+' de '+((obj.bytesTotal/1024/1024).toFixed(1))+' MB');
            c.down('span.kbs').update(kbs.toFixed(0)+' kb/s');
            c.down('span.rem').update(rem);
        },

        onUploadComplete: function(obj)
        {
            if(!obj.data || obj.data == '')
                alert("Desgraciadamente ha ocurrido un error.\nPor favor, suba el dato de nuevo.");

            var id = (obj.data.indexOf(',')>0)? obj.data.substr(0, obj.data.indexOf(',')): obj.data;
            var c  = $$(obj.id);

            this._uploading--;
            this._complete++;
            this.setTransferStatus();

            var a = $$('uploads'), b = a, i = a.next('div.info'), fname = c.down('p span').innerHTML;
            if(!i && !this.isIntro()){
                b.insert({after:'<div class="info center" style="height:48px;text-align:center;display:none;"><button id="showlinks">lista de enlaces</button></div>'});
                $$('showlinks').observe('click', this.currentList.bind(this));
            }
            delete b;
            delete i;

            c.down('h1').morph('width:1025px', {duration:.8});
            c.down('.bg').hide();
            c.down('h1').fade();
            //c.down('p span').morph('color:#C1DBFF').insert(' &nbsp; <b style="color:#1A50A2">&middot;</b> &nbsp; EditKey: <b class="editKey" style="font-weight:bold" title="Cliquee para cambiar, presione CTRL + C para copiar.">'+c.className.substr(18)+'</b>');
            c.down('p small').fade({delay:.5});
            c.down('.mask').fade({delay:.5});

            if(id != 'forbidden'){
                //c.down('p span').morph('color:#C1DBFF').insert(' &nbsp; <b style="color:#1A50A2">&middot;</b> &nbsp; EditKey: <b class="editKey" style="font-weight:bold" title="Cliquee para cambiar, presione CTRL + C para copiar.">'+c.className.substr(18)+'</b>');
                c.insert({top:'<a href="http://ul.to/'+id+'" style="display:none;padding:0 0 5px 5px;margin-top:4px;text-decoration:none" title="Para copiar pulse CTRL + C">http://ul.to/'+id+'</a>'});

                c.down('a').appear({delay:.2,duration:.3}).observe('mouseover', function(){
                    this.select(c.down('a'));
                    //			if(!c.down('p span span'))
                    //				c.down('p span').insert('<span> &nbsp; <b style="color:#1A50A2">&middot;</b> &nbsp; Presione <b>CTRL + C</b> para copiar.</span>')
                    //					.down('span').fade({delay:1.5,duration:.2,afterFinish:function(){ c.down('p span span').remove(); }.bind(this)});
                }.bind(this));
                //c.down('.editKey').observe('mouseover', this.select.bind(this, c.down('.editKey')));
                //c.down('.editKey').observe('click', function(){ this.changeAccess(id, c); }.bind(this));
            } else {
                c.insert({top:'<div style="text-shadow:0 1px 2px #1C5CBE;margin:8px 0 0 10px;font-size:15px">Este dato ha sido remarcado como ilegal por el art&#237;fice y por lo tanto no puede  guardarlo. Por favor, respete nuestro Aviso legal AGB.</div>'});
            }
            //c.insert({top:'<a href="http://ul.to/'+id+'" style="display:none;padding:0 0 5px 5px;margin-top:4px;text-decoration:none" title="Para copiar pulse CTRL + C">http://ul.to/'+id+'</a>'});

            if(this.isIntro()){
                c.down('a').setStyle('text-align:center');

                setTimeout(function(){
                    c.down('p').setStyle('text-align:center');
                }.bind(this), 1500);
            }

            if(this._complete>1 && !a.next('div.info').visible()){
                a.next('div.info').blindDown();
            }
            if(this._waiting.length > 0)
                this.startUpload(this._waiting.shift());

            this.changeContent();
        },

        changeContent: function()
        {
            var url	= location.href.sub('http://','');
            if(window.Manage) {
                setTimeout(function(){window.Manage.cache.reset();window.Manage.load(window.Manage.current_folder);},500);
            }
        },

        onUploadError: function(obj)
        {
            if(this._waiting.length > 0)
                this.startUpload(this._waiting.shift());

            var c = $$(obj.id);
            this._uploading--;
            this.setTransferStatus();
            setTimeout(function(){ c.shake({duration:.7}); }.bind(this), 600);
            c.down('h1').setStyle({background:'url(../img/e/error.gif) 0 0',textShadow:'#761212 0 1px 1px',textAlign:'center'})
                .morph('width:960px', {duration:.4}).update('error');
            c.down('small').update('<b>Por favor, suba el dato de nuevo.</b> &nbsp;&middot;&nbsp; <b class="a">apagar</b>');
            c.down('small b.a').observe('click', function(){ c.blindUp({afterFinish:placeFooter}); }.bind(this));
        },

        select: function(c)
        {
            if(window.getSelection)
            {
                var selection = window.getSelection();
                selection.selectAllChildren(c);
            }
            else if(document.body.createTextRange)
            {
                var range = document.body.createTextRange();
                range.moveToElementText(c);
                range.select();
            }
        },

        currentList: function(){
            var links = "";
            if(parseInt(this._complete)>0){
                var fdiv=false, fname=false, furl=false;
                var i=0;
                while(true){
                    if($$('file'+i)){
                        fdiv = $$('file'+i);
                        if(fdiv.down('a')){
                            furl = fdiv.down('a').href;
                            fname = fdiv.down('p').down('span').innerHTML;
                            fname = fname.substr(0,fname.indexOf('<b'));
                            if(fname.indexOf('&nbsp;')) fname = fname.substr(0,fname.indexOf('&nbsp;'));
                            if(fname.indexOf(' ')) fname = fname.substr(0,fname.indexOf(' '));
                            links += furl+'/'+fname+"\n";
                        }
                        i++;
                    }else{
                        break
                    };
                }
            }
            this.ol(links);
        },

        alert: function(obj) {
            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }

            obj = Object.extend({
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><button style="margin-left:45%" onclick="Uploader.overlay=false;Uploader.observeOl();">Ok</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true
            }, obj || { });

            if(obj.timeout)
                setTimeout(this.ol.bind(this,obj),obj.timeout);
            else
                this.ol(obj);
        },

        confirm: function(obj) {
            // reset status
            this.confirmed = false;

            obj = Object.extend({
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><button style="margin-left:32%" onclick="Uploader.confirmed=true;Uploader.overlay=false;Uploader.observeOl();">Ok</button><button style="margin-left:5%" onclick="Uploader.confirmed=false;Uploader.overlay=false;Uploader.observeOl();">cancelar</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true
            }, obj || { });

            if(obj.timeout)
                setTimeout(this.ol.bind(this,obj),obj.timeout);
            else
                this.ol(obj);
        },

        prompt: function(obj) {
            // reset prompt
            this.prompted = false;

            obj = Object.extend({
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><input type="teyt" name="olprompt" id="olprompt" style="width:360px;" /><br class="cb" /><button style="margin-left:32%" onclick="Uploader.prompted=$$(\'olprompt\').getValue();Uploader.overlay=false;Uploader.observeOl();">Ok</button><button style="margin-left:5%" onclick="Uploader.prompted=false;Uploader.overlay=false;Uploader.observeOl();">cancelar</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true
            }, obj || { });

            if(obj.timeout)
                setTimeout(this.ol.bind(this,obj),obj.timeout);
            else
                this.ol(obj);
        },

        ol: function(txt){
            if($$('ol'))
                return this.observeOl();

            this.overlay = true;
            window.scrollTo(0,0);

            var newDiv = '<div style="position:fixed;left:0px;top:0px;width:100%;height:100%;z-index:99999;"><div id="ol" style="display:none;'+((Object.isString(txt) || !txt.style)?'width:750px;height:500px;':txt.style)+'">' +
                '	<div class="border"> <br />' +
                '		<div style="position:absolute;left:5px;top:5px;width:99%;height:'+(txt.fixed?'0':'30')+'px;background:transparent;cursor:move;"><img src="img/sym/cross.png" style="position:absolute;top:5px;right:5px;cursor:pointer;" onclick="Uploader.overlay=false;Uploader.observeOl();" /></div>' +
                (Object.isString(txt) ?
                '		<h1 class="cB"> &nbsp; &nbsp; Linkliste</h1>' +
                '		<textarea>'+txt+'</textarea>'
                    :
                    txt.html.replace("%d",txt.text)
                ) +
                '	</div>' +
                '</div></div>';
            $$('head').insert({before:newDiv});
            $$('ol').appear({duration:.3,from:0,to:1});
            $$('ol').down('div').observe('mouseenter',function(){if(this.overlay==false)this.overlay=true;}.bind(this));
            $$('ol').down('div').observe('mouseleave',function(){if(this.overlay==true)this.overlay=false;}.bind(this));
            $$('ol').down('div div').observe('mouseup',function(){this.overlay=true;}.bind(this));
            $$('ol').down('div div').observe('mousedown',function(){this.overlay='x,y';}.bind(this));

            var se = $$('ol').down('select');
            if(se){
                se.observe('mouseup',function(){this.overlay=true;}.bind(this));
            };

            if(txt.overlay) {
                $$('ol').insert({before:'<div id="ol.overlay" style="width:100%;height:100%;background:#fff"></div>'});
                $$('ol.overlay').setOpacity(0.4);
            }

            // close all tooltips
            $$$('.tooltip').each(function(tt){tt.hide();}.bind(this));

            var moveable = true;
            if(!Object.isString(txt))
                if(txt.fixed)
                    moveable = false;

            if(moveable)
                document.body.observe('mousemove',function(e){
                    if(!e) e = window.event;
                    e.preventDefault();
                    if(e.returnValue)
                        e.returnValue = false;

                    if(!$$('ol'))
                        return false;

                    if(this.overlay!=true && this.overlay!=false) {
                        var pag = new Array();
                        pag[0] = e.pageX? e.pageX : e.clientX;
                        pag[1] = e.pageY? e.pageY : e.clientY;
                        var div = new Array();
                        div[0] = isNaN(parseInt($$('ol').down('div').style.left)) ? ((Object.isString(txt) || !txt.style) ? -375 : parseInt('-'+(parseInt($$('ol').style.width)/2)) ) : parseInt($$('ol').down('div').style.left);
                        div[1] = isNaN(parseInt($$('ol').down('div').style.top)) ? ((Object.isString(txt) || !txt.style) ? -250 : parseInt('-'+(parseInt($$('ol').style.height)/2)) ) : parseInt($$('ol').down('div').style.top);
                        if(this.overlay!='x,y') {
                            dif = this.overlay.split(',');
                            $$('ol').down('div').style.left = (div[0]+(pag[0]-dif[0]))+'px';
                            $$('ol').down('div').style.top = (div[1]+(pag[1]-dif[1]))+'px';
                        }
                        this.overlay = pag[0]+','+pag[1];
                    }

                    return false;
                }.bind(this));
            else
                $$('ol').down('div div').style.cursor = 'default';

            if(!txt.permanent)
                setTimeout(function(){
                    document.body.onclick = function() {
                        this.observeOl();
                    }.bind(this);
                }.bind(this),1000);

            if(txt.afterFinish)
                this.overlayAfterFinish = txt.afterFinish;
        },

        observeOl: function(){
            if(!this.overlay) {
                if($$('ol'))	$$('ol').fade({duration:.3,Transition:Effect.Transitions.linear,afterFinish:function(){ if($$('ol')) $$('ol').up('div').remove(); }});
                document.body.onclick = function() {};
                if(Object.isFunction(this.overlayAfterFinish))
                    return this.overlayAfterFinish();
            }
        },

        changeAccess: function(id, c)
        {
            var oldCode = $$(c).down('.editKey').innerHTML;
            var newCode;
            if(newCode = prompt('Por favor, intruduzca un nuevo EditKey.', oldCode))
            {
                var forbidden = [' ', '/', '\'', '"', '%', '#', '&', ';'];
                var error	  = false;

                forbidden.each(function(char){
                    if(newCode.include(char)){
                        newCode = newCode.gsub(char, '');
                        error = true;
                    }
                }.bind(this));

                if(error)
                    alert('Su Editkey contiene car&#225;cteres prohibidos y fu&#233; adaptado por nuestro sistema. Ahora se llama %c'.sub('%c', newCode));

                if(newCode.length > 30){
                    newCode = newCode.substr(0, 30);
                    alert('El EditKey que usted ha intruducido fu&#233; demasiado largo y nuestro sistema lo ha adaptado. Ahora se llama: %c'.sub('%c', newCode));
                }

                var global = false;

                new Ajax.Request('file/'+id+'/edit/key/'+encodeURIComponent(newCode), {parameters:'global='+global,onComplete:function(t){
                    if(t.responseText)
                        return alert(t.responseText);

                    c.down('.editKey').update(newCode).highlight({startcolor:'#0069ff',endcolor:'#4387e8'});
                }.bind(this)});
            }
        },

        setTransferStatus: function()
        {
            $$$('head')[0].insert({top:'<link rel="shortcut icon" href="img/'+(this._uploading<1?'sym/tick.png':'load/dts.gif')+'" type="image/vnd.microsoft.icon">'});

            if(this._uploading>0)
                window.onbeforeunload = function(event){ event.returnValue = ('There are still uploads in progress&hellip;'); };
            else
                window.onbeforeunload = null;
        }
    });

var cOverlay = Class.create(
    {
        _obj: { },
        _id: 'ol',
        overlay: false,
        confirmed: false,
        prompted: false,
        overlayAfterFinish: false,

        initialize: function(obj) {
            this.reset();

            if(obj) {
                this._obj = obj;
                if(obj.id)
                    this._id = obj.id;
            }
        },

        create: function(obj){
            var ol = obj.id ? obj.id : this._id;

            if($$(ol))
                return this.observe(ol);

            this.overlay = true;
            window.scrollTo(0,0);

            var newDiv = '<div style="position:fixed;left:0px;top:0px;width:100%;height:100%;z-index:'+(obj.index ? obj.index : 1000)+';"><div class="overl" id="'+ol+'" style="display:none;'+((Object.isString(obj) || !obj.style)?'width:750px;height:500px;':obj.style)+'">' +
                '  <div class="border"> <br />' +
                '      <div style="position:absolute;left:5px;top:5px;width:99%;height:'+(obj.fixed?'0':'30')+'px;background:transparent;cursor:move;"><img src="img/sym/cross.png" style="position:absolute;top:5px;right:5px;cursor:pointer;" /></div>' +
                (Object.isString(obj) ?
                '      <h1 class="cB"> &nbsp; &nbsp; Linkliste</h1>' +
                '      <textarea>'+obj+'</textarea>'
                    :
                    obj.html.replace("%d",obj.text)
                ) +
                '  </div>' +
                '</div></div>';
            $$('head').insert({before:newDiv});
            $$(ol).appear({duration:.3,from:0,to:1});
            $$(ol).down('div').observe('mouseover',function(){if(this.overlay==false)this.overlay=true;}.bind(this));
            $$(ol).down('div').observe('mouseout',function(){if(this.overlay==true)this.overlay=false;}.bind(this));
            $$(ol).down('div div').observe('mouseup',function(){this.overlay=true;}.bind(this));
            $$(ol).down('div div').observe('mousedown',function(){this.overlay='x,y';}.bind(this));
            $$(ol).down('img').observe('click',function(ol){this.overlay=false;this.observe(ol);}.bind(this,ol));

            if(obj.overlay) {
                $$(ol).insert({before:'<div id="'+ol+'.overlay" style="width:100%;height:100%;background:#fff"></div>'});
                $$(ol+'.overlay').setOpacity(0.4);
            }

            // close all tooltips
            $$$('.tooltip').each(function(tt){tt.hide();}.bind(this));

            var moveable = true;
            if(!Object.isString(obj))
                if(obj.fixed)
                    moveable = false;

            if(moveable)
                document.body.observe('mousemove',function(e,ol){
                    if(!e) e = window.event;
                    e.preventDefault();
                    if(e.returnValue)
                        e.returnValue = false;

                    // disable browser selection
                    if(window.getSelection) {
                        var sel = window.getSelection();
                        sel.removeAllRanges();
                    }

                    if(!$$(ol))
                        return false;

                    if(this.overlay!=true && this.overlay!=false) {
                        var pag = new Array();
                        pag[0] = e.pageX? e.pageX : e.clientX;
                        pag[1] = e.pageY? e.pageY : e.clientY;
                        var div = new Array();
                        div[0] = isNaN(parseInt($$(ol).down('div').style.left)) ? ((Object.isString(obj) || !obj.style) ? -375 : parseInt('-'+(parseInt($$(ol).style.width)/2)) ) : parseInt($$(ol).down('div').style.left);
                        div[1] = isNaN(parseInt($$(ol).down('div').style.top)) ? ((Object.isString(obj) || !obj.style) ? -250 : parseInt('-'+(parseInt($$(ol).style.height)/2)) ) : parseInt($$(ol).down('div').style.top);
                        if(this.overlay!='x,y') {
                            dif = this.overlay.split(',');
                            $$(ol).down('div').style.left = (div[0]+(pag[0]-dif[0]))+'px';
                            $$(ol).down('div').style.top = (div[1]+(pag[1]-dif[1]))+'px';
                        }
                        this.overlay = pag[0]+','+pag[1];
                    }

                    return false;
                }.bind(this,ol));
            else
                $$(ol).down('div div').style.cursor = 'default';

            if(!obj.permanent)
                setTimeout(function(ol){
                    document.body.onclick = function(ol) {
                        this.observe(ol);
                    }.bind(this,ol);
                }.bind(this,ol),1000);

            if(obj.afterFinish)
                this.overlayAfterFinish = obj.afterFinish;
        },

        open: function(el,settings) {
            // only allow elements
            if(!Object.isElement(el))
                return;

            // set overlay.id
            if(el.id)
                this._id = 'overlay.'+el.id;

            // save settings for object
            if(settings)
                this._obj = settings;

            var obj = Object.extend({
                id: this._id,
                html: el.innerHTML,
                text: '',
                style:'width:'+(el.style.width)+'px;height:'+(el.style.height)+'px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, this._obj);

            this.create.bind(this,obj);
        },

        alert: function(obj) {
            // set id
            var ol = obj.id ? obj.id : this._id;

            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }

            obj = Object.extend({
                id: ol,
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><button style="margin-left:45%">Ok</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, obj || { });

            setTimeout(function(ol){
                if(!$$(ol)) return;
                // set button action
                $$(ol).down('button').observe('click',function(ol){this.overlay=false;this.observe(ol);}.bind(this,ol));
            }.bind(this,ol),obj.timeout ? (obj.timeout + 500) : 500);

            setTimeout(this.create.bind(this,obj),obj.timeout ? obj.timeout : 10);
        },

        confirm: function(obj) {
            // reset status
            this.confirmed = false;
            // set id
            var ol = obj.id ? obj.id : this._id;

            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }

            obj = Object.extend({
                id: ol,
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><button style="margin-left:32%">Ok</button><button style="margin-left:5%">cancelar</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, obj || { });

            setTimeout(function(ol){
                if(!$$(ol)) return;
                // set button action
                $$(ol).down('button',0).observe('click',function(ol){this.confirmed=true;this.overlay=false;this.observe(ol);}.bind(this,ol));
                $$(ol).down('button',1).observe('click',function(ol){this.confirmed=false;this.overlay=false;this.observe(ol);}.bind(this,ol));
            }.bind(this,ol),obj.timeout ? (obj.timeout + 500) : 500);

            setTimeout(this.create.bind(this,obj),obj.timeout ? obj.timeout : 10);
        },
        confirm2: function(obj) {
            // reset status
            this.confirmed = false;
            // set id
            var ol = obj.id ? obj.id : this._id;

            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }

            obj = Object.extend({
                id: ol,
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><div class="rbtn" style="margin-left:32%">Ok</div><div class="rbtn" style="margin-left:5%">cancelar</div>',
                text: '',
                style:'width:400px;height:140px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, obj || { });

            setTimeout(function(ol){
                if(!$$(ol)) return;
                // set button action
                $$(ol).down('.rbtn',0).observe('click',function(ol){this.confirmed=true;this.overlay=false;this.observe(ol);}.bind(this,ol));
                $$(ol).down('.rbtn',1).observe('click',function(ol){this.confirmed=false;this.overlay=false;this.observe(ol);}.bind(this,ol));
            }.bind(this,ol),obj.timeout ? (obj.timeout + 500) : 500);

            setTimeout(this.create.bind(this,obj),obj.timeout ? obj.timeout : 10);
        },

        prompt: function(obj) {
            // reset prompt
            this.prompted = false;
            // set id
            var ol = obj.id ? obj.id : this._id;

            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }

            obj = Object.extend({
                id: ol,
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><input type="teyt" name="'+ol+'prompt" id="'+ol+'prompt" style="width:360px;" /><br class="cb" /><button style="margin-left:32%">Ok</button><button style="margin-left:5%">cancelar</button>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, obj || { });

            setTimeout(function(ol){
                if(!$$(ol)) return;
                // set button action
                $$(ol).down('button',0).observe('click',function(ol){this.prompted=$$(ol+'prompt').getValue();;this.overlay=false;this.observe(ol);}.bind(this,ol));
                $$(ol).down('button',1).observe('click',function(ol){this.prompted=false;this.overlay=false;this.observe(ol);}.bind(this,ol));
            }.bind(this,ol),obj.timeout ? (obj.timeout + 500) : 500);

            setTimeout(this.create.bind(this,obj),obj.timeout ? obj.timeout : 10);
        },
        prompt2: function(obj, answ) {
            // reset prompt
            this.prompted = false;
            // set id
            var ol = obj.id ? obj.id : this._id;

            if(Object.isString(obj)) {
                var txt = obj;
                obj = undefined;
                obj = Object.extend({text: txt}, { });
            }
            answ = typeof(answ)=='undefined'?'':answ;

            obj = Object.extend({
                id: ol,
                html: '<div class="aC" style="width:90%;margin-left:5%;color:#000;"><br />%d</div><br class="cb" /><input type="teyt" name="'+ol+'prompt" id="'+ol+'prompt" style="width:360px;margin-left:15px;" value="'+answ+'" /><br class="cb" /><div class="rbtn" style="margin-left:32%">Ok</div><div class="rbtn" style="margin-left:5%">cancelar</div>',
                text: '',
                style:'width:400px;height:130px;',
                permanent: true,
                overlay: true,
                fixed: true,
                index: 1001
            }, obj || { });

            setTimeout(function(ol){
                if(!$$(ol)) return;
                // set button action
                $$(ol).down('.rbtn',0).observe('click',function(ol){this.prompted=$$(ol+'prompt').getValue();;this.overlay=false;this.observe(ol);}.bind(this,ol));
                $$(ol).down('.rbtn',1).observe('click',function(ol){this.prompted=false;this.overlay=false;this.observe(ol);}.bind(this,ol));
            }.bind(this,ol),obj.timeout ? (obj.timeout + 500) : 500);

            setTimeout(this.create.bind(this,obj),obj.timeout ? obj.timeout : 10);
        },
        observe: function(ol) {
            if(!ol) var ol = this._id;

            if(!this.overlay) {
                this.close(ol);
                document.body.onclick = function() {};
            }
        },

        close: function(ol) {
            if(!ol) var ol = this._id;
            if($$(ol)) {
                var frame = $$(ol).up('div');
                $$(ol).fade({duration:.3,Transition:Effect.Transitions.linear,afterFinish:function(frame){ if(frame){ try{frame.remove();}catch(e){} } }.bind(this,frame)});
            }

            if(Object.isFunction(this.overlayAfterFinish))
                this.overlayAfterFinish();

            return this.reset();
        },

        exit: function(ol) {
            if(!ol) var ol = this._id;
            if($$(ol)) {
                var frame = $$(ol).up('div');
                $$(ol).fade({duration:.3,Transition:Effect.Transitions.linear,afterFinish:function(frame){ if(frame){ try{frame.remove();}catch(e){} } }.bind(this,frame)});
            }

            return this.reset();
        },

        reset: function(type) {
            this.overlay = (!type || type!=2) ? false : true;
            this.confirmed = false;
            this.prompted = false;
            this.overlayAfterFinish = false;
        }
    });

function generate(len)
{
    var pwd = '';
    var con = new Array('b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','x','y','z');
    var voc = new Array('a','e','i','o','u');

    for(i=0; i < len/2; i++)
    {
        var c = Math.ceil(Math.random() * 1000) % 20;
        var v = Math.ceil(Math.random() * 1000) % 5;
        pwd += con[c] + voc[v];
    }

    return pwd;
}

function placeFooter()
{
    if($$('content') == null) return;
    $$('content').setStyle({minHeight:(document.viewport.getHeight()-(73+$$('head').getHeight()+$$('foot').getHeight())+'px')});
}

function blinkNews(num) {
    $$('head').down('div.navi').down('a[href="news"]').morph('color:#ade2fb',{duration: 0.7});
    setTimeout(function(){
        $$('head').down('div.navi').down('a[href="news"]').morph('color:#fff',{duration: 0.3});
        if(num<3)
            setTimeout(function(){blinkNews(num+1)},300);
    },700);
}

String.prototype.isMail = function(){
    var mailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return mailPattern.test(this)
}

Number.prototype.number = function(c, d, t){
    var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

String.prototype.isMail = function(){
    var mailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return mailPattern.test(this)
}

var User, Uploader, Login, Lang, EditKey, uploadServer,
    cRegister, cOrder, cSpace, cPayment, cLogin, cMe,
    cDownload, cAffiliate, cFiles, cFolder, unFocus, cStart;

document.observe('dom:loaded', function()
{
    Lang = new cLang();
    User = new cUser();
    Overlay = new cOverlay();
    EditKey = (document.cookie+';').match(/EditKey\=(.*)\;/) ? (document.cookie+';').match(/EditKey\=(.*)\;/)[1] : false;
    if(EditKey){
        if(EditKey.include(';'))
            EditKey = EditKey.substr(0, EditKey.indexOf(';')-1);
    }
    if($$('nupload'))
        setTimeout("Uploader = new cUploader('nupload');", 500);

    //blinkNews(1);
    placeFooter();
    Event.observe(document.onresize ? document : window, 'resize', placeFooter);
}.bind(this));