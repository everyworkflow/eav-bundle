<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Repository;

use EveryWorkflow\EavBundle\Attribute\BaseAttribute;
use EveryWorkflow\MongoBundle\Repository\BaseDocumentRepository;
use EveryWorkflow\MongoBundle\Support\Attribute\RepositoryAttribute;

#[RepositoryAttribute(
    documentClass: BaseAttribute::class,
    collectionName: 'attribute_collection',
    primaryKey: ['entity_code', 'code']
)]
class AttributeRepository extends BaseDocumentRepository implements AttributeRepositoryInterface
{
    // Something
}
