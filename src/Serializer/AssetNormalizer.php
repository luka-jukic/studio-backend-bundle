<?php

/**
 * Pimcore
 *
 * This source file is available under following license:
 * - Pimcore Commercial License (PCL)
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     PCL
 */

namespace Pimcore\Bundle\StudioApiBundle\Serializer;

use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AssetNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'ASSET_NORMALIZER_ALREADY_CALLED';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function normalize($object, $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;

        $data = $this->normalizer->normalize($object, $format, $context);

        if (isset($data['data']) && $data['data']) {
            $data['data'] = base64_encode($data['data']);
        }

        if ($object instanceof Asset\Image) {
            $data['thumbnail'] = $object->getThumbnail()->getPath(['frontend' => true]);
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Asset;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Asset::class => false,
        ];
    }
}
