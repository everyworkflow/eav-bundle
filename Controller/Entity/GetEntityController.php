<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Entity;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\EavBundle\Form\EntityFormInterface;
use EveryWorkflow\EavBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetEntityController extends AbstractController
{
    protected EntityFormInterface $entityForm;
    protected EntityRepositoryInterface $entityRepository;

    public function __construct(
        EntityFormInterface $entityForm,
        EntityRepositoryInterface $entityRepository
    ) {
        $this->entityForm = $entityForm;
        $this->entityRepository = $entityRepository;
    }

    #[EwRoute(
        path: "eav/entity/{code}",
        name: 'eav.entity.view',
        methods: 'GET',
        permissions: 'eav.entity.view',
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
            'data_form' => $this->entityForm->toArray(),
        ];

        if ('create' !== $code) {
            try {
                $entity = $this->entityRepository->findOne(['code' => $code]);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        return new JsonResponse($data);
    }
}
