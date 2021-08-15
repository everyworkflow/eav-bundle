<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Document;

use EveryWorkflow\CoreBundle\Annotation\EWFDataTypes;
use EveryWorkflow\MongoBundle\Document\BaseDocument;
use EveryWorkflow\MongoBundle\Document\HelperTrait\CreatedUpdatedHelperTrait;
use EveryWorkflow\MongoBundle\Document\HelperTrait\StatusHelperTrait;

class EntityDocument extends BaseDocument implements EntityDocumentInterface
{
    use CreatedUpdatedHelperTrait;
    use StatusHelperTrait;

    /**
     * @return $this
     * @EWFDataTypes(type="string", mongofield=self::KEY_CODE, required=TRUE, minLength=3, maxLength=50)
     */
    public function setCode(string $code): self
    {
        $this->dataObject->setData(self::KEY_CODE, $code);

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->dataObject->getData(self::KEY_CODE);
    }

    /**
     * @return $this
     * @EWFDataTypes(type="string", mongofield=self::KEY_CLASS, required=TRUE)
     */
    public function setClass(string $class): self
    {
        $this->dataObject->setData(self::KEY_CLASS, $class);

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->dataObject->getData(self::KEY_CLASS);
    }

    /**
     * @return $this
     * @EWFDataTypes(type="string", mongofield=self::KEY_NAME, required=TRUE)
     */
    public function setName(string $name): self
    {
        $this->dataObject->setData(self::KEY_NAME, $name);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->dataObject->getData(self::KEY_NAME);
    }
}
