<?PHP

namespace System\Events;

class ResponseEvents
{
	public function onBeforeDisplay() : void
	{
        //$output = &\Arkit\App::$Output;
        
        //$output->assign('VAR_NAME', 'VAR_VALUE');
	}

    public function onPageNotFound() : void
    {
        header('Status: 404');
        readfile(dirname(__FILE__) . 'view/404.html');
    }

    public function onAccessDenied() : void
    {
        header('Status: 401');
        readfile(dirname(__FILE__) . 'view/401.html');
    }

    public function onForbiddenAccess() : void
    {
        header('Status: 403');
        readfile(dirname(__FILE__) . 'view/403.html');
    }
}

?>