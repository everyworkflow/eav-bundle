<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Tests\Unit\Attribute;

use EveryWorkflow\CoreBundle\Annotation\EWFAnnotationReader;
use EveryWorkflow\CoreBundle\Helper\CoreHelper;
use EveryWorkflow\CoreBundle\Helper\CoreHelperInterface;
use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use EveryWorkflow\DataFormBundle\Factory\FieldOptionFactory;
use EveryWorkflow\DataGridBundle\Factory\ActionFactory;
use EveryWorkflow\DataGridBundle\Factory\DataGridFactory;
use EveryWorkflow\EavBundle\Attribute\BaseAttributeInterface;
use EveryWorkflow\EavBundle\Document\AttributeDocument;
use EveryWorkflow\EavBundle\Document\EntityDocument;
use EveryWorkflow\EavBundle\Factory\AttributeFactory;
use EveryWorkflow\EavBundle\Factory\AttributeFactoryInterface;
use EveryWorkflow\EavBundle\Form\AttributeForm;
use EveryWorkflow\EavBundle\GridConfig\AttributeGridConfig;
use EveryWorkflow\EavBundle\Model\EavConfigProvider;
use EveryWorkflow\EavBundle\Model\EavConfigProviderInterface;
use EveryWorkflow\EavBundle\Repository\AttributeRepository;
use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use EveryWorkflow\EavBundle\Repository\EntityRepository;
use EveryWorkflow\EavBundle\Tests\Unit\BaseEavTestCase;
use EveryWorkflow\MongoBundle\Model\MongoConnectionInterface;

class AttributeCrudTest extends BaseEavTestCase
{
    protected EavConfigProviderInterface $eavConfigProvider;
    protected AttributeFactoryInterface $attributeFactory;
    protected AttributeRepositoryInterface $attributeRepository;
    protected MongoConnectionInterface $mongoConnection;

    protected array $testAttributeData = [];

    protected function getNewCoreHelper(string $documentClass): CoreHelperInterface
    {
        /* TODO: Get original EWFAnnotationReader object to also include validateData() */
        $annotationReader = $this->getMockBuilder(EWFAnnotationReader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $annotationReader->method('getDocumentClass')->willReturn($documentClass);
        $annotationReader->method('validateData')->willReturn(true);
        $coreHelper = $this->getMockBuilder(CoreHelper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $coreHelper->method('getEWFAnnotationReaderInterface')->willReturn($annotationReader);

        return $coreHelper;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $container = self::getContainer();
        $this->mongoConnection = $this->getMongoConnection();
        $this->eavConfigProvider = new EavConfigProvider($container->getParameter('eav'));
        $this->attributeFactory = new AttributeFactory($this->getDataObjectFactory(), $container, $this->eavConfigProvider);
        $this->attributeRepository = new AttributeRepository(
            $this->mongoConnection,
            $this->attributeFactory,
            $this->getNewCoreHelper(AttributeDocument::class)
        );
        for ($i = 1; $i < 50; ++$i) {
            $this->testAttributeData[] = $this->attributeFactory->createAttribute([
                'code' => 'attr_'.$i,
                'name' => 'Test attribute '.$i,
                'entity_code' => 'test_entity_crud',
                'type' => 'text_attribute',
            ]);
        }
        foreach ($this->testAttributeData as $item) {
            $this->attributeRepository->save($item);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->attributeRepository->getCollection()->drop();
    }

    public function testListPageWithPagination(): void
    {
//         self::assertCount(
//             $this->attributeRepository->getCollection()->countDocuments(),
//             $this->testAttributeData,
//             'Mongo document count must be same.'
//         );

        $container = self::getContainer();
        $dataObjectFactory = $this->getDataObjectFactory();
        $formFieldFactory = $this->getFormFieldFactory($container);
        $fieldOptionFactory = new FieldOptionFactory($dataObjectFactory);
        $actionFactory = new ActionFactory($dataObjectFactory);
        $dataGridConfig = new AttributeGridConfig($dataObjectFactory->create(), $actionFactory);
        $formFactory = $this->getFormFactory($container);
        $dataGridFactory = new DataGridFactory($formFactory, $dataObjectFactory, $actionFactory);

        $parameter = $dataGridFactory->createParameter([
            'sort' => [
                '_id' => -1,
            ],
            'limit' => 20,
        ], [
            'entity_code' => 'test_entity_crud',
        ]);

        $entityRepository = new EntityRepository(
            $this->mongoConnection,
            $this->attributeFactory,
            $this->getNewCoreHelper(EntityDocument::class)
        );
        $form = new AttributeForm(
            $dataObjectFactory->create(),
            $formFieldFactory,
            $entityRepository,
            $this->getEavConfigProvider($container),
            $fieldOptionFactory,
        );

        $dataGrid = $dataGridFactory->create($this->attributeRepository, $dataGridConfig, $parameter, $form);
        $gridData = $dataGrid->toArray();

        self::assertIsBool(
            count($this->testAttributeData) <= count($gridData['data_collection']['results']),
            'Data grid result count must be same.'
        );

        self::assertArrayHasKey('data_collection', $gridData, 'Data must contain >> data_collection << array key.');
        self::assertArrayHasKey(
            'meta',
            $gridData['data_collection'],
            'Data must contain >> data_collection[meta] << array key.'
        );
        self::assertArrayHasKey(
            'results',
            $gridData['data_collection'],
            'Data must contain >> data_collection[results] << array key.'
        );
        self::assertCount(
            count($this->testAttributeData) < 20 ? count($this->testAttributeData) : 20,
            $gridData['data_collection']['results'],
            'Count of data_collection results must be same.'
        );

        $testIndex = 2;
        /** @var DataObjectInterface $testItem */
        $testCode = $gridData['data_collection']['results'][$testIndex]['code'];
        $exampleAttrs = array_filter(
            $this->testAttributeData,
            static fn (BaseAttributeInterface $item) => $item->getCode() === $testCode
        );
        /** @var BaseAttributeInterface $exampleAttr */
        $exampleAttr = $exampleAttrs[array_key_first($exampleAttrs)];
        self::assertEquals($exampleAttr->getCode(), $gridData['data_collection']['results'][$testIndex]['code']);
        self::assertEquals($exampleAttr->getName(), $gridData['data_collection']['results'][$testIndex]['name']);
        self::assertEquals(
            $exampleAttr->getEntityCode(),
            $gridData['data_collection']['results'][$testIndex]['entity_code']
        );
        self::assertEquals($exampleAttr->getType(), $gridData['data_collection']['results'][$testIndex]['type']);

        self::assertArrayHasKey(
            'data_grid_config',
            $gridData,
            'Data must contain >> data_grid_config << array key.'
        );

        self::assertArrayHasKey(
            'active_columns',
            $gridData['data_grid_config'],
            'Data must contain >> data_grid_config[active_columns] << array key.'
        );
        self::assertCount(
            count($dataGridConfig->getActiveColumns()),
            $gridData['data_grid_config']['active_columns'],
            'Count of data_grid_config active_columns must be same.'
        );
        self::assertArrayHasKey(
            'sortable_columns',
            $gridData['data_grid_config'],
            'Data must contain >> data_grid_config[sortable_columns] << array key.'
        );
        self::assertCount(
            count($dataGridConfig->getSortableColumns()),
            $gridData['data_grid_config']['sortable_columns'],
            'Count of data_grid_config sortable_columns must be same.'
        );
        self::assertArrayHasKey(
            'filterable_columns',
            $gridData['data_grid_config'],
            'Data must contain >> data_grid_config[filterable_columns] << array key.'
        );
        self::assertCount(
            count($dataGridConfig->getFilterableColumns()),
            $gridData['data_grid_config']['filterable_columns'],
            'Count of data_grid_config filterable_columns must be same.'
        );

        self::assertArrayHasKey('data_form', $gridData, 'Data must contain >> data_form << array key.');
        self::assertCount(
            count($form->getFields()),
            $gridData['data_form']['fields'],
            'Count of form field and grid data form field must be same.'
        );
    }
}