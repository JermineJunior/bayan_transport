<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    FileText,
    Calendar,
    Fuel,
    Gauge,
    Coins,
    Printer,
} from 'lucide-vue-next';

interface GeneralSettings {
    name: string;
    location: string;
    phone: string;
    email: string;
    logo: string | null;
}

interface Props {
    settings: GeneralSettings;
    orders: any[];
    filters: {
        start_date: string | null;
        end_date: string | null;
    };
    totals: {
        total_gasoline: number;
        total_benzin: number;
        total_amount: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'تقرير الفترة',
        href: '/reports/period',
    },
];

function print() {
    window.print();
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 print:space-y-4">
            <!-- Print Header -->
            <div class="mb-4 hidden text-center print:block">
                <h1 class="text-xl font-bold">{{ settings.name }}</h1>
                <p class="text-sm font-semibold">تقرير الفترة</p>
                <p
                    v-if="filters.start_date || filters.end_date"
                    class="text-xs text-muted-foreground"
                >
                    <span v-if="filters.start_date && filters.end_date">
                        {{ filters.start_date }} إلى {{ filters.end_date }}
                    </span>
                    <span v-else-if="filters.start_date"
                        >من {{ filters.start_date }}</span
                    >
                    <span v-else-if="filters.end_date"
                        >إلى {{ filters.end_date }}</span
                    >
                </p>
            </div>

            <!-- Header -->
            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <div class="flex items-center gap-2">
                    <FileText class="h-6 w-6 text-primary print:hidden" />
                    <h1 class="text-2xl font-bold">تقرير الفترة</h1>
                </div>
                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="print()"
                        class="print:hidden"
                    >
                        <Printer class="ml-2 h-4 w-4" />
                        طباعة
                    </Button>
                    <Link href="/reports/period" class="print:hidden">
                        <Button variant="ghost" size="sm">
                            <ArrowRight class="ml-2 h-4 w-4" />
                            رجوع
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Date Range -->
            <div
                v-if="filters.start_date || filters.end_date"
                class="flex items-center gap-2 text-sm text-muted-foreground print:hidden"
            >
                <Calendar class="h-4 w-4" />
                <span v-if="filters.start_date && filters.end_date">
                    من {{ filters.start_date }} إلى {{ filters.end_date }}
                </span>
                <span v-else-if="filters.start_date"
                    >من {{ filters.start_date }}</span
                >
                <span v-else-if="filters.end_date"
                    >إلى {{ filters.end_date }}</span
                >
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm print:text-xs">
                    <thead class="bg-muted/50 print:bg-gray-100">
                        <tr>
                            <th class="p-2 text-right font-semibold print:p-1">
                                #
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                التاريخ
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                رقم السيارة
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                الشركة
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                الوجهة
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                بنزين
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                جازولين
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                مانفستو
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                أجرة
                            </th>
                            <th class="p-2 text-right font-semibold print:p-1">
                                المبلغ
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(order, index) in orders"
                            :key="order.id"
                            class="border-b transition-colors hover:bg-muted/50 print:hover:bg-transparent"
                        >
                            <td class="p-2 align-middle print:p-1">
                                {{ index + 1 }}
                            </td>
                            <td class="p-2 align-middle print:p-1">
                                {{ order.date }}
                            </td>
                            <td class="p-2 align-middle print:p-1">
                                {{ order.car_number }}
                            </td>
                            <td class="p-2 align-middle print:p-1">
                                {{ order.company }}
                            </td>
                            <td class="p-2 align-middle print:p-1">
                                {{ order.destination }}
                            </td>
                            <td class="p-2 text-end align-middle print:p-1">
                                {{ order.gasoline }}
                            </td>
                            <td class="p-2 text-end align-middle print:p-1">
                                {{ order.benzin }}
                            </td>
                            <td class="p-2 text-end align-middle print:p-1">
                                {{ order.manfisto }}
                            </td>
                            <td class="p-2 text-end align-middle print:p-1">
                                {{ order.freightage }}
                            </td>
                            <td class="p-2 text-end align-middle print:p-1">
                                {{ order.amount }}
                            </td>
                        </tr>
                        <tr v-if="orders.length === 0">
                            <td
                                colspan="10"
                                class="p-4 text-center text-muted-foreground print:p-2"
                            >
                                لا توجد بيانات
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Totals Cards -->
            <div
                v-if="orders.length > 0"
                class="grid grid-cols-1 gap-4 md:grid-cols-3 print:grid-cols-3 print:gap-2"
            >
                <div
                    class="rounded-lg border bg-card p-4 text-card-foreground shadow-sm print:border-gray-300 print:p-2 print:shadow-none"
                >
                    <div class="flex items-center gap-3 print:gap-2">
                        <div class="rounded-full bg-primary/10 p-2 print:p-1">
                            <Fuel
                                class="h-5 w-5 text-primary print:h-4 print:w-4"
                            />
                        </div>
                        <div>
                            <p
                                class="text-sm text-muted-foreground print:text-xs"
                            >
                                مجموع البنزين
                            </p>
                            <p class="text-lg font-semibold print:text-sm">
                                {{ totals.total_benzin }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card p-4 text-card-foreground shadow-sm print:border-gray-300 print:p-2 print:shadow-none"
                >
                    <div class="flex items-center gap-3 print:gap-2">
                        <div class="rounded-full bg-primary/10 p-2 print:p-1">
                            <Gauge
                                class="h-5 w-5 text-primary print:h-4 print:w-4"
                            />
                        </div>
                        <div>
                            <p
                                class="text-sm text-muted-foreground print:text-xs"
                            >
                                مجموع الجازولين
                            </p>
                            <p class="text-lg font-semibold print:text-sm">
                                {{ totals.total_gasoline }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="rounded-lg border bg-card p-4 text-card-foreground shadow-sm print:border-gray-300 print:p-2 print:shadow-none"
                >
                    <div class="flex items-center gap-3 print:gap-2">
                        <div class="rounded-full bg-primary/10 p-2 print:p-1">
                            <Coins
                                class="h-5 w-5 text-primary print:h-4 print:w-4"
                            />
                        </div>
                        <div>
                            <p
                                class="text-sm text-muted-foreground print:text-xs"
                            >
                                مجموع المبلغ
                            </p>
                            <p class="text-lg font-semibold print:text-sm">
                                {{ totals.total_amount }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    @page {
        size: landscape;
        margin: 0.5cm;
    }

    * {
        font-size: 10px !important;
    }

    .print\:text-xs {
        font-size: 9px !important;
    }

    .print\:text-sm {
        font-size: 11px !important;
    }
}
</style>
