{{$session = 'systems'}}
{{block name=content}}
    <ol class="breadcrumb">
        <li class="root">Arkit v1.2</li>
        <li class="active">Systems</li>
    </ol>
    <div class="panel">
        <div class="panel-header">
            <span class="title">Application systems</span>
            <a class="btn white right blue-text always-visible-header-btn" role="link" href="{{url id='cmd.systems.new'}}">
                <i class="large material-icons">add</i>
            </a>
        </div>
        <div class="panel-content">
            <table id="table" class="bordered highlight">
                <thead>
                    <tr>
                        <th>Systems</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
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
                    <tr>
                        <td class="center-align">No systems registered</td>
                    </tr>
                {{/if}}
                </tbody>
            </table>          
        </div>
    </div>
    <br><br>
    <script>
        App.handler = {

            init : function(){
                $('main a[role="link"]').click((e)=>{
                    e.preventDefault();
                    let ref = $(e.currentTarget).attr('href');
                    App.workingArea.loadFrom(ref);
                });
            },

            release : function(){
                $('main a[role="link"]').off('click');
            }
        };
    </script>
{{/block}}