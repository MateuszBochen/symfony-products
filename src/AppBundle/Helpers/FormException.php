<?php

namespace AppBundle\Helpers;

use Symfony\Component\HttpFoundation\Response;

class FormException
{

    private $response;

    public function __construct($status, $form)
    {
        $this->response = new Response();
        $this->response->headers->set('Content-Type', 'application/json');
        $this->response->setStatusCode($status);

        $json = $this->getFormErrors($form);

        $this->response->setContent(json_encode($json));
    }

    public function response()
    {
        return $this->response;
    }

    protected function getFormErrors(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }
        $srd = new \stdClass();
        $srd->errors = $errors;
        return $srd;
    }

}
