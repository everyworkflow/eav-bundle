<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Entity;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\EavBundle\Repository\EntityRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SaveEntityController extends AbstractController
{
    protected EntityRepositoryInterface $entityRepository;

    public function __construct(EntityRepositoryInterface $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    #[EwRoute(
        path: "eav/entity/{uuid}",
        name: 'eav.entity.save',
        methods: 'POST',
        permissions: 'eav.entity.save',
        swagger: [
            'parameters' => [
                [
                    'name' => 'uuid',
                    'in' => 'path',
                    'default' => 'create',
                ]
            ],
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'properties' => [
                                'code' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'class' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'name' => [
                                    'type' => 'string',
                                    'required' => true,
                                ],
                                'status' => [
                                    'type' => 'string',
                                    'required' => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    )]
    public function __invoke(Request $request, string $uuid = 'create'): JsonResponse
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
                        return new JsonResponse([
                            'message' => "Entity with code '${submitData['code']}' already exists."
                        ], JsonResponse::HTTP_BAD_REQUEST);
                    }
                } catch (\Exception $e) {
                    // ignore if entity code doesn't exist
                }
            }
            $item = $this->entityRepository->create($submitData);
        } else {
            $item = $this->entityRepository->findById($uuid);
            foreach ($submitData as $key => $val) {
                $item->setData($key, $val);
            }
        }

        $item = $this->entityRepository->saveOne($item);


        return new JsonResponse([
            'message' => 'Successfully saved changes.',
            'item' => $item->toArray(),
        ]);
    }
}
