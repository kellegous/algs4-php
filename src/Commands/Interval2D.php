<?php

declare(strict_types=1);

namespace Kellegous\Algs4\Commands;

use InvalidArgumentException;
use Kellegous\Algs4\Counter;
use Kellegous\Algs4\Graphics\Canvas;
use Kellegous\Algs4\Graphics\Color;
use Kellegous\Algs4\Graphics\Destination;
use Kellegous\Algs4\Graphics\Drawing;
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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Kellegous\Algs4\parse_float;
use function Kellegous\Algs4\parse_int;

#[AsCommand(
    name: 'interval2d',
    description: 'command associated with Interval2D'
)]
final class Interval2D extends Command
{
    private const int SIZE = 512;

    private const string DEFAULT_DEST = 'interval2d.png';

    #[Override]
    protected function configure(): void
    {
        $this->addOption(
            'dest',
            'd',
            InputOption::VALUE_REQUIRED,
            'the destination file',
            self::DEFAULT_DEST
        );

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
        $x_min = self::getFloatArgument($input, 'xmin');
        $x_max = self::getFloatArgument($input, 'xmax');
        $y_min = self::getFloatArgument($input, 'ymin');
        $y_max = self::getFloatArgument($input, 'ymax');
        $trials = parse_int($input->getArgument('trials'));

        $drawing = new Drawing(self::SIZE, self::SIZE);
        $canvas = new Canvas($drawing);

        $x_interval = Interval1D::fromMinMax($x_min, $x_max);
        $y_interval = Interval1D::fromMinMax($y_min, $y_max);
        $box = \Kellegous\Algs4\Interval2D::fromXY($x_interval, $y_interval);
        $canvas->rectangle(
            $box->x()->min(),
            $box->y()->min(),
            $box->x()->max(),
            $box->y()->max()
        )->stroke(Color::black($drawing));

        $counter = new Counter('hits');
        $random = Random::withEngine(new Mt19937());
        for ($i = 0; $i < $trials; $i++) {
            $x = $random->uniformFloat();
            $y = $random->uniformFloat();
            $point = new Point2D($x, $y);
            if ($box->contains($point)) {
                $counter->increment();
            } else {
                $canvas->point($x, $y, Color::black($drawing));
            }
        }

        Stdio::out()->println($counter);
        Stdio::out()->printf("box area = %.2f\n", $box->area());

        $drawing->writePNG(
            Destination::toFile($input->getOption('dest'))
        );

        return Command::SUCCESS;
    }

    private static function getFloatArgument(
        InputInterface $input,
        string $name,
        float $min = 0.0,
        float $max = 1.0
    ): float {
        $v = parse_float($input->getArgument($name));
        if ($v < $min || $v > $max) {
            throw new InvalidArgumentException(
                sprintf('%s must be in range (%0.2g, %0.2g)', $name, $min, $max)
            );
        }
        return $v;
    }
}