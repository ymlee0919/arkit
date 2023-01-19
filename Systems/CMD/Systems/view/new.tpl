{{extends file='../../_base/view/base.tpl'}}
{{$session = 'systems'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li><a href="{{url id='cmd.systems'}}">Systems</a></li>
        <li class="active">New</li>
    </ol>

    <form id="add-form" method="post" action="{{url id='cmd.systems.add'}}">
        {{$CSRF_INPUT}}
        <div class="card">
            <div class="card-content">
                <span class="card-title">New System</span>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="System name" id="system" name="system" type="text" class="validate" required>
                        <label for="name-es">System:</label>
                    </div>
                    <div class="input-field col s12 m4">
                        <select id="model" name="model">
                            <option value="0">No Model Need</option>
                            {{foreach $Models as $Model}}
                            <option value="{{$Model}}">{{$Model}}</option>
                            {{/foreach}}
                        </select>
                        <label for="model">Model:</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="access" value="yes" />
                                <span>Require access control</span>
                            </label>
                        </p>
                    </div>
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="output" value="yes" />
                                <span>Require custom output</span>
                            </label>
                        </p>
                    </div>
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="base" value="yes" />
                                <span>Require Base tpl</span>
                            </label>
                        </p>
                    </div>
                </div>
                <br>
            </div>
            <div class="card-action right-align">
                <button class="btn blue" type="submit"><i class="material-icons left">check</i>Create</button> &nbsp; &nbsp;
                <a href="{{url id='cmd.systems'}}" class="btn red"><i class="material-icons left">block</i>Cancel</a>
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