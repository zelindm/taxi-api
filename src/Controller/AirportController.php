<?php

namespace App\Controller;

use App\Entity\Airport;
use App\Entity\Ride;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AirportController extends AbstractController
{
    /**
     * @Route("/getAirports", methods={"GET"})
     */
    public function index()
    {
        $headers = ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Headers' => '*'];

        $airports = $this->getDoctrine()
            ->getRepository(Airport::class)
            ->findAll();

        $response = [];
        foreach ($airports as $airport) {
            /** @var Airport $airport */
            $response[] = [
                'name'      => $airport->getName(),
                'terminals' => $airport->getAvailibleTerminals(),
            ];
        }

        return $this->json($response, 200, $headers);
    }
}
