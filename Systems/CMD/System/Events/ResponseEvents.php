<?php

namespace CMD\System\Events;

class ResponseEvents
{
    public function onBeforeDisplay()
    {
        \Arkit\App::$Response->assign('baseTpl', \Arkit\App::$Request->isAJAX() ? 'ajax.tpl' : 'base.tpl');
    }
}