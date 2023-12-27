<div class="">
    <a href="#" data-target="main-menu" class="top-nav sidenav-trigger full hide-on-large-only">
        <i class="material-icons">menu</i>
    </a>
</div>
<ul style="transform: translateX(0px);" id="main-menu" class="sidenav sidenav-fixed">
    <li class="bold{{if $session == 'dashboard'}} selected{{/if}}" style="margin-top:10px">
        <a role="menuitem" href="{{url id='cmd.dashboard'}}" class="waves-effect waves-green">Dashboard<i class="material-icons left">apps</i></a>
    </li>
    <li class="bold{{if $session == 'models'}} selected{{/if}}" style="margin-top:10px">
        <a role="menuitem" href="{{url id='cmd.models'}}" class="waves-effect waves-green">Models<i class="material-icons left">cloud_circle</i></a>
    </li>
    <li class="bold{{if $session == 'systems'}} selected{{/if}}" style="margin-top:10px">
        <a role="menuitem" href="{{url id='cmd.systems'}}" class="waves-effect waves-green">Systems<i class="material-icons left">collections_bookmark</i></a>
    </li>
    <li class="bold{{if $session == 'samples'}} selected{{/if}}" style="margin-top:10px">
        <a role="menuitem" href="{{url id='cmd.samples'}}" class="waves-effect waves-green">Samples<i class="material-icons left">library_books</i></a>
    </li>
</ul>