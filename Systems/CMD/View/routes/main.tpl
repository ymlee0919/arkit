{{extends $baseTpl}}
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
        {{include './main.js'}}
    </script>
{{/block}}

{{block name=script}}
    <script>
        {{include './main.ready.js'}}
    </script>
{{/block}}