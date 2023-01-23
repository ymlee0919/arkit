{{extends file='../../_base/view/base.tpl'}}
{{$session = 'dashboard'}}{{$current = 'admin'}}
{{block name=body}}
    <br>
    <h1 class="center-align light-blue-text text-darken-2">Arkit v1.2</h1>
    <h4 class="center-align light-blue-text">Automatic Generation Dashboard</h4>
    <br>
    <br>
    <p style="word-break: break-word">
        {{$extra}}
    </p>
{{/block}}