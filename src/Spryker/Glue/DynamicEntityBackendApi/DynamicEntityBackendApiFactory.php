<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi;

use Monolog\Handler\BufferHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Builder\Route\RouteBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Builder\Route\RouteBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Client\DynamicEntityBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToLocaleFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToStorageFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Service\DynamicEntityBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DocumentationSchemaExpander;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DocumentationSchemaExpanderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DynamicEntityProtectedPathCollectionExpander;
use Spryker\Glue\DynamicEntityBackendApi\Expander\DynamicEntityProtectedPathCollectionExpanderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathDeleteMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathGetMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPatchMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPostMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathPutMethodBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\SchemaBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\SchemaBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\DynamicApiPathMethodFormatter;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\DynamicApiPathMethodFormatterInterface;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilder;
use Spryker\Glue\DynamicEntityBackendApi\Formatter\TreeBuilder\DynamicEntityConfigurationTreeBuilderInterface;
use Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter\InvalidationVoter;
use Spryker\Glue\DynamicEntityBackendApi\InvalidationVoter\InvalidationVoterInterface;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLogger;
use Spryker\Glue\DynamicEntityBackendApi\Logger\DynamicEntityBackendApiLoggerInterface;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Creator\DynamicEntityCreator;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Creator\DynamicEntityCreatorInterface;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Deleter\DynamicEntityDeleter;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Deleter\DynamicEntityDeleterInterface;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReader;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Updater\DynamicEntityUpdater;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Updater\DynamicEntityUpdaterInterface;
use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig getConfig()
 */
class DynamicEntityBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'dynamicEntityLogger';

    public function getDynamicEntityFacade(): DynamicEntityBackendApiToDynamicEntityFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::FACADE_DYNAMIC_ENTITY);
    }

    public function getServiceUtilEncoding(): DynamicEntityBackendApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    public function createDynamicEntityReader(): DynamicEntityReaderInterface
    {
        return new DynamicEntityReader(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    public function createDynamicEntityCreator(): DynamicEntityCreatorInterface
    {
        return new DynamicEntityCreator(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    public function createDynamicEntityUpdater(): DynamicEntityUpdaterInterface
    {
        return new DynamicEntityUpdater(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }

    public function createGlueRequestDynamicEntityMapper(): GlueRequestDynamicEntityMapper
    {
        return new GlueRequestDynamicEntityMapper(
            $this->getServiceUtilEncoding(),
            $this->getConfig(),
        );
    }

    public function createGlueResponseDynamicEntityMapper(): GlueResponseDynamicEntityMapper
    {
        return new GlueResponseDynamicEntityMapper(
            $this->getServiceUtilEncoding(),
            $this->getGlossaryStorageClient(),
            $this->getLocaleFacade(),
        );
    }

    public function createRouteBuilder(): RouteBuilderInterface
    {
        return new RouteBuilder($this->getConfig());
    }

    public function getGlossaryStorageClient(): DynamicEntityBackendApiToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    public function getLocaleFacade(): DynamicEntityBackendApiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::FACADE_LOCALE);
    }

    public function createDynamicEntityLogger(): DynamicEntityBackendApiLoggerInterface
    {
        return new DynamicEntityBackendApiLogger(
            $this->createLogger(),
        );
    }

    public function createDocumentationSchemaExpander(): DocumentationSchemaExpanderInterface
    {
        return new DocumentationSchemaExpander($this->createDynamicEntityReader());
    }

    public function createLogger(): ?LoggerInterface
    {
        if (!$this->getConfig()->isLoggingEnabled()) {
            return null;
        }

        return new Logger(static::LOGGER_NAME, [
            $this->createBufferedStreamHandler(),
        ]);
    }

    public function createBufferedStreamHandler(): HandlerInterface
    {
        return new BufferHandler(
            $this->createStreamHandler(),
        );
    }

    public function createStreamHandler(): HandlerInterface
    {
        return new StreamHandler($this->getConfig()->getLogFilepath());
    }

    public function createDynamicEntityProtectedPathCollectionExpander(): DynamicEntityProtectedPathCollectionExpanderInterface
    {
        return new DynamicEntityProtectedPathCollectionExpander($this->getConfig());
    }

    public function createDynamicApiPathMethodFormatter(): DynamicApiPathMethodFormatterInterface
    {
        return new DynamicApiPathMethodFormatter($this->getPathMethodBuilders());
    }

    /**
     * @return array<\Spryker\Glue\DynamicEntityBackendApi\Formatter\Builder\PathMethodBuilderInterface>
     */
    public function getPathMethodBuilders(): array
    {
        return [
            $this->createPathGetMethodBuilder(),
            $this->createPathPostMethodBuilder(),
            $this->createPathPutMethodBuilder(),
            $this->createPathPatchMethodBuilder(),
            $this->createPathDeleteMethodBuilder(),
        ];
    }

    public function createPathGetMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathGetMethodBuilder(
            $this->getConfig(),
            $this->createDynamicEntityConfigurationTreeBuilder(),
            $this->createSchemaBuilder(),
        );
    }

    public function createPathPostMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPostMethodBuilder(
            $this->getConfig(),
            $this->createDynamicEntityConfigurationTreeBuilder(),
            $this->createSchemaBuilder(),
        );
    }

    public function createPathPutMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPutMethodBuilder(
            $this->getConfig(),
            $this->createDynamicEntityConfigurationTreeBuilder(),
            $this->createSchemaBuilder(),
        );
    }

    public function createPathPatchMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathPatchMethodBuilder(
            $this->getConfig(),
            $this->createDynamicEntityConfigurationTreeBuilder(),
            $this->createSchemaBuilder(),
        );
    }

    public function createPathDeleteMethodBuilder(): PathMethodBuilderInterface
    {
        return new PathDeleteMethodBuilder(
            $this->getConfig(),
            $this->createDynamicEntityConfigurationTreeBuilder(),
            $this->createSchemaBuilder(),
        );
    }

    public function createInvalidationVoter(): InvalidationVoterInterface
    {
        return new InvalidationVoter(
            $this->getDynamicEntityFacade(),
            $this->getConfig(),
            $this->getStorageFacade(),
        );
    }

    public function getStorageFacade(): DynamicEntityBackendApiToStorageFacadeInterface
    {
        return $this->getProvidedDependency(DynamicEntityBackendApiDependencyProvider::FACADE_STORAGE);
    }

    public function createDynamicEntityConfigurationTreeBuilder(): DynamicEntityConfigurationTreeBuilderInterface
    {
        return new DynamicEntityConfigurationTreeBuilder();
    }

    public function createSchemaBuilder(): SchemaBuilderInterface
    {
        return new SchemaBuilder();
    }

    public function createDynamicEntityDeleter(): DynamicEntityDeleterInterface
    {
        return new DynamicEntityDeleter(
            $this->getDynamicEntityFacade(),
            $this->createGlueRequestDynamicEntityMapper(),
            $this->createGlueResponseDynamicEntityMapper(),
            $this->createDynamicEntityLogger(),
        );
    }
}
