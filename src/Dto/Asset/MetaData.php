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

namespace Pimcore\Bundle\StudioApiBundle\Dto\Asset;

readonly class MetaData
{
    public function __construct(
        private string $name,
        private ?string $language,
        private mixed $data,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}