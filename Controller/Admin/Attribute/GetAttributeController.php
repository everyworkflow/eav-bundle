<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Attribute;

use EveryWorkflow\EavBundle\Form\AttributeFormInterface;
use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetAttributeController extends AbstractController
{
    protected AttributeRepositoryInterface $attributeRepository;

    protected AttributeFormInterface $attributeForm;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeFormInterface $attributeForm
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->attributeForm = $attributeForm;
    }

    /**
     * @Route(
     *     path="admin_api/eav/attribute/{uuid}",
     *     defaults={"uuid"="create"},
     *     name="admin.eav.attribute.view",
     *     methods="GET"
     * )
     *
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $data = [
            'data_form' => $this->attributeForm->toArray(),
        ];

        if ('create' !== $uuid) {
            try {
                $entity = $this->attributeRepository->findById($uuid);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        return (new JsonResponse())->setData($data);
    }
}
