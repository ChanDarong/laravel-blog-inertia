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
import { Trash2, Eye, Plus, Search } from 'lucide-vue-next'
import { ref, watch } from 'vue'
import TableFooter from '@/components/ui/table/TableFooter.vue';

import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogClose
} from '@/components/ui/dialog'


import ActionDropdown from '@/components/admin/ActionDropdown.vue';
import TablePagination from '@/components/admin/TablePagination.vue';
import Create from './Create.vue';
import Edit from '../blog-categories/Edit.vue';
import { Notify } from 'notiflix/build/notiflix-notify-aio'

const props = defineProps<{
    categories: {
        data: Array<{
            id: number;
            name: string;
            description?: string;
            created_at: string;
        }>;
        from: number;
        to: number;
        total: number;
        per_page: number;
        current_page: number;
        prev_page_url: string;
        next_page_url: string;
    }
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Blog Categories',
        href: '/admin/blog-categories',
    }
];

// Reference to the Edit component
const editComponentRef = ref<InstanceType<typeof Edit> | null>(null);

// Function to open edit dialog
const openEditDialog = (id: number) => {
    // Find the category in our current data
    const category = props.categories.data.find(cat => cat.id === id);

    if (category && editComponentRef.value) {
        editComponentRef.value.openDialog(category);
    } else {
        Notify.failure('Category not found');
    }
};

// Watch for flash messages
// Flash messages will be handled by individual components
</script>

<template>
    <Head title="Blog Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="px-4 py-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Blog Categories</h1>
                    <p class="text-muted-foreground">Manage your blog categories</p>
                </div>
                <Link href="/admin/blog-categories/create">

                </Link>
                <Create />
            </div>

            <!-- Blog Categories Table -->
            <div class="rounded-md border mt-4">
                <Table>
                    <TableHeader>
                        <TableRow class="uppercase text-xs tracking-widest">
                            <TableHead class="w-[100px] text-center">
                                ID
                            </TableHead>
                            <TableHead class="text-center">Name</TableHead>
                            <TableHead class="text-center">
                                Created At
                            </TableHead>
                            <TableHead class="w-[100px] text-center">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="category in props.categories.data" :key="category.id">
                            <TableCell class="text-center">
                                {{ category.id }}
                            </TableCell>
                            <TableCell class="text-center">{{ category.name }}</TableCell>
                            <TableCell class="text-center">
                                {{ category.created_at }}
                            </TableCell>
                            <TableCell class="text-center">
                                <ActionDropdown
                                    :editUrl="`/admin/blog-categories/${category.id}/edit`"
                                    :deleteUrl="`/admin/blog-categories/${category.id}`"
                                    :isDialog="true"
                                    :id="category.id"
                                    @openEditDialog="openEditDialog"
                                />
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!props.categories.data.length">
                            <TableCell colspan="5" class="text-center">No data found</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="py-4 flex justify-between items-center">
                <TablePagination :paginator="props.categories" routePrefix="blog-categories" />
            </div>

            <!-- Edit Component with ref -->
            <Edit ref="editComponentRef" />
        </div>

    </AppLayout>
</template>
