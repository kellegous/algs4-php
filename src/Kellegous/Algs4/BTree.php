<?php

namespace Kellegous\Algs4;

use Kellegous\Algs4\BTree\Entry;
use Kellegous\Algs4\BTree\Node;

/**
 * @template K extends \Comparable
 * @template V
 */
class BTree implements \Countable
{
    const M = 4;

    /**
     * @var Node<K, V|null>
     */
    private Node $root;

    private int $height = 0;

    private int $n = 0;

    public function __construct()
    {
        $this->root = new Node(0);
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function count(): int
    {
        return $this->n;
    }

    public function height(): int
    {
        return $this->height;
    }

    /**
     * @param K $key
     *
     * @return V|null
     */
    public function get(mixed $key): mixed
    {
        if ($key === null) {
            throw new \InvalidArgumentException("argument to get() is null");
        }
        return $this->search($this->root, $key, $this->height);
    }

    public function put(mixed $key, mixed $val): void
    {
        if ($key === null) {
            throw new \InvalidArgumentException("argument key to put() is null");
        }
        $u = $this->insert($this->root, $key, $val, $this->height);
        $this->n++;
        if ($u === null) {
            return;
        }

        // need to split the root
        $t = new Node(2);
        $t->children[0] = new Entry($this->root->getChild(0)->key, null, $this->root);
        $t->children[1] = new Entry($u->getChild(0)->key, null, $u);
        $this->root = $t;
        $this->height++;
    }

    /**
     * @param Node<K, V|null> $h
     * @param K $key
     * @param V|null $val
     * @param int $ht
     *
     * @return Node<K, V|null>|null
     */
    private function insert(Node $h, mixed $key, mixed $val, int $ht): ?Node
    {
        $t = new Entry($key, $val, null);

        if ($ht === 0) {
            // external node
            for ($j = 0; $j < $h->m; $j++) {
                if ($key < T::notNull($h->children[$j])->key) {
                    break;
                }
            }
        } else {
            // internal node
            for ($j = 0; $j < $h->m; $j++) {
                if (($j + 1 == $h->m) || $key < $h->getChild($j + 1)->key) {
                    $u = $this->insert($h->getChild($j++)->mustHaveNext(), $key, $val, $ht - 1);
                    if ($u === null) {
                        return null;
                    }
                    $t->key = T::notNull($u->children[0])->key;
                    $t->val = null;
                    $t->next = $u;
                    break;
                }
            }
        }

        for ($i = $h->m; $i > $j; $i--) {
            $h->children[$i] = $h->children[$i - 1];
        }
        $h->children[$j] = $t;
        $h->m++;
        return ($h->m < self::M) ? null : $this->split($h);
    }

    /**
     * @param Node<K, V|null> $h
     *
     * @return Node<K, V|null>
     */
    private function split(Node $h): Node
    {
        $n = self::M / 2;
        $t = new Node($n);
        $h->m = $n;
        for ($j = 0; $j < $n; $j++) {
            $t->children[$j] = $h->children[$n + $j];
            // $h->children[$n + $j] = null;
        }
        return $t;
    }

    /**
     * @param Node<K, V|null> $x
     * @param K $key
     * @param int $ht
     *
     * @return V|null
     */
    private function search(Node $x, mixed $key, int $ht): mixed
    {
        if ($ht === 0) {
            // external node
            for ($j = 0; $j < $x->m; $j++) {
                $child = $x->getChild($j);
                if ($key === $child->key) {
                    return $child->val;
                }
            }
        } else {
            // internal node
            for ($j = 0; $j < $x->m; $j++) {
                if ($j + 1 === $x->m || $key < $x->getChild($j + 1)->key) {
                    return $this->search($x->getChild($j)->mustHaveNext(), $key, $ht - 1);
                }
            }
        }

        return null;
    }
}