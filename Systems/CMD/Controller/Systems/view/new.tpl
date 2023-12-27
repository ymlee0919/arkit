{{$session = 'systems'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li><a role="link" href="{{url id='cmd.systems'}}">Systems</a></li>
        <li class="active">New</li>
    </ol>

<form id="main-form" method="post" action="{{url id='cmd.systems.add'}}">
    {{$CSRF_INPUT}}
        <div class="panel">
            <div class="panel-header">
                <span class="title">New system</span>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="System name" id="system" name="system" type="text" class="validate" required>
                        <label for="system">System:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <select id="model" name="model">
                            <option value="0">No Model Need</option>
                            {{foreach $Models as $Model}}
                            <option value="{{$Model}}">{{$Model}}</option>
                            {{/foreach}}
                        </select>
                        <label for="model">Model:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="access" value="yes" />
                                <span>Require access control</span>
                            </label>
                        </p>
                    </div>
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="output" value="yes" />
                                <span>Require custom output</span>
                            </label>
                        </p>
                    </div>
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="base" value="yes" />
                                <span>Require Base tpl</span>
                            </label>
                        </p>
                    </div>
                </div>
            </div>
            <div class="panel-footer right-align">
                <button class="btn blue lighten-1" type="submit"><i class="material-icons left">check</i>Create</button> &nbsp; &nbsp;
                <a href="{{url id='cmd.systems'}}" role="link" class="btn white grey-text text-darken-1"><i class="material-icons left">block</i>Cancel</a>
            </div>
        </div>
    </form>
    <br>

    <script>
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
    </script>
{{/block}}