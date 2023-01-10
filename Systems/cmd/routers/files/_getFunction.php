public function functionName($params) : void
    {
        $response = App::$Response;

        // Validate parameters ...

        // Load the template
        $response->loadTemplate('template.tpl');

        // Set form ID
        App::loadFormValidator();
        App::$Form->setId($this->formId)->generateCsrfCode();

        // Connect
        App::$Model->connect('root');

        // Process the request ...

        $response->assign('FieldName', $FieldValue);

        // Assign items from flash memory stored in session
        $flashMemory = new ViewFlashMemory($this->formId);
        $flashMemory->sendToResponse($response, 'VIEW');

        // Finally, display the template
        $response->displayTemplate();
    }

    /// End of class