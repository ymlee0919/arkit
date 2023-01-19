{{extends file='../../_base/view/base.tpl'}}
{{$session = 'models'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li class="active">Models</li>
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
                <th>Models</th>
                <td>
                    <a class="btn-floating btn blue right" href="{{url id='cmd.models.new'}}">
                        <i class="large material-icons">add</i>
                    </a>
                </td>
            </tr>
        </thead>
        <tbody>
        {{foreach $Models as $Model}}
            <tr>
                <td colspan="2">{{$Model}}</td>
            </tr>
        {{/foreach}}
        </tbody>
    </table>
{{/block}}