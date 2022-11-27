{{extends file='../../_base/view/base.tpl'}}
{{$session = 'packages'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li class="active">Packages</li>
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

    <table>
        <thead>
            <tr>
                <th>Packages</th>
                <td>
                    <a class="btn-floating btn blue right" href="{{url id='cmd.packages.new'}}">
                        <i class="large material-icons">add</i>
                    </a>
                </td>
            </tr>
        </thead>
        <tbody>
        {{foreach $Packages as $Package}}
            <tr>
                <td>{{$Package}}</td>
                <td>
                    <a class="btn btn-flat waves-effect waves-yellow right" href="{{url id='cmd.router' package=$Package}}">
                        <i class="large material-icons">call_split</i>
                    </a>
                </td>
            </tr>
        {{/foreach}}
        </tbody>
    </table>
{{/block}}