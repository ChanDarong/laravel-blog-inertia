<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge'
import { Trash2, Edit, Eye, Plus, Search } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import TableFooter from '@/components/ui/table/TableFooter.vue';
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationNext,
  PaginationPrevious,
} from '@/components/ui/pagination';

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            created_at: string;
        }>;
        from: number;
        to: number;
        total: number;
        per_page: number;
        current_page: number;
    }
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
                        Add User
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
                            <TableCell>

                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.users.data.length">
                            <TableCell colspan="5" class="text-center">No data found</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="py-4 flex justify-between items-center">
                <div class="text-sm">
                    Showing {{ props.users.from }} to {{ props.users.to }} of {{ props.users.total }} entires
                </div>
                <div>
                    <Pagination v-slot="{ page }" :items-per-page="props.users.per_page" :total="props.users.total" :default-page="props.users.current_page">
                        <PaginationContent v-slot="{ items }">
                        <PaginationPrevious />

                        <template v-for="(item, index) in items" :key="index">
                            <PaginationItem
                            v-if="item.type === 'page'"
                            :value="item.value"
                            :is-active="item.value === page"
                            >
                            {{ item.value }}
                            </PaginationItem>
                        </template>

                        <!-- <PaginationEllipsis :index="4" /> -->

                        <PaginationNext />
                        </PaginationContent>
                    </Pagination>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
