<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Entity;

use EveryWorkflow\EavBundle\Document\EntityDocumentInterface;
use EveryWorkflow\EavBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SaveEntityController extends AbstractController
{
    protected EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @Route(
     *     path="admin_api/eav/entity/{uuid}",
     *     defaults={"uuid"="create"},
     *     name="admin.eav.entity.save",
     *     methods="POST"
     * )
     *
     * @throws \ReflectionException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $submitData = json_decode($request->getContent(), true);

        /* @var EntityDocumentInterface $entity */
        if ('create' === $uuid) {
            if (isset($submitData['code'])) {
                try {
                    $entityByCode = $this->entityRepository->findOne([
                        'code' => $submitData['code'],
                    ]);
                    if ($entityByCode) {
                        return (new JsonResponse())
                            ->setData(['message' => "Entity with code '${submitData['code']}' already exists."])
                            ->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
                    }
                } catch (\Exception $e) {
                    // ignore if entity code doesn't exist
                }
            }
            $entity = $this->entityRepository->getNewDocument($submitData);
        } else {
            $entity = $this->entityRepository->findById($uuid);
            foreach ($submitData as $key => $val) {
                $entity->setData($key, $val);
            }
        }
        $result = $this->entityRepository->save($entity);

        if ($result->getUpsertedId()) {
            $entity->setData('_id', $result->getUpsertedId());
        }

        return (new JsonResponse())->setData([
            'message' => 'Successfully saved changes.',
            'item' => $entity->toArray(),
        ]);
    }
}
