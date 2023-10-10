<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of nulldark/routing
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Nulldark\Tests\Units;

use Nulldark\Routing\Route;
use Nulldark\Routing\RouteCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(RouteCollection::class)]
class RouteCollectionTest extends TestCase
{
    /**
     * @covers \Nulldark\Routing\RouteCollection::count
     * @return void
     */
    public function testCount(): void
    {
        $collection = new RouteCollection();
        $collection->add('route_1', new Route('/'));
        $collection->add('route_2', new Route('/'));

        $this->assertCount(2, $collection);
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::add
     * @return void
     */
    public function testRoute(): void
    {
        $collection = new RouteCollection();
        $collection->add('foo', $route = new Route('/bar'));

        $this->assertNull($collection->get('bar'));
        $this->assertEquals($route, $collection->get('foo'));
        $this->assertEquals(['foo' => $route], $collection->all());
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::add
     * @return void
     */
    public function testOverriddenRoute(): void
    {
        $collection = new RouteCollection();
        $collection->add('foo', new Route('/foo'));
        $collection->add('foo', new Route('/bar'));

        $this->assertEquals('/bar', $collection->get('foo')->getPath());
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::getIterator
     * @return void
     */
    public function testIterator(): void
    {
        $collection = new RouteCollection();

        $collection->add('foo', $foo = new Route('/foo'));
        $collection->add('bar', $bar = new Route('/bar'));

        $this->assertInstanceOf(\ArrayIterator::class, $collection->getIterator());
        $this->assertSame([
            'foo' => $foo,
            'bar' => $bar
        ], $collection->getIterator()->getArrayCopy());
    }

    /**
     * @covers \Nulldark\Routing\RouteCollection::mergeCollection
     * @return void
     */
    public function testMergeCollection(): void
    {
        $collection1 = new RouteCollection();
        $collection1->add('foo_1', $foo_1 = new Route('/foo_1'));

        $collection2 = new RouteCollection();
        $collection2->add('foo_2', $foo_2 = new Route('/foo_2'));
        $collection2->mergeCollection($collection1);

        $this->assertEquals($foo_1, $collection2->get('foo_1'));
        $this->assertEquals($foo_2, $collection2->get('foo_2'));
        $this->assertCount(2, $collection2);
    }

    public function testOverriddenMergeCollection(): void
    {
        $collection1 = new RouteCollection();
        $collection1->add('foo_1', new Route('/foo_1'));

        $collection2 = new RouteCollection();
        $collection2->add('foo_1', $foo_2 = new Route('/foo_2'));
        $collection1->mergeCollection($collection2);

        $this->assertCount(1, $collection1);
        $this->assertEquals($foo_2, $collection1->get('foo_1'));
        $this->assertEquals('/foo_2', $collection1->get('foo_1')->getPath());
    }
}