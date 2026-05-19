<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Builder\Route;

use Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteBuilder implements RouteBuilderInterface
{
    /**
     * @var string
     */
    protected const string ROUTE_COLLECTION_PATH_PLACEHOLDER = '/%s/{resourceName}';

    /**
     * @var string
     */
    protected const string ROUTE_PATH_PLACEHOLDER = '/%s/{resourceName}/{id}';

    /**
     * @var string
     */
    protected const string RESOURCE_NAME_ROUTE_PARAMETER = 'resourceName';

    /**
     * @var string
     */
    protected const string RESOURCE_NAME_PATTERN = '[\w-]+';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_COLLECTION_GET = 'dynamicEntityCollectionGET';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_COLLECTION_POST = 'dynamicEntityCollectionPOST';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_COLLECTION_PATCH = 'dynamicEntityCollectionPATCH';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_COLLECTION_PUT = 'dynamicEntityCollectionPUT';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_COLLECTION_DELETE = 'dynamicEntityCollectionDELETE';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_GET = 'dynamicEntityGET';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_PATCH = 'dynamicEntityPATCH';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_PUT = 'dynamicEntityPUT';

    /**
     * @var string
     */
    protected const string ROUTE_NAME_DELETE = 'dynamicEntityDELETE';

    /**
     * @var string
     */
    protected const string CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const string METHOD = '_method';

    /**
     * @var string
     */
    protected const string STRATEGIES_AUTHORIZATION = '_authorization_strategies';

    /**
     * @uses {@link \Spryker\Zed\ApiKeyAuthorizationConnector\Communication\Plugin\Authorization\ApiKeyAuthorizationStrategyPlugin::STRATEGY_NAME}
     *
     * @var string
     */
    protected const string STRATEGY_AUTHORIZATION_API_KEY = 'ApiKey';

    /**
     * @var string
     */
    protected const string GET_COLLECTION_ACTION = 'getCollectionAction';

    /**
     * @var string
     */
    protected const string GET_ACTION = 'getAction';

    /**
     * @var string
     */
    protected const string POST_ACTION = 'postAction';

    /**
     * @var string
     */
    protected const string PATCH_ACTION = 'patchAction';

    /**
     * @var string
     */
    protected const string PUT_ACTION = 'putAction';

    /**
     * @var string
     */
    protected const string DELETE_ACTION = 'deleteAction';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $config;

    public function __construct(DynamicEntityBackendApiConfig $config)
    {
        $this->config = $config;
    }

    public function buildRouteCollection(RouteCollection $routeCollection): RouteCollection
    {
        $collectionPath = sprintf(static::ROUTE_COLLECTION_PATH_PLACEHOLDER, $this->config->getRoutePrefix());
        $entityPath = sprintf(static::ROUTE_PATH_PLACEHOLDER, $this->config->getRoutePrefix());
        $resourceConstraints = [static::RESOURCE_NAME_ROUTE_PARAMETER => static::RESOURCE_NAME_PATTERN];

        $routeCollection->add(static::ROUTE_NAME_COLLECTION_GET, $this->buildRoute(static::GET_COLLECTION_ACTION, Request::METHOD_GET, $collectionPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_COLLECTION_POST, $this->buildRoute(static::POST_ACTION, Request::METHOD_POST, $collectionPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_COLLECTION_PATCH, $this->buildRoute(static::PATCH_ACTION, Request::METHOD_PATCH, $collectionPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_COLLECTION_PUT, $this->buildRoute(static::PUT_ACTION, Request::METHOD_PUT, $collectionPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_COLLECTION_DELETE, $this->buildRoute(static::DELETE_ACTION, Request::METHOD_DELETE, $collectionPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_GET, $this->buildRoute(static::GET_ACTION, Request::METHOD_GET, $entityPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_PATCH, $this->buildRoute(static::PATCH_ACTION, Request::METHOD_PATCH, $entityPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_PUT, $this->buildRoute(static::PUT_ACTION, Request::METHOD_PUT, $entityPath, $resourceConstraints));
        $routeCollection->add(static::ROUTE_NAME_DELETE, $this->buildRoute(static::DELETE_ACTION, Request::METHOD_DELETE, $entityPath, $resourceConstraints));

        return $routeCollection;
    }

    /**
     * @param array<string, string> $requirements
     */
    protected function buildRoute(string $action, string $method, string $path, array $requirements = []): Route
    {
        $route = new Route($path);
        $route->setDefault(static::CONTROLLER, [DynamicEntityBackendApiController::class, $action])
            ->setDefault(static::METHOD, $method)
            ->setDefault(static::STRATEGIES_AUTHORIZATION, [static::STRATEGY_AUTHORIZATION_API_KEY])
            ->setMethods($method)
            ->setRequirements($requirements);

        return $route;
    }
}
