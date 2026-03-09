<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { Truck, ClipboardList, Users, Warehouse } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface Props {
    stats: {
        ordersThisMonth: number;
        totalOrders: number;
        totalDrivers: number;
        totalWarehouses: number;
    };
}

defineProps<Props>();

const formatNumber = (num: number) => {
    return new Intl.NumberFormat('en-US').format(num);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-bold">لوحة التحكم</h1>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                ترحيلات الشهر
                            </p>
                            <p class="mt-1 text-3xl font-bold">
                                {{ formatNumber(stats.ordersThisMonth) }}
                            </p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10"
                        >
                            <ClipboardList class="h-6 w-6 text-primary" />
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                إجمالي الترحيلات
                            </p>
                            <p class="mt-1 text-3xl font-bold">
                                {{ formatNumber(stats.totalOrders) }}
                            </p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900"
                        >
                            <Truck
                                class="h-6 w-6 text-blue-600 dark:text-blue-400"
                            />
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                السائقين
                            </p>
                            <p class="mt-1 text-3xl font-bold">
                                {{ formatNumber(stats.totalDrivers) }}
                            </p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900"
                        >
                            <Users
                                class="h-6 w-6 text-green-600 dark:text-green-400"
                            />
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                المستودعات
                            </p>
                            <p class="mt-1 text-3xl font-bold">
                                {{ formatNumber(stats.totalWarehouses) }}
                            </p>
                        </div>
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900"
                        >
                            <Warehouse
                                class="h-6 w-6 text-orange-600 dark:text-orange-400"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
