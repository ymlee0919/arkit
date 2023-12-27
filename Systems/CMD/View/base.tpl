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
    {{include './components/navbar.tpl'}}
    
    {{*  -------------------- SIDE VAR -------------------- *}}
    {{include './components/mainMenu.tpl'}}
</header>
<div id="screen-locker" class="block-screen"> <div class="loading spinner-border"> </div> </div>
<main id="working_space">
{{block name="content"}}
{{/block}}
</main>
</body>
</html>