<?php

namespace App\Command;

use App\Repository\AccountDeletionRequestRepository;
use App\Service\AccountDeletionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:delete-expired-accounts')]
class DeleteExpiredAccountsCommand extends Command
{
    public function __construct(
        private AccountDeletionRequestRepository $repo,
        private AccountDeletionService $service
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $now = new \DateTime();
        $requests = $this->repo->findExpiredRequests($now);

        foreach ($requests as $req) {
            $this->service->anonymiseUser($req->getUser());
        }

        $output->writeln(count($requests) . ' comptes supprimés.');
        return Command::SUCCESS;
    }
}
