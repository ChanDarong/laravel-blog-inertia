import { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(urlToCheck: NonNullable<InertiaLinkProps['href']>, currentUrl: string) {
    const checkUrl = toUrl(urlToCheck);

    // For exact root match (dashboard)
    if (checkUrl === '/' && currentUrl === '/') {
        return true;
    }

    // For other routes, check if current URL starts with the check URL
    // This will match /admin/users, /admin/users/create, /admin/users/1/edit, etc.
    if (checkUrl !== '/' && currentUrl.startsWith(checkUrl)) {
        return true;
    }

    return false;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}
