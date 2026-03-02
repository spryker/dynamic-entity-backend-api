<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi;

use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientBridge;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeBridge;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToLocaleFacadeBridge;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeBridge;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceBridge;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig getConfig()
 */
class DynamicEntityBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_DYNAMIC_ENTITY = 'FACADE_DYNAMIC_ENTITY';

    /**
     * @var string
     */
    public const FACADE_STORAGE = 'FACADE_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addDynamicEntityFacade($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addStorageFacade($container);

        return $container;
    }

    protected function addDynamicEntityFacade(Container $container): Container
    {
        $container->set(static::FACADE_DYNAMIC_ENTITY, function (Container $container) {
            return new DynamicEntityBackendApiToDynamicEntityFacadeBridge(
                $container->getLocator()->dynamicEntity()->facade(),
            );
        });

        return $container;
    }

    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new DynamicEntityBackendApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new DynamicEntityBackendApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new DynamicEntityBackendApiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }

    protected function addStorageFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORAGE, function (Container $container) {
            return new DynamicEntityBackendApiToStorageFacadeBridge($container->getLocator()->storage()->facade());
        });

        return $container;
    }
}
