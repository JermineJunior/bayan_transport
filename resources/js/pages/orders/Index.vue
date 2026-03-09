<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
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
import {
    Eye,
    Pencil,
    Trash2,
    Plus,
    ArrowUp,
    ArrowDown,
    ClipboardList,
    User,
    Truck,
    Warehouse,
} from 'lucide-vue-next';

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
    router.get(
        '/orders',
        { sort: field, direction: newDirection },
        { replace: true },
    );
};

const deleteOrder = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا الطلب؟')) {
        form.delete(`/orders/${id}`);
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('ar-SA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};

const formatAmount = (amount: number) => {
    return new Intl.NumberFormat('ar-SA', {
        style: 'currency',
        currency: 'SDG',
    }).format(amount);
};

const SortableHeader = (field: string, label: string) => {
    const isActive = props.sort === field;
    return { isActive, label };
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" sidebar_open="false">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
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

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead
                                class="cursor-pointer font-semibold"
                                @click="sortBy('order_number')"
                            >
                                <div class="flex items-center gap-2">
                                    رقم الطلب
                                    <template v-if="sort === 'order_number'">
                                        <ArrowUp
                                            v-if="direction === 'asc'"
                                            class="h-4 w-4"
                                        />
                                        <ArrowDown v-else class="h-4 w-4" />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer font-semibold"
                                @click="sortBy('date')"
                            >
                                <div class="flex items-center gap-2">
                                    التاريخ
                                    <template v-if="sort === 'date'">
                                        <ArrowUp
                                            v-if="direction === 'asc'"
                                            class="h-4 w-4"
                                        />
                                        <ArrowDown v-else class="h-4 w-4" />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead class="font-semibold">العميل</TableHead>
                            <TableHead class="font-semibold">السائق</TableHead>
                            <TableHead class="font-semibold"
                                >المستودع</TableHead
                            >
                            <TableHead
                                class="cursor-pointer font-semibold"
                                @click="sortBy('car_number')"
                            >
                                <div class="flex items-center gap-2">
                                    رقم السيارة
                                    <template v-if="sort === 'car_number'">
                                        <ArrowUp
                                            v-if="direction === 'asc'"
                                            class="h-4 w-4"
                                        />
                                        <ArrowDown v-else class="h-4 w-4" />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead
                                class="cursor-pointer font-semibold"
                                @click="sortBy('amount')"
                            >
                                <div class="flex items-center gap-2">
                                    المبلغ
                                    <template v-if="sort === 'amount'">
                                        <ArrowUp
                                            v-if="direction === 'asc'"
                                            class="h-4 w-4"
                                        />
                                        <ArrowDown v-else class="h-4 w-4" />
                                    </template>
                                </div>
                            </TableHead>
                            <TableHead class="font-semibold">الشركة</TableHead>
                            <TableHead class="font-semibold">الوجهة</TableHead>
                            <TableHead class="text-left font-semibold"
                                >الإجراءات</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="order in props.orders"
                            :key="order.id"
                            class="hover:bg-muted/50"
                        >
                            <TableCell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <ClipboardList
                                            class="h-4 w-4 text-primary"
                                        />
                                    </div>
                                    {{ order.order_number }}
                                </div>
                            </TableCell>
                            <TableCell>{{ formatDate(order.date) }}</TableCell>
                            <TableCell>
                                <div
                                    v-if="order.customer"
                                    class="flex items-center gap-2"
                                >
                                    <User
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ order.customer.name }}
                                </div>
                                <span v-else class="text-muted-foreground"
                                    >-</span
                                >
                            </TableCell>
                            <TableCell>
                                <div class="flex items-center gap-2">
                                    <Truck
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ order.driver.name }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    v-if="order.warehouse"
                                    class="flex items-center gap-2"
                                >
                                    <Warehouse
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ order.warehouse.name }}
                                </div>
                                <span v-else class="text-muted-foreground"
                                    >-</span
                                >
                            </TableCell>
                            <TableCell>{{ order.car_number }}</TableCell>
                            <TableCell class="font-medium">
                                {{ formatAmount(order.amount) }}
                            </TableCell>
                            <TableCell>{{ order.company || '-' }}</TableCell>
                            <TableCell>{{
                                order.destination || '-'
                            }}</TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link :href="`/orders/${order.id}`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="عرض"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link :href="`/orders/${order.id}/edit`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="تعديل"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="text-destructive hover:text-destructive"
                                        title="حذف"
                                        @click="deleteOrder(order.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
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
