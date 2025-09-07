<script setup lang="ts">
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

import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage
} from '@/components/ui/form'

import { Button } from '@/components/ui/button'
import { LoaderCircle, Plus } from 'lucide-vue-next'
import { Notify } from 'notiflix/build/notiflix-notify-aio'

import { Form, Head, Link, usePage, useForm as useInertiaForm } from '@inertiajs/vue3';
import BlogCategoryController from '@/actions/App/Http/Controllers/Admin/BlogCategoryController';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import InputError from '@/components/InputError.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';

import { useForm } from 'vee-validate';
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'
import { ref, watch } from 'vue'

// Props for category data
const props = defineProps<{
    categoryData?: {
        id: number;
        name: string;
        description?: string;
    } | null;
}>();

const formSchema = toTypedSchema(z.object({
        name: z.string({
            message: 'Name is required'
        })
        .min(2, {
            message: 'Name must be at least 2 characters long'
        })
        .max(100),
    description: z.string().max(500).optional()
}));

const veeForm = useForm({
  validationSchema: formSchema,
});

// Dialog state
const isDialogOpen = ref(false);
const editingCategory = ref<{
    id: number;
    name: string;
    description?: string;
} | null>(null);

// Create Inertia form for handling submission and processing state
const inertiaForm = useInertiaForm({
    name: '',
    description: ''
});

// Method to open dialog with category data
const openDialog = (category: { id: number; name: string; description?: string }) => {
    editingCategory.value = category;

    // Set form values
    veeForm.setValues({
        name: category.name,
        description: category.description || ''
    });

    // Set Inertia form values
    inertiaForm.name = category.name;
    inertiaForm.description = category.description || '';

    // Open dialog
    isDialogOpen.value = true;
};

// Close dialog method
const closeDialog = () => {
    isDialogOpen.value = false;
    editingCategory.value = null;
    veeForm.resetForm();
    inertiaForm.reset();
};

// Expose methods to parent component
defineExpose({
    openDialog,
    closeDialog
});

// Handle form submission
const handleSubmit = async () => {
    if (!editingCategory.value) return;

    // Validate with vee-validate first
    const { valid } = await veeForm.validate();

    if (valid) {
        // Update Inertia form with current values
        inertiaForm.name = veeForm.values.name || '';
        inertiaForm.description = veeForm.values.description || '';

        // Submit with Inertia using PUT method for updates
        inertiaForm.put(`/admin/blog-categories/${editingCategory.value.id}`, {
            onSuccess: (response) => {
                // Reset forms on success
                veeForm.resetForm();
                inertiaForm.reset();
                // Close the dialog
                closeDialog();

                const flash = response.props.flash as { success?: string };
                if (flash?.success) {
                    Notify.success(flash.success);
                }
            },
            onError: (errors) => {
                // Handle validation errors
                if (errors) {
                    Object.keys(errors).forEach(key => {
                        const message = Array.isArray(errors[key]) ? errors[key][0] : errors[key];
                        if (message) {
                            Notify.failure(message);
                        }
                    });
                } else {
                    Notify.failure('Something went wrong. Please try again.');
                }
            }
        });
    }
};

</script>

<template>
    <Dialog v-model:open="isDialogOpen">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Edit category</DialogTitle>
                <DialogDescription>
                    Update the category details and click save when you're done.
                </DialogDescription>
            </DialogHeader>

            <Form v-slot="{ errors, recentlySuccessful }">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem v-auto-animate>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="Category name"
                                        v-bind="componentField"
                                        v-model="inertiaForm.name"
                                        :class="{ 'border-destructive focus:border-destructive': errors.name }"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <div class="grid gap-2">
                        <FormField v-slot="{ componentField }" name="description">
                            <FormItem v-auto-animate>
                                <FormLabel>Description</FormLabel>
                                <FormControl>
                                    <Textarea
                                        placeholder="Category description..."
                                        class="resize-none"
                                        v-bind="componentField"
                                        v-model="inertiaForm.description"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>
                </div>
            </Form>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" variant="outline" @click="closeDialog">
                        Cancel
                    </Button>
                </DialogClose>
                <Button type="button" @click="handleSubmit" :disabled="inertiaForm.processing">
                    <LoaderCircle v-if="inertiaForm.processing" class="h-4 w-4 animate-spin" />
                    Update
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
