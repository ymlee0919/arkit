<?PHP

namespace Arkit\Core\HTTP\Response\Template;

/**
 * Class to define a generic template. Wrapper for each template engine.
 */
interface TemplateInterface
{
    /**
     * Constructor of the class
     *
     * @param string $templateFolder Folder containing the templates to display
     * @param array $configs Configuration options
     */
    public function __construct(string $templateFolder, array $configs = []);

    /**
     * Assign a value to the template
     *
     * @param string $templateFieldName Name of the field into the template
     * @param mixed $value Value of the field
     * @return void
     */
    public function assign(string $templateFieldName, mixed $value) : void;

    /**
     * Display the template and sent it as response
     *
     * @param string $templateName File name of the template
     * @param array $values (Optional) Values set to the template. Append new values.
     * @return void
     */
    public function display(string $templateName, array $values = []) : void;

    /**
     * Fetch a template into string
     *
     * @param string $templateName Name of the template
     * @param array $values (Optional) Values set to the template. Append new values.
     * @return string Template code
     */
    public function fetch(string $templateName, array $values = []) : string;

    /**
     * Get a template var already set to the template
     *
     * @param string $fieldName Template field name
     * @return mixed
     */
    public function getTemplateVars(string $fieldName) : mixed;
    
}