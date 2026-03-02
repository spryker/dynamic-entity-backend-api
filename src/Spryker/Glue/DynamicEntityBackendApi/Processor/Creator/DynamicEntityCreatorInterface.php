<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Processor\Creator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;

interface DynamicEntityCreatorInterface
{
    public function createDynamicEntityCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;
}
