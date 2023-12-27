App.handler = {
    mainForm : {
        form : null,
        validator: null,
        models: null,

        init: function(){
            let me = this;

            this.form = $('#main-form');
            this.models = $('#model').formSelect();

            this.validator = this.form.validate({
                submitHandler: me.onSubmit.bind(me)
            });
        },

        onSubmit : function(){
            let me = this;
            App.lockScreen();
            App.serverRequest.sendForm(me.form, me.onSuccess.bind(me), me.onError.bind(me))
        },

        release : function(){
            this.form.off('submit');
            this.form = null;

            this.models.formSelect('destroy');
            this.models = null;

            this.validator.destroy();
            this.validator = null;
        },

        onSuccess : function(data, textStatus){
            App.workingArea.load('{{url id='cmd.systems'}}');
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
    init : function(){
        this.mainForm.init();

        $('main a[role="link"]').click((e)=>{
            e.preventDefault();
            let ref = $(e.currentTarget).attr('href');
            App.workingArea.load(ref);
        });
    },

    release : function(){
        this.mainForm.release();

        $('main a[role="link"]').off('click');
    }
};