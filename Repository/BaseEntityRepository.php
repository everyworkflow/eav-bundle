<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use Carbon\Carbon;
use EveryWorkflow\CoreBundle\Helper\CoreHelperInterface;
use EveryWorkflow\DataFormBundle\Model\FormInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Entity\BaseEntityInterface;
use EveryWorkflow\EavBundle\Factory\EntityFactoryInterface;
use EveryWorkflow\EavBundle\Form\EntityAttributeFormInterface;
use EveryWorkflow\MongoBundle\Factory\DocumentFactoryInterface;
use EveryWorkflow\MongoBundle\Model\MongoConnectionInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class BaseEntityRepository extends BaseDocumentRepository implements BaseEntityRepositoryInterface
{
    /**
     * Collection name must be defined.
     */
    protected string $collectionName = '';
    /**
     * index name must be defined.
     */
    protected array $indexNames = ['_id'];
    /**
     * Entity code must be defined.
     */
    protected string $entityCode = '';
    /**
     * Entity event prefix must be defined.
     */
    protected string $entityEventPrefix = '';

    protected array $entityAttributes = [];

    protected EntityFactoryInterface $entityFactory;
    protected AttributeRepositoryInterface $attributeRepository;
    protected EntityAttributeFormInterface $entityAttributeForm;

    public function __construct(
        MongoConnectionInterface $mongoConnection,
        DocumentFactoryInterface $documentFactory,
        CoreHelperInterface $coreHelper,
        EntityFactoryInterface $entityFactory,
        AttributeRepositoryInterface $attributeRepository,
        EntityAttributeFormInterface $entityAttributeForm,
        $entityAttributes = []
    ) {
        parent::__construct($mongoConnection, $documentFactory, $coreHelper);
        $this->entityFactory = $entityFactory;
        $this->attributeRepository = $attributeRepository;
        $this->entityAttributeForm = $entityAttributeForm;
        $this->entityAttributes = $entityAttributes;
    }

    /**
     * @return mixed
     *
     * @throws \ReflectionException
     *
     * @todo Need to manage in more proper way
     */
    public function getEntityClass(): string
    {
        return $this->coreHelper->getEWFAnnotationReaderInterface()->getDocumentClass($this);
    }

    public function getEntityFactory(): EntityFactoryInterface
    {
        return $this->entityFactory;
    }

    /**
     * @throws \ReflectionException
     */
    public function getNewEntity(array $data = []): BaseEntityInterface
    {
        return $this->getEntityFactory()->create($this->getEntityClass(), $data);
    }

    public function getEntityCode(): string
    {
        return $this->entityCode;
    }

    /**
     * @return $this
     */
    public function setEntityCode(string $entityCode): self
    {
        $this->entityCode = $entityCode;

        return $this;
    }

    public function findById(string $uuid): BaseEntityInterface
    {
        /* @psalm-suppress UndefinedClass */
        return $this->getNewEntity($this->findOne([
            '_id' => new \MongoDB\BSON\ObjectId($uuid),
        ])->toArray());
    }

    /**
     * @throws \Exception
     */
    public function saveEntity(
        BaseEntityInterface $entity,
        array $otherFilter = [],
        array $otherOptions = []
    ): UpdateResult | InsertOneResult {
        if (!$entity->getCreatedAt()) {
            $entity->setCreatedAt(Carbon::now());
        }
        if (!$entity->getUpdatedAt()) {
            $entity->setUpdatedAt(Carbon::now());
        }

        return $this->save($entity, $otherFilter, $otherOptions);
    }

    public function setSystemAttribute(): void
    {
        $systemAttribute = $this->coreHelper->getEWFAnnotationReaderInterface()->getEntityAttribute($this);
        foreach ($systemAttribute as $item) {
            $attribute = $this->attributeRepository->getDocumentFactory()->createAttribute($item);
            if ($attribute) {
                $this->attributeRepository->save($attribute);
            }
        }
    }

    /**
     * @return BaseAttributeInterface[]
     */
    public function getAttributes(): array
    {
        $attributeInfo = $this->coreHelper->getEWFCacheInterface()
            ->getItem('base_entity_attribute' . $this->getEntityCode());
        if (!$this->entityAttributes) {
            if (!$attributeInfo->isHit() || true) {
                //                $this->setSystemAttribute();
                $this->entityAttributes = $this->attributeRepository->find(['entity_code' => $this->getEntityCode()]);
                $attributeInfo->set($this->entityAttributes);
                $this->coreHelper->getEWFCacheInterface()->save($attributeInfo);
            }
        }
        $this->entityAttributes = $attributeInfo->get();

        return $this->entityAttributes;
    }

    public function getForm(): FormInterface
    {
        return $this->entityAttributeForm->loadAttributeFields($this);
    }
}
