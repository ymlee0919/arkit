{{extends file='../../_base/view/base.tpl'}}
{{$session = 'systems'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li><a href="{{url id='cmd.systems'}}">Packages</a></li>
        <li class="active">Router of {{$System}}</li>
    </ol>
    {{if isset($ACTION_ERROR)}}
        <div class="chip red white-text">
            {{$ACTION_ERROR}}
            <i class="close material-icons yellow-text">close</i>
        </div>
    {{/if}}
    {{if isset($INPUT_ERRORS)}}
        {{foreach $INPUT_ERRORS as $field => $error}}
            <div class="chip red white-text">
            {{$error}}
            <i class="close material-icons yellow-text">close</i>
            </div>{{if not $error@first}}{{/if}}
        {{/foreach}}
    {{/if}}
    {{if isset($ACTION_SUCCESS)}}
        <div class="chip green white-text">
            {{$ACTION_SUCCESS}}
            <i class="close material-icons yellow-text">close</i>
        </div>
    {{/if}}
    <div class="card">
        <div class="card-content">
            <span class="card-title">Router Rules Manager</span>
            <div class="row">
                <div class="row">
                    <div class="col s12">
                        <ul class="tabs">
                            {{*<li class="tab col s3"><a class="active" href="#test1">Whole Router</a></li>*}}
                            <li class="tab col s3"><a class="active" href="#test2">GET Method</a></li>
                            <li class="tab col s3"><a href="#test3">POST Method</a></li>
                        </ul>
                    </div>
                    {{*<div id="test1" class="col s12 active">
                        <br>
                        <form id="whole-file" method="post" action="{{url id='cmd.router.generate-all'}}">
                            {{$CSRF_INPUT}}<input type="hidden" name="system" value="{{$System}}">
                            Generate all classes and functions for the router file<br><br>
                            <button class="btn blue tooltipped" data-position="top" data-tooltip="Generate all file rules" type="submit"><i class="material-icons left">done_all</i>Generate all</button>
                        </form>
                    </div>*}}
                    <div id="test2" class="col s12 active">
                        <br>
                        <form id="get-method" method="post" action="{{url id='cmd.router.generate-rule'}}">
                            {{$CSRF_INPUT}}<input type="hidden" name="system" value="{{$System}}">
                            <div class="row">
                                <div class="input-field col s6 m4">
                                    <input placeholder="Rule Id" id="id" name="id" type="text" class="validate">
                                    <label for="id">Rule:</label>
                                </div>
                                <div class="input-field col s6 m4">
                                    <input placeholder="Template to load, set empty if none" id="template" name="template" type="text" class="validate">
                                    <label for="template">Template:</label>
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
                    <div id="test3" class="col s12">
                        <br>
                        <form id="post-method" method="post" action="{{url id='cmd.router.generate-rule'}}">
                            {{$CSRF_INPUT}}<input type="hidden" name="system" value="{{$System}}">
                            <div class="row">
                                <div class="input-field col s6 m4">
                                    <input placeholder="Rule Id" id="id" name="id" type="text" class="validate">
                                    <label for="id">Rule:</label>
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
        </div>
        <div class="card-action right-align">
            <a href="{{url id='cmd.systems'}}" class="btn red"><i class="material-icons left">block</i>Cancel</a>
        </div>
    </div>

    <br>
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