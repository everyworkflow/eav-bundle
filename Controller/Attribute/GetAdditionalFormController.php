<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\EavBundle\Controller\Attribute;

use EveryWorkflow\CoreBundle\Annotation\EwRoute;
use EveryWorkflow\DataFormBundle\Factory\FormFactoryInterface;
use EveryWorkflow\EavBundle\Model\EavConfigProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetAdditionalFormController extends AbstractController
{
    protected EavConfigProviderInterface $eavConfigProvider;
    protected FormFactoryInterface $formFactory;

    public function __construct(
        EavConfigProviderInterface $eavConfigProvider,
        FormFactoryInterface $formFactory
    ) {
        $this->eavConfigProvider = $eavConfigProvider;
        $this->formFactory = $formFactory;
    }

    #[EwRoute(
        path: "eav/attribute/additional-form/{type}",
        name: 'eav.attribute.additional_form',
        methods: 'GET',
        priority: 5,
        permissions: 'eav.attribute.view',
        swagger: [
            'parameters' => [
                [
                    'name' => 'type',
                    'in' => 'type',
                    'default' => 'create',
                ]
            ]
        ]
    )]
    public function __invoke(string $type): JsonResponse
    {
        $attributeFormClass = $this->eavConfigProvider->get('attribute_forms.' . $type);
        if (!$attributeFormClass) {
            throw new NotFoundHttpException('Attribute type not found.');
        }

        $form = $this->formFactory->createByClassName($attributeFormClass);
        return new JsonResponse([
            'data_form' => $form->toArray(),
        ]);
    }
}
