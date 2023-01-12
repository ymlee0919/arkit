{{extends file='../../_base/view/base.tpl'}}
{{$session = 'systems'}}{{$current = 'admin'}}
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
    {{if isset($INPUT_ERROR)}}
        {{foreach $INPUT_ERROR as $field => $error}}
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
                    <a class="btn-floating btn blue right" href="{{url id='cmd.systems.new'}}">
                        <i class="large material-icons">add</i>
                    </a>
                </td>
            </tr>
        </thead>
        <tbody>
        {{foreach $Systems as $System}}
            <tr>
                <td>{{$System}}</td>
                <td>
                    <a class="btn btn-flat waves-effect waves-red right" href="{{url id='cmd.router' system=$System}}">
                        <i class="large material-icons yellow-text">call_split</i>
                    </a>
                </td>
            </tr>
        {{/foreach}}
        </tbody>
    </table>
{{/block}}