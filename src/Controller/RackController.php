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
        /** @var TTNWebhookData $ttnData */
        $ttnData = $this->deserializeModel($request, TTNWebhookData::class);

        $logger->info('Data received from {device} for app {app_id}', [
            'app_id' => $ttnData->appId,
            'device' => $ttnData->devId,
            'payload' => $ttnData->payloadRaw,
            'payload-decoded' => base64_decode($ttnData->payloadRaw),
            'payload-fields' => $ttnData->payloadFields ?? 'null',
        ]);

        $responseData = ['status' => 'OK'];

        return $this->json($responseData);
    }
}
