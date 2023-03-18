public function functionName($params) : void
    {
        $response = &\Arkit\App::$Response;

        // Validate parameters ...

        // Validate entry
        $form = &\Arkit\App::$InputValidator;

        $form->setId($this->formId);
        $form->checkValues(\Arkit\App::$Request);

        // Validate each field
        $form->validate('field1')->setCustomError('The custom error')->isRequired()->isInteger()->greaterThan(1, true);
        $form->validate('field2')->alias('field name')->isRequired()->isString()->withLengthBetween(1,40);
        $form->validate('file')->alias('fileId')->isFile()->isImage();
        $form->validate('field3')->isRequired()->isPersonalData()->isEmail();

        $form->validateCsrfCode();

        if(!$form->isValid())
        {
            $output->inputErrors($form->getErrors());
            $output->redirectTo('RULE-ID');
        }

        // End of request, send the response
        $output->success('Request successfully processed');
        $response->redirectTo('RULE-ID');
    }

    /// End of class