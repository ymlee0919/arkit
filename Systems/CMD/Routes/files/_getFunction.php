public function functionName($params) : void
    {
        $response = \Arkit\App::$Response;

        // Validate parameters ...

        // Load the template
        $response->loadTemplate('template.tpl');

        // Set form ID
        \Arkit\App::loadFormValidator();
        \Arkit\App::$Form->setId($this->formId)->generateCsrfCode();

        // Connect
        \Arkit\App::$Model->connect('root');

        // Process the request ...

        $response->assign('FieldName', $FieldValue);

        // Assign items from flash memory stored in session
        $flashMemory = new ViewFlashMemory($this->formId);
        $flashMemory->sendToResponse($response, 'VIEW');

        // Finally, display the template
        $response->displayTemplate();
    }

    /// End of class