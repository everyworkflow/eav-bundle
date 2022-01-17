<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\CoreBundle\Factory\ValidatorFactoryInterface;
use EveryWorkflow\CoreBundle\Helper\CoreHelperInterface;
use EveryWorkflow\CoreBundle\Model\SystemDateTimeInterface;
use EveryWorkflow\CoreBundle\Support\ArrayableInterface;
use EveryWorkflow\DataFormBundle\Model\FormInterface;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Entity\BaseEntityInterface;
use EveryWorkflow\EavBundle\Form\EntityAttributeFormInterface;
use EveryWorkflow\EavBundle\Support\Attribute\EntityRepositoryAttribute;
use EveryWorkflow\MongoBundle\Factory\DocumentFactoryInterface;
use EveryWorkflow\MongoBundle\Model\MongoConnectionInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BaseEntityRepository extends BaseDocumentRepository implements BaseEntityRepositoryInterface
{
    /**
     * Entity code must be defined.
     */
    protected string $entityCode = '';

    protected array $entityAttributes = [];

    protected AttributeRepositoryInterface $attributeRepository;
    protected EntityAttributeFormInterface $entityAttributeForm;

    public function __construct(
        MongoConnectionInterface $mongoConnection,
        DocumentFactoryInterface $documentFactory,
        CoreHelperInterface $coreHelper,
        SystemDateTimeInterface $systemDateTime,
        ValidatorFactoryInterface $validatorFactory,
        EventDispatcherInterface $eventDispatcher,
        AttributeRepositoryInterface $attributeRepository,
        EntityAttributeFormInterface $entityAttributeForm,
        $entityAttributes = []
    ) {
        parent::__construct(
            $mongoConnection,
            $documentFactory,
            $coreHelper,
            $systemDateTime,
            $validatorFactory,
            $eventDispatcher
        );
        $this->attributeRepository = $attributeRepository;
        $this->entityAttributeForm = $entityAttributeForm;
        $this->entityAttributes = $entityAttributes;
    }

    public function getRepositoryAttribute(): ?EntityRepositoryAttribute
    {
        if (!$this->repositoryAttribute) {
            $reflectionClass = new ReflectionClass(get_class($this));
            $attributes = $reflectionClass->getAttributes(EntityRepositoryAttribute::class);
            foreach ($attributes as $attribute) {
                $this->repositoryAttribute = $attribute->newInstance();
            }
        }

        return $this->repositoryAttribute;
    }

    public function getEntityCode(): string
    {
        if (empty($this->entityCode) && $this->getRepositoryAttribute()) {
            $this->entityCode = $this->getRepositoryAttribute()->getEntityCode();
        }

        return $this->entityCode;
    }

    public function setEntityCode(string $entityCode): self
    {
        $this->entityCode = $entityCode;

        return $this;
    }

    public function create(array $data = []): BaseEntityInterface
    {
        return $this->documentFactory->create($this->getDocumentClass(), $data);
    }

    /**
     * @throws PrimaryKeyMissingException
     * @throws \Exception
     */
    public function saveOne(
        ArrayableInterface $document,
        array $otherFilter = [],
        array $otherOptions = []
    ): BaseEntityInterface {
        return parent::saveOne($document, $otherFilter, $otherOptions);
    }

    /**
     * @return BaseAttributeInterface[]
     */
    public function getAttributes(): array
    {
        if (!$this->entityAttributes) {
            $attributeInfo = $this->coreHelper->getEWFCacheInterface()
                ->getItem('base_entity_attribute' . $this->getEntityCode());
            if (!$attributeInfo->isHit() || true) {
                // $this->setSystemAttribute();
                $this->entityAttributes = $this->attributeRepository->find(['entity_code' => $this->getEntityCode()]);
                $attributeInfo->set($this->entityAttributes);
                $this->coreHelper->getEWFCacheInterface()->save($attributeInfo);
                $this->entityAttributes = $attributeInfo->get();
            }
        }

        return $this->entityAttributes;
    }

    public function getForm(): FormInterface
    {
        return $this->entityAttributeForm->loadAttributeFields($this);
    }
}
