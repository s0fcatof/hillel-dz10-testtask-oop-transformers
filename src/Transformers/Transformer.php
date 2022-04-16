<?php

namespace Hillel\Transformers;

abstract class Transformer
{
    protected int $speed;

    protected int $weight;

    protected int $height;

    protected array $mergedWith = [];

    public function getSpeed(): int
    {
        $speed = $this->speed;
        foreach ($this->mergedWith as $object) {
            if ($speed > $object->speed) {
                $speed = $object->speed;
            }
        }
        return $speed;
    }

    public function getWeight(): int
    {
        return $this->sumIfMerged('weight');
    }

    public function getHeight(): int
    {
        return $this->sumIfMerged('height');
    }

    protected function sumIfMerged($var): int
    {
        $$var = $this->$var;
        foreach ($this->mergedWith as $object) {
            $$var += $object->$var;
        }
        return $$var;
    }

    public function addTransformer(Transformer | array $transformer)
    {
        if (gettype($transformer) == 'array') {
            $this->mergedWith = array_merge($this->mergedWith, $transformer);
            return;
        }
        $this->mergedWith[] = $transformer;
    }
}
