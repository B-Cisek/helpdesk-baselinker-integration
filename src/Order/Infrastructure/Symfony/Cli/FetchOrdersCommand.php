<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Symfony\Cli;

use App\Order\Application\Command\Sync\FetchOrders;
use App\Order\Infrastructure\BaseLinker\Client\Exception\BaselinkerException;
use App\Order\Infrastructure\Marketplace\Exception\UnsupportedMarketplaceException;
use App\Order\Infrastructure\Marketplace\MarketplaceProvider;
use App\Shared\Application\Command\Sync\CommandBus;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:order:fetch',
    description: 'Fetch orders from a marketplace via BaseLinker API',
)]
final class FetchOrdersCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly MarketplaceProvider $marketplaceProvider,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'marketplace',
                InputArgument::OPTIONAL,
                'Marketplace name (e.g., allegro, amazon)',
            )
            ->addOption(
                'list',
                'l',
                InputOption::VALUE_NONE,
                'List all available marketplaces',
            );
    }

    /**
     * Example command to fetch orders from BaseLinker.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('list')) {
            return $this->listMarketplaces($io);
        }

        $marketplace = $input->getArgument('marketplace');

        if (!$marketplace) {
            $io->error('Please provide a marketplace name or use --list to see available marketplaces.');
            return Command::FAILURE;
        }

        try {
            $io->title(sprintf('Fetching orders from %s', $marketplace));

            $this->commandBus->dispatch(new FetchOrders($marketplace));

            $io->success(sprintf(
                'Orders from "%s" have been fetched.',
                $marketplace
            ));

            return Command::SUCCESS;
        } catch (UnsupportedMarketplaceException $e) {
            $io->error($e->getMessage());
            $io->note(sprintf(
                'Available marketplaces: %s',
                implode(', ', $this->marketplaceProvider->getAvailableSources()),
            ));

            return Command::FAILURE;
        } catch (BaselinkerException $e) {
            $io->error('BaseLinker API error: ' . $e->getMessage());

            return Command::FAILURE;
        } catch (\Throwable $e) {
            $io->error('Unexpected error: ' . $e->getMessage());

            if ($output->isVerbose()) {
                $io->text($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    private function listMarketplaces(SymfonyStyle $io): int
    {
        $sources = $this->marketplaceProvider->getAvailableSources();

        if (empty($sources)) {
            $io->warning('No marketplaces are currently configured.');
            return Command::SUCCESS;
        }

        $io->title('Available Marketplaces');

        $io->listing($sources);

        $io->info(sprintf('Total: %d marketplace(s)', count($sources)));

        return Command::SUCCESS;
    }
}
