<?php
namespace CMD\Controller\Dashboard;

class DashboardController extends \CMD\System\Core\Controller
{
    public function Show() : void
    {
        $response = \Arkit\App::$Response;
        
        // Read models
        $modelsDir = \Arkit\App::fullPath('/Model');
        $models = [];
        if(is_dir($modelsDir))
        {
            $d = dir($modelsDir);
            while (false !== ($model = $d->read())) {
                if($model[0] != '.')
                    $models[] = $model;
            }
            $d->close();
        }
        $response->assign('Models', $models);

        // Read systems
        $systems = [];
        $d = dir(\Arkit\App::fullPath('/Systems'));
        while (false !== ($system = $d->read())) {
            if($system[0] != '.' && $system != 'CMD')
                $systems[] = $system;
        }
        $d->close();

        $response->assign('Systems', $systems);
        
        $responseTpl = './dashboard/main.tpl';
        $response->displayTemplate($responseTpl);
    }

    public function GetFonts() : void
    {
        header('Content-Type: application/octet-stream');
        header('Cache-Control: private, must-revalidate, max-age=7200');
        readfile(\Arkit\App::fullPath('Systems/CMD/View/src/materialize/fonts/icons.woff2'));
    }
} 