<!DOCTYPE html>
<html lang="es"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Arkit - Dashboard</title>
    <!--  Android 5 Chrome Color-->
    <meta name="theme-color" content="#EE6E73">
    <!-- CSS-->
    <link href="/public/jsLibs/materialize/css/materialize.min.css" rel="stylesheet" type="text/css">
    <link href="/public/jsLibs/materialize/css/material-icons.css" rel="stylesheet" type="text/css">

    <!--  Scripts-->
    <script src="/public/jsLibs/jquery/js/jquery-3.js"></script>
    <script src="/public/jsLibs/materialize/js/materialize.min.js" type="text/javascript"></script>
    <script src="/public/jsLibs/validator/js/jquery.validate.min.js" type="text/javascript"></script>

	<style type="text/css">
        main{padding-left:230px;padding-right:30px;margin-top: 25px}
        .sidenav {width: 200px;padding-bottom:130px}.sidenav.sidenav-fixed{top: auto}@media only screen and (max-width:992px){.sidenav.sidenav-fixed {-webkit-transform: translateX(-105%);transform: translateX(-105%);top: 0}}
        a.collapsible-header{padding: 10px 10px !important}
        .breadcrumb{padding:15px 25px;margin-bottom:25px;list-style:none;background-color:#f5f5f5;border-radius:4px}.breadcrumb>li{display:inline-block}.breadcrumb>li+li:before{padding:0 5px;color:#ccc;content:"/\00a0"}.breadcrumb>.active{color:#777}.btn-admin{padding: 0 1rem !important;}
        .modal-footer{border-top: 1px solid rgba(0,0,0,0.1) !important}
        input.error{margin-bottom: 3px !important;} div.file-field > div > label.error{position: absolute !important;top: 25px;text-transform: initial; left: 35px;}
        label.error{position: relative !important; margin-bottom: 10px; font-size: 12px; color: red;}

        .menu-dropdown-content{top:-64px}
    </style>
    {{block name=includes}}{{/block}}
<body style="background-color: #f7fcfe">
<header>
    {{*  -------------------- NAV VAR -------------------- *}}
    <div class="navbar-fixed">
        <nav class="indigo" style="left:0">
            <div class="nav-wrapper">
                <div class="container">
                    <a href="#!" class="brand-logo">Arkit Dashboard</a>
                    <ul class="right hide-on-med-and-down">
                        <li>
                            <a class="admin-dropdown-button" href="#!" data-target="dropdown-notifications">
                                Tools <i class="material-icons left">settings</i>
                            </a>
                            <ul id="dropdown-admin-user" class="dropdown-content menu-dropdown-content">
                                <li><a href="#!" class="active disabled"><strong>User</strong></a></li>
                                <li class="divider"></li>
                                <li><a href="#">Cerrar sessi&oacute;n &nbsp; <i class="material-icons right">exit_to_app</i></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="admin-dropdown-button" href="#!" data-target="dropdown-admin-user">
                                User <i class="material-icons left">account_circle</i>
                            </a>
                            <ul id="dropdown-notifications" class="dropdown-content menu-dropdown-content">
                                <li><a href="#">Send Email &nbsp; <i class="material-icons left">email</i></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    {{*  -------------------- SIDE VAR -------------------- *}}
    <div class="">
        <a href="#" data-target="nav-mobile" class="top-nav sidenav-trigger full hide-on-large-only">
            <i class="material-icons">menu</i>
        </a>
    </div>
    <ul style="transform: translateX(0px);" id="nav-mobile" class="sidenav sidenav-fixed">
        <li class="bold{{if $session == 'dashboard'}} active blue lighten-4{{/if}}" style="margin-top:20px">
            <a href="{{url id='cmd.dashboard'}}" class="waves-effect waves-green">Home<i class="material-icons left">apps</i></a>
        </li>
        <li class="bold{{if $session == 'models'}} active blue lighten-4{{/if}}" style="margin-top:20px">
            <a href="{{url id='cmd.models'}}" class="waves-effect waves-green">Models<i class="material-icons left">cloud_circle</i></a>
        </li>
        <li class="bold{{if $session == 'packages'}} active blue lighten-4{{/if}}" style="margin-top:20px">
            <a href="{{url id='cmd.packages'}}" class="waves-effect waves-green">Packages<i class="material-icons left">collections_bookmark</i></a>
        </li>
        <li class="bold{{if $session == 'samples'}} active blue lighten-4{{/if}}" style="margin-top:20px">
            <a href="{{url id='cmd.samples'}}" class="waves-effect waves-green">Samples<i class="material-icons left">library_books</i></a>
        </li>
        <li class="bold"><a href="#" class="waves-effect waves-red">Reports</a></li>
    </ul>
</header>
<main>
    <div class="">
        {{block name=body}}
        {{/block}}
    </div>
</main>
<script>
    $(document).ready(function(){
        $('.admin-dropdown-button').dropdown({ hover: true, belowOrigin:true, constrainWidth: false });

        $('.collapsible').collapsible();
        $('.collapsible.expandable').collapsible({accordion: false});
        $('#nav-mobile').sidenav({'edge': 'left'});

        function hideChip(){
            if(!!$('div.chip').length)
                $($('div.chip').get(0)).fadeOut('normal', function(e){
                    $($('div.chip').get(0)).remove();
                    setTimeout(hideChip, 1000)
                });
        }

        setTimeout(hideChip, 1500);
    });
</script>

{{block name=script}}{{/block}}

</body>
</html>