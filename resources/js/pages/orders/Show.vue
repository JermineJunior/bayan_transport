<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import {
    ArrowLeft,
    Pencil,
    ClipboardList,
    User,
    DollarSign,
    Plus,
    Trash2,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Payment {
    id: number;
    amount_paid: number;
    date: string;
    notes: string | null;
}

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
    payments: Payment[];
    created_at: string;
    updated_at: string;
}

interface Props {
    order: Order;
}

const props = defineProps<Props>();
// calculate the order total paid
const totalPaid = computed(() => {
    if (!props.order.payments || !Array.isArray(props.order.payments)) {
        return 0;
    }
    return props.order.payments.reduce(
        (sum, p) => sum + Number(p.amount_paid || 0),
        0,
    );
});

const remaining = computed(() => {
    const amount = Number(props.order.amount) ?? 0;
    return amount - totalPaid.value;
});

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

const deleteDialogOpen = ref(false);
const paymentToDelete = ref<number | null>(null);

const confirmDelete = (paymentId: number) => {
    paymentToDelete.value = paymentId;
    deleteDialogOpen.value = true;
};

const deletePayment = () => {
    if (paymentToDelete.value) {
        router.delete(`/payments/${paymentToDelete.value}`);
        deleteDialogOpen.value = false;
        paymentToDelete.value = null;
    }
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
                                <div class="text-sm text-primary">المبلغ</div>
                                <div class="text-2xl font-bold text-primary">
                                    {{ formatAmount(props.order.amount) }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle class="flex items-center gap-2">
                                <DollarSign class="h-5 w-5" />
                                المدفوعات
                            </CardTitle>
                            <div
                                v-if="remaining <= 0"
                                class="rounded-md bg-green-100 px-3 py-1.5 text-sm font-medium text-green-700"
                            >
                                مخلص
                            </div>
                            <Link
                                v-else
                                :href="`/payments/create/${props.order.id}`"
                            >
                                <Button size="sm" class="gap-1">
                                    <Plus class="h-4 w-4" />
                                    إضافة دفعة
                                </Button>
                            </Link>
                        </div>
                        <CardDescription>
                            متابعة المدفوعات والمتبقي للطلب
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-4 md:grid-cols-3">
                            <div class="rounded-lg border bg-muted/50 p-4">
                                <div class="text-sm text-muted-foreground">
                                    المبلغ الإجمالي
                                </div>
                                <div class="text-xl font-bold">
                                    {{ formatAmount(props.order.amount) }}
                                </div>
                            </div>
                            <div class="rounded-lg border bg-green-50 p-4">
                                <div class="text-sm text-green-600">
                                    المدفوع
                                </div>
                                <div class="text-xl font-bold text-green-700">
                                    {{ formatAmount(totalPaid) }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg border p-4"
                                :class="
                                    remaining > 0 ? 'bg-red-50' : 'bg-green-50'
                                "
                            >
                                <div
                                    class="text-sm"
                                    :class="
                                        remaining > 0
                                            ? 'text-red-600'
                                            : 'text-green-600'
                                    "
                                >
                                    المتبقي
                                </div>
                                <div
                                    class="text-xl font-bold"
                                    :class="
                                        remaining > 0
                                            ? 'text-red-700'
                                            : 'text-green-700'
                                    "
                                >
                                    {{ formatAmount(remaining) }}
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                props.order.payments &&
                                props.order.payments.length > 0
                            "
                            class="mt-4"
                        >
                            <h4 class="mb-3 font-medium">سجل المدفوعات</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b text-right">
                                            <th class="pb-2 font-medium">
                                                التاريخ
                                            </th>
                                            <th class="pb-2 font-medium">
                                                المبلغ
                                            </th>
                                            <th class="pb-2 font-medium">
                                                ملاحظات
                                            </th>
                                            <th class="w-24 pb-2 font-medium">
                                                إجراءات
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="payment in props.order
                                                .payments"
                                            :key="payment.id"
                                            class="border-b"
                                        >
                                            <td class="py-2">
                                                {{ formatDate(payment.date) }}
                                            </td>
                                            <td
                                                class="py-2 font-medium text-green-600"
                                            >
                                                {{
                                                    formatAmount(
                                                        payment.amount_paid,
                                                    )
                                                }}
                                            </td>
                                            <td
                                                class="py-2 text-muted-foreground"
                                            >
                                                {{ payment.notes || '-' }}
                                            </td>
                                            <td class="py-2">
                                                <div class="flex gap-1">
                                                    <Link
                                                        :href="`/payments/${payment.id}/edit`"
                                                    >
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            class="h-8 w-8"
                                                        >
                                                            <Pencil
                                                                class="h-4 w-4"
                                                            />
                                                        </Button>
                                                    </Link>
                                                    <Button
                                                        variant="ghost"
                                                        size="icon"
                                                        class="h-8 w-8 text-red-500 hover:text-red-600"
                                                        @click="
                                                            confirmDelete(
                                                                payment.id,
                                                            )
                                                        "
                                                    >
                                                        <Trash2
                                                            class="h-4 w-4"
                                                        />
                                                    </Button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div
                            v-else
                            class="mt-4 text-center text-muted-foreground"
                        >
                            لا توجد دفعات مسجلة
                        </div>
                    </CardContent>
                </Card>
            </div>

            <Dialog v-model:open="deleteDialogOpen">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>حذف الدفعة</DialogTitle>
                        <DialogDescription>
                            هل أنت متأكد من حذف هذه الدفعة؟ لا يمكن التراجع عن
                            هذا الإجراء.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button
                            variant="outline"
                            @click="deleteDialogOpen = false"
                        >
                            إلغاء
                        </Button>
                        <Button variant="destructive" @click="deletePayment">
                            حذف
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
