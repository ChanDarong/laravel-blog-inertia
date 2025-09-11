import { User, PaginationMeta, BlogCategory } from './index.d';
import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface PaginationMeta {
    from: number;
    to: number;
    total: number;
    per_page: number;
    current_page: number;
    // previous page URL
    prev_page_url: string;
    // next page URL
    next_page_url: string;
}

export interface UserPagination {
    data: Array<User>;
    from: number;
    to: number;
    total: number;
    per_page: number;
    current_page: number;
    // previous page URL
    prev_page_url: string;
    // next page URL
    next_page_url: string;

}

export interface BlogCategory {
    id: number;
    name: string;
    description?: string;
    created_at: string;
}

export interface BlogCategoryPagination {
    data: Array<BlogCategory>;
    from: number;
    to: number;
    total: number;
    per_page: number;
    current_page: number;
    prev_page_url: string;
    next_page_url: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
