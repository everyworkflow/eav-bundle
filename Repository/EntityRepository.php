<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\CoreBundle\Annotation\RepoDocument;
use EveryWorkflow\EavBundle\Document\EntityDocument;
use EveryWorkflow\EavBundle\Document\EntityDocumentInterface;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;

/**
 * @RepoDocument(doc_name=EntityDocument::class)
 */
class EntityRepository extends BaseDocumentRepository implements EntityRepositoryInterface
{
    protected string $collectionName = 'entity_collection';
    protected array $indexNames = ['code'];

    /**
     * @return array|object|null
     */
    public function deleteByCode(string $entityCode, array $otherFilter = []): object | array | null
    {
        return $this->deleteByFilter(array_merge(['code' => $entityCode], $otherFilter));
    }

    /**
     * @psalm-return \MongoDB\Driver\Cursor<array|object>
     */
    public function getSelectOptions(): \MongoDB\Driver\Cursor
    {
        return $this->getCollection()->find(['status' => EntityDocumentInterface::STATUS_ENABLE]);
    }
}
