public function functionName($params) : void
    {
        $output = App::$Output;

        // Validate parameters ...

        $output->loadTemplate('template.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId($this->formId)->generateCsrfCode();

        // Process the request ...

        $output->assign('FieldName', $FieldValue);
        //$output->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');
        //$output->beforeDisplay();
        $output->displayTemplate();
    }

    /// End of class