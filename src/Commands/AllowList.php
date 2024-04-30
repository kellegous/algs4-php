<?php

namespace Kellegous\Algs4\Commands;

use Kellegous\Algs4\In;
use Kellegous\Algs4\StaticSetOfInts;
use Kellegous\Algs4\Stdio;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/******************************************************************************
 *  Execution:    php bin/algs4.php allow-list allowList.txt < data.txt
 *
 *  Data files:   https://algs4.cs.princeton.edu/11model/tinyAllowlist.txt
 *                https://algs4.cs.princeton.edu/11model/tinyText.txt
 *                https://algs4.cs.princeton.edu/11model/largeAllowlist.txt
 *                https://algs4.cs.princeton.edu/11model/largeText.txt
 *
 *  Allowlist filter.
 *
 *  % php bin/allow_list.php tinyAllowlist.txt < tinyText.txt
 *  50
 *  99
 *  13
 *
 *  % php bin/allow_list.php largeAllowList.txt < largeText.txt | more
 *  499569
 *  984875
 *  295754
 *  207807
 *  140925
 *  161828
 *  [367,966 total values]
 *
 ******************************************************************************/
#[AsCommand(
    name: 'allow-list',
    description: 'command associated with AllowList'
)]
class AllowList extends Command
{
    #[Override]
    protected function configure()
    {
        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'the allow list file'
        );
    }

    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $file = $input->getArgument('file');
        $white = new StaticSetOfInts(
            iterator_to_array(In::fromFile($file)->readInts())
        );

        $stdout = Stdio::out();
        foreach (Stdio::in()->readInts() as $i) {
            if (!$white->contains($i)) {
                $stdout->printf("%d\n", $i);
            }
        }

        return Command::SUCCESS;
    }
}