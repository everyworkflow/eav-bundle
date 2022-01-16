/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import DataFormPageComponent from '@EveryWorkflow/DataFormBundle/Component/DataFormPageComponent';

const AttributeFormPage = () => {
    return (
        <DataFormPageComponent
            title="Attribute"
            getPath="/eav/attribute/{code}"
            savePath="/eav/attribute/{code}"
            primaryKey="code"
            primaryKeyLabel="Code"
        />
    );
};

export default AttributeFormPage;
