<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helpdesk\BaseLinker\Client\BaseLinkerClientInterface;
use App\Helpdesk\BaseLinker\Marketplace\MarketplaceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/')]
    public function index(MarketplaceProvider $marketplaceProvider): JsonResponse
    {
        $data = $marketplaceProvider->get('allegro');

        return $this->json(['message' => 'Hello World!']);
    }
}
