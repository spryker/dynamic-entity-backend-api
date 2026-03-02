<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface DynamicEntityReaderInterface
{
    public function getDynamicEntityCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    public function getDynamicEntity(
        string $id,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;

    /**
     * @return array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    public function getDynamicEntityConfigurations(): array;
}
