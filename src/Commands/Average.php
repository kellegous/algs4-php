<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Commands;

use Kellegous\Algs4\Stdio;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  average.php provides a program for reading in a sequence
 *  of real numbers and printing out their average.
 *  <p>
 *  For additional documentation, see <a href="https://algs4.cs.princeton.edu/11model">Section 1.1</a> of
 *  <i>Algorithms, 4th Edition</i> by Robert Sedgewick and Kevin Wayne.
 * </p>
 *
 * @author Robert Sedgewick
 * @author Kevin Wayne
 * @author Kelly Norton
 */
#[AsCommand(
    name: 'average',
    description: 'Reads a sequence of real numbers and prints out their average.'
)]
class Average extends Command
{
    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $sum = 0;
        $count = 0;
        foreach (Stdio::in()->readFloats() as $x) {
            $sum += $x;
            $count++;
        }
        $average = $sum / $count;
        Stdio::out()->printf("Average is %s\n", $average);
        return Command::SUCCESS;
    }
}