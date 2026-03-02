<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;

interface DynamicEntityBackendApiToDynamicEntityFacadeInterface
{
    public function getDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCriteriaTransfer $dynamicEntityConfigurationCriteriaTransfer
    ): DynamicEntityConfigurationCollectionTransfer;

    public function getDynamicEntityCollection(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityCollectionTransfer;

    public function createDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer;

    public function updateDynamicEntityCollection(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer;

    public function deleteDynamicEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer;
}
