/*
 * @copyright EveryWorkflow. All rights reserved.
 */
import React, { useState } from 'react';
import Table from 'antd/lib/table';
import Popconfirm from 'antd/lib/popconfirm';
import Space from 'antd/lib/space';
import Button from 'antd/lib/button';
import Input from 'antd/lib/input';
import SearchOutlined from '@ant-design/icons/SearchOutlined';
import Highlighter from 'react-highlight-words';
import AlertAction, { ALERT_TYPE_SUCCESS } from '@EveryWorkflow/PanelBundle/Action/AlertAction';

interface AttributeValueOptionsTableProps {
    options: Array<any>;
    setOptions: React.Dispatch<React.SetStateAction<any[]>>;
    setSidePanel: React.Dispatch<React.SetStateAction<any>>;
    selectedOptionCodes: Array<any>;
    setSelectedOptionCodes: React.Dispatch<React.SetStateAction<any[]>>;
}

const AttributeValueOptionsTable = ({ options, setOptions, setSidePanel, selectedOptionCodes, setSelectedOptionCodes }: AttributeValueOptionsTableProps) => {
    const [searchData, setSearchData] = useState<{ searchText: string, searchedColumn: string } | undefined>();
    let searchInputNode: any = undefined;

    const handleSearch = (selectedKeys: any, confirm: any, dataIndex: string) => {
        confirm();
        setSearchData({
            searchText: selectedKeys[0],
            searchedColumn: dataIndex,
        });
    };

    const handleReset = (clearFilters: any) => {
        clearFilters();
        setSearchData(undefined);
    };

    const getColumnSearchProps = (dataIndex: string) => ({
        filterDropdown: ({ setSelectedKeys, selectedKeys, confirm, clearFilters }: any) => (
            <div style={{ padding: 8 }}>
                <Input
                    ref={node => {
                        searchInputNode = node;
                    }}
                    placeholder={`Search ${dataIndex}`}
                    value={selectedKeys[0]}
                    onChange={e => setSelectedKeys(e.target.value ? [e.target.value] : [])}
                    onPressEnter={() => handleSearch(selectedKeys, confirm, dataIndex)}
                    style={{ marginBottom: 8, display: 'block' }}
                />
                <Space>
                    <Button
                        type="primary"
                        onClick={() => handleSearch(selectedKeys, confirm, dataIndex)}
                        icon={<SearchOutlined />}
                        size="small"
                        style={{ width: 90 }}
                    >
                        Search
                    </Button>
                    <Button onClick={() => handleReset(clearFilters)} size="small" style={{ width: 90 }}>
                        Reset
                    </Button>
                    <Button
                        type="link"
                        size="small"
                        onClick={() => {
                            confirm({ closeDropdown: false });
                            setSearchData({
                                searchText: selectedKeys[0],
                                searchedColumn: dataIndex,
                            });
                        }}
                    >
                        Filter
                    </Button>
                </Space>
            </div>
        ),
        filterIcon: (filtered: string) => <SearchOutlined style={{ color: filtered ? '#1890ff' : undefined }} />,
        onFilter: (value: string, record: any) => {
            return record[dataIndex] ? record[dataIndex].toString().toLowerCase().includes(value.toLowerCase()) : '';
        },
        onFilterDropdownVisibleChange: (visible: any) => {
            if (visible) {
                setTimeout(() => searchInputNode?.select(), 100);
            }
        },
        render: (text: string) =>
            searchData?.searchedColumn === dataIndex ? (
                <Highlighter
                    highlightStyle={{ backgroundColor: '#ffc069', padding: 0 }}
                    searchWords={[searchData?.searchText]}
                    autoEscape
                    textToHighlight={text ? text.toString() : ''}
                />
            ) : (
                text
            ),
    });

    const columns: Array<any> = [
        {
            title: 'Code',
            dataIndex: 'code',
            sorter: (a: any, b: any) => a.code.length - b.code.length,
            filterSearch: true,
            ...getColumnSearchProps('code'),
        },
        {
            title: 'Label',
            dataIndex: 'label',
            sorter: (a: any, b: any) => a.label.length - b.label.length,
            filterSearch: true,
            ...getColumnSearchProps('label'),
        },
        {
            title: 'Sort order',
            dataIndex: 'sort_order',
            sorter: (a: any, b: any) => a.sort_order - b.sort_order,
            width: 140,
            filterSearch: true,
            ...getColumnSearchProps('sort_order'),
        },
        {
            title: 'Action',
            dataIndex: 'action',
            width: 100,
            render: (_: any, record: any) => (
                <Space>
                    <Button
                        type="primary"
                        ghost={true}
                        onClick={() => {
                            setSidePanel({ type: 'edit', code: record.code });
                        }}
                    >
                        Edit
                    </Button>
                    <Popconfirm
                        title={'Are you sure to delete?'}
                        onConfirm={() => {
                            let newOptions: Array<any> = [...options];
                            newOptions = newOptions.filter((item: any) => item.code !== record.code);
                            setOptions(newOptions);
                            AlertAction({
                                message: 'Option ' + record.code + ' deleted temporarily.',
                                type: ALERT_TYPE_SUCCESS,
                            });
                        }}>
                        <Button danger={true}>Delete</Button>
                    </Popconfirm>
                </Space>
            )
        },
    ];

    return (
        <>
            <Table
                rowSelection={{
                    selectedRowKeys: selectedOptionCodes,
                    onChange: (selectedRowKeys: Array<any>) => {
                        setSelectedOptionCodes(selectedRowKeys);
                    }
                }}
                dataSource={options}
                columns={columns}
                rowKey="code"
            />
        </>
    );
}

export default AttributeValueOptionsTable;
