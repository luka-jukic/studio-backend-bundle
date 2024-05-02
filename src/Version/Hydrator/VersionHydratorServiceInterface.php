<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\StudioBackendBundle\Version\Hydrator;

use Pimcore\Bundle\StudioBackendBundle\Version\Request\VersionParameters;
use Pimcore\Bundle\StudioBackendBundle\Version\Result\ListingResult;
use Pimcore\Bundle\StudioBackendBundle\Version\Schema\AssetVersion;
use Pimcore\Bundle\StudioBackendBundle\Version\Schema\DataObjectVersion;
use Pimcore\Bundle\StudioBackendBundle\Version\Schema\DocumentVersion;
use Pimcore\Model\UserInterface;

/**
 * @internal
 */
interface VersionHydratorServiceInterface
{
    public function getHydratedVersions(
        VersionParameters $parameters,
        UserInterface $user
    ): ListingResult;

    public function getHydratedVersionData(
        int $id,
        UserInterface $user
    ): AssetVersion|DataObjectVersion|DocumentVersion;
}
