public function functionName($params) : void
    {
        $response = App::$Response;

        // Validate parameters ...

        $response->loadTemplate('template.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId($this->formId)->generateCsrfCode();

        // Process the request ...
        App::$Model->connect('root');

        $response->assign('FieldName', $FieldValue);
        //$response->setSessionVars('INPUT_ERROR', 'ACTION_ERROR', 'ACTION_SUCCESS');
        $response->displayTemplate();
    }

    /// End of class