<?php
class DashBoard
{
    public function Show() : void
    {
        $output = App::$Output;
        $output->loadTemplate('main.tpl');
        $output->displayTemplate();
    }

    public function GetFonts() : void
    {
        header('ContentType: application/octet-stream');
        readfile(App::fullPath('Systems/cmd/_base/view/fonts/icons.woff2'));
    }
} 