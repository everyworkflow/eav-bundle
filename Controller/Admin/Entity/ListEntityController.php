<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Admin\Entity;

use EveryWorkflow\DataGridBundle\Model\DataGridInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListEntityController extends AbstractController
{
    protected DataGridInterface $dataGrid;

    public function __construct(DataGridInterface $dataGrid)
    {
        $this->dataGrid = $dataGrid;
    }

    /**
     * @Route(
     *     path="admin_api/eav/entity",
     *     name="admin.eav.entity",
     *     priority=10,
     *     methods="GET"
     * )
     */
    public function __invoke(Request $request): JsonResponse
    {
        $dataGrid = $this->dataGrid->setFromRequest($request);

        return (new JsonResponse())->setData($dataGrid->toArray());
    }
}
