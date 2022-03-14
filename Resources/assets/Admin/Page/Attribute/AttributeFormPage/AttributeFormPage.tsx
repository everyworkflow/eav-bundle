/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { lazy } from 'react';
import DataFormPageComponent from '@EveryWorkflow/DataFormBundle/Component/DataFormPageComponent';

const AttributeFormPage = () => {
    return (
        <DataFormPageComponent
            title="Attribute"
            getPath="/eav/attribute/{code}"
            savePath="/eav/attribute/{code}"
            primaryKey="code"
            primaryKeyLabel="Code"
            formSectionMaps={{
                attribute_select_options: lazy(
                    () => import('@EveryWorkflow/EavBundle/Admin/Page/Attribute/AttributeFormPage/OptionsFormSection')
                )
            }}
        />
    );
};

export default AttributeFormPage;
