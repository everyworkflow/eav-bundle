/*
 * @copyright EveryWorkflow. All rights reserved.
 */

import React from 'react';
import Form from 'antd/lib/form';
import Button from 'antd/lib/button';
import DataFormComponent from '@EveryWorkflow/DataFormBundle/Component/DataFormComponent';

const formData: any = {
    fields: [
        {
            label: 'Code',
            name: 'code',
            field_type: 'text_field',
            is_required: true,
        },
        {
            label: 'Label',
            name: 'label',
            field_type: 'text_field',
            is_required: true,
        },
        {
            label: 'Option type',
            name: 'option_type',
            field_type: 'select_field',
            options: [
                {
                    key: '',
                    value: 'No swatch',
                },
                {
                    key: 'text_swatch',
                    value: 'Text swatch',
                },
                {
                    key: 'color_swatch',
                    value: 'Color swatch',
                },
                {
                    key: 'image_swatch',
                    value: 'Image swatch',
                },
            ],
            is_actionable: true,
            field_actions: {
                '': [
                    {
                        action_type: 'hide_field',
                        field_names: ['text_swatch', 'color_swatch', 'image_swatch']
                    }
                ],
                'text_swatch': [
                    {
                        action_type: 'show_field',
                        field_names: ['text_swatch']
                    },
                    {
                        action_type: 'hide_field',
                        field_names: ['color_swatch', 'image_swatch']
                    },
                ],
                'color_swatch': [
                    {
                        action_type: 'show_field',
                        field_names: ['color_swatch']
                    },
                    {
                        action_type: 'hide_field',
                        field_names: ['text_swatch', 'image_swatch']
                    },
                ],
                'image_swatch': [
                    {
                        action_type: 'show_field',
                        field_names: ['image_swatch']
                    },
                    {
                        action_type: 'hide_field',
                        field_names: ['text_swatch', 'color_swatch']
                    },
                ],
            }
        },
        {
            label: 'Text swatch',
            name: 'text_swatch',
            field_type: 'text_field',
        },
        {
            label: 'Color swatch',
            name: 'color_swatch',
            field_type: 'color_picker_field',
        },
        {
            label: 'Image swatch',
            name: 'image_swatch',
            field_type: 'media_image_selector_field',
        },
        {
            label: 'Sort order',
            name: 'sort_order',
            field_type: 'text_field',
            input_type: 'number',
            is_required: true,
        }
    ],
};

interface AttributeValueFormProps {
    optionIndex?: number;
    initialValues?: any;
    onSubmit: (data: any, optionIndex?: number) => void;
}

const AttributeValueForm = ({ optionIndex, initialValues, onSubmit }: AttributeValueFormProps) => {
    const [form] = Form.useForm();

    const onFormSubmit = (data: any) => {
        onSubmit(data, optionIndex);
    }

    return (
        <>
            <DataFormComponent
                formId={'start-session-form'}
                form={form}
                formData={formData}
                initialValues={initialValues}
                onSubmit={onFormSubmit}
            />
            <Button
                type="primary"
                onClick={() => {
                    form?.submit();
                }}>
                Save
            </Button>
        </>
    );
}

export default AttributeValueForm;
