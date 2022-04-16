<?php

require_once 'vendor/autoload.php';

use Hillel\Transformers;

echo '<pre>';

$factory = new Transformers\TransformerFactory();

$factory->addType(new Transformers\Transformer1());
$factory->addType(new Transformers\Transformer2());

print_r($factory->createTransformer1(5));
print_r($factory->createTransformer2(2));

$mergeTransformer = new Transformers\MergeTransformer();
$mergeTransformer->addTransformer(new Transformers\Transformer2());
$mergeTransformer->addTransformer($factory->createTransformer2(2));

$factory->addType($mergeTransformer);

$transformer = current($factory->createMergeTransformer(1));
echo $transformer->getSpeed() . PHP_EOL;
echo $transformer->getWeight() . PHP_EOL;
echo $transformer->getHeight() . PHP_EOL;

echo '</pre>';
