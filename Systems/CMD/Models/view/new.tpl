{{$session = 'models'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li><a role="link" href="{{url id='cmd.models'}}">Models</a></li>
        <li class="active">New</li>
    </ol>

    <form id="main-form" method="post" action="{{url id='cmd.models.add'}}">
    {{$CSRF_INPUT}}
        <div class="panel">
            <div class="panel-header">
                <span class="title">New model</span>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="Model name" id="model" name="model" type="text" class="validate" required>
                        <label for="model" class="active">Model:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input placeholder="Database name" id="database" name="database" type="text" class="validate" required>
                        <label for="database" class="active">Database:</label>
                    </div>
                </div>
                <fieldset id="connection">
                    <label for="connection">Connection</label>
                    <div class="row">
                        <div class="input-field col s12 m4">
                            <input placeholder="Host" id="host" name="host" type="text" class="validate" required>
                            <label for="host" class="active">Host:</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input placeholder="User name" id="user" name="user" type="text" class="validate" required>
                            <label for="user" class="active">User:</label>
                        </div>
                        <div class="input-field col s12 m4">
                            <input placeholder="Password" id="pass" name="pass" type="text" class="validate">
                            <label for="pass" class="active">Password:</label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="panel-footer right-align">
                <button class="btn blue lighten-1" type="submit"><i class="material-icons left">check</i>Create</button> &nbsp; &nbsp;
                <a href="{{url id='cmd.models'}}" role="link" class="btn white grey-text text-darken-1"><i class="material-icons left">block</i>Cancel</a>
            </div>
        </div>
    </form>
    <br>

    <script>
        App.handler = {
            mainForm : {
                form : null,
                validator: null,

                init: function(){
                    let me = this;

                    this.form = $('#main-form');
                    this.validator = this.form.validate({
                        submitHandler: me.onSubmit.bind(me)
                    });
                },

                onSubmit : function(){
                    let me = this;
                    App.lockScreen();
                    App.workingArea.sendForm('main-form', me.onSuccess.bind(me), me.onError.bind(me))
                },

                release : function(){
                    this.form.off('submit');
                    this.form = null;

                    this.validator.destroy();
                    this.validator = null;
                },

                onSuccess : function(data, textStatus){
                    App.workingArea.loadFrom('{{url id='cmd.models'}}');
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
                    App.workingArea.loadFrom(ref);
                });
            },

            release : function(){
                this.mainForm.release();

                $('main a[role="link"]').off('click');
            }
        };
    </script>
{{/block}}