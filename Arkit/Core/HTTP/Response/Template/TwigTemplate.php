<?PHP

namespace Arkit\Core\HTTP\Response\Template;

/**
* Class to define a template. Twig is used as template engine.
*/
class TwigTemplate implements TemplateInterface
{

    private \Twig\Environment $twig;

    private array $values;

    /**
     * Constructor of the class
     *
     * @param string $templateFolder Folder containing the templates to display
     * @param array $configs Configuration options (See \Twig\Environment configuration)
     */
    public function __construct(string $templateFolder, array $configs = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader($templateFolder);

        // Make folder for twig
        $RESOURCES_FOLDER = \Arkit\App::fullPath('/resources/twig');
        if(!is_dir($RESOURCES_FOLDER))
            @mkdir($RESOURCES_FOLDER);
        
        // Set cache templates
        $config['cache'] = $RESOURCES_FOLDER . '/templates_c';

        // Start Environment
        $this->twig = new \Twig\Environment($loader, $config);

        // Create values storage
        $this->values = [];
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
        $this->values[$templateFieldName] = $value;
    }

    /**
     * Display the template and sent it as response
     *
     * @param string $templateName File name of the template
     * @param array $values (Optional) Values set to the template
     * @return void
     */
    public function display(string $templateName, array $values = []): void
    {
        $vals = $this->values + $values;
        $this->twig->display($templateName, $vals);
    }

    /**
     * Fetch a template into string
     *
     * @param string $templateName Name of the template
     * @param array $values (Optional) Values set to the template
     * @return string Template code
     */
    public function fetch(string $templateName, array $values = []): string
    {
        $vals = $this->values + $values;
        return $this->twig->render($templateName, $vals);
    }

    /**
     * Get a template var already set to the template
     *
     * @param string $fieldName Template field name
     * @return mixed
     */
    public function getTemplateVars(string $fieldName): mixed
    {
        return $this->values[$fieldName] ?? null;
    }
}