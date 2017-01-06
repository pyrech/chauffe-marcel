<?php

namespace ChauffeMarcel\Command;

use ChauffeMarcel\Configuration\Storage;
use ChauffeMarcel\Marcel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PeriodicMarcelCommand extends Command
{
    private $storage;
    private $marcel;

    public function __construct(Storage $storage, Marcel $marcel)
    {
        $this->storage = $storage;
        $this->marcel = $marcel;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('chauffe_marcel:periodic')
            ->setDescription('Require heating if needed')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->storage->retrieve();

        $this->marcel->chauffe($configuration);
    }
}
