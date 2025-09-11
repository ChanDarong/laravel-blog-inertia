<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus } from 'lucide-vue-next'

import { User, PaginationMeta, UserPagination } from '@/types'
import { edit, destroy } from '@/actions/App/Http/Controllers/Admin/UserController';

import ActionDropdown from '@/components/admin/ActionDropdown.vue';
import TablePagination from '@/components/admin/TablePagination.vue';

const props = defineProps<{
    users: UserPagination
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Users',
        href: '/admin/users',
    }
];
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="px-4 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Users</h1>
                    <p class="text-muted-foreground">Manage your users</p>
                </div>
                <Link href="/admin/users/create">
                    <Button>
                        <Plus class="h-4 w-4" />
                        Add New
                    </Button>
                </Link>
            </div>

            <!-- Users Table -->
            <div class="rounded-md border mt-4">
                <Table>
                    <TableHeader>
                        <TableRow class="uppercase text-xs tracking-widest">
                            <TableHead class="w-[100px] text-center">
                                ID
                            </TableHead>
                            <TableHead class="text-center">Name</TableHead>
                            <TableHead class="text-center">Email</TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            <TableHead class="w-[100px] text-center">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in props.users.data" :key="user.id">
                            <TableCell class="text-center">
                                {{ user.id }}
                            </TableCell>
                            <TableCell class="text-center">{{ user.name }}</TableCell>
                            <TableCell class="text-center">{{ user.email }}</TableCell>
                            <TableCell class="text-center">
                                {{ user.created_at }}
                            </TableCell>
                            <TableCell class="text-center">
                                <ActionDropdown
                                    :editUrl="edit(user.id).url"
                                    :deleteUrl="destroy(user.id).url"
                                />
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.users.data.length">
                            <TableCell colspan="5" class="text-center">No data found</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="py-4 flex justify-between items-center">
                <TablePagination :paginator="props.users" routePrefix="users" />
            </div>
        </div>

    </AppLayout>
</template>
