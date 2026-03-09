<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
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

const form = useForm({
    date: props.order.date,
    order_number: props.order.order_number,
    customer_id: props.order.customer_id ?? '',
    driver_id: props.order.driver_id ?? '',
    warehouse_id: props.order.warehouse_id ?? '',
    car_number: props.order.car_number,
    gasoline: props.order.gasoline ?? '',
    benzin: props.order.benzin ?? '',
    fleet: props.order.fleet ?? '',
    transit: props.order.transit ?? '',
    window_fee: props.order.window_fee ?? '',
    manfisto: props.order.manfisto ?? '',
    freightage: props.order.freightage ?? '',
    tax: props.order.tax ?? '',
    commission: props.order.commission ?? '',
    amount: props.order.amount,
    company: props.order.company ?? '',
    destination: props.order.destination ?? '',
});

const customerDialogOpen = ref(false);
const driverDialogOpen = ref(false);
const warehouseDialogOpen = ref(false);

const customerForm = useForm({ name: '', phone: '' });
const driverForm = useForm({ name: '', phone: '' });
const warehouseForm = useForm({ name: '', is_active: true });

const submit = () => {
    form.put(`/orders/${props.order.id}`, {
        onSuccess: () => {
            // Success
        },
    });
};

const saveCustomer = () => {
    customerForm.post('/customers', {
        onSuccess: () => {
            customerDialogOpen.value = false;
            window.location.reload();
        },
    });
};

const saveDriver = () => {
    driverForm.post('/drivers', {
        onSuccess: () => {
            driverDialogOpen.value = false;
            window.location.reload();
        },
    });
};

const saveWarehouse = () => {
    warehouseForm.post('/warehouses', {
        onSuccess: () => {
            warehouseDialogOpen.value = false;
            window.location.reload();
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
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
                                />
                            </div>
                            <div>
                                <Label for="car_number">رقم السيارة</Label>
                                <Input
                                    id="car_number"
                                    v-model="form.car_number"
                                    required
                                    class="mt-1.5"
                                    placeholder="أدخل رقم السيارة"
                                />
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
                                <div class="relative mt-1.5">
                                    <select
                                        id="customer_id"
                                        v-model="form.customer_id"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                                                variant="ghost"
                                                class="absolute top-1/2 left-8 -translate-y-1/2 text-muted-foreground hover:text-foreground"
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
                                <div class="relative mt-1.5">
                                    <select
                                        id="driver_id"
                                        v-model="form.driver_id"
                                        required
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                                                variant="ghost"
                                                class="absolute top-1/2 left-8 -translate-y-1/2 text-muted-foreground hover:text-foreground"
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
                                <div class="relative mt-1.5">
                                    <select
                                        id="warehouse_id"
                                        v-model="form.warehouse_id"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
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
                                                variant="ghost"
                                                class="absolute top-1/2 left-8 -translate-y-1/2 text-muted-foreground hover:text-foreground"
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
                                <Label for="gasoline">البنزين</Label>
                                <Input
                                    id="gasoline"
                                    v-model="form.gasoline"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="benzin">البنزين (2)</Label>
                                <Input
                                    id="benzin"
                                    v-model="form.benzin"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="fleet">الأسطول</Label>
                                <Input
                                    id="fleet"
                                    v-model="form.fleet"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="transit">الترانزيت</Label>
                                <Input
                                    id="transit"
                                    v-model="form.transit"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="window_fee">رسوم النافذة</Label>
                                <Input
                                    id="window_fee"
                                    v-model="form.window_fee"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="manfisto">المنفيستو</Label>
                                <Input
                                    id="manfisto"
                                    v-model="form.manfisto"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="freightage">النولون</Label>
                                <Input
                                    id="freightage"
                                    v-model="form.freightage"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="tax">الضريبة</Label>
                                <Input
                                    id="tax"
                                    v-model="form.tax"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="commission">العمولة</Label>
                                <Input
                                    id="commission"
                                    v-model="form.commission"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
                            </div>
                            <div>
                                <Label for="amount">المبلغ الإجمالي</Label>
                                <Input
                                    id="amount"
                                    v-model="form.amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    class="mt-1.5"
                                    placeholder="0.00"
                                />
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
