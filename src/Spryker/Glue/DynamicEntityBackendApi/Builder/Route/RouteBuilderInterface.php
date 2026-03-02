<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Builder\Route;

use Symfony\Component\Routing\RouteCollection;

interface RouteBuilderInterface
{
    public function buildRouteCollection(RouteCollection $routeCollection): RouteCollection;
}
