<?php namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Imagens extends BaseController
{
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
    }

    /**
     * Serve imagens do diretório writable/uploads
     */
    public function produto($filename)
    {
        $filePath = WRITEPATH . 'uploads/produtos/' . $filename;
        
        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404);
        }
        
        $mimeType = mime_content_type($filePath);
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000');
        
        return $this->response->setBody(file_get_contents($filePath));
    }
}

