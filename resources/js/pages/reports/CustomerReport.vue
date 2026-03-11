<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    FileText,
    Calendar,
    Fuel,
    Gauge,
    Coins,
    Printer,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface GeneralSettings {
    name: string;
    location: string;
    phone: string;
    email: string;
    logo: string | null;
    note: string | null;
}

interface Props {
    settings: GeneralSettings;
    customer: {
        id: number;
        name: string;
    };
    orders: any[];
    filters: {
        start_date: string | null;
        end_date: string | null;
    };
    totals: {
        total_amount: string;
        total_benzin: string;
        total_gasoline: string;
        orders_count: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'التقارير', href: '/reports/customer' },
    { title: 'تقرير العميل', href: '/reports/customer' },
];

const printReport = () => {
    window.print();
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 print:space-y-4">
            <!-- Print Header -->
            <div class="hidden print:block">
                <img
                    src="/image/reports/report_header.png"
                    class="mb-4 w-full object-contain"
                    alt="Report Header"
                />
            </div>

            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <FileText class="h-5 w-5 text-primary print:hidden" />
                    <h2 class="text-xl font-semibold print:text-lg">
                        تقرير العميل: {{ customer.name }}
                    </h2>
                </div>
                <div class="flex gap-2 print:hidden">
                    <Button variant="outline" size="sm" @click="printReport">
                        <Printer class="ms-2 h-4 w-4" />
                        طباعة
                    </Button>
                    <Link href="/reports/customer">
                        <Button variant="outline" size="sm">
                            <ArrowRight class="ms-2 h-4 w-4" />
                            رجوع
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Report Info -->
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm print:border-gray-300 print:shadow-none"
            >
                <div class="flex items-start gap-4 p-6 print:p-4">
                    <div class="flex flex-col space-y-1">
                        <h3
                            class="text-lg leading-none font-semibold tracking-tight print:text-base"
                        >
                            {{ settings.name }}
                        </h3>
                        <p class="text-sm text-muted-foreground print:text-xs">
                            {{ settings.location }}
                        </p>
                        <p class="text-sm text-muted-foreground print:text-xs">
                            {{ settings.phone }}
                        </p>
                        <p
                            v-if="settings.note"
                            class="text-sm text-muted-foreground print:text-xs"
                        >
                            {{ settings.note }}
                        </p>
                    </div>
                    <img
                        v-if="settings.logo"
                        :src="`/image/logo/${settings.logo}`"
                        class="ms-auto h-20 w-20 object-contain print:h-16 print:w-16"
                        alt="logo"
                    />
                </div>
                <div class="px-6 pb-6 print:px-4 print:pb-4">
                    <div
                        class="flex items-center gap-4 text-sm text-muted-foreground print:text-xs"
                    >
                        <div
                            v-if="filters.start_date || filters.end_date"
                            class="flex items-center gap-1"
                        >
                            <Calendar class="h-4 w-4 print:h-3 print:w-3" />
                            <span>
                                {{ filters.start_date || '-' }} إلى
                                {{ filters.end_date || '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm print:border-gray-300 print:shadow-none"
            >
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm print:text-xs">
                        <thead
                            class="[&_tr]:border-b print:[&_tr]:border-gray-300"
                        >
                            <tr
                                class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted print:hover:bg-transparent"
                            >
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    #
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    التاريخ
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    رقم السيارة
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    الشركة
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    الوجهة
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    الجاز
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    البنزين
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    المنفيستو
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    النولون
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground print:h-8 print:p-2"
                                >
                                    المبلغ
                                </th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            <tr
                                v-for="(order, index) in orders"
                                :key="order.id"
                                class="border-b transition-colors hover:bg-muted/50 print:border-gray-300 print:hover:bg-transparent"
                            >
                                <td class="p-4 align-middle print:p-2">
                                    {{ index + 1 }}
                                </td>
                                <td class="p-4 align-middle print:p-2">
                                    {{ order.date }}
                                </td>
                                <td class="p-4 align-middle print:p-2">
                                    {{ order.car_number }}
                                </td>
                                <td class="p-4 align-middle print:p-2">
                                    {{ order.company }}
                                </td>
                                <td class="p-4 align-middle print:p-2">
                                    {{ order.destination }}
                                </td>
                                <td class="p-4 text-end align-middle print:p-2">
                                    {{ order.gasoline }}
                                </td>
                                <td class="p-4 text-end align-middle print:p-2">
                                    {{ order.benzin }}
                                </td>
                                <td class="p-4 text-end align-middle print:p-2">
                                    {{ order.manfisto }}
                                </td>
                                <td class="p-4 text-end align-middle print:p-2">
                                    {{ order.freightage }}
                                </td>
                                <td class="p-4 text-end align-middle print:p-2">
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

        <!-- Print Footer -->
        <div class="hidden print:block">
            <img
                src="/image/reports/report_footer.png"
                class="mt-4 w-full object-contain"
                alt="Report Footer"
            />
        </div>
    </AppLayout>
</template>

<style scoped>
@media print {
    @page {
        size: landscape;
        margin: 0.5cm;
    }
}
</style>
