<?php

declare(strict_types=1);

namespace App\Normalizer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class EntityNormalizer extends ObjectNormalizer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ?ClassMetadataFactoryInterface $classMetadataFactory = null,
        ?NameConverterInterface $nameConverter = null,
        ?PropertyAccessorInterface $propertyAccessor = null,
        ?PropertyTypeExtractorInterface $propertyTypeExtractor = null
    ) {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization(mixed $data, string $type, $format = null): bool
    {
        return (0 === strpos($type, 'App\\Entity\\') && is_numeric($data)) || (Collection::class === $type && is_iterable($data));
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize(mixed $data, string $class, $format = null, array $context = []): mixed
    {
        if (is_iterable($data)) {
            $datas = new ArrayCollection();
            $entites = [...$data];

            foreach (array_unique($entites) as $entity) {
                if (!is_numeric($data)) {
                    continue;
                }

                $datas->add($this->entityManager->find($class, $entity));
            }

            return $datas;
        }

        return $this->entityManager->find($class, $data);
    }
}
