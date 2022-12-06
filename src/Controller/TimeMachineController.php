<?php

namespace App\Controller;

use App\Entity\TimeMachine;
use App\Repository\TimeMachineRepository;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse as Response;

class TimeMachineController extends AbstractController
{
    #[Route('/timeMachine', name: 'app_time_machine')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TimeMachineController.php',
        ]);
    }

    #[Route('/timeMachine/new', name: 'app_time_machine_post', methods: ['POST'])]
    public function post(Request $request, TimeMachineRepository $timeMachineRepository): void
    {
        $timeMachineEntry = new TimeMachine();
        $timeMachineRepository->save($timeMachineEntry, true);
    }
}
