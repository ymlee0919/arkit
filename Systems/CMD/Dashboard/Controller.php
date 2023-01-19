<?php
namespace CMD\Dashboard;

class Controller
{
    public function Show() : void
    {
        $output = \Arkit\App::$Response;
        $output->loadTemplate('main.tpl');
        $output->displayTemplate();
    }

    public function GetFonts() : void
    {
        header('Content-Type: application/octet-stream');
        header('Cache-Control: private, must-revalidate, max-age=7200');
        readfile(\Arkit\App::fullPath('Systems/cmd/_base/view/fonts/icons.woff2'));
    }
} 