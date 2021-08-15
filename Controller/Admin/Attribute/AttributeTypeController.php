<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Attribute;

use EveryWorkflow\EavBundle\Factory\AttributeFieldFactoryInterface;
use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AttributeTypeController extends AbstractController
{
    protected AttributeRepositoryInterface $attributeRepository;
    protected AttributeFieldFactoryInterface $attributeFieldFactory;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeFieldFactoryInterface $attributeFieldFactory
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeFieldFactory = $attributeFieldFactory;
    }

    /**
     * @Route(
     *     path="admin_api/eav/attribute_type",
     *     name="admin.eav.attribute_type",
     *     priority=10,
     *     methods="GET"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $fields = [];
        $attributes = $this->attributeRepository->find(['entity_code' => 'user']);
        foreach ($attributes as $attribute) {
            $fields[] = $this->attributeFieldFactory->createFromAttribute($attribute)->toArray();
        }

        return (new JsonResponse())->setData($fields);
    }
}
