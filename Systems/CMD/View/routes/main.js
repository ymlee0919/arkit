App.handler = {
    getForm: {
        form : null,
        validator: null,

        init: function(){
            let me = this;

            this.form = $('#get-method');
            this.validator = this.form.validate({
                submitHandler: me.onSubmit.bind(me)
            });
        },

        onSubmit : function(){
            let me = this;
            App.lockScreen();
            App.serverRequest.sendForm('get-method', me.onSuccess.bind(me), me.onError.bind(me))
        },

        release : function(){
            this.form.off('submit');
            this.form = null;

            this.validator.destroy();
            this.validator = null;
        },

        onSuccess : function(data, textStatus){
            App.releaseScreen();
            this.form.trigger("reset");
            App.notify('success', data.message, true, null);
        },

        onError: function(httpResponse, textStatus, errorThrown){
            App.releaseScreen();
            let response, me = this;
            try{
                response = JSON.parse(httpResponse.responseText);
                switch(httpResponse.status)
                {
                    case 400:
                        this.validator.showErrors(response.inputErrors);
                        return;
                    case 409:
                        App.notify('error', response.error, false, null)
                        return;
                }
            } catch(e) {
                App.notify('error', errorThrown, false, null)
            }
        }
    },
    postForm: {
        form : null,
        validator: null,

        init: function(){
            let me = this;

            this.form = $('#post-method');
            this.validator = this.form.validate({
                submitHandler: me.onSubmit.bind(me)
            });
        },

        onSubmit : function(){
            let me = this;
            App.lockScreen();
            App.serverRequest.sendForm('post-method', me.onSuccess.bind(me), me.onError.bind(me))
        },

        release : function(){
            this.form.off('submit');
            this.form = null;

            this.validator.destroy();
            this.validator = null;
        },

        onSuccess : function(data, textStatus){
            App.releaseScreen();
            this.form.trigger("reset");
            App.notify('success', data.message, true, null);
        },

        onError: function(httpResponse, textStatus, errorThrown){
            App.releaseScreen();
            let response, me = this;
            try{
                response = JSON.parse(httpResponse.responseText);
                switch(httpResponse.status)
                {
                    case 400:
                        this.validator.showErrors(response.inputErrors);
                        return;
                    case 409:
                        App.notify('error', response.error, false, null)
                        return;
                }
            } catch(e) {
                App.notify('error', errorThrown, false, null)
            }
        }
    },
    tabs: {
        el: null,
        init: function(){
            this.el = $('#methods-tabs');
            this.el.tabs();
            this.el.tabs('select', 'get-method-tab');
        },

        release: function(){
            this.el.tabs('destroy');
            this.el = null;
        }
    },

    init : function(){
        this.tabs.init();

        this.getForm.init();
        this.postForm.init();

        $('.tooltipped').tooltip();

        $('main a[role="link"]').click((e)=>{
            e.preventDefault();
            let ref = $(e.currentTarget).attr('href');
            App.workingArea.load(ref);
        });
    },

    release : function(){
        this.tabs.release();
        this.tabs = null;

        this.getForm.release();
        this.getForm = null;

        this.postForm.release();
        this.postForm = null;

        $('.tooltipped').tooltip('destroy');

        $('main a[role="link"]').off('click');
    }
};