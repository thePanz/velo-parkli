<?php

namespace App\Controller;

use App\Service\InfluxManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfluxAdminController extends AbstractController
{
    private InfluxManager $influxManager;

    public function __construct(InfluxManager $influxManager)
    {
        $this->influxManager = $influxManager;
    }

    /**
     * @Route("/influx/recreate", name="influx_recreate", methods={"POST"})
     */
    public function drop(): Response
    {
        $this->influxManager->dropDatabase();
        $this->influxManager->createDatabase();

        return $this->json([
            'message' => 'Influx DB has been recreated',
        ]);
    }
}
