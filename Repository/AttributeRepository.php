<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\CoreBundle\Annotation\RepoDocument;
use EveryWorkflow\EavBundle\Attribute\BaseAttribute;
use EveryWorkflow\EavBundle\Document\AttributeDocument;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use MongoDB\Model\BSONDocument;

/**
 * @RepoDocument(doc_name=AttributeDocument::class)
 */
class AttributeRepository extends BaseDocumentRepository implements AttributeRepositoryInterface
{
    protected string $collectionName = 'attribute_collection';
    protected array $indexNames = ['entity_code', 'code'];

    /**
     * @param array $filter
     *
     * @return BaseAttribute[]
     */
    public function find(array $filter = [], array $options = []): array
    {
        $items = [];
        $mongoData = $this->getCollection()->find($filter, $options);
        /** @var BSONDocument $mongoItem */
        foreach ($mongoData as $mongoItem) {
            $itemData = $mongoItem->getArrayCopy();
            $items[] = $this->getDocumentFactory()->createAttribute($itemData);
        }

        return $items;
    }
}
