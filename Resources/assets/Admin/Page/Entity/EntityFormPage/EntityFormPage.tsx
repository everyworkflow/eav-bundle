/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import DataFormPageComponent from '@EveryWorkflow/DataFormBundle/Component/DataFormPageComponent';

const EntityFormPage = () => {
    return (
        <DataFormPageComponent
            title="Entity"
            getPath="/eav/entity/{code}"
            savePath="/eav/entity/{code}"
            primaryKey="code"
            primaryKeyLabel="Code"
        />
    );
};

export default EntityFormPage;
