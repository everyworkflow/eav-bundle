<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Tests\Unit\Entity;

use EveryWorkflow\CoreBundle\Helper\CoreHelper;
use EveryWorkflow\CoreBundle\Model\SystemDateTime;
use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactory;
use EveryWorkflow\DataFormBundle\Factory\FormFieldFactory;
use EveryWorkflow\EavBundle\Factory\AttributeFieldFactory;
use EveryWorkflow\EavBundle\Form\EntityAttributeForm;
use EveryWorkflow\EavBundle\Repository\AttributeRepository;
use EveryWorkflow\EavBundle\Repository\BaseEntityRepository;
use EveryWorkflow\EavBundle\Tests\Unit\BaseEavTestCase;
use EveryWorkflow\MongoBundle\Factory\DocumentFactory;

class EntityMustBeUniqueTest extends BaseEavTestCase
{
    public function testMultipleEntityInstancesMustBeUnique(): void
    {
        /** @var CoreHelper $coreHelper */
        $coreHelper = $this->getMockBuilder(CoreHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $documentFactory = new DocumentFactory($this->getDataObjectFactory());
        $attributeRepository = new AttributeRepository(
            $this->getMongoConnection(),
            $documentFactory,
            $coreHelper,
            new SystemDateTime($this->getCoreConfigProvider()),
            $this->getValidatorFactory()
        );
        /** @var AttributeFieldFactory $attributeFieldFactory */
        $attributeFieldFactory = $this->getMockBuilder(AttributeFieldFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        /** @var FormFieldFactory $formFieldFactory */
        $formFieldFactory = $this->getMockBuilder(FormFieldFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fieldOptionFactory = new FieldOptionFactory($this->getDataObjectFactory());
        $entityAttributeForm = new EntityAttributeForm(
            $this->getDataObjectFactory()->create(),
            $formFieldFactory,
            $attributeFieldFactory,
            $fieldOptionFactory,
        );

        /* Setting up test entity 1 object */
        $test1DocRepository = new BaseEntityRepository(
            $this->getMongoConnection(),
            $documentFactory,
            $coreHelper,
            $this->getValidatorFactory(),
            new SystemDateTime($this->getCoreConfigProvider()),
            $attributeRepository,
            $entityAttributeForm,
        );
        $test1DocRepository->setCollectionName('test_1_entity');

        /* Setting up another test entity 2 object */
        $test2DocRepository = new BaseEntityRepository(
            $this->getMongoConnection(),
            $documentFactory,
            $coreHelper,
            $this->getValidatorFactory(),
            new SystemDateTime($this->getCoreConfigProvider()),
            $attributeRepository,
            $entityAttributeForm,
        );
        $test2DocRepository->setCollectionName('test_2_entity');

        /* Testing if document repository of multiple entity objects has clean dependencies */
        self::assertEquals(
            'test_1_entity',
            $test1DocRepository->getCollectionName(),
            'Entity 1 collection name must be valid and unique',
        );
        self::assertEquals(
            'test_2_entity',
            $test2DocRepository->getCollectionName(),
            'Entity 2 collection name must be valid and unique',
        );
    }
}
