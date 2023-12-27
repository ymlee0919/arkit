var App = {
    i18n : {
        calendar: {
            cancel: 'Cancelar',
            months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
        }
    },

    handler : null,

    tools: {
        parseDate : function(strDate){
            return this.parseDateFromFormat(strDate, 'yyyy-mm-dd', '-');
        },
        parseDateFromFormat(strDate, format, separator){
            let keys = format.split(separator),parts = strDate.split(separator),hash = [];
            hash[keys[0]] = parts[0];hash[keys[1]] = parts[1];hash[keys[2]] = parts[2];
            return new Date(hash['yyyy'], parseInt(hash['mm']) - 1, hash['dd'])
        },
        addDays : function(date, days){
            return new Date(date.setDate(date.getDate() + days));
        }
    },

    loader : {
        includes : null,
        stack: null,

        init : function(){
            this.includes = new Set();
            this.stack = [];
        },

        requireCss : function(cssFile){
            if(this.includes.has(cssFile))
                return;
            this.stack.push('CSS::' + cssFile);
        },

        requireJs : function(jsFile){
            if(this.includes.has(jsFile))
                return;
            this.stack.push('JS::' + jsFile);
        },

        load : function(){
            if(this.stack.length == 0)
                App.handler.init();
            else {
                let element = this.stack.shift();
                let tokens = element.split('::');
                if(tokens[0] == 'CSS')
                    this._loadCss(tokens[1]);
                else
                    this._loadJs(tokens[1]);
            }
        },

        onFileLoaded : function(file){
            this.includes.add(file);
            if(this.stack.length == 0)
                App.handler.init();
            else
                this.load();
        },

        _loadCss : function(file){
            let n   = document.createElement('link');
            n.type  = "text/css"; 
            n.media = "all"; 
            n.rel   = "stylesheet";
            n.href  = file; 
            let r   = document.getElementsByTagName('link')[0];
            r.parentNode.insertBefore(n,r);
            
            this.onFileLoaded(file);
        },

        _loadJs : function(file){
            let me = this;
            let n = document.createElement('script');
            n.src = file;
            n.async = 1;
            (function(n,f){
                n.onload = function(){
                    me.onFileLoaded(f);
                }
            })(n,file);
            let r = document.getElementsByTagName('script')[0];
            r.parentNode.insertBefore(n,r);
        }
    },

    serverRequest : {
        param : null,

        init : function(){
            this.param = {
                type: null,
                url: null,
                dataType : null
            };

            return this;
        },

        setMethod : function(method){
            this.param.type = method;
            return this;
        },

        setUrl : function(url){
            this.param.url = url;
            return this;
        },

        setResponseType : function(responseType){
            this.param.dataType = responseType;
            return this;
        },

        setParams :  function(parameters){
            this.param.data = parameters;
            return this;
        },

        onSuccess: function(onSuccess){
            this.param.success = onSuccess;
            return this;
        },

        onError: function(onError){
            this.param.error = onError;
            return this;
        },

        dispatch: function(){
            $.ajax(this.param);
        },

        sendForm: function(form, onSuccess, onError){
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType : 'json',
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    if(XMLHttpRequest.status == 500)
                        App.notify('error', 'Unexpected Internal Server Error. It was notified and will be fixed ASAP.', false, null);

                    if(!!onError)
                        onError(XMLHttpRequest, textStatus, errorThrown);
                    else {
                        App.notify('error', textStatus, false, null);
                    }
                },
                success : function(data, textStatus) {
                    if(!!onSuccess)
                        onSuccess(data, textStatus)
                    else {
                        App.notify('success', 'Request successfully processed', true, null);
                    }
                }
            });
        }
    },

    screenLocker: {
        el : null,
        init : function(){
            this.el = $('#screen-locker');
        },
        show : function(){
            $(document.body).addClass('no-scoll');
            this.el.addClass('active').css({display:'block'});
            this.el.show();
        },
        hide : function(){
            $(document.body).removeClass('no-scoll');
            this.el.removeClass('active').css({display:'none'});
            this.el.hide();
        }
    },

    // Bar on the top
    topBar : {
        init : function(){
            $('.top-bar-dropdown-button').dropdown({ hover: true, belowOrigin:true, constrainWidth: false });
        }
    },

    // Left menu
    mainMenu : {
        
        // Init main menu
        init : function(){    
            $('.sidenav').sidenav();
            $('#main-menu').sidenav({'edge': 'left'});
            
            this._initMenuItems();
        },

        // Init menu items
        _initMenuItems : function(){
            let me = this;
            $('.collapsible').collapsible();
            $('.collapsible.expandable').collapsible({accordion: false});

            $('a[role="menuitem"]').click((e)=>{
                e.preventDefault();
                let ref = $(e.currentTarget).attr('href');
                App.workingArea.load(ref);
            });
        },

        activate: function(url){
            App.updateUrl(url);
            let el = $('ul.sidenav a[href="' + url +'"]');
            if(el.length === 0) return;

            $('ul.sidenav li').removeClass('selected');
            el.parent().addClass('selected');
            
            $('ul.sidenav a.collapsible-header').removeClass('active');
            if(!!el.attr('data-parent'))
            {
                let parent = $(el.attr('data-parent')),
                    index = parent.attr('data-index');
                parent.addClass('active');
                parent.parent().parent().collapsible('open', index);
            }
                
        }
    },

    // Working area
    workingArea : {
        content : null,
        handler : null,
        currentUrl : null,

        _showContent: function(content){
            var me = this;
            me.content.fadeTo('fast', 0.3, ()=>{
                me.content.html("");
                me.content.html(content);
                me.content.fadeTo('fast', 1, ()=>{
                    App.releaseScreen();
                    if(App.handler != null){
                        if(typeof App.handler.load == "function") {
                            App.handler.load();
                        } else {
                            App.handler.init();
                        }
                    }
                })
            });
        },

        init : function(){
            this.content = $('#working_space');
        },

        load : function(url){
            let me = this;

            // Freeze the application
            App.lockScreen();

            // Make the request
            $.ajax({
                type: 'GET',
                url: url,
                dataType : 'html',
                error: function(jqXHR, textStatus, errorThrown){
                    App.releaseScreen();
                    switch(jqXHR.status)
                    {
                        case 500:
                            App.notify('error', jqXHR.statusText, false, null);
                            break;
                        default:
                            let error;
                            try{
                                let response = JSON.parse(jqXHR.responseText);
                                error = response.message;
                            }
                            catch(e){
                                error = textStatus;
                            }
                            finally{
                                App.notify('error', error, false, null);
                            }
                            break;
                    }
                    
                },
                success : function(data, textStatus, jqXHR ){
                    if(me.currentUrl != url){
                        App.mainMenu.activate(url);
                        me.currentUrl = url;
                    }

                    if(App.handler != null && typeof App.handler.release == "function")
                        App.handler.release();

                    App.handler = null;
                    me._showContent(data);
                }
            });
        },

        reload: function(){
            this.load(this.currentUrl);
        }
    },

    init : function() {
        this.topBar.init();
        this.mainMenu.init();
        this.workingArea.init();
        this.screenLocker.init();
        this.loader.init();

        if(App.handler != null){
            if(typeof App.handler.load == "function") {
                App.handler.load();
            } else {
                if(typeof this.handler.init == "function") 
                    App.handler.init();
            }
        }
        
    },

    lockScreen : function() {
        this.screenLocker.show();
    },

    releaseScreen : function(){
        this.screenLocker.hide();
    },

    notify : function(type, message, autoHide, callback){
        notif({
            type: type,
            msg: message,
            autohide: autoHide,
            timeout: 2000,
            zindex: 500000,
            clickable: !autoHide,
            callback: callback,
            position: 'center'
        });
    },

    updateUrl : function(newUrl){
        if(newUrl.indexOf(window.location.origin) != -1)
            return;

        let nextUrl = window.location.protocol + '//' + window.location.host + newUrl;
        window.history.pushState({page: nextUrl}, document.title, nextUrl);
    }
}

$(document).ready(function(){
    App.init();
});