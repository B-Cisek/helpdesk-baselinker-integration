<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Symfony\Controller;

use App\Order\Application\Command\Sync\FetchOrders;
use App\Order\Infrastructure\BaseLinker\Client\Exception\BaselinkerException;
use App\Order\Infrastructure\Marketplace\Exception\UnsupportedMarketplaceException;
use App\Shared\Application\Command\Sync\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    public function __construct(private readonly CommandBus $syncCommandBus) {}

    /**
     * Example endpoint to fetch orders from BaseLinker.
     */
    #[Route('/api/fetch-orders/{marketplace}', methods: ['POST'])]
    public function index(string $marketplace): JsonResponse
    {
        try {
            $this->syncCommandBus->dispatch(new FetchOrders($marketplace));

            return new JsonResponse([
                'status' => 'success',
                'message' => sprintf('Orders from "%s" have been fetched.', $marketplace),
            ]);
        } catch (UnsupportedMarketplaceException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        } catch (BaselinkerException) {
            return new JsonResponse(['error' => 'BaseLinker API error'], 502);
        } catch (\Throwable) {
            return new JsonResponse(['error' => 'Internal server error'], 500);
        }
    }
}
