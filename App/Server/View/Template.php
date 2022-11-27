<?PHP
import('Libs.Smarty.vendor.autoload');

class PageTemplate extends Smarty
{
	public function __construct($template_folder)
	{
		parent::__construct();

		$RESOURCES_FOLDER = App::fullPath('/resources/smarty');
		$TEMPLATES_FOLDER = $template_folder;
		
        $this->setTemplateDir($TEMPLATES_FOLDER);
        $this->setCompileDir($RESOURCES_FOLDER . '/templates_c/');
        $this->setConfigDir($RESOURCES_FOLDER . '/configs/');
        $this->setCacheDir($RESOURCES_FOLDER . '/cache/');

        $this->left_delimiter = '{{';
        $this->right_delimiter = '}}';
		
        if(RUN_MODE != DEBUG_MODE)
		$this->autoload_filters = array('output' => array('trimwhitespace'));
    }
}
?>