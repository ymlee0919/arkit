<!DOCTYPE html>
<html lang="es"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Generation Dashboard - Arkit</title>
    <!--  Android 5 Chrome Color-->
    <meta name="theme-color" content="#EE6E73">
    <!-- CSS-->
    <style type="text/css">
        {{include file=$smarty.current_dir|cat:'/src/materialize/css/materialize-custom.css'}}
        {{include file=$smarty.current_dir|cat:'/src/materialize/css/material-icons.css'}}
        {{include file=$smarty.current_dir|cat:'/src/core/css/core.css'}}
        {{include file=$smarty.current_dir|cat:'/src/core/css/theme-slate.css'}}
        {{include file=$smarty.current_dir|cat:'/src/notify/css/notifIt.css'}}
    </style>
    <script>
        {{include file=$smarty.current_dir|cat:'/src/jquery/js/jquery-3.6.3.js'}}
        {{include file=$smarty.current_dir|cat:'/src/materialize/js/materialize.min.js'}}
        {{include file=$smarty.current_dir|cat:'/src/validate/js/jquery.validate.min.js'}}
        {{include file=$smarty.current_dir|cat:'/src/notify/js/notifIt.js'}}
        {{include file=$smarty.current_dir|cat:'/src/core/js/core.js'}}
    </script>
    <!--  Scripts-->
<body>
<header>
    {{*  -------------------- NAV VAR -------------------- *}}
    <div class="navbar-fixed">
        <nav class="top-header">
            <div class="nav-wrapper">
                <div class="container">
                    <a href="#!" class="brand-logo" style="display:flex">
                        <img src="/public/images/app/banner.png" style="padding-top: 7px; padding-right: 10px; height: 57px;"> Arkit Dashboard
                    </a>
                    <ul class="right hide-on-med-and-down">
                        <li>
                            <a class="top-bar-dropdown-button" href="#!" data-target="dropdown-notifications">
                                Tools <i class="material-icons left">settings</i>
                            </a>
                            <ul id="dropdown-admin-user" class="dropdown-content menu-dropdown-content">
                                <li><a href="#!" class="active disabled"><strong>User</strong></a></li>
                                <li class="divider"></li>
                                <li><a href="#">Cerrar sessi&oacute;n &nbsp; <i class="material-icons right">exit_to_app</i></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="top-bar-dropdown-button" href="#!" data-target="dropdown-admin-user">
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
</header>
<div id="screen-locker" class="block-screen">
    <div class="loading spinner-border">
    </div>
</div>
<main id="working_space">
{{block name="content"}}
{{/block}}
</main>
</body>
</html>