<?php
namespace CMD\Controller\Dashboard;

class DashboardController extends \CMD\System\Core\Controller
{
    public function Show() : void
    {
        $response = \Arkit\App::$Response;

        // Read models
        $modelsHandler = new \CMD\Model\Models\ModelsHandler();
        $models = $modelsHandler->getList();
        $response->assign('Models', $models);

        // Read systems
        $systemsHandler = new \CMD\Model\Systems\SystemsHandler();
        $systems = $systemsHandler->getList();
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