public function functionName($params) : void
    {
        $output = App::$Output;

        // Validate parameters ...

        $output->loadTemplate('template.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId($this->formId)->generateCsrfCode();

        // Process the request ...
        App::$Model->connect('root');

        $output->assign('FieldName', $FieldValue);
        //$output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');
        $output->displayTemplate();
    }

    /// End of class