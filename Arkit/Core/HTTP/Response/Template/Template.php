<?PHP

namespace Arkit\Core\HTTP\Response\Template;

/**
 * Class to define a template. Smarty is used as template engine.
 */
class Template extends \Smarty
{
    /**
     * Constructor of the class. Initialize the Smarty directories according Arkit structure.
     *
     * @param string $templateFolder Directory to load the template file
     */
    public function __construct(string $templateFolder)
    {
        parent::__construct();

        $RESOURCES_FOLDER = \Arkit\App::fullPath('/resources/smarty');
        $TEMPLATES_FOLDER = $templateFolder;

        $this->setTemplateDir($TEMPLATES_FOLDER);
        $this->setCompileDir($RESOURCES_FOLDER . '/templates_c/');
        $this->setConfigDir($RESOURCES_FOLDER . '/configs/');
        $this->setCacheDir($RESOURCES_FOLDER . '/cache/');
        $this->addPluginsDir(__DIR__ . DIRECTORY_SEPARATOR. 'plugins');

        $this->left_delimiter = '{{';
        $this->right_delimiter = '}}';

        if (RUN_MODE != DEBUG_MODE)
            $this->autoload_filters = array('output' => array('trimwhitespace'));
    }
}

?>