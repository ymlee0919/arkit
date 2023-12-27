{{extends $baseTpl}}
{{$session = 'dashboard'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
    </ol>
    <h5 class="center-align indigo-text text-darken-2">Arkit Generation Dashboard</h5>
    <br>
    <div class="row">
        <div class="col s12 m6">
            <div class="panel">
                <div class="panel-header">
                    <span class="title">Models</span>
                    <a class="btn white right blue-text always-visible-header-btn" role="link" href="{{url id='cmd.models.new'}}">
                        <i class="large material-icons">add</i>
                    </a>
                </div>
                <div class="panel-content">
                    <table id="table" class="bordered highlight">
                        <tbody>
                        {{if count($Models) > 0}}
                        {{foreach $Models as $Model}}
                            <tr>
                                <td>{{$Model}}</td>
                            </tr>
                        {{/foreach}}
                        {{else}}
                            <tr>
                                <td class="center-align">No models registered</td>
                            </tr>
                        {{/if}}
                        </tbody>
                    </table>          
                </div>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="panel">
                <div class="panel-header">
                    <span class="title">Systems</span>
                    <a class="btn white right blue-text always-visible-header-btn" role="link" href="{{url id='cmd.systems.new'}}">
                        <i class="large material-icons">add</i>
                    </a>
                </div>
                <div class="panel-content">
                    <table id="table" class="bordered highlight">
                        <tbody>
                        {{if count($Systems) > 0}}
                        {{foreach $Systems as $System}}
                            <tr>
                                <td>{{$System}}</td>
                                <td>
                                    <a class="btn btn-flat btn-admin white right" role="link" href="{{url id='cmd.router' system=$System}}">
                                        <i class="large material-icons indigo-text">call_split</i>
                                    </a>
                                </td>
                            </tr>
                        {{/foreach}}
                        {{else}}
                            <tr colspan="2">
                                <td class="center-align">No systems registered</td>
                            </tr>
                        {{/if}}
                        </tbody>
                    </table>          
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <script>
        {{include './main.js'}}
    </script>
{{/block}}