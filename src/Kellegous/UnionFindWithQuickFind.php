<?php

namespace Kellegous;

class UnionFindWithQuickFind implements \Countable
{
    /**
     * @var \SplFixedArray
     */
    private $id;

    /**
     * @var int
     */
    private $count;

    public function __construct(
        int $n
    ) {
        $id = new \SplFixedArray($n);
        for ($i = 0; $i < $n; $i++) {
            $id[$i] = $i;
        }
        $this->id = $id;
        $this->count = $n;
    }

    public function count(): int
    {
        return $this->count;
    }

    private function validateIndex(int $p)
    {
        $n = count($this->id);
        if ($p < 0 || $p >= $n) {
            throw new \InvalidArgumentException(
                "index must be >= 0 and < {$n}: {$p}"
            );
        }
    }

    public function find(int $p): int
    {
        $this->validateIndex($p);
        return $this->id[$p];
    }

    public function union(int $p, int $q)
    {
        $p_id = $this->find($p);
        $q_id = $this->find($q);
        if ($p_id === $q_id) {
            return;
        }

        $n = count($this->id);
        for ($i = 0; $i < $n; $i++) {
            if ($this->id[$i] === $p_id) {
                $this->id[$i] = $q_id;
            }
        }
        $this->count--;
    }
}