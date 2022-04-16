<?php

namespace Hillel\Tests;

use Hillel\Transformers;
use phpDocumentor\Reflection\Types\Object_;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

class TransformersTest extends TestCase
{
    protected Transformers\TransformerFactory $factory;
    protected Transformers\MergeTransformer $mergeTransformer;

    public function setUp(): void
    {
        $this->factory = new Transformers\TransformerFactory();
        $this->mergeTransformer = new Transformers\MergeTransformer();
    }

    public function testAddType()
    {
        $this->factory->addType(new Transformers\Transformer1());

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer1()],
            (new ReflectionClass(Transformers\TransformerFactory::class))
                ->getProperty('types')
                ->getValue($this->factory)
        );

        $this->factory->addType(new Transformers\Transformer2());

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer1(), new Transformers\Transformer2()],
            (new ReflectionClass(Transformers\TransformerFactory::class))
                ->getProperty('types')
                ->getValue($this->factory)
        );
    }

    public function testCreate()
    {
        $this->factory->addType(new Transformers\Transformer1());
        $this->factory->addType(new Transformers\Transformer2());

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer1(), new Transformers\Transformer1()],
            $this->factory->createTransformer1(2)
        );

        $this->assertEquals(
            2,
            count($this->factory->createTransformer1(2))
        );

        $this->assertEqualsCanonicalizing(
            [
                new Transformers\Transformer2(), new Transformers\Transformer2(), new Transformers\Transformer2(),
                new Transformers\Transformer2(), new Transformers\Transformer2()
            ],
            $this->factory->createTransformer2(5)
        );

        $this->assertEquals(
            5,
            count($this->factory->createTransformer2(5))
        );

        $this->expectException(\Exception::class);
        $this->factory->createTransformer3(2);
    }

    public function testAddTransformer()
    {
        $this->factory->addType(new Transformers\Transformer2());
        $this->mergeTransformer->addTransformer(new Transformers\Transformer2());

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer2()],
            (new ReflectionClass(Transformers\MergeTransformer::class))
                ->getProperty('mergedWith')
                ->getValue($this->mergeTransformer)
        );

        $this->mergeTransformer->addTransformer($this->factory->createTransformer2(2));

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer2(), new Transformers\Transformer2(), new Transformers\Transformer2()],
            (new ReflectionClass(Transformers\MergeTransformer::class))
                ->getProperty('mergedWith')
                ->getValue($this->mergeTransformer)
        );

        $this->factory->addType($this->mergeTransformer);

        $this->assertEqualsCanonicalizing(
            [new Transformers\Transformer2(), $this->mergeTransformer],
            (new ReflectionClass(Transformers\TransformerFactory::class))
                ->getProperty('types')
                ->getValue($this->factory)
        );
    }

    public function testGetters()
    {
        $this->factory->addType(new Transformers\Transformer2());
        $this->mergeTransformer->addTransformer(new Transformers\Transformer2());
        $this->mergeTransformer->addTransformer($this->factory->createTransformer2(2));
        $this->factory->addType($this->mergeTransformer);
        $transformer = current($this->factory->createMergeTransformer(1));

        $this->assertEquals(
            10,
            $transformer->getSpeed()
        );

        $this->assertEquals(
            260,
            $transformer->getWeight()
        );

        $this->assertEquals(
            80,
            $transformer->getHeight()
        );

        $this->assertEqualsCanonicalizing(
            20,
            (new ReflectionClass(Transformers\MergeTransformer::class))
                ->getProperty('speed')
                ->getValue($this->mergeTransformer)
        );

        $this->assertEqualsCanonicalizing(
            20,
            (new ReflectionClass(Transformers\MergeTransformer::class))
                ->getProperty('weight')
                ->getValue($this->mergeTransformer)
        );

        $this->assertEqualsCanonicalizing(
            5,
            (new ReflectionClass(Transformers\MergeTransformer::class))
                ->getProperty('height')
                ->getValue($this->mergeTransformer)
        );
    }
}
