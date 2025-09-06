<script setup lang="ts">
    import { router } from '@inertiajs/vue3';
    import {
        Pagination,
        PaginationContent,
        PaginationPrevious,
        PaginationItem,
        PaginationNext
    } from '../ui/pagination';

    interface Props {
        paginator: {
            from: number;
            to: number;
            total: number;
            per_page: number;
            current_page: number;
            prev_page_url: string;
            next_page_url: string;
        },
        routePrefix: string;
    }

    const props = defineProps<Props>();
</script>

<template>
    <div class="text-sm">
        Showing {{ props.paginator.from }} to {{ props.paginator.to }} of {{ props.paginator.total }} entires
    </div>
    <div>
        <Pagination v-slot="{ page }" :items-per-page="props.paginator.per_page" :total="props.paginator.total" :default-page="props.paginator.current_page">
            <PaginationContent v-slot="{ items }">
                <PaginationPrevious @click="router.visit(paginator.prev_page_url)" />

                <template v-for="(item, index) in items" :key="index">
                    <PaginationItem
                        v-if="item.type === 'page'"
                        :value="item.value"
                        :is-active="item.value === page"
                        @click="router.visit(`/admin/${props.routePrefix}?page=${item.value}`)"
                    >
                        {{ item.value }}
                    </PaginationItem>
                </template>

                <!-- <PaginationEllipsis :index="4" /> -->

                <PaginationNext @click="router.visit(paginator.next_page_url)" />
            </PaginationContent>
        </Pagination>
    </div>
</template>
