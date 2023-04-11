<?php

namespace App\Controller;

use App\Entity\TimeMachine;
use App\Repository\TimeMachineRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/timeMachine')]
class TimeMachineController extends AbstractController
{
    public function __construct()
    {
        // https://symfony.com/doc/current/components/serializer.html#usage
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    #[Route('/', name: 'app_time_machine')]
    public function index(TimeMachineRepository $timeMachineRepository): Response
    {
        $timeMachines = $timeMachineRepository->findAll();
        $jsonContent = $this->serializer->serialize($timeMachines, 'json');

        return new Response($jsonContent, 200, array('Content-Type' => 'application/json;charset=UTF-8'));
    }

    #[Route('/random', name: 'app_time_machine_random', methods: ['GET'])]
    public function randomTimeMachine(TimeMachineRepository $timeMachineRepository): Response {
        $timeMachines = $timeMachineRepository->getRandomTimeMachine();
        $jsonContent = $this->serializer->serialize($timeMachines, 'json');
        return new Response($jsonContent, 200, array('Content-Type' => 'application/json;charset=UTF-8'));
    }

    #[Route('/new', name: 'app_time_machine_post', methods: ['POST'])]
    public function post(Request $request, TimeMachineRepository $timeMachineRepository): Response
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $timeMachine = $this->serializer->denormalize($data, TimeMachine::class);

        $timeMachineRepository->save($timeMachine, true);

        return new Response('Time Machine created!', 200, array('Content-Type' => 'text/plain;charset=UTF-8'));
    }
}
