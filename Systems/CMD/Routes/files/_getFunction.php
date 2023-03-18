public function functionName($params) : void
    {
        $response = \Arkit\App::$Response;

        // Validate parameters ...

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId($this->formId)->generateCsrfCode();

        // Process the request ...

        $response->assign('FieldName', $FieldValue);

        // Finally, display the template
        $response->displayTemplate('sample.tpl');
    }

    /// End of class