<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RawRequestTransformerListener
{
    const METHODS = ['PATCH', 'PUT'];
    const CONTENT_TYPE = 'multipart/form-data';

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $contentType = $this->getContentType();

        if (in_array($request->getMethod(), self::METHODS) &&
            $this->getContentType() == self::CONTENT_TYPE) {
            $this->parseRequest($request);
        }
    }

    private function getContentType()
    {
        if (!isset($_SERVER["CONTENT_TYPE"])) {
            return '';
        }

        $contentType = $_SERVER["CONTENT_TYPE"];
        $contentType = str_replace('"', '', $contentType);
        $contentType = explode(';', $contentType);
        return $contentType[0];
    }

    private function parseRequest(Request $request)
    {
        $data = [];
        new \AppBundle\Helpers\RequestParser($data);
        $request->request->replace($data['post']);
        $request->files->set('file', $data['file']);
    }
}
