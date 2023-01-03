public function functionName($params) : void
    {
        $response = &App::$Response;

        // Validate parameters ...

        // Validate entry
        $post = App::$Request->PostAll();
        $form = &App::$Form;

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
            $form->storeErrorsInSession('INPUT_ERROR', true);
            $output->redirectTo('RULE-ID');
        }

        // Process the request ...
        App::$Model->connect('root');

        // End of request, send the response
        App::$Session->set_flash('ACTION_SUCCESS', 'SUCCESS MESSAGE');
        $response->redirectTo('RULE-ID');
    }

    /// End of class