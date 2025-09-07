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

const formSchema = toTypedSchema(z.object({
    name: z.string({
            message: 'Name is required'
        })
        .min(2, {
            message: 'Name must be at least 2 characters long'
        })
        .max(100),
    description: z.string().optional()
}));

const veeForm = useForm({
  validationSchema: formSchema,
});

// Dialog state
const isDialogOpen = ref(false);

// Create Inertia form for handling submission and processing state
const inertiaForm = useInertiaForm({
    name: '',
    description: ''
});

// Handle form submission
const handleSubmit = async () => {
    // Validate with vee-validate first
    const { valid } = await veeForm.validate();

    if (valid) {
        // Update Inertia form with current values
        inertiaForm.name = veeForm.values.name || '';
        inertiaForm.description = veeForm.values.description || '';

        // Submit with Inertia
        inertiaForm.post(BlogCategoryController.store().url, {
            onSuccess: (response) => {
                // Reset forms on success
                veeForm.resetForm();
                inertiaForm.reset();
                // Close the dialog
                isDialogOpen.value = false;
                // console.log(response.props.flash.success);
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
        <DialogTrigger>
            <Button>
                <Plus class="h-4 w-4" />
                Add New
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Add new category</DialogTitle>
                <DialogDescription>
                    Click save when you're done.
                </DialogDescription>
            </DialogHeader>

            <Form v-bind="BlogCategoryController.store()" v-slot="{ errors, recentlySuccessful }">
                <div class="grid gap-6">
                    <div class="grid gap-2">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem v-auto-animate>
                                <FormLabel>Name</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="Full name"
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
                                        placeholder="Something..."
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
                    <Button type="button" variant="outline">
                        Close
                    </Button>
                </DialogClose>
                <Button type="button" @click="handleSubmit" :disabled="inertiaForm.processing">
                    <LoaderCircle v-if="inertiaForm.processing" class="h-4 w-4 animate-spin" />
                    Save
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
