/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import SelectFieldInterface from '@EveryWorkflow/DataFormBundle/Model/Field/SelectFieldInterface';
import SelectFieldColumn from '@EveryWorkflow/DataGridBundle/Column/SelectFieldColumn';
import { NavLink } from 'react-router-dom';

interface EntityCodeColumnProps {
    fieldData?: SelectFieldInterface;
    fieldValue?: any;
}

const EntityCodeColumn = ({ fieldData, fieldValue }: EntityCodeColumnProps) => {
    return (
        <NavLink to={'/system/entity/' + fieldValue + '/edit'}>
            <SelectFieldColumn fieldData={fieldData} fieldValue={fieldValue} />
        </NavLink>
    );
}

export default EntityCodeColumn;
