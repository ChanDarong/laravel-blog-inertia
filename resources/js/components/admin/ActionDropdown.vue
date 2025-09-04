<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import Button from '../ui/button/Button.vue';
import { Icon } from "@iconify/vue";
import { Notify } from 'notiflix/build/notiflix-notify-aio';
import { Confirm } from 'notiflix/build/notiflix-confirm-aio';

const props = defineProps<{
    editUrl: string;
    viewUrl?: string;
    deleteUrl?: string;
}>();

const deleteRecord = () => {
    Confirm.show(
        'Confirm Delete',
        'Are you sure you want to delete this item? This action cannot be undone.',
        'Yes, Delete',
        'Cancel',
        () => {
            if (props.deleteUrl) {
            router.delete(props.deleteUrl, {
                onSuccess: () => {
                Notify.success('Item deleted successfully!')
                },
                onError: (errors: any) => {
                if (errors.message) {
                    Notify.failure(errors.message)
                } else {
                    Notify.failure('Failed to delete item.')
                }
                }
            });
            }
        },
        () => {
            
        },
        {
        },
    );
}

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>
            <Button size="sm" variant="ghost">
                <Icon icon="mdi:dots-vertical" class="h-5 w-5" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent>
            <Link :href="props.editUrl">
                <DropdownMenuItem>
                    <Icon icon="lucide:pencil" class="h-5 w-5" /> Edit
                </DropdownMenuItem>
            </Link>
            <Link :href="props.viewUrl">
                <DropdownMenuItem>
                    <Icon icon="lucide:eye" class="h-5 w-5" /> View
                </DropdownMenuItem>
            </Link>
            <DropdownMenuItem variant="destructive" @click="deleteRecord">
                <Icon icon="lucide:trash" class="h-5 w-5" /> Delete
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
