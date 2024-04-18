import React, { useEffect, useState } from "react";
import axios from 'axios';
import { BlockStack, Box, IndexTable, InlineGrid, InlineStack, Layout, Page, Text } from "@shopify/polaris";
const User = () => {
    const [users, setUsers] = useState(null)
    useEffect(() => {
        axios.get('/api/users').then((res) => {
            setUsers(res.data)
            console.log('users', res)
        })
    }, [])
    return (
        <div className="w-full">
            <Page title="Users" primaryAction={{ content: "Add user", url: '/admin/users/new' }}>
                <Layout>
                    <Layout.Section>
                        <Box background="bg-surface" padding={400}>
                            <BlockStack gap="100">
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
                                        <InlineGrid gap="100" columns={{ xs: 4 }}>
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
export default User