<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Attribute;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\EavBundle\Form\AttributeFormInterface;
use EveryWorkflow\EavBundle\Repository\AttributeRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    #[EwRoute(
        path: "eav/attribute/{code}",
        name: 'eav.attribute.view',
        methods: 'GET',
        permissions: 'eav.attribute.view',
        swagger: [
            'parameters' => [
                [
                    'name' => 'code',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ]
        ]
    )]
    public function __invoke(string $code = 'create'): JsonResponse
    {
        $data = [
            'data_form' => $this->attributeForm->toArray(),
        ];

        if ('create' !== $code) {
            try {
                $entity = $this->attributeRepository->findOne(['code' => $code]);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        return new JsonResponse($data);
    }
}
