<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Eye, Pencil, Trash2, Plus, ArrowUp, ArrowDown } from 'lucide-vue-next';

interface Order {
    id: number;
    date: string;
    order_number: string;
    car_number: string;
    amount: number;
    company: string | null;
    destination: string | null;
    customer: { id: number; name: string } | null;
    driver: { id: number; name: string };
    warehouse: { id: number; name: string } | null;
    gasoline: number | null;
    benzin: number | null;
    fleet: number | null;
    transit: number | null;
    window_fee: number | null;
    manfisto: number | null;
    freightage: number | null;
    tax: number | null;
    commission: number | null;
    created_at: string;
}

interface Props {
    orders: Order[];
    sort: string;
    direction: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'الطلبات', href: '/orders' },
];

const form = useForm({});

const sortBy = (field: string) => {
    const newDirection =
        props.sort === field && props.direction === 'asc' ? 'desc' : 'asc';
    router.visit('/orders', {
        method: 'get',
        data: { sort: field, direction: newDirection },
        replace: true,
        preserveState: true,
    });
};

const deleteOrder = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا الطلب؟')) {
        form.delete(`/orders/${id}`);
    }
};

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
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" :sidebar-open="false">
        <div class="space-y-6 p-2">
            <div class="flex items-center justify-between px-4">
                <div>
                    <h1 class="text-2xl font-bold">الطلبات</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        إدارة بيانات الطلبات
                    </p>
                </div>
                <Link href="/orders/create">
                    <Button class="gap-2">
                        <Plus class="h-4 w-4" />
                        إضافة طلب
                    </Button>
                </Link>
            </div>

            <div class="overflow-x-auto rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead
                                class="cursor-pointer text-xs font-semibold whitespace-nowrap hover:text-primary"
                                @click="sortBy('order_number')"
                            >
                                <div class="flex items-center gap-1">
                                    رقم الطلب
                                    <template
                                        v-if="props.sort === 'order_number'"
                                    >
                                        <ArrowUp
                                            v-if="props.direction === 'asc'"
                                            class="h-3 w-3 text-primary"
                                        />
                                        <ArrowDown
                                            v-else
                                            class="h-3 w-3 text-primary"
                                        />
                                    </template>
                                    <template v-else>
                                        <ArrowUp
                                            class="h-3 w-3 text-muted-foreground/50"
                                        />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer text-xs font-semibold whitespace-nowrap hover:text-primary"
                                @click="sortBy('date')"
                            >
                                <div class="flex items-center gap-1">
                                    التاريخ
                                    <template v-if="props.sort === 'date'">
                                        <ArrowUp
                                            v-if="props.direction === 'asc'"
                                            class="h-3 w-3 text-primary"
                                        />
                                        <ArrowDown
                                            v-else
                                            class="h-3 w-3 text-primary"
                                        />
                                    </template>
                                    <template v-else>
                                        <ArrowUp
                                            class="h-3 w-3 text-muted-foreground/50"
                                        />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >العميل</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >السائق</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >المستودع</TableHead
                            >
                            <TableHead
                                class="cursor-pointer text-xs font-semibold whitespace-nowrap hover:text-primary"
                                @click="sortBy('car_number')"
                            >
                                <div class="flex items-center gap-1">
                                    رقم السيارة
                                    <template
                                        v-if="props.sort === 'car_number'"
                                    >
                                        <ArrowUp
                                            v-if="props.direction === 'asc'"
                                            class="h-3 w-3 text-primary"
                                        />
                                        <ArrowDown
                                            v-else
                                            class="h-3 w-3 text-primary"
                                        />
                                    </template>
                                    <template v-else>
                                        <ArrowUp
                                            class="h-3 w-3 text-muted-foreground/50"
                                        />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >الشركة</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >الوجهة</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >الجاز</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >البنزين</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >الطوف</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >العبور</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >النافذة</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >المنفيستو</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >النولون</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >الضريبة</TableHead
                            >
                            <TableHead
                                class="text-xs font-semibold whitespace-nowrap"
                                >العمولة</TableHead
                            >
                            <TableHead
                                class="cursor-pointer text-xs font-semibold whitespace-nowrap hover:text-primary"
                                @click="sortBy('amount')"
                            >
                                <div class="flex items-center gap-1">
                                    المبلغ
                                    <template v-if="props.sort === 'amount'">
                                        <ArrowUp
                                            v-if="props.direction === 'asc'"
                                            class="h-3 w-3 text-primary"
                                        />
                                        <ArrowDown
                                            v-else
                                            class="h-3 w-3 text-primary"
                                        />
                                    </template>
                                    <template v-else>
                                        <ArrowUp
                                            class="h-3 w-3 text-muted-foreground/50"
                                        />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="text-left text-xs font-semibold whitespace-nowrap"
                                >الإجراءات</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="order in props.orders"
                            :key="order.id"
                            class="border-b hover:bg-muted/50"
                        >
                            <TableCell
                                class="border-r text-xs font-medium whitespace-nowrap"
                            >
                                {{ order.order_number.slice(-5) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatDate(order.date) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.customer?.name || '-' }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.driver.name }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.warehouse?.name || '-' }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.car_number }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.company || '-' }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ order.destination || '-' }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.gasoline) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.benzin) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.fleet) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.transit) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.window_fee) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.manfisto) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.freightage) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.tax) }}
                            </TableCell>
                            <TableCell
                                class="border-r text-xs whitespace-nowrap"
                            >
                                {{ formatAmount(order.commission) }}
                            </TableCell>
                            <TableCell
                                class="text-xs font-medium whitespace-nowrap"
                            >
                                {{ formatAmount(order.amount) }}
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link :href="`/orders/${order.id}`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="عرض"
                                            class="h-7 w-7"
                                        >
                                            <Eye class="h-3 w-3" />
                                        </Button>
                                    </Link>
                                    <Link :href="`/orders/${order.id}/edit`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="تعديل"
                                            class="h-7 w-7"
                                        >
                                            <Pencil class="h-3 w-3" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="h-7 w-7 text-destructive hover:text-destructive"
                                        title="حذف"
                                        @click="deleteOrder(order.id)"
                                    >
                                        <Trash2 class="h-3 w-3" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
