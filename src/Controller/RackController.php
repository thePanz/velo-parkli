<?php

namespace App\Controller;

use App\Model\TTNWebhookData;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RackController extends AbstractApiController
{
    /**
     * @Route("/rack", methods={"POST"})
     */
    public function postStatus(Request $request, LoggerInterface $logger): Response
    {
        $ttnData = $this->deserializeModel($request, TTNWebhookData::class);

        $logger->info('Data received', ['data' => $ttnData]);

        $responseData = ['status' => 'OK'];

        return $this->json($responseData);
    }
}
