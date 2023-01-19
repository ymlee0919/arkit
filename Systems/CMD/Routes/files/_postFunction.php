public function functionName($params) : void
    {
        $response = &\Arkit\App::$Response;
        $flashMemory = new ViewFlashMemory($this->formId);

        // Validate parameters ...

        // Validate entry
        $post = \Arkit\App::$Request->PostAll();
        $form = &\Arkit\App::$Form;

        $form->setId($this->formId);
        $form->checkValues($post);

        // Validate each field
        $form->validate('field1')->setCustomError('The custom error')->isRequired()->isInteger()->greaterThan(1, true);
        $form->validate('field2')->alias('field name')->isRequired()->isString()->withLengthBetween(1,40);
        $form->validate('file')->alias('fileId')->isFile()->isImage();
        $form->validate('field3')->isRequired()->isPersonalData()->isEmail();

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $flashMemory->storeInputErrors($form->getErrors());
            $output->redirectTo('RULE-ID');
        }

        // Process the request ...
        \Arkit\App::$Model->connect('root');

        // End of request, send the response
        $flashMemory->storeSuccessMessage('Request successfully processed');
        $response->redirectTo('RULE-ID');
    }

    /// End of class