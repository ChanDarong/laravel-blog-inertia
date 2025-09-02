<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-show="message">
        <p class="text-destructive-foreground text-sm dark:text-red-500">
            {{ message }}
        </p>
    </div>
</template>
