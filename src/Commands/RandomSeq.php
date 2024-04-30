<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Commands;

use InvalidArgumentException;
use Kellegous\Algs4\InputFormatException;
use Kellegous\Algs4\Random;
use Kellegous\Algs4\Stdio;
use Override;
use Random\Engine\Mt19937;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Kellegous\Algs4\parse_float;

/**
 * random_seq.php is a client that prints out a pseudorandom sequence of real
 * numbers in a given range.
 *
 * Prints N numbers between lo and hi.
 *
 * % bin/algs4.php random-seq --range=100.0-200.0 5
 * 123.43
 * 153.13
 * 144.38
 * 155.18
 * 104.02
 *
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
    name: 'random-seq',
    description: 'Prints out a pseudorandom sequence of real numbers in a given range.'
)]
class RandomSeq extends Command
{
    #[Override]
    protected function configure(): void
    {
        $this->addArgument(
            'n',
            InputArgument::REQUIRED,
            'the number of random numbers to generate'
        );

        $this->addOption(
            'range',
            'r',
            InputArgument::OPTIONAL,
            'the range of the random numbers',
            '0.0-1.0'
        );
    }

    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $n = $input->getArgument('n');
        [$lo, $hi] = self::parseRange($input->getOption('range'));
        $random = Random::withEngine(new Mt19937());

        $out = Stdio::out();
        for ($i = 0; $i < $n; $i++) {
            $x = $random->uniformFloat($lo, $hi);
            $out->printf("%.2f\n", $x);
        }
        return Command::SUCCESS;
    }

    /**
     * @param string $v
     * @return array{float, float}
     * @throws InputFormatException
     */
    private static function parseRange(string $v): array
    {
        $parts = explode('-', $v);

        if (count($parts) !== 2) {
            throw new InvalidArgumentException('invalid range');
        }

        $min = parse_float($parts[0]);
        $max = parse_float($parts[1]);

        if ($min >= $max) {
            throw new InvalidArgumentException('min must be less than max');
        }

        return [$min, $max];
    }
}