{{extends $baseTpl}}
{{$session = 'models'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li class="active">Models</li>
    </ol>
    <div class="panel">
        <div class="panel-header">
            <span class="title">Application models</span>
            <a class="btn white right blue-text always-visible-header-btn" role="link" href="{{url id='cmd.models.new'}}">
                <i class="large material-icons">add</i>
            </a>
        </div>
        <div class="panel-content">
            <table id="table" class="bordered highlight">
                <thead>
                    <tr>
                        <th>Models</th>
                    </tr>
                </thead>
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
    <br><br>
    <script>
        {{include './main.js'}}
    </script>
{{/block}}