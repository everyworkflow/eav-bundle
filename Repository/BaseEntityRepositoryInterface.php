<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\CoreBundle\Support\ArrayableInterface;
use EveryWorkflow\DataFormBundle\Model\FormInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Entity\BaseEntityInterface;
use EveryWorkflow\EavBundle\Factory\EntityFactoryInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepositoryInterface;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

interface BaseEntityRepositoryInterface extends BaseDocumentRepositoryInterface
{
    public function getEntityFactory(): EntityFactoryInterface;

    public function getNewEntity(array $data = []): BaseEntityInterface;

    public function getEntityCode(): string;

    public function setEntityCode(string $entityCode): self;

    /**
     * @throws \Exception
     */
    public function findById(string $uuid): BaseEntityInterface;

    /**
     * @throws \Exception
     */
    public function saveEntity(
        BaseEntityInterface $entity,
        array $otherFilter = [],
        array $otherOptions = []
    ): UpdateResult | InsertOneResult;

    /**
     * @return BaseAttributeInterface[]
     *
     * @throws \ReflectionException
     */
    public function getAttributes(): array;

    public function getForm(): FormInterface;
}
