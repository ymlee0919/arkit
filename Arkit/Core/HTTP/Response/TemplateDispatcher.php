<?php

namespace Arkit\Core\HTTP\Response;

use Arkit\Core\HTTP\Response\Template\TemplateInterface;

/**
 * Dispatch the response using a template engine
 */
class TemplateDispatcher implements DispatcherInterface
{
    /**
     * Template engine
     * @var TemplateInterface
     */
    private TemplateInterface $template;

    /**
     * Template directory
     * @param string|null $folderTemplate
     */
    public function __construct(?string $folderTemplate)
    {
        if(empty($folderTemplate))
        {
            $folderTemplate = \Arkit\App::fullPath('/Systems/' . \Arkit\App::$store['CONTROLLER']);
            $folderTemplate = dirname($folderTemplate) . DIRECTORY_SEPARATOR . 'view';
        }

        $this->template = new Template\SmartyTemplate($folderTemplate);
    }

    /**
     * Return the template
     * @return TemplateInterface
     */
    public function getTemplate() : TemplateInterface
    {
        return $this->template;
    }

    /**
     * @inheritDoc
     */
    public function assignValues(array &$values): void
    {
        foreach ($values as $varName => $value)
            $this->template->assign($varName, $value);
    }

    /**
     * @inheritDoc
     */
    public function assign(string $varName, mixed $value): void
    {
        $this->template->assign($varName, $value);
    }

    /**
     * @inheritDoc
     */
    public function inputError(string $fieldName, string $error): void
    {
        $errors = $this->template->getTemplateVars('INPUT_ERRORS');
        if(is_null($errors))
            $errors = [];

        $errors[$fieldName] = $error;
        $this->template->assign('INPUT_ERRORS', $errors);
    }

    /**
     * @inheritDoc
     */
    public function inputErrors(array $errors): void
    {
        $this->template->assign('INPUT_ERRORS', $errors);
    }

    /**
     * @inheritDoc
     */
    public function error(string $errorType, string $message): void
    {
        $this->template->assign($errorType, $message);
    }

    /**
     * @inheritDoc
     */
    public function warning(string $message): void
    {
        $this->template->assign('WARNING', $message);
    }

    /**
     * @inheritDoc
     */
    public function success(string $message): void
    {
        $this->template->assign('SUCCESS_MESSAGE', $message);
    }

    /**
     * @inheritDoc
     */
    public function dispatch(?string $resource, ?array $arguments = null): void
    {
        // Validate the template exists
        //$file = $this->template->getTemplateDir(0) . $resource;
        //var_dump($file);die;
        //if(!file_exists($file))
        //    throw new \Exception('The provided template do not exists');

        // Set the current working directory
        $this->template->assign('CWD', \Arkit\App::$ROOT_DIR);
        // Set the current URL
        $this->template->assign('URL', \Arkit\App::$Request->getRequestUrl());

        // Assign Cross Site Request Forgery code and input
        if (isset(\Arkit\App::$store['CSRF']))
        {
            $this->template->assign('CSRF_INPUT', \Arkit\App::$store['CSRF']['HTML']);
            $this->template->assign('CSRF_CODE', \Arkit\App::$store['CSRF']['CODE']);
        }

        // Assign flash vars if exists
        $flashMemory = new \Arkit\Helper\View\ViewFlashMemory(\Arkit\App::$Request->getRequestUrl());
        $flashMemory->sendToResponse();

        \Arkit\Core\Monitor\ErrorHandler::stop();

        try
        {
            if(!is_null($arguments) && isset($arguments['cache']))
                $this->template->display($resource, $arguments['cache']);
            else
                $this->template->display($resource);
        }
        catch (\Exception $ex)
        {
            @\Arkit\Core\Monitor\ErrorHandler::handleException($ex);
        }
    }
}