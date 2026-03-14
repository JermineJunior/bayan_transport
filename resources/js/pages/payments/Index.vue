<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import { ArrowRight, Calendar, User, Filter, Printer } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Customer {
    id: number;
    name: string;
}

interface Payment {
    id: number;
    order_id: number;
    order_number: string;
    order_date: string;
    customer: Customer | null;
    driver: { id: number; name: string } | null;
    order_amount: number;
    amount_paid: number;
    total_paid: number;
    remaining: number;
    date: string;
    notes: string | null;
    created_at: string;
}

interface Props {
    customers: Customer[];
    payments: Payment[];
    filters: {
        customer_id: number | null;
        start_date: string | null;
        end_date: string | null;
        status: string | null;
    };
    totals: {
        total_paid: string;
        total_remaining: string;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المدفوعات', href: '/payments' },
];

const customerId = ref<number | null>(null);
const startDate = ref('');
const endDate = ref('');
const status = ref<string | null>(null);

const formatAmount = (amount: number | null) => {
    if (amount === null || amount === undefined) return '-';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
};

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};

const applyFilters = () => {
    const params: Record<string, string | number | null> = {};

    if (customerId.value) params.customer_id = customerId.value;
    if (startDate.value) params.start_date = startDate.value;
    if (endDate.value) params.end_date = endDate.value;
    if (status.value) params.status = status.value;

    router.get('/payments', params);
};

const clearFilters = () => {
    customerId.value = null;
    startDate.value = '';
    endDate.value = '';
    status.value = null;
    router.get('/payments');
};

const printReport = () => {
    window.print();
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6 print:space-y-4 print:p-0">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">تقرير المدفوعات</h1>
                <div class="flex gap-2 print:hidden">
                    <Button variant="outline" size="sm" @click="printReport">
                        <Printer class="ms-2 h-4 w-4" />
                        طباعة
                    </Button>
                </div>
            </div>

            <div class="rounded-lg border bg-card p-4 print:hidden">
                <div class="mb-4 flex items-center gap-2">
                    <Filter class="h-4 w-4" />
                    <span class="font-medium">الفلاتر</span>
                </div>
                <div class="grid gap-4 md:grid-cols-5">
                    <div class="space-y-2">
                        <Label for="customer">العميل</Label>
                        <select
                            id="customer"
                            v-model="customerId"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option :value="null">كل العملاء</option>
                            <option
                                v-for="c in customers"
                                :key="c.id"
                                :value="c.id"
                            >
                                {{ c.name }}
                            </option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <Label for="start_date">من تاريخ</Label>
                        <Input
                            id="start_date"
                            v-model="startDate"
                            type="date"
                        />
                    </div>
                    <div class="space-y-2">
                        <Label for="end_date">إلى تاريخ</Label>
                        <Input id="end_date" v-model="endDate" type="date" />
                    </div>
                    <div class="space-y-2">
                        <Label for="status">الحالة</Label>
                        <select
                            id="status"
                            v-model="status"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option :value="null">الكل</option>
                            <option value="partial">مدفوع جزئياً</option>
                            <option value="paid">مدفوع بالكامل</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <Button @click="applyFilters" class="flex-1"
                            >تطبيق</Button
                        >
                        <Button variant="outline" @click="clearFilters"
                            >مسح</Button
                        >
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-lg border bg-green-50 p-4">
                    <div class="text-sm text-green-600">إجمالي المدفوع</div>
                    <div class="text-2xl font-bold text-green-700">
                        {{ totals.total_paid }}
                    </div>
                </div>
                <div class="rounded-lg border bg-red-50 p-4">
                    <div class="text-sm text-red-600">إجمالي المتبقي</div>
                    <div class="text-2xl font-bold text-red-700">
                        {{ totals.total_remaining }}
                    </div>
                </div>
            </div>

            <div class="rounded-lg border bg-card">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b bg-muted/50 text-right">
                                <th class="p-4 font-medium">رقم الطلب</th>
                                <th class="p-4 font-medium">التاريخ</th>
                                <th class="p-4 font-medium">العميل</th>
                                <th class="p-4 font-medium">السائق</th>
                                <th class="p-4 text-left font-medium">
                                    المبلغ
                                </th>
                                <th class="p-4 text-left font-medium">مدفوع</th>
                                <th class="p-4 text-left font-medium">متبقي</th>
                                <th class="p-4 font-medium">تاريخ الدفعة</th>
                                <th class="p-4 font-medium">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="payment in payments"
                                :key="payment.id"
                                class="border-b hover:bg-muted/50"
                            >
                                <td class="p-4">
                                    <Link
                                        :href="`/orders/${payment.order_id}`"
                                        class="text-primary hover:underline"
                                    >
                                        {{ payment.order_number }}
                                    </Link>
                                </td>
                                <td class="p-4">
                                    {{ formatDate(payment.order_date) }}
                                </td>
                                <td class="p-4">
                                    {{ payment.customer?.name || '-' }}
                                </td>
                                <td class="p-4">
                                    {{ payment.driver?.name || '-' }}
                                </td>
                                <td class="p-4 text-left font-medium">
                                    {{ formatAmount(payment.order_amount) }}
                                </td>
                                <td class="p-4 text-left text-green-600">
                                    {{ formatAmount(payment.amount_paid) }}
                                </td>
                                <td
                                    class="p-4 text-left"
                                    :class="
                                        payment.remaining > 0
                                            ? 'text-red-600'
                                            : 'text-green-600'
                                    "
                                >
                                    {{ formatAmount(payment.remaining) }}
                                </td>
                                <td class="p-4">
                                    {{ formatDate(payment.date) }}
                                </td>
                                <td class="p-4 text-muted-foreground">
                                    {{ payment.notes || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div
                    v-if="payments.length === 0"
                    class="p-8 text-center text-muted-foreground"
                >
                    لا توجد دفعات
                </div>
            </div>
        </div>
    </AppLayout>
</template>
