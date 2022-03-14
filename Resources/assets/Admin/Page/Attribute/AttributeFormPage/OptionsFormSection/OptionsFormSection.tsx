/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React, { useContext } from 'react';
import CardSectionInterface from "@EveryWorkflow/DataFormBundle/Model/Section/CardSectionInterface";
import AttributeValueOptionsComponent from "@EveryWorkflow/EavBundle/Component/AttributeValueOptionsComponent";
import FormContext from '@EveryWorkflow/DataFormBundle/Context/FormContext';
import Form from 'antd/lib/form';
import Input from 'antd/lib/input';

interface OptionsFormSectionProps {
    sectionData: CardSectionInterface;
}

const OptionsFormSection = ({ sectionData }: OptionsFormSectionProps) => {
    const { state: formState } = useContext(FormContext);

    return (
        <div id={'form-section-' + sectionData.code} className="app-container" style={{ marginBottom: 24 }}>
            <div style={{ display: 'none' }}>
                <Form.Item
                    name={'options'}
                    initialValue={formState.initial_values['options'] ?? []}>
                    <Input.TextArea />
                </Form.Item>
            </div>
            <AttributeValueOptionsComponent
                initalOptions={formState.initial_values['options'] ?? []}
                onChange={(options: Array<any>) => {
                    formState.form?.setFieldsValue({ options: [...options] });
                }} />
        </div>
    );
};

export default OptionsFormSection;
