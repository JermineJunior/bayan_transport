<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { edit } from '@/routes/appearance';

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: computed(
            () => t.value.appearance || 'المظهر',
        ) as unknown as string,
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="t.appearance || 'المظهر'" />

        <SettingsLayout>
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ t.appearance || 'المظهر' }}
                    </h2>
                    <p class="text-sm text-muted-foreground">
                        {{ t.appearance_desc || 'تخصيص مظهر التطبيق' }}
                    </p>
                </div>
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
