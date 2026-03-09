<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    ArrowLeft,
    Pencil,
    ClipboardList,
    User,
    Truck,
    Warehouse,
    Calendar,
    Car,
    MapPin,
    Building2,
} from 'lucide-vue-next';

interface Order {
    id: number;
    date: string;
    order_number: string;
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
    driver: { id: number; name: string; phone: string | null } | null;
    warehouse: { id: number; name: string } | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    order: Order;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'الطلبات', href: '/orders' },
    { title: props.order.order_number, href: `/orders/${props.order.id}` },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};

const formatAmount = (amount: number | null) => {
    if (amount === null || amount === undefined) return '-';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
};

const formatDateTime = (date: string) => {
    return new Date(date).toLocaleString('en-GB', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" :sidebar-open="false">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link href="/orders">
                        <Button variant="outline" size="icon">
                            <ArrowLeft class="h-4 w-4" />
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">تفاصيل الطلب</h1>
                        <p class="mt-1 text-sm text-muted-foreground">
                            رقم الطلب: {{ props.order.order_number }}
                        </p>
                    </div>
                </div>
                <Link :href="`/orders/${props.order.id}/edit`">
                    <Button class="gap-2">
                        <Pencil class="h-4 w-4" />
                        تعديل
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <ClipboardList class="h-5 w-5" />
                            معلومات الطلب
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-muted-foreground">رقم الطلب</span>
                            <span class="font-medium">{{
                                props.order.order_number
                            }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-muted-foreground">التاريخ</span>
                            <span class="font-medium">{{
                                formatDate(props.order.date)
                            }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-muted-foreground"
                                >رقم السيارة</span
                            >
                            <span class="font-medium">{{
                                props.order.car_number
                            }}</span>
                        </div>
                        <div
                            v-if="props.order.company"
                            class="flex justify-between border-b pb-2"
                        >
                            <span class="text-muted-foreground">الشركة</span>
                            <span class="font-medium">{{
                                props.order.company
                            }}</span>
                        </div>
                        <div
                            v-if="props.order.destination"
                            class="flex justify-between border-b pb-2"
                        >
                            <span class="text-muted-foreground">الوجهة</span>
                            <span class="font-medium">{{
                                props.order.destination
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground"
                                >تاريخ الإنشاء</span
                            >
                            <span class="font-medium">{{
                                formatDateTime(props.order.created_at)
                            }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <User class="h-5 w-5" />
                            الأطراف
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-muted-foreground">العميل</span>
                            <span class="font-medium">{{
                                props.order.customer?.name || '-'
                            }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-muted-foreground">السائق</span>
                            <span class="font-medium">{{
                                props.order.driver?.name || '-'
                            }}</span>
                        </div>
                        <div
                            v-if="props.order.driver?.phone"
                            class="flex justify-between border-b pb-2"
                        >
                            <span class="text-muted-foreground"
                                >هاتف السائق</span
                            >
                            <span class="font-medium">{{
                                props.order.driver.phone
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">المستودع</span>
                            <span class="font-medium">{{
                                props.order.warehouse?.name || '-'
                            }}</span>
                        </div>
                    </CardContent>
                </Card>

                <Card class="md:col-span-2">
                    <CardHeader>
                        <CardTitle>المبالغ والرسوم</CardTitle>
                        <CardDescription>
                            تفاصيل المبالغ والرسوم المتعلقة بالطلب
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-5"
                        >
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    الجاز
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.gasoline) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    البنزين
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.benzin) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    الطوف
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.fleet) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    العبور
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.transit) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    رسوم النافذة
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.window_fee) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    المنفيستو
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.manfisto) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    النولون
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.freightage) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    الضريبة
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.tax) }}
                                </div>
                            </div>
                            <div class="rounded-lg border p-4">
                                <div class="text-sm text-muted-foreground">
                                    العمولة
                                </div>
                                <div class="text-lg font-medium">
                                    {{ formatAmount(props.order.commission) }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg border border-primary bg-primary/5 p-4"
                            >
                                <div class="text-sm text-primary">
                                    المبلغ الإجمالي
                                </div>
                                <div class="text-2xl font-bold text-primary">
                                    {{ formatAmount(props.order.amount) }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
