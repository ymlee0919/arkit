<?php
class Samples
{
    public function Show()
    {
        $output = App::$Output;
        $output->loadTemplate('samples.tpl');

        $output->displayTemplate();
    }
} 