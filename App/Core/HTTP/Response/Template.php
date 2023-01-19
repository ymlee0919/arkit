<?PHP

namespace Arkit\Core\HTTP\Response;

\Loader::import('Smarty', 'Libs.Smarty.vendor.autoload');

class Template extends \Smarty
{
    public function __construct(string $templateFolder)
    {
        parent::__construct();

        $RESOURCES_FOLDER = \Arkit\App::fullPath('/resources/smarty');
        $TEMPLATES_FOLDER = $templateFolder;

        $this->setTemplateDir($TEMPLATES_FOLDER);
        $this->setCompileDir($RESOURCES_FOLDER . '/templates_c/');
        $this->setConfigDir($RESOURCES_FOLDER . '/configs/');
        $this->setCacheDir($RESOURCES_FOLDER . '/cache/');

        $this->left_delimiter = '{{';
        $this->right_delimiter = '}}';

        if (RUN_MODE != DEBUG_MODE)
            $this->autoload_filters = array('output' => array('trimwhitespace'));
    }
}

?>