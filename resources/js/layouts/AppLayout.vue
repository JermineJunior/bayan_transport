<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
    sidebarOpen?: boolean | null;
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    sidebarOpen: null,
});
</script>

<template>
    <AppSidebarLayout :breadcrumbs="breadcrumbs" :sidebar-open="sidebarOpen">
        <slot />
    </AppSidebarLayout>
</template>
