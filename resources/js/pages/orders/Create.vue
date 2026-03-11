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

interface Option {
    id: number;
    name: string;
}

interface Props {
    orderNumber: string;
    customers: Option[];
    drivers: Option[];
    warehouses: Option[];
    selectedCustomer?: number | null;
    selectedDriver?: number | null;
    selectedWarehouse?: number | null;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'الطلبات', href: '/orders' },
    { title: 'إضافة طلب', href: '/orders/create' },
];

const form = useForm({
    date: new Date().toISOString().split('T')[0],
    order_number: props.orderNumber,
    customer_id: props.selectedCustomer ?? '',
    driver_id: props.selectedDriver ?? '',
    warehouse_id: props.selectedWarehouse ?? '',
    car_number: '',
    gasoline: '',
    benzin: '',
    fleet: '',
    transit: '',
    window_fee: '',
    manfisto: '',
    freightage: '',
    tax: '',
    commission: '',
    amount: '',
    company: '',
    destination: '',
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
    'amount',
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

    form.transform((data) => processedData).post('/orders');
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
                    <h1 class="text-2xl font-bold">إضافة طلب جديد</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        رقم الطلب: {{ props.orderNumber }}
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
                        حفظ
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
