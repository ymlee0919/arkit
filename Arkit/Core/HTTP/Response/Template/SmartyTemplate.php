<?PHP

namespace Arkit\Core\HTTP\Response\Template;

/**
 * Class to define a template. Smarty is used as template engine.
 */
class SmartyTemplate implements TemplateInterface
{
    private \Smarty $template;

    /**
     * Constructor of the class. Initialize the Smarty directories according Arkit structure.
     *
     * @param string $templateFolder Directory to load the template file
     * @param array $configs (Optional) Configuration options
     */
    public function __construct(string $templateFolder, $configs = [])
    {
        $this->template = new \Smarty();

        $RESOURCES_FOLDER = \Arkit\App::fullPath('/resources/smarty');
        $TEMPLATES_FOLDER = $templateFolder;

        $this->template->setTemplateDir($TEMPLATES_FOLDER);
        $this->template->setCompileDir($RESOURCES_FOLDER . '/templates_c/');
        $this->template->setConfigDir($RESOURCES_FOLDER . '/configs/');
        $this->template->setCacheDir($RESOURCES_FOLDER . '/cache/');

        $this->template->addPluginsDir(__DIR__ . DIRECTORY_SEPARATOR. 'Smarty/plugins');

        $this->template->left_delimiter = '{{';
        $this->template->right_delimiter = '}}';

        if (\Arkit\App::$Env['RUN_MODE'] != DEBUG_MODE)
            $this->template->autoload_filters = array('output' => array('trimwhitespace'));
    }

    /**
     * Assign a value to the template
     *
     * @param string $templateFieldName Name of the field into the template
     * @param mixed $value Value of the field
     * @return void
     */
    public function assign(string $templateFieldName, mixed $value): void
    {
        if(is_object($value))
            $this->template->assignByRef($templateFieldName, $value);
        else
            $this->template->assign($templateFieldName, $value);
    }

    /**
     * Display the template and sent it as response
     *
     * @param string $templateName File name of the template
     * @param array $values (Optional) Values set to the template. Append new values.
     * @return void
     */
    public function display(string $templateName, array $values = []): void
    {
        if(!empty($values))
            foreach($values as $key => $value)
                $this->assign($key, $value);
        
        $this->template->display($templateName);
    }

    /**
     * Fetch a template into string
     *
     * @param string $templateName Name of the template
     * @param array $values (Optional) Values set to the template. Append new values.
     * @return string Template code
     */
    public function fetch(string $templateName, array $values = []): string
    {
        if (!empty($values))
            foreach ($values as $key => $value)
                $this->assign($key, $value);

        return $this->template->fetch($templateName);
    }

    /**
     * Get a template var already set to the template
     *
     * @param string $fieldName Template field name
     * @return mixed
     */
    public function getTemplateVars(string $fieldName): mixed
    {
        return $this->template->getTemplateVars($fieldName);
    }
}

?>