public function functionName($params) : void
    {
        $request = &\Arkit\App::$Request;
        $response = &\Arkit\App::$Response;
        
        // Validate parameters ...

        // Set form ID
        \Arkit\App::loadInputValidator();
        \Arkit\App::$InputValidator->setId($this->formId)->generateCsrfCode();

        // Process the request ...

        $response->assign('FieldName', $FieldValue);

        // Finally, display the template
        $response->displayTemplate('template.tpl');
    }

    /// End of class