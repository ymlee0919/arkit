<?php
class Samples
{
    public function Show()
    {
        $output = App::$Response;
        $output->loadTemplate('samples.tpl');

        $output->displayTemplate();
    }
} 