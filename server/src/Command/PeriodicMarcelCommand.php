<?php

namespace App\Command;

use App\Configuration\Storage;
use App\Marcel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PeriodicMarcelCommand extends Command
{
    protected static $defaultName = 'chauffe_marcel:periodic';
    protected static $defaultDescription = 'Require heating if needed';

    private Storage $storage;
    private Marcel $marcel;

    public function __construct(Storage $storage, Marcel $marcel)
    {
        $this->storage = $storage;
        $this->marcel = $marcel;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configuration = $this->storage->retrieve();

        $this->marcel->chauffe($configuration);

        return Command::SUCCESS;
    }
}
