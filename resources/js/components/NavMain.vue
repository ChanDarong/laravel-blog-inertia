<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage, type InertiaLinkProps } from '@inertiajs/vue3';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();

// Helper function to extract pathname from absolute URL for comparison
const getPathnameFromUrl = (href: NonNullable<InertiaLinkProps['href']>): string => {
    // Handle different href types from InertiaLinkProps
    const url = typeof href === 'string' ? href : href.url || '';

    try {
        return new URL(url).pathname;
    } catch {
        // If it's already a relative path, return as is
        return url;
    }
};

// Helper function to check if current page matches navigation item (including sub-routes)
const isActiveNavItem = (href: NonNullable<InertiaLinkProps['href']>): boolean => {
    const itemPath = getPathnameFromUrl(href);
    const currentPath = page.url;

    // For exact root match (dashboard)
    if (itemPath === '/' && currentPath === '/') {
        return true;
    }

    // For other routes, check if current path starts with item path
    // This will match /admin/users, /admin/users/create, /admin/users/1/edit, etc.
    if (itemPath !== '/' && currentPath.startsWith(itemPath)) {
        return true;
    }

    return false;
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton as-child :is-active="isActiveNavItem(item.href)" :tooltip="item.title">
                    <Link :href="item.href">
                        <component :is="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
