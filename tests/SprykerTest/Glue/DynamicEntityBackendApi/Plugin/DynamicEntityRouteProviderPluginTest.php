<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DynamicEntityBackendApi\Plugin;

use Codeception\Test\Unit;
use Spryker\Glue\DynamicEntityBackendApi\Plugin\GlueApplication\DynamicEntityRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DynamicEntityBackendApi
 * @group Plugin
 * @group DynamicEntityRouteProviderPluginTest
 * Add your own group annotations below this line
 */
class DynamicEntityRouteProviderPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiTester
     */
    protected $tester;

    /**
     * @var string
     */
    protected const COLLECTION_PATH = '/dynamic-entity/{resourceName}';

    /**
     * @var string
     */
    protected const BY_ID_PATH = '/dynamic-entity/{resourceName}/{id}';

    /**
     * @var string
     */
    protected const GET_COLLECTION_ACTION = 'getCollectionAction';

    /**
     * @var string
     */
    protected const GET_ACTION = 'getAction';

    /**
     * @var string
     */
    protected const POST_ACTION = 'postAction';

    /**
     * @var string
     */
    protected const PATCH_ACTION = 'patchAction';

    /**
     * @var string
     */
    protected const PUT_ACTION = 'putAction';

    /**
     * @var string
     */
    protected const DELETE_ACTION = 'deleteAction';

    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const METHOD = '_method';

    /**
     * @dataProvider routeDataProvider
     *
     * @param string $routeName
     * @param string $path
     * @param string $method
     * @param string $action
     *
     * @return void
     */
    public function testDynamicEntityRouteProviderPluginAddsRoutes(string $routeName, string $path, string $method, string $action): void
    {
        //Arrange
        $dynamicEntityRouteProviderPlugin = new DynamicEntityRouteProviderPlugin();
        $routeCollection = new RouteCollection();

        //Act
        $routeCollection = $dynamicEntityRouteProviderPlugin->addRoutes($routeCollection);

        //Assert
        $route = $routeCollection->get($routeName);
        $this->assertNotNull($route);
        $this->assertEquals($path, $route->getPath());
        $this->assertEquals($method, $route->getDefaults()[static::METHOD]);
        $this->assertEquals($action, $route->getDefaults()[static::CONTROLLER][1]);
    }

    /**
     * @return array<mixed>
     */
    protected function routeDataProvider(): array
    {
        return [
            ['dynamicEntityCollectionGET', static::COLLECTION_PATH, Request::METHOD_GET, static::GET_COLLECTION_ACTION],
            ['dynamicEntityCollectionPOST', static::COLLECTION_PATH, Request::METHOD_POST, static::POST_ACTION],
            ['dynamicEntityCollectionPATCH', static::COLLECTION_PATH, Request::METHOD_PATCH, static::PATCH_ACTION],
            ['dynamicEntityCollectionPUT', static::COLLECTION_PATH, Request::METHOD_PUT, static::PUT_ACTION],
            ['dynamicEntityCollectionDELETE', static::COLLECTION_PATH, Request::METHOD_DELETE, static::DELETE_ACTION],
            ['dynamicEntityGET', static::BY_ID_PATH, Request::METHOD_GET, static::GET_ACTION],
            ['dynamicEntityPATCH', static::BY_ID_PATH, Request::METHOD_PATCH, static::PATCH_ACTION],
            ['dynamicEntityPUT', static::BY_ID_PATH, Request::METHOD_PUT, static::PUT_ACTION],
            ['dynamicEntityDELETE', static::BY_ID_PATH, Request::METHOD_DELETE, static::DELETE_ACTION],
        ];
    }
}
