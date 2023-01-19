{{extends file='../../_base/view/base.tpl'}}
{{$session = 'models'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li><a href="{{url id='cmd.models'}}">Models</a></li>
        <li class="active">New</li>
    </ol>

    <form id="add-form" method="post" action="{{url id='cmd.models.add'}}">
        {{$CSRF_INPUT}}
        <div class="card">
            <div class="card-content">
                <span class="card-title">New Model</span>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="Model name" id="model" name="model" type="text" class="validate" required>
                        <label for="model">Model:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input placeholder="Database name" id="database" name="database" type="text" class="validate" required>
                        <label for="database">Database:</label>
                    </div>
                </div>
                <span class="card-title"><small style="font-weight: 400">Connection:</small></span>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="Host" id="host" name="host" type="text" class="validate" required>
                        <label for="host">Host:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input placeholder="User name" id="user" name="user" type="text" class="validate" required>
                        <label for="user">User:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input placeholder="Password" id="pass" name="pass" type="text" class="validate">
                        <label for="pass">Password:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <p>
                            <label>
                                <input type="checkbox" name="crypt" value="yes" />
                                <span>Need Crypt</span>
                            </label>
                        </p>
                    </div>
                </div>
                <br>
            </div>
            <div class="card-action right-align">
                <button class="btn blue" type="submit"><i class="material-icons left">check</i>Create</button> &nbsp; &nbsp;
                <a href="{{url id='cmd.models'}}" class="btn red"><i class="material-icons left">block</i>Cancel</a>
            </div>
        </div>
    </form>
    <br>
{{/block}}

{{block name=script}}
    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });
    </script>
{{/block}}