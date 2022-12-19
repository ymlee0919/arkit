{{extends file='../../_base/view/base.tpl'}}
{{$session = 'packages'}}{{$current = 'admin'}}
{{block name=body}}
    <ol class="breadcrumb">
        <li><a href="{{url id='cmd.packages'}}">Packages</a></li>
        <li class="active">New</li>
    </ol>

    <form id="add-form" method="post" action="{{url id='cmd.packages.add'}}">
        {{$CSRF_INPUT}}
        <div class="card">
            <div class="card-content">
                <span class="card-title">New Package</span>
                <div class="row">
                    <div class="input-field col s12 m4">
                        <input placeholder="Package name" id="package" name="package" type="text" class="validate" required>
                        <label for="name-es">Package:</label>
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
                                <input type="checkbox" name="firewall" value="yes" />
                                <span>Require Firewall</span>
                            </label>
                        </p>
                    </div>
                    <div class="col s6 m3">
                        <p>
                            <label>
                                <input type="checkbox" name="preloader" value="yes" />
                                <span>Require Page pre-loader</span>
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
                <a href="{{url id='cmd.packages'}}" class="btn red"><i class="material-icons left">block</i>Cancel</a>
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