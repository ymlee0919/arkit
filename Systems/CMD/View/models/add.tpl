{{$session = 'models'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li><a role="link" href="{{url id='cmd.models'}}">Models</a></li>
        <li class="active">New</li>
    </ol>

    <form id="main-form" method="post" action="{{url id='cmd.models.add'}}">
    {{$CSRF_INPUT}}
        <div class="panel">
            <div class="panel-header">
                <span class="title">New model</span>
            </div>
            <div class="panel-content">
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="Model name" id="model" name="model" type="text" class="validate" required>
                        <label for="model" class="active">Model:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <input placeholder="Database name" id="database" name="database" type="text" class="validate" required>
                        <label for="database" class="active">Database:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <select id="type-select" name="type">
                            <option value="mysql">MySql</option>
                            <option value="pgsql">PostgreSQL</option>
                        </select>
                        <label for="type-select">Type:</label>
                    </div>
                     <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="master" value="yes" />
                                <span>Require Master Class</span>
                            </label>
                        </p>
                    </div>
                </div>
                <fieldset id="connection">
                    <label for="connection">Connection</label>
                    <div class="row">
                        <div class="input-field col s12 m3">
                            <input placeholder="Host" id="host" name="host" type="text" class="validate" required>
                            <label for="host" class="active">Host:</label>
                        </div>
                        <div class="input-field col s12 m3">
                            <input placeholder="Port" id="port" name="port" type="number" class="validate" required>
                            <label for="port" class="active">Port:</label>
                        </div>
                        <div class="input-field col s12 m3">
                            <input placeholder="User name" id="user" name="user" type="text" class="validate" required>
                            <label for="user" class="active">User:</label>
                        </div>
                        <div class="input-field col s12 m3">
                            <input placeholder="Password" id="pass" name="pass" type="text" class="validate">
                            <label for="pass" class="active">Password:</label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="panel-footer right-align">
                <button class="btn blue lighten-1" type="submit"><i class="material-icons left">check</i>Create</button> &nbsp; &nbsp;
                <a href="{{url id='cmd.models'}}" role="link" class="btn white grey-text text-darken-1"><i class="material-icons left">block</i>Cancel</a>
            </div>
        </div>
    </form>
    <br>
    <script>
        {{include './add.js'}}
    </script>
{{/block}}