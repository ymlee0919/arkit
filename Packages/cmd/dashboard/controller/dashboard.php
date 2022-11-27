<?php
class DashBoard
{
    public function Show()
    {
        $output = App::$Output;
        $output->loadTemplate('main.tpl');
        $output->displayTemplate();
    }
} 