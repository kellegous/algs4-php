<?php

namespace Kellegous\Algs4\Commands;

use Kellegous\Algs4\Stdio;
use Kellegous\Algs4\Vector;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vector',
    description: 'command associated with Vector'
)]
class ForVector extends Command
{
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $x = Vector::fromData(1.0, 2.0, 3.0, 4.0);
        $y = Vector::fromData(5.0, 2.0, 4.0, 1.0);

        $out = Stdio::out();
        $out->printf("         x = %s\n", $x);
        $out->printf("         y = %s\n", $y);

        $z = $x->plus($y);
        $out->printf("         z = %s\n", $z);

        $z = $z->scale(10.0);
        $out->printf("       10z = %s\n", $z);

        $out->printf("       |x| = %0.2g\n", $x->magnitude());
        $out->printf("    <x, y> = %0.2g\n", $x->dot($y));
        $out->printf("dist(x, y) = %0.2g\n", $x->distanceTo($y));
        $out->printf("    dir(x) = %s\n", $x->direction());

        return Command::SUCCESS;
    }
}