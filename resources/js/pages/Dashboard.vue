<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Truck,
    ClipboardList,
    Users,
    Warehouse,
    Plus,
    UserPlus,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';

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
        currentMonthName: string;
    };
}

const props = defineProps<Props>();

const formatNumber = (num: number) => {
    return new Intl.NumberFormat('en-US').format(num);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <h1 class="mb-6 text-2xl font-bold">لوحة التحكم</h1>

            <!-- Quick Create Links -->
            <div class="mb-6 flex flex-wrap gap-3">
                <Link
                    href="/orders/create"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90"
                >
                    <Plus class="h-4 w-4" />
                    طلب جديد
                </Link>
                <Link
                    href="/customers"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                >
                    <UserPlus class="h-4 w-4" />
                    إضافة عميل
                </Link>
                <Link
                    href="/drivers"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                >
                    <UserPlus class="h-4 w-4" />
                    إضافة سائق
                </Link>
                <Link
                    href="/warehouses"
                    class="inline-flex items-center gap-2 rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700"
                >
                    <Plus class="h-4 w-4" />
                    إضافة مستودع
                </Link>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border bg-card p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground">
                                ترحيلات شهر {{ stats.currentMonthName }}
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
