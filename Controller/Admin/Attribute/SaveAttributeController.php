<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Attribute;

use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SaveAttributeController extends AbstractController
{
    protected AttributeRepositoryInterface $attributeRepository;

    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @Route(
     *     path="admin_api/eav/attribute/{uuid}",
     *     defaults={"uuid"="create"},
     *     name="admin.eav.attribute.save",
     *     methods="POST"
     * )
     *
     * @throws \ReflectionException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $submitData = json_decode($request->getContent(), true);
        if ('create' === $uuid) {
            $attribute = $this->attributeRepository->getNewDocument($submitData);
        } else {
            $attribute = $this->attributeRepository->findById($uuid);
            foreach ($submitData as $key => $val) {
                $attribute->setData($key, $val);
            }
        }
        $result = $this->attributeRepository->save($attribute);

        if ($result->getUpsertedId()) {
            $attribute->setData('_id', $result->getUpsertedId());
        }

        return (new JsonResponse())->setData([
            'message' => 'Successfully saved changes.',
            'item' => $attribute->toArray(),
        ]);
    }
}
