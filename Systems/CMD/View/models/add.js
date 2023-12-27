App.handler = {
    mainForm : {
        form : null,
        validator: null,

        init: function(){
            let me = this;

            $('select').formSelect();
            this.form = $('#main-form');
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

            this.validator.destroy();
            this.validator = null;
        },

        onSuccess : function(data, textStatus){
            App.workingArea.load('{{url id='cmd.models'}}');
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