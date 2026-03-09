<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { ArrowLeft, Plus } from 'lucide-vue-next';

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

const formatNumber = (value: number | null) => {
    if (value === null || value === undefined) return '';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
};

const formatDateForInput = (date: string) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().split('T')[0];
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

const customerDialogOpen = ref(false);
const driverDialogOpen = ref(false);
const warehouseDialogOpen = ref(false);

const customerForm = useForm({ name: '', phone: '', redirect_to: '' });
const driverForm = useForm({ name: '', phone: '', redirect_to: '' });
const warehouseForm = useForm({ name: '', is_active: true, redirect_to: '' });

const handleAmountBlur = (field: string, event: Event) => {
    const target = event.target as HTMLInputElement;
    let value = target.value.replace(/,/g, '');
    if (value) {
        const num = parseFloat(value);
        if (!isNaN(num)) {
            form[field] = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(num);
        }
    } else {
        form[field] = '';
    }
};

const handleAmountFocus = (field: string, event: Event) => {
    const target = event.target as HTMLInputElement;
    if (form[field]) {
        target.value = form[field].toString().replace(/,/g, '');
    }
};

const submit = () => {
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

    const processedData: Record<string, any> = {};

    Object.keys(form.data()).forEach((key) => {
        const value = form.data()[key];
        if (amountFields.includes(key) && value) {
            processedData[key] = parseFloat(value.toString().replace(/,/g, ''));
        } else {
            processedData[key] = value;
        }
    });

    form.transform((data) => processedData).put(`/orders/${props.order.id}`, {
        onSuccess: () => {
            // Success
        },
    });
};

const saveCustomer = () => {
    customerForm.post('/customers', {
        onSuccess: () => {
            customerDialogOpen.value = false;
        },
    });
};

const saveDriver = () => {
    driverForm.post('/drivers', {
        onSuccess: () => {
            driverDialogOpen.value = false;
        },
    });
};

const saveWarehouse = () => {
    warehouseForm.post('/warehouses', {
        onSuccess: () => {
            warehouseDialogOpen.value = false;
        },
    });
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
                <Card>
                    <CardHeader>
                        <CardTitle>معلومات الطلب</CardTitle>
                        <CardDescription>
                            البيانات الأساسية للطلب
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <Label for="date">التاريخ</Label>
                                <Input
                                    id="date"
                                    v-model="form.date"
                                    type="date"
                                    required
                                    class="mt-1.5"
                                    :class="{
                                        'border-red-500': form.errors.date,
                                    }"
                                />
                                <p
                                    v-if="form.errors.date"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.date }}
                                </p>
                            </div>
                            <div>
                                <Label for="car_number">رقم السيارة</Label>
                                <Input
                                    id="car_number"
                                    v-model="form.car_number"
                                    required
                                    class="mt-1.5"
                                    :class="{
                                        'border-red-500':
                                            form.errors.car_number,
                                    }"
                                    placeholder="أدخل رقم السيارة"
                                />
                                <p
                                    v-if="form.errors.car_number"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.car_number }}
                                </p>
                            </div>
                            <div>
                                <Label for="company">الشركة</Label>
                                <Input
                                    id="company"
                                    v-model="form.company"
                                    class="mt-1.5"
                                    placeholder="أدخل اسم الشركة"
                                />
                            </div>
                            <div>
                                <Label for="destination">الوجهة</Label>
                                <Input
                                    id="destination"
                                    v-model="form.destination"
                                    class="mt-1.5"
                                    placeholder="أدخل الوجهة"
                                />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>الطرافاء</CardTitle>
                        <CardDescription>
                            معلومات العميل والسائق والمستودع
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <Label for="customer_id">العميل</Label>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <select
                                        id="customer_id"
                                        v-model="form.customer_id"
                                        class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="">اختر العميل</option>
                                        <option
                                            v-for="customer in props.customers"
                                            :key="customer.id"
                                            :value="customer.id"
                                        >
                                            {{ customer.name }}
                                        </option>
                                    </select>
                                    <Dialog v-model:open="customerDialogOpen">
                                        <DialogTrigger as-child>
                                            <Button
                                                type="button"
                                                size="icon"
                                                variant="outline"
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle
                                                    >إضافة عميل
                                                    جديد</DialogTitle
                                                >
                                                <DialogDescription>
                                                    أدخل بيانات العميل الجديد
                                                </DialogDescription>
                                            </DialogHeader>
                                            <form
                                                @submit.prevent="saveCustomer"
                                                class="space-y-4"
                                            >
                                                <div>
                                                    <Label for="customer_name"
                                                        >الاسم</Label
                                                    >
                                                    <Input
                                                        id="customer_name"
                                                        v-model="
                                                            customerForm.name
                                                        "
                                                        required
                                                        class="mt-1.5"
                                                        placeholder="أدخل اسم العميل"
                                                    />
                                                </div>
                                                <div>
                                                    <Label for="customer_phone"
                                                        >رقم الهاتف</Label
                                                    >
                                                    <Input
                                                        id="customer_phone"
                                                        v-model="
                                                            customerForm.phone
                                                        "
                                                        class="mt-1.5"
                                                        placeholder="أدخل رقم الهاتف"
                                                    />
                                                </div>
                                                <DialogFooter>
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="outline"
                                                            type="button"
                                                            >إلغاء</Button
                                                        >
                                                    </DialogClose>
                                                    <Button type="submit"
                                                        >حفظ</Button
                                                    >
                                                </DialogFooter>
                                            </form>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </div>

                            <div>
                                <Label for="driver_id">السائق</Label>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <select
                                        id="driver_id"
                                        v-model="form.driver_id"
                                        required
                                        class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="">اختر السائق</option>
                                        <option
                                            v-for="driver in props.drivers"
                                            :key="driver.id"
                                            :value="driver.id"
                                        >
                                            {{ driver.name }}
                                        </option>
                                    </select>
                                    <Dialog v-model:open="driverDialogOpen">
                                        <DialogTrigger as-child>
                                            <Button
                                                type="button"
                                                size="icon"
                                                variant="outline"
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle
                                                    >إضافة سائق
                                                    جديد</DialogTitle
                                                >
                                                <DialogDescription>
                                                    أدخل بيانات السائق الجديد
                                                </DialogDescription>
                                            </DialogHeader>
                                            <form
                                                @submit.prevent="saveDriver"
                                                class="space-y-4"
                                            >
                                                <div>
                                                    <Label for="driver_name"
                                                        >الاسم</Label
                                                    >
                                                    <Input
                                                        id="driver_name"
                                                        v-model="
                                                            driverForm.name
                                                        "
                                                        required
                                                        class="mt-1.5"
                                                        placeholder="أدخل اسم السائق"
                                                    />
                                                </div>
                                                <div>
                                                    <Label for="driver_phone"
                                                        >رقم الهاتف</Label
                                                    >
                                                    <Input
                                                        id="driver_phone"
                                                        v-model="
                                                            driverForm.phone
                                                        "
                                                        class="mt-1.5"
                                                        placeholder="أدخل رقم الهاتف"
                                                    />
                                                </div>
                                                <DialogFooter>
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="outline"
                                                            type="button"
                                                            >إلغاء</Button
                                                        >
                                                    </DialogClose>
                                                    <Button type="submit"
                                                        >حفظ</Button
                                                    >
                                                </DialogFooter>
                                            </form>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </div>

                            <div>
                                <Label for="warehouse_id">المستودع</Label>
                                <div class="mt-1.5 flex items-center gap-2">
                                    <select
                                        id="warehouse_id"
                                        v-model="form.warehouse_id"
                                        class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        <option value="">اختر المستودع</option>
                                        <option
                                            v-for="warehouse in props.warehouses"
                                            :key="warehouse.id"
                                            :value="warehouse.id"
                                        >
                                            {{ warehouse.name }}
                                        </option>
                                    </select>
                                    <Dialog v-model:open="warehouseDialogOpen">
                                        <DialogTrigger as-child>
                                            <Button
                                                type="button"
                                                size="icon"
                                                variant="outline"
                                                class="h-10 w-10 flex-shrink-0"
                                            >
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                        </DialogTrigger>
                                        <DialogContent>
                                            <DialogHeader>
                                                <DialogTitle
                                                    >إضافة مستودع
                                                    جديد</DialogTitle
                                                >
                                                <DialogDescription>
                                                    أدخل بيانات المستودع الجديد
                                                </DialogDescription>
                                            </DialogHeader>
                                            <form
                                                @submit.prevent="saveWarehouse"
                                                class="space-y-4"
                                            >
                                                <div>
                                                    <Label for="warehouse_name"
                                                        >الاسم</Label
                                                    >
                                                    <Input
                                                        id="warehouse_name"
                                                        v-model="
                                                            warehouseForm.name
                                                        "
                                                        required
                                                        class="mt-1.5"
                                                        placeholder="أدخل اسم المستودع"
                                                    />
                                                </div>
                                                <div
                                                    class="flex items-center gap-2"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        id="warehouse_active"
                                                        v-model="
                                                            warehouseForm.is_active
                                                        "
                                                        class="h-4 w-4"
                                                    />
                                                    <Label
                                                        for="warehouse_active"
                                                        class="cursor-pointer"
                                                        >نشط</Label
                                                    >
                                                </div>
                                                <DialogFooter>
                                                    <DialogClose as-child>
                                                        <Button
                                                            variant="outline"
                                                            type="button"
                                                            >إلغاء</Button
                                                        >
                                                    </DialogClose>
                                                    <Button type="submit"
                                                        >حفظ</Button
                                                    >
                                                </DialogFooter>
                                            </form>
                                        </DialogContent>
                                    </Dialog>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>المبالغ</CardTitle>
                        <CardDescription>
                            تفاصيل المبالغ والرسوم
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div
                            class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <div>
                                <Label for="gasoline">الجاز</Label>
                                <Input
                                    id="gasoline"
                                    :model-value="form.gasoline"
                                    @blur="handleAmountBlur('gasoline', $event)"
                                    @focus="
                                        handleAmountFocus('gasoline', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="benzin">البنزين</Label>
                                <Input
                                    id="benzin"
                                    :model-value="form.benzin"
                                    @blur="handleAmountBlur('benzin', $event)"
                                    @focus="handleAmountFocus('benzin', $event)"
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="fleet">الطوف</Label>
                                <Input
                                    id="fleet"
                                    :model-value="form.fleet"
                                    @blur="handleAmountBlur('fleet', $event)"
                                    @focus="handleAmountFocus('fleet', $event)"
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="transit">العبور</Label>
                                <Input
                                    id="transit"
                                    :model-value="form.transit"
                                    @blur="handleAmountBlur('transit', $event)"
                                    @focus="
                                        handleAmountFocus('transit', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="window_fee">رسوم النافذة</Label>
                                <Input
                                    id="window_fee"
                                    :model-value="form.window_fee"
                                    @blur="
                                        handleAmountBlur('window_fee', $event)
                                    "
                                    @focus="
                                        handleAmountFocus('window_fee', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="manfisto">المنفيستو</Label>
                                <Input
                                    id="manfisto"
                                    :model-value="form.manfisto"
                                    @blur="handleAmountBlur('manfisto', $event)"
                                    @focus="
                                        handleAmountFocus('manfisto', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="freightage">النولون</Label>
                                <Input
                                    id="freightage"
                                    :model-value="form.freightage"
                                    @blur="
                                        handleAmountBlur('freightage', $event)
                                    "
                                    @focus="
                                        handleAmountFocus('freightage', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="tax">الضريبة</Label>
                                <Input
                                    id="tax"
                                    :model-value="form.tax"
                                    @blur="handleAmountBlur('tax', $event)"
                                    @focus="handleAmountFocus('tax', $event)"
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="commission">العمولة</Label>
                                <Input
                                    id="commission"
                                    :model-value="form.commission"
                                    @blur="
                                        handleAmountBlur('commission', $event)
                                    "
                                    @focus="
                                        handleAmountFocus('commission', $event)
                                    "
                                    type="text"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="amount">المبلغ الإجمالي</Label>
                                <Input
                                    id="amount"
                                    :model-value="form.amount"
                                    @blur="handleAmountBlur('amount', $event)"
                                    @focus="handleAmountFocus('amount', $event)"
                                    type="text"
                                    required
                                    class="mt-1.5"
                                    :class="{
                                        'border-red-500': form.errors.amount,
                                    }"
                                    placeholder="0.00"
                                />
                                <p
                                    v-if="form.errors.amount"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.amount }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

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
