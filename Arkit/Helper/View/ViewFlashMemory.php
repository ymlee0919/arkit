<?php

namespace Arkit\Helper\View;

/**
 * Class to store flash values in session
 */
class ViewFlashMemory
{
    /**
     * @var string Internal view name
     */
    private string $viewName;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->viewName = 'VIEW.' . md5($url);
    }

    /**
     * Store an input error
     * @param string $fieldName
     * @param string $error
     * @return void
     */
    public function storeInputError(string $fieldName, string $error): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        if (!isset($hash['INPUT_ERRORS']))
            $hash['INPUT_ERRORS'] = [];

        $hash['INPUT_ERRORS'][$fieldName] = $error;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * Store a list of input errors
     * @param array $errorList
     * @return void
     */
    public function storeInputErrors(array $errorList): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        if (!isset($hash['INPUT_ERRORS']))
            $hash['INPUT_ERRORS'] = [];

        foreach ($errorList as $fieldName => $error)
            $hash['INPUT_ERRORS'][$fieldName] = $error;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * Store the success message
     * @param string $message
     * @return void
     */
    public function storeSuccessMessage(string $message): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        $hash['SUCCESS_MESSAGE'] = $message;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * Store the warning
     * @param string $warning
     * @return void
     */
    public function storeWarning(string $warning): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        $hash['WARNING'] = $warning;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * Store other error types, for internals and others
     * @param string $errorType
     * @param string $error
     * @return void
     */
    public function storeCustomError(string $errorType, string $error): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        if (!isset($hash['CUSTOM_ERRORS']))
            $hash['CUSTOM_ERRORS'] = [];

        $hash['CUSTOM_ERRORS'][$errorType] = $error;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * Store custom values, useful for inserted form values
     * @param string $indexName
     * @param mixed $value
     * @return void
     */
    public function storeCustomValue(string $indexName, mixed $value): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            $hash = array();

        if (!isset($hash['VALUES']))
            $hash['VALUES'] = [];

        $hash['VALUES'][$indexName] = $value;

        \Arkit\App::$Session->setFlash($this->viewName, $hash);
    }

    /**
     * @return void
     */
    public function sendToResponse(): void
    {
        $hash = \Arkit\App::$Session[$this->viewName];
        if (is_null($hash))
            return;

        // Dispatch input errors
        if(isset($hash['INPUT_ERRORS']))
            \Arkit\App::$Response->inputErrors($hash['INPUT_ERRORS'], false);

        // Dispatch values
        if(isset($hash['VALUES']))
            \Arkit\App::$Response->assign($hash['VALUES'], null, false);

        // Dispatch custom errors
        if(isset($hash['CUSTOM_ERRORS']))
            foreach ($hash['CUSTOM_ERRORS'] as $errorType => $message)
                \Arkit\App::$Response->error($errorType, $message);

        // Dispatch warning
        if(isset($hash['WARNING']))
            \Arkit\App::$Response->warning($hash['WARNING'], false);

        // Dispatch success message
        if(isset($hash['SUCCESS_MESSAGE']))
            \Arkit\App::$Response->warning($hash['SUCCESS_MESSAGE'], false);
    }

}