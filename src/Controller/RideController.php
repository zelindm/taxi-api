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

class RideController extends AbstractController
{
    /**
     * @Route("/api/store", methods={"POST"})
     */
    public function store(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        \Swift_Mailer $mailer
    ): Response {
        $headers = ['Access-Control-Allow-Origin' => '*', 'Access-Control-Allow-Headers' => '*'];
        $req     = json_decode($request->getContent(), true);

        /** @var Airport $airport */
        $airport = $this->getDoctrine()
            ->getRepository(Airport::class)
            ->findOneBy(['name' => $req['airport']]);

        if (!$airport) {
            return $this->json(['errors' => ['airport not found']], 400, $headers);
        }

        $ride = new Ride();
        $ride->setClientName($req['name']);
        $ride->setArrivedAt(new \DateTime($req['date'] . ' ' . $req['time']));
        $ride->setPhone($req['phone']);
        $ride->setFlightNumber($req['flightNumber']);
        $ride->setCreatedAt(new \DateTime());
        $ride->setAirport($airport);
        if (isset($req['terminal'])) {
            $ride->setTerminal($req['terminal']);
        }

        $errors = $validator->validate($ride);
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], 400, $headers);
        }

        $entityManager->persist($ride);
        $entityManager->flush();

        $this->sendNotification($ride, $mailer);

        return $this->json(['result' => true], 200, $headers);
    }

    /**
     * @Route("/api/store", methods="OPTIONS")
     */
    public function options(): Response
    {
        $headers = [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Methods' => 'POST, OPTIONS, GET',
        ];

        return new Response('', 200, $headers);
    }

    /**
     * @param Ride $ride
     * @param \Swift_Mailer $mailer
     */
    private function sendNotification(Ride $ride, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('New ride'))
            ->setFrom('info@unbiased.co.uk')
            ->setTo('zelindm@gmail.com')
            ->setBody($ride->getDesciption());

        $mailer->send($message);
    }
}
