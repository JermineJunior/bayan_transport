<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';
import OrderInfoSection from './partials/OrderInfoSection.vue';
import OrderPartiesSection from './partials/OrderPartiesSection.vue';
import OrderAmountsSection from './partials/OrderAmountsSection.vue';

interface Order {
    id: number;
    date: string;
    order_number: string;
    customer_id: number | null;
    driver_id: number;
    warehouse_id: number | null;
    car_number: string;
    gasoline: number | null;
    benzin: number | null;
    fleet: number | null;
    transit: number | null;
    window_fee: number | null;
    manfisto: number | null;
    freightage: number | null;
    tax: number | null;
    commission: number | null;
    amount: number;
    company: string | null;
    destination: string | null;
    customer: { id: number; name: string } | null;
    driver: { id: number; name: string };
    warehouse: { id: number; name: string } | null;
}

interface Option {
    id: number;
    name: string;
}

interface Props {
    order: Order;
    customers: Option[];
    drivers: Option[];
    warehouses: Option[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'الطلبات', href: '/orders' },
    { title: 'تعديل الطلب', href: `/orders/${props.order.id}/edit` },
];

const formatDateForInput = (date: string) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().split('T')[0];
};

const formatNumber = (value: number | null) => {
    if (value === null || value === undefined) return '';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
};

const form = useForm({
    date: formatDateForInput(props.order.date),
    order_number: props.order.order_number,
    customer_id: props.order.customer_id ?? '',
    driver_id: props.order.driver_id ?? '',
    warehouse_id: props.order.warehouse_id ?? '',
    car_number: props.order.car_number,
    gasoline: formatNumber(props.order.gasoline),
    benzin: formatNumber(props.order.benzin),
    fleet: formatNumber(props.order.fleet),
    transit: formatNumber(props.order.transit),
    window_fee: formatNumber(props.order.window_fee),
    manfisto: formatNumber(props.order.manfisto),
    freightage: formatNumber(props.order.freightage),
    tax: formatNumber(props.order.tax),
    commission: formatNumber(props.order.commission),
    amount: formatNumber(props.order.amount),
    company: props.order.company ?? '',
    destination: props.order.destination ?? '',
});

const amountFields = [
    'gasoline',
    'benzin',
    'fleet',
    'transit',
    'window_fee',
    'manfisto',
    'freightage',
    'tax',
    'commission',
];

const submit = () => {
    const processedData: Record<string, any> = {};

    Object.keys(form.data()).forEach((key) => {
        const value = form.data()[key];
        if (amountFields.includes(key) && value) {
            processedData[key] = parseFloat(value.toString().replace(/,/g, ''));
        } else {
            processedData[key] = value;
        }
    });

    form.transform((data) => processedData).put(`/orders/${props.order.id}`);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" :sidebar-open="false">
        <div class="space-y-6 p-6">
            <div class="flex items-center gap-4">
                <Link href="/orders">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">تعديل الطلب</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        رقم الطلب: {{ props.order.order_number }}
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <OrderInfoSection :form="form" />

                <OrderPartiesSection
                    :form="form"
                    :customers="props.customers"
                    :drivers="props.drivers"
                    :warehouses="props.warehouses"
                />

                <OrderAmountsSection :form="form" />

                <div class="flex justify-end gap-2">
                    <Link href="/orders">
                        <Button variant="outline" type="button">إلغاء</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        تحديث
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
