<?PHP

namespace SystemName\System\Events;

class ResponseEvents
{
	public function onBeforeDisplay() : void
	{
        //$output = &\Arkit\App::$Output;
        
        //$output->assign('VAR_NAME', 'VAR_VALUE');
	}

    public function onPageNotFound() : void
    {
        $request = \Arkit\App::$Request;
        $response = \Arkit\App::$Response;

        http_response_code(404);
        if($request->isAJAX())
        {
            $response->assign('success', false);
            $response->error('message', 'Page not found');
            $response->toJSON();
        }
        else
            readfile(dirname(__FILE__) . '/view/404.html');
    }

    public function onAccessDenied() : void
    {
        $request = \Arkit\App::$Request;
        $response = \Arkit\App::$Response;

        http_response_code(401);
        if($request->isAJAX())
        {
            $response->assign('success', false);
            $response->error('message', 'Access denied');
            $response->toJSON();
        }
        else
            readfile(dirname(__FILE__) . '/view/401.html');
    }

    public function onForbiddenAccess() : void
    {
        $request = \Arkit\App::$Request;
        $response = \Arkit\App::$Response;

        http_response_code(403);
        if($request->isAJAX())
        {
            $response->assign('success', false);
            $response->error('message', 'Forbidden access');
            $response->toJSON();
        }
        else
            readfile(dirname(__FILE__) . '/view/403.html');
        
    }
}

?>