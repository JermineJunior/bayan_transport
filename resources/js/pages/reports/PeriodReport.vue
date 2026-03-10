<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowRight, FileText, Calendar } from 'lucide-vue-next';

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
        total_amount: string;
        total_freightage: string;
        total_tax: string;
        total_commission: string;
        orders_count: number;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'التقارير', href: '/reports/period' },
    { title: 'تقرير الفترة', href: '/reports/period' },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <FileText class="h-5 w-5 text-primary" />
                    <h2 class="text-xl font-semibold">تقرير الفترة</h2>
                </div>
                <Link href="/reports/period">
                    <Button variant="outline" size="sm">
                        <ArrowRight class="ms-2 h-4 w-4" />
                        رجوع
                    </Button>
                </Link>
            </div>

            <!-- Report Info -->
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm"
            >
                <div class="flex flex-col space-y-1.5 p-6">
                    <h3
                        class="text-lg leading-none font-semibold tracking-tight"
                    >
                        {{ settings.name }}
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        {{ settings.location }} - {{ settings.phone }}
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <div
                        class="flex items-center gap-4 text-sm text-muted-foreground"
                    >
                        <div class="flex items-center gap-1">
                            <Calendar class="h-4 w-4" />
                            <span>
                                {{ filters.start_date }} إلى
                                {{ filters.end_date }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm"
            >
                <div class="relative w-full overflow-auto">
                    <table class="w-full caption-bottom text-sm">
                        <thead class="[&_tr]:border-b">
                            <tr
                                class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted"
                            >
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    #
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    التاريخ
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    رقم الطلب
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    العميل
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    السائق
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    المستودع
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    الوجهة
                                </th>
                                <th
                                    class="h-12 px-4 text-start align-middle font-medium text-muted-foreground"
                                >
                                    الشركة
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground"
                                >
                                    المبلغ
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground"
                                >
                                    الأجور
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground"
                                >
                                    الضريبة
                                </th>
                                <th
                                    class="h-12 px-4 text-end align-middle font-medium text-muted-foreground"
                                >
                                    العمولة
                                </th>
                            </tr>
                        </thead>
                        <tbody class="[&_tr:last-child]:border-0">
                            <tr
                                v-for="(order, index) in orders"
                                :key="order.id"
                                class="border-b transition-colors hover:bg-muted/50"
                            >
                                <td class="p-4 align-middle">
                                    {{ index + 1 }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.date }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.order_number.slice(-5) }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.customer?.name || '-' }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.driver?.name || '-' }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.warehouse?.name || '-' }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.destination }}
                                </td>
                                <td class="p-4 align-middle">
                                    {{ order.company }}
                                </td>
                                <td class="p-4 text-end align-middle">
                                    {{ order.amount }}
                                </td>
                                <td class="p-4 text-end align-middle">
                                    {{ order.freightage }}
                                </td>
                                <td class="p-4 text-end align-middle">
                                    {{ order.tax }}
                                </td>
                                <td class="p-4 text-end align-middle">
                                    {{ order.commission }}
                                </td>
                            </tr>
                            <tr v-if="orders.length === 0">
                                <td
                                    colspan="12"
                                    class="p-4 text-center text-muted-foreground"
                                >
                                    لا توجد بيانات
                                </td>
                            </tr>
                        </tbody>
                        <tfoot
                            v-if="orders.length > 0"
                            class="border-t bg-muted/50"
                        >
                            <tr class="transition-colors hover:bg-muted/50">
                                <td
                                    class="p-4 text-start align-middle font-semibold"
                                    colspan="8"
                                >
                                    الإجمالي
                                </td>
                                <td
                                    class="p-4 text-end align-middle font-semibold"
                                >
                                    {{ totals.total_amount }}
                                </td>
                                <td
                                    class="p-4 text-end align-middle font-semibold"
                                >
                                    {{ totals.total_freightage }}
                                </td>
                                <td
                                    class="p-4 text-end align-middle font-semibold"
                                >
                                    {{ totals.total_tax }}
                                </td>
                                <td
                                    class="p-4 text-end align-middle font-semibold"
                                >
                                    {{ totals.total_commission }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div
                v-if="orders.length > 0"
                class="rounded-lg border bg-muted/50 p-4"
            >
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium"
                        >عدد الطلبات: {{ totals.orders_count }}</span
                    >
                </div>
            </div>
        </div>
    </AppLayout>
</template>
