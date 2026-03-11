<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref } from 'vue';
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
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Option {
    id: number;
    name: string;
}

interface Props {
    form: Record<string, any>;
    customers: Option[];
    drivers: Option[];
    warehouses: Option[];
}

const props = defineProps<Props>();

const customerDialogOpen = ref(false);
const driverDialogOpen = ref(false);
const warehouseDialogOpen = ref(false);

const customerForm = useForm({
    name: '',
    phone: '',
    redirect_to: 'order_create',
});
const driverForm = useForm({
    name: '',
    phone: '',
    redirect_to: 'order_create',
});
const warehouseForm = useForm({
    name: '',
    is_active: true,
    redirect_to: 'order_create',
});

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
    <Card>
        <CardHeader>
            <CardTitle>الاطراف والمستودعات</CardTitle>
            <CardDescription>معلومات العميل والسائق والمستودع</CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Customer -->
                <div>
                    <Label>العميل</Label>
                    <div class="mt-1.5 flex items-center gap-2">
                        <select
                            v-model="props.form.customer_id"
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
                                    <DialogTitle>إضافة عميل جديد</DialogTitle>
                                    <DialogDescription
                                        >أدخل بيانات العميل
                                        الجديد</DialogDescription
                                    >
                                </DialogHeader>
                                <form
                                    @submit.prevent="saveCustomer"
                                    class="space-y-4"
                                >
                                    <div>
                                        <Label for="customer_name">الاسم</Label>
                                        <Input
                                            id="customer_name"
                                            v-model="customerForm.name"
                                            required
                                            class="mt-1.5"
                                            placeholder="أدخل اسم العميل"
                                        />
                                        <p
                                            v-if="customerForm.errors.name"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ customerForm.errors.name }}
                                        </p>
                                    </div>
                                    <div>
                                        <Label for="customer_phone"
                                            >رقم الهاتف</Label
                                        >
                                        <Input
                                            id="customer_phone"
                                            v-model="customerForm.phone"
                                            class="mt-1.5"
                                            placeholder="أدخل رقم الهاتف"
                                        />
                                    </div>
                                    <input
                                        type="hidden"
                                        v-model="customerForm.redirect_to"
                                    />
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button
                                                variant="outline"
                                                type="button"
                                                >إلغاء</Button
                                            >
                                        </DialogClose>
                                        <Button type="submit">حفظ</Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <!-- Driver -->
                <div>
                    <Label
                        >السائق <span class="text-destructive">*</span></Label
                    >
                    <div class="mt-1.5 flex items-center gap-2">
                        <select
                            v-model="props.form.driver_id"
                            class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                            required
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
                                    <DialogTitle>إضافة سائق جديد</DialogTitle>
                                    <DialogDescription
                                        >أدخل بيانات السائق
                                        الجديد</DialogDescription
                                    >
                                </DialogHeader>
                                <form
                                    @submit.prevent="saveDriver"
                                    class="space-y-4"
                                >
                                    <div>
                                        <Label for="driver_name">الاسم</Label>
                                        <Input
                                            id="driver_name"
                                            v-model="driverForm.name"
                                            required
                                            class="mt-1.5"
                                            placeholder="أدخل اسم السائق"
                                        />
                                        <p
                                            v-if="driverForm.errors.name"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ driverForm.errors.name }}
                                        </p>
                                    </div>
                                    <div>
                                        <Label for="driver_phone"
                                            >رقم الهاتف</Label
                                        >
                                        <Input
                                            id="driver_phone"
                                            v-model="driverForm.phone"
                                            class="mt-1.5"
                                            placeholder="أدخل رقم الهاتف"
                                        />
                                    </div>
                                    <input
                                        type="hidden"
                                        v-model="driverForm.redirect_to"
                                    />
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button
                                                variant="outline"
                                                type="button"
                                                >إلغاء</Button
                                            >
                                        </DialogClose>
                                        <Button type="submit">حفظ</Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                    <p
                        v-if="props.form.errors.driver_id"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ props.form.errors.driver_id }}
                    </p>
                </div>

                <!-- Warehouse -->
                <div>
                    <Label>المستودع</Label>
                    <div class="mt-1.5 flex items-center gap-2">
                        <select
                            v-model="props.form.warehouse_id"
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
                                    <DialogTitle>إضافة مستودع جديد</DialogTitle>
                                    <DialogDescription
                                        >أدخل بيانات المستودع
                                        الجديد</DialogDescription
                                    >
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
                                            v-model="warehouseForm.name"
                                            required
                                            class="mt-1.5"
                                            placeholder="أدخل اسم المستودع"
                                        />
                                        <p
                                            v-if="warehouseForm.errors.name"
                                            class="mt-1 text-sm text-red-500"
                                        >
                                            {{ warehouseForm.errors.name }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="checkbox"
                                            id="warehouse_active"
                                            v-model="warehouseForm.is_active"
                                            class="h-4 w-4"
                                        />
                                        <Label
                                            for="warehouse_active"
                                            class="cursor-pointer"
                                            >نشط</Label
                                        >
                                    </div>
                                    <input
                                        type="hidden"
                                        v-model="warehouseForm.redirect_to"
                                    />
                                    <DialogFooter>
                                        <DialogClose as-child>
                                            <Button
                                                variant="outline"
                                                type="button"
                                                >إلغاء</Button
                                            >
                                        </DialogClose>
                                        <Button type="submit">حفظ</Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
