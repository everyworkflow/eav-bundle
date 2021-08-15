<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Entity;

use EveryWorkflow\EavBundle\Form\EntityFormInterface;
use EveryWorkflow\EavBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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

    /**
     * @Route(
     *     path="admin_api/eav/entity/{uuid}",
     *     defaults={"uuid"="create"},
     *     name="admin.eav.entity.view",
     *     methods="GET"
     * )
     *
     * @throws \ReflectionException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $data = [
            'data_form' => $this->entityForm->toArray(),
        ];

        if ('create' !== $uuid) {
            try {
                $entity = $this->entityRepository->findById($uuid);
                $data['item'] = $entity->toArray();
            } catch (\Exception $e) {
                // ignore if _id doesn't exist
            }
        }

        return (new JsonResponse())->setData($data);
    }
}
