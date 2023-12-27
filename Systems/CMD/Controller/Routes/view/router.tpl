{{$session = 'systems'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li><a role="link" href="{{url id='cmd.systems'}}">Systems</a></li>
        <li>{{$System}}</li>
        <li class="active">Router</li>
    </ol>
    <div class="panel">
        <div class="panel-header">
            <span class="title">Router Rules Manager for {{$System}} system</span>
        </div>
        <div class="panel-content">
            <div class="row">
                <div class="col s12">
                    <ul id="methods-tabs" class="tabs">
                        <li class="tab col s3"><a class="active" href="#get-method-tab">GET Method</a></li>
                        <li class="tab col s3"><a href="#post-method-tab">POST Method</a></li>
                    </ul>
                </div>
                <div id="get-method-tab" class="col s12 active">
                    <br>
                    <form id="get-method" method="post" action="{{url id='cmd.router.generate-rule'}}">
                        {{$CSRF_INPUT}}<input type="hidden" name="system" value="{{$System}}">
                        <div class="row">
                            <div class="input-field col s6 m4">
                                <input placeholder="Rule Id" id="id" name="id" type="text" class="validate">
                                <label for="id" class="active">Rule:</label>
                            </div>
                            <div class="input-field col s6 m4">
                                <input placeholder="Template to load, set empty if none" id="template" name="template" type="text" class="validate">
                                <label for="template" class="active">Output template:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="i18n" value="yes" />
                                        <span>Internationalization</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="helper" value="yes" />
                                        <span>Helper</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="email" value="yes" />
                                        <span>Email</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="pdf" value="yes" />
                                        <span>PDF</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                        <button class="btn green tooltipped" data-position="top" data-tooltip="Generate GET method handler" type="submit"><i class="material-icons left">check</i>Generate</button>
                    </form>
                </div>
                <div id="post-method-tab" class="col s12">
                    <br>
                    <form id="post-method" method="post" action="{{url id='cmd.router.generate-rule'}}">
                        {{$CSRF_INPUT}}<input type="hidden" name="system" value="{{$System}}">
                        <div class="row">
                            <div class="input-field col s6 m4">
                                <input placeholder="Rule Id" id="id" name="id" type="text" class="validate">
                                <label for="id" class="active">Rule:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="i18n" value="yes" />
                                        <span>Internationalization</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="helper" value="yes" />
                                        <span>Helper</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="email" value="yes" />
                                        <span>Email</span>
                                    </label>
                                </p>
                            </div>
                            <div class="col s6 m3">
                                <p>
                                    <label>
                                        <input type="checkbox" name="pdf" value="yes" />
                                        <span>PDF</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                        <button class="btn green tooltipped" data-position="top" data-tooltip="Generate POST method handler" type="submit"><i class="material-icons left">check</i>Generate</button>
                    </form>
                </div>
            </div>         
        </div>
        <div class="panel-footer right-align">
            <a href="{{url id='cmd.systems'}}" role="link" class="btn white grey-text text-darken-1"><i class="material-icons left">block</i>Cancel</a>
        </div>
    </div>

    <br><br>
    <script>
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
    </script>
{{/block}}

{{block name=script}}
    <script>
        $(document).ready(function(){
            $('.tooltipped').tooltip();
            $('.tabs').tabs();
            $('.tabs').tabs('select', 'test1');
        });
    </script>
{{/block}}