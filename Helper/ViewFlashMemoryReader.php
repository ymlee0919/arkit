<?php

/**
 * Class to retrieve flash values in session
 */
class ViewFlashMemoryReader
{
    /**
     * @var string Internal view name
     */
    private string $viewName;

    /**
     * @param string $viewName
     */
    public function __construct(string $viewName)
    {
        $this->viewName = 'VIEW.' . $viewName;
    }

    /**
     * Store an input error
     * @param string $fieldName
     * @param string $error
     * @return void
     */
    public function storeInputError(string $fieldName, string $error) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        if(!isset($hash['INPUT_ERRORS']))
            $hash['INPUT_ERRORS'] = [];

        $hash['INPUT_ERRORS'][$fieldName] = $error;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store a list of input errors
     * @param array $errorList
     * @return void
     */
    public function storeInputErrors(array $errorList) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        if(!isset($hash['INPUT_ERRORS']))
            $hash['INPUT_ERRORS'] = [];

        foreach ($errorList as $fieldName => $error)
            $hash['INPUT_ERRORS'][$fieldName] = $error;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store an action error
     * @param string $error
     * @return void
     */
    public function storeActionError(string $error) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        $hash['ACTION_ERROR'] = $error;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store the success message
     * @param string $message
     * @return void
     */
    public function storeSuccessMessage(string $message) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        $hash['SUCCESS_MESSAGE'] = $message;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store the warning
     * @param string $warning
     * @return void
     */
    public function storeWarning(string $warning) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        $hash['WARNING'] = $warning;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store other error types, for internals and others
     * @param string $errorType
     * @param string $error
     * @return void
     */
    public function storeCustomError(string $errorType, string $error) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        if(!isset($hash['CUSTOM_ERRORS']))
            $hash['CUSTOM_ERRORS'] = [];

        $hash['CUSTOM_ERRORS'][$errorType] = $error;

        App::$Session->set($this->viewName, $hash, true);
    }

    /**
     * Store custom values, useful for inserted form values
     * @param string $indexName
     * @param mixed $value
     * @return void
     */
    public function storeCustomValue(string $indexName, mixed $value) : void
    {
        $hash = App::$Session->get($this->viewName);
        if(is_null($hash))
            $hash = array();

        if(!isset($hash['VALUES']))
            $hash['VALUES'] = [];

        $hash['VALUES'][$indexName] = $value;

        App::$Session->set($this->viewName, $hash, true);
    }

}