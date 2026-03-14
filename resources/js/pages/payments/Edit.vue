<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { ArrowLeft, DollarSign, Calendar, Save } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Order {
    id: number;
    order_number: string;
    date: string;
    amount: number;
    customer: { id: number; name: string } | null;
    driver: { id: number; name: string } | null;
}

interface Payment {
    id: number;
    amount_paid: number;
    date: string;
    notes: string | null;
    created_at: string;
    order_id: number;
}

interface Props {
    payment: Payment;
    order: Order;
    remaining: number;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'الطلبات', href: '/orders' },
    { title: props.order.order_number, href: `/orders/${props.order.id}` },
    { title: 'تعديل دفعة', href: `/payments/${props.payment.id}/edit` },
];

const amount = ref<number>(props.payment.amount_paid);
const date = ref(props.payment.date);
const notes = ref(props.payment.notes || '');
const isSubmitting = ref(false);

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

const submitPayment = () => {
    if (!amount.value || amount.value <= 0) return;

    isSubmitting.value = true;
    router.put(`/payments/${props.payment.id}`, {
        amount_paid: amount.value,
        date: date.value,
        notes: notes.value || null,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" :sidebar-open="false">
        <div class="space-y-6 p-6">
            <div class="flex items-center gap-4">
                <Link :href="`/orders/${order.id}`">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">تعديل دفعة</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        طلب رقم: {{ order.order_number }}
                    </p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="rounded-lg border bg-card p-6">
                    <h3 class="mb-4 text-lg font-semibold">معلومات الطلب</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">العميل</span>
                            <span class="font-medium">{{
                                order.customer?.name || '-'
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">السائق</span>
                            <span class="font-medium">{{
                                order.driver?.name || '-'
                            }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">التاريخ</span>
                            <span class="font-medium">{{
                                formatDate(order.date)
                            }}</span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-muted-foreground"
                                >المبلغ الإجمالي</span
                            >
                            <span class="text-xl font-bold text-primary">
                                {{ formatAmount(order.amount) }}
                            </span>
                        </div>
                        <div class="flex justify-between border-t pt-3">
                            <span class="text-muted-foreground">المتبقي</span>
                            <span
                                class="text-xl font-bold"
                                :class="
                                    remaining > 0
                                        ? 'text-red-600'
                                        : 'text-green-600'
                                "
                            >
                                {{ formatAmount(remaining) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border bg-card p-6">
                    <h3 class="mb-4 text-lg font-semibold">تعديل الدفعة</h3>
                    <form @submit.prevent="submitPayment" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="amount">المبلغ</Label>
                            <div class="relative">
                                <DollarSign
                                    class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                />
                                <Input
                                    id="amount"
                                    v-model.number="amount"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    :max="remaining + payment.amount_paid"
                                    placeholder="أدخل المبلغ"
                                    class="ps-9"
                                    required
                                />
                            </div>
                            <p class="text-xs text-muted-foreground">
                                الحد الأقصى:
                                {{
                                    formatAmount(
                                        remaining + payment.amount_paid,
                                    )
                                }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="date">التاريخ</Label>
                            <div class="relative">
                                <Calendar
                                    class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                />
                                <Input
                                    id="date"
                                    v-model="date"
                                    type="date"
                                    class="ps-9"
                                    required
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="notes">ملاحظات</Label>
                            <textarea
                                id="notes"
                                v-model="notes"
                                placeholder="ملاحظات اختيارية"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            ></textarea>
                        </div>

                        <Button
                            type="submit"
                            class="w-full"
                            :disabled="isSubmitting || !amount"
                        >
                            <Save class="me-2 h-4 w-4" />
                            حفظ التعديلات
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
