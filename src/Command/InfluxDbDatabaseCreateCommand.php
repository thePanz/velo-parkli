<?php

namespace App\Command;

use App\Service\InfluxManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InfluxDbDatabaseCreateCommand extends Command
{
    protected static $defaultName = 'influxdb:database:create';

    private InfluxManager $influxDbManager;

    public function __construct(InfluxManager $influxDbManager)
    {
        parent::__construct();
        $this->influxDbManager = $influxDbManager;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->influxDbManager->createDatabase();

        return Command::SUCCESS;
    }
}
