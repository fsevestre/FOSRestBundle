<?php

namespace FOS\RestBundle\Tests\Routing\Loader;

use FOS\RestBundle\Routing\Loader\RestfulControllerLoader,
    FOS\RestBundle\Routing\RestfulRouteCollection;

/*
 * This file is part of the FOS/RestBundle
 *
 * (c) Lukas Kahwe Smith <smith@pooteeweet.org>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 * (c) Bulat Shakirzyanov <avalanche123>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * RestfulControllerLoader test.
 *
 * @author Konstantin Kudryashov <ever.zet@gmail.com>
 */
class RestfulControllerLoaderTest extends LoaderTest
{
    /**
     * Test that UsersController RESTful class gets parsed correctly.
     */
    public function testUsersFixture()
    {
        $collection     = $this->loadFromControllerFixture('UsersController');
        $etalonRoutes   = $this->loadEtalonRoutesInfo('users_controller.yml');

        $this->assertTrue($collection instanceof RestfulRouteCollection);
        $this->assertEquals(13, count($collection->all()));

        foreach ($etalonRoutes as $name => $params) {
            $route = $collection->get($name);

            $this->assertNotNull($route, sprintf('route %s exists', $name));
            $this->assertEquals($params['pattern'], $route->getPattern());
            $this->assertEquals($params['method'], $route->getRequirement('_method'));
            $this->assertContains($params['controller'], $route->getDefault('_controller'));
        }
    }

    /**
     * Test that annotated UsersController RESTful class gets parsed correctly.
     */
    public function testAnnotatedUsersFixture()
    {
        $collection     = $this->loadFromControllerFixture('AnnotatedUsersController');
        $etalonRoutes   = $this->loadEtalonRoutesInfo('annotated_users_controller.yml');

        $this->assertTrue($collection instanceof RestfulRouteCollection);
        $this->assertEquals(10, count($collection->all()));

        foreach ($etalonRoutes as $name => $params) {
            $route = $collection->get($name);

            $this->assertNotNull($route);
            $this->assertEquals($params['pattern'], $route->getPattern());
            $this->assertEquals($params['requirements'], $route->getRequirements());
            $this->assertContains($params['controller'], $route->getDefault('_controller'));
        }
    }

    /**
     * Load routes collection from fixture class under Tests\Fixtures directory.
     *
     * @param   string  $fixtureName    name of the class fixture
     */
    protected function loadFromControllerFixture($fixtureName)
    {
        $loader = $this->getControllerLoader();

        return $loader->load('FOS\RestBundle\Tests\Fixtures\Controller\\' . $fixtureName, 'rest');
    }
}