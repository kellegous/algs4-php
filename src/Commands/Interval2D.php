<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Commands;

use Kellegous\Algs4\Counter;
use Kellegous\Algs4\Interval1D;
use Kellegous\Algs4\Point2D;
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
use function Kellegous\Algs4\parse_int;

#[AsCommand(
    name: 'interval2d',
    description: 'command associated with Interval2D'
)]
final class Interval2D extends Command
{

    #[Override]
    protected function configure(): void
    {
        $this->addArgument(
            'xmin',
            InputArgument::REQUIRED,
            'the minimum x coordinate'
        );
        $this->addArgument(
            'xmax',
            InputArgument::REQUIRED,
            'the maximum x coordinate'
        );
        $this->addArgument(
            'ymin',
            InputArgument::REQUIRED,
            'the minimum y coordinate'
        );
        $this->addArgument(
            'ymax',
            InputArgument::REQUIRED,
            'the maximum y coordinate'
        );
        $this->addArgument(
            'trials',
            InputArgument::REQUIRED,
            'the number of trials'
        );
    }

    #[Override]
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $x_min = parse_float($input->getArgument('xmin'));
        $x_max = parse_float($input->getArgument('xmax'));
        $y_min = parse_float($input->getArgument('ymin'));
        $y_max = parse_float($input->getArgument('ymax'));
        $trials = parse_int($input->getArgument('trials'));

        $x_interval = Interval1D::fromMinMax($x_min, $x_max);
        $y_interval = Interval1D::fromMinMax($y_min, $y_max);
        $box = \Kellegous\Algs4\Interval2D::fromXY($x_interval, $y_interval);
        // $box->draw();

        $counter = new Counter('hits');
        $random = Random::withEngine(new Mt19937());
        for ($i = 0; $i < $trials; $i++) {
            $x = $random->uniformFloat();
            $y = $random->uniformFloat();
            $point = new Point2D($x, $y);
            if ($box->contains($point)) {
                $counter->increment();
            }
            // else $point->draw();
        }

        Stdio::out()->println($counter);
        Stdio::out()->printf("box area = %.2f\n", $box->area());
        return Command::SUCCESS;
    }
}