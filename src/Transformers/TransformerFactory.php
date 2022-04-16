<?php

namespace Hillel\Transformers;

class TransformerFactory
{
    private array $types = [];

    private array $method_casts = [
        'create' => 'create'
    ];

    public function addType(Transformer $transformer): void
    {
        $this->types[] = clone $transformer;
    }

    public function __call(string $name, array $args)
    {
        foreach ($this->method_casts as $key => $method_cast) {
            if (str_starts_with($name, $key)) {
                return $this->$method_cast(substr($name, strlen($key)), reset($args));
            }
        }
        throw new \Exception('Function does not exist.');
    }

    private function create(string $type, int $amount): array
    {
        foreach ($this->types as $object) {
            if (str_ends_with(get_class($object), $type)) {
                return array_fill(0, $amount, clone $object);
            }
        }
        throw new \Exception('Type has not been added or does not exist.');
    }
}
