<?php
class Samples
{
    public function Show()
    {
        $output = \Arkit\App::$Response;
        $output->loadTemplate('samples.tpl');

        $output->displayTemplate();
    }
} 