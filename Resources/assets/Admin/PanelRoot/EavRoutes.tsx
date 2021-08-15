/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import {lazy} from "react";

const EntityListPage = lazy(() => import("@EveryWorkflow/EavBundle/Admin/Page/System/Entity/EntityListPage"));
const EntityFormPage = lazy(() => import("@EveryWorkflow/EavBundle/Admin/Page/System/Entity/EntityFormPage"));
const AttributeListPage = lazy(() => import("@EveryWorkflow/EavBundle/Admin/Page/System/Attribute/AttributeListPage"));
const AttributeFormPage = lazy(() => import("@EveryWorkflow/EavBundle/Admin/Page/System/Attribute/AttributeFormPage"));

export const EavRoutes = [
    {
        path: '/system/entity',
        exact: true,
        component: EntityListPage
    },
    {
        path: '/system/entity/create',
        exact: true,
        component: EntityFormPage
    },
    {
        path: '/system/entity/:uuid/edit',
        exact: true,
        component: EntityFormPage
    },
    {
        path: '/system/attribute',
        exact: true,
        component: AttributeListPage
    },
    {
        path: '/system/attribute/create',
        exact: true,
        component: AttributeFormPage
    },
    {
        path: '/system/attribute/:uuid/edit',
        exact: true,
        component: AttributeFormPage
    }
];
