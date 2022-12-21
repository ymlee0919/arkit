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
        header('Content-Type: application/octet-stream');
        header('Cache-Control: private, must-revalidate, max-age=7200');
        readfile(App::fullPath('Systems/cmd/_base/view/fonts/icons.woff2'));
    }
} 