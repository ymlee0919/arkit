{{extends file='../../_base/view/base.tpl'}}
{{$session = 'samples'}}{{$current = 'admin'}}
{{block name=body}}

    <ol class="breadcrumb">
        <li class="active">Code Samples</li>
    </ol>

    <div class="row">
        <div class="col m3 s12">
            <ul class="collapsible">
                <li>
                    <div class="collapsible-header"><i class="material-icons">filter_drama</i>First</div>
                    <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                    <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                    <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                </li>
            </ul>
        </div>
        <div class="col m9 s12">
            <div class="card">
                <div class="card-content">
                    <span id="title" class="card-title">Select a sample</span>
                    <div id="code">
                        <br>
                        <p>Select a sample to show the code</p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{/block}}