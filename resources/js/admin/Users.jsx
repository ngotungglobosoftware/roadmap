import React, { useEffect, useState } from "react";
import axios from 'axios';
import { BlockStack, Box, IndexTable, InlineGrid, InlineStack, Layout, Page, Select, Text, TextField } from "@shopify/polaris";
import { useCallback } from "react";
import { debounce } from "lodash";
const Users = () => {
    const [users, setUsers] = useState(null)

    const [queryValue, setQueryValue] = useState("");
    const [querySearch, setQuerySearch] = useState("");
    const [limit, setLimit] = useState(1);
    const [page, setPage] = useState(1);
    const [sort, setSort] = useState("asc");
    useEffect(() => {
        axios.get('/api/users').then((res) => {
            setUsers(res.data)
        })
    }, [sort, querySearch, page, limit])
    const handleQueryValueRemove = useCallback(() => {
        setQueryValue("");
        setQuerySearch("");
    }, []);
    const debounceSearch = useCallback(
        debounce((nextValue) => {
            setQuerySearch(nextValue);
        }, 250),
        []
    );
    const handleSearchChange = (value) => {
        setQueryValue(value);
        debounceSearch(value);
    };
    return (
        <div className="w-full p-8">
            <Page title="Users" primaryAction={{ content: "Add user", url: '/admin/user/new' }}>
                <Layout>
                    <Layout.Section>
                        <Box background="bg-surface" padding={400} borderRadius="200">
                            <BlockStack gap="200">
                                <InlineStack align="space-between">
                                    <TextField
                                        placeholder="Search name"
                                        value={queryValue}
                                        onChange={handleSearchChange}
                                        clearButton={handleQueryValueRemove}
                                    />
                                    <Select
                                        options={[
                                            { label: "New to old", value: "desc" },
                                            { label: "Old to new", value: "asc" }
                                        ]}
                                        value={sort}
                                        onChange={(e) => setSort(e)}
                                    />
                                </InlineStack>
                                <InlineGrid gap="100" columns={{ xs: 4 }}>
                                    <Text as="span" variant="bodyMd" fontWeight="semibold">
                                        Name
                                    </Text>
                                    <Text as="span" variant="bodyMd" fontWeight="semibold">
                                        email
                                    </Text>
                                    <Text as="span" variant="bodyMd" fontWeight="semibold">
                                        mobile
                                    </Text>
                                    <Text as="span" variant="bodyMd" fontWeight="semibold">
                                        create at
                                    </Text>
                                </InlineGrid>
                                {users?.data?.map((item, index) => {
                                    return (
                                        <InlineGrid gap="100" columns={{ xs: 4 }} key={index}>
                                            <Text as="span" variant="bodyMd" fontWeight="semibold">
                                                {`${item?.firstName} ${item?.middleName} ${item?.lastName}`}
                                            </Text>
                                            <Text as="span" variant="bodyMd">
                                                {item?.email}
                                            </Text>
                                            <Text as="span" variant="bodyMd">
                                                {item?.mobile}
                                            </Text>
                                            <Text as="span" variant="bodyMd">
                                                {item?.registeredAt}
                                            </Text>
                                        </InlineGrid>
                                    )
                                })}
                            </BlockStack>
                        </Box>
                    </Layout.Section>
                </Layout>
            </Page>
        </div>
    )
}
export default Users