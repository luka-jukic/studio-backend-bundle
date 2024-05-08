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

namespace Pimcore\Bundle\StudioBackendBundle\DataIndex;

use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Asset;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Archive;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Audio;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Document;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Folder;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Image;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Text;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Unknown;
use Pimcore\Bundle\StudioBackendBundle\Asset\Schema\Type\Video;
use Pimcore\Bundle\StudioBackendBundle\DataIndex\Adapter\AssetSearchAdapterInterface;
use Pimcore\Bundle\StudioBackendBundle\DataIndex\Query\QueryInterface;

final readonly class AssetSearchService implements AssetSearchServiceInterface
{
    public function __construct(private AssetSearchAdapterInterface $assetSearchAdapter)
    {
    }

    public function searchAssets(QueryInterface $assetQuery): AssetSearchResult
    {
        return $this->assetSearchAdapter->searchAssets($assetQuery);
    }

    public function getAssetById(int $id): Asset|Archive|Audio|Document|Folder|Image|Text|Unknown|Video
    {
        return $this->assetSearchAdapter->getAssetById($id);
    }
}