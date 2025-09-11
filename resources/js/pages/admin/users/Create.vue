<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { computed, onMounted } from 'vue';
import { Notify } from 'notiflix/build/notiflix-notify-aio';
import { usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { store } from '@/actions/App/Http/Controllers/Admin/UserController';

import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage
} from '@/components/ui/form'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Users',
        href: '/admin/users',
    },
    {
        title: 'Create',
        href: '/admin/users/create',
    }
];

const formSchema = toTypedSchema(z.object({
    name: z.string({ message: 'Name is required' }).min(2, 'Name must be at least 2 characters').max(50, 'Name must not exceed 50 characters'),
    email: z.string({ message: 'Email is required' }).email('Please enter a valid email address'),
    password: z.string({ message: 'Password is required' }).min(6, 'Password must be at least 6 characters').max(100, 'Password must not exceed 100 characters'),
    password_confirmation: z.string({ message: 'Password confirmation is required' }).min(6, 'Password confirmation must be at least 6 characters').max(100, 'Password confirmation must not exceed 100 characters'),
}).refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match',
    path: ['password_confirmation'],
}));

const { isFieldDirty, handleSubmit, isSubmitting, errors: formErrors } = useForm({
  validationSchema: formSchema,
  validateOnMount: false,
})

// Check if form is valid (no errors and all fields are valid)
const isFormValid = computed(() => {
    // Don't block submission due to pending validation - let the form handle it
    return Object.keys(formErrors.value).length === 0
})

// Handle flash messages from controller
const page = usePage()
onMounted(() => {
    // Check for flash messages from Laravel session
    const flash = page.props.flash as any;
    if (flash?.success) {
        Notify.success(flash.success);
    }
    if (flash?.error) {
        Notify.failure(flash.error);
    }
    if (flash?.warning) {
        Notify.warning(flash.warning);
    }
    if (flash?.info) {
        Notify.info(flash.info);
    }
})

// Handle form submission with Inertia
const onSubmit = handleSubmit((values) => {
    // Use Inertia's router for submission
    router.post(store().url, values, {
        onSuccess: () => {
            // Show success notification
            Notify.success('User created successfully!');
            // Handle success (redirect is handled by backend)
        },
        onError: (errors: any) => {
            // Show error notification for validation errors
            if (Object.keys(errors).length > 0) {
                // Display all error messages from controller
                Object.values(errors).forEach((error: any) => {
                    if (Array.isArray(error)) {
                        // Handle Laravel validation errors (arrays)
                        error.forEach((message: string) => {
                            Notify.failure(message);
                        });
                    } else {
                        // Handle single error messages
                        Notify.failure(error);
                    }
                });
            } else {
                Notify.failure('Something went wrong. Please try again.');
            }
            console.log('Backend validation errors:', errors);
        }
    });
});

</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 py-6">
            <h1 class="text-2xl font-semibold">Create User</h1>
            <form @submit="onSubmit" class="space-y-6 my-6">
                <div v-auto-animate class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem v-auto-animate>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="Full name"
                                        v-bind="componentField"
                                        :class="{ 'border-destructive focus:border-destructive': formErrors.name }"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="space-y-2">
                        <FormField v-slot="{ componentField }" name="email" :validate-on-blur="!isFieldDirty">
                            <FormItem v-auto-animate>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input
                                        type="email"
                                        placeholder="hello@example.com"
                                        v-bind="componentField"
                                        :class="{ 'border-destructive focus:border-destructive': formErrors.email }"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="space-y-2">
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem v-auto-animate>
                                <FormLabel>Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        placeholder="Password"
                                        v-bind="componentField"
                                        :class="{ 'border-destructive focus:border-destructive': formErrors.password }"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="space-y-2">
                        <FormField v-slot="{ componentField }" name="password_confirmation">
                            <FormItem v-auto-animate>
                                <FormLabel>Confirm Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        placeholder="Confirm Password"
                                        v-bind="componentField"
                                        :class="{ 'border-destructive focus:border-destructive': formErrors.password_confirmation }"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <Button variant="outline" type="button" @click="$inertia.visit('/admin/users')">
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="!isFormValid || isSubmitting"
                    >
                        <LoaderCircle v-if="isSubmitting" class="h-4 w-4 animate-spin" />
                        <span v-else>Save</span>
                    </Button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>
