<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
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
import Pagination from '@/components/ui/pagination/Pagination.vue';
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
import {
    Eye,
    Pencil,
    Trash2,
    Plus,
    Warehouse,
    ToggleRight,
} from 'lucide-vue-next';

interface Warehouse {
    id: number;
    name: string;
    is_active: boolean;
}

interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    warehouses: {
        data: Warehouse[];
        links: Link[];
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        total: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستودعات', href: '/warehouses' },
];

const isOpen = ref(false);
const editingWarehouse = ref<Warehouse | null>(null);

const form = useForm({
    name: '',
    is_active: true,
});

const openCreate = () => {
    editingWarehouse.value = null;
    form.name = '';
    form.is_active = true;
    isOpen.value = true;
};

const openEdit = (warehouse: Warehouse) => {
    editingWarehouse.value = warehouse;
    form.name = warehouse.name;
    form.is_active = warehouse.is_active;
    isOpen.value = true;
};

const submit = () => {
    if (editingWarehouse.value) {
        form.put(`/warehouses/${editingWarehouse.value.id}`, {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    } else {
        form.post('/warehouses', {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    }
};

const deleteWarehouse = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا المستودع؟')) {
        form.delete(`/warehouses/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">المستودعات</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        إدارة بيانات المستودعات
                    </p>
                </div>
                <Dialog v-model:open="isOpen">
                    <DialogTrigger as-child>
                        <Button @click="openCreate" class="gap-2">
                            <Plus class="h-4 w-4" />
                            إضافة مستودع
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="max-h-[85vh] max-w-md">
                        <DialogHeader>
                            <DialogTitle>
                                {{
                                    editingWarehouse
                                        ? 'تعديل المستودع'
                                        : 'إضافة مستودع جديد'
                                }}
                            </DialogTitle>
                            <DialogDescription>
                                {{
                                    editingWarehouse
                                        ? 'تعديل بيانات المستودع'
                                        : 'إنشاء مستودع جديد'
                                }}
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="submit" class="space-y-4 py-4">
                            <div>
                                <Label for="name">الاسم</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    required
                                    class="mt-1.5"
                                    :class="{
                                        'border-red-500': form.errors.name,
                                    }"
                                    placeholder="أدخل اسم المستودع"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    v-model="form.is_active"
                                    class="h-4 w-4"
                                />
                                <Label for="is_active" class="cursor-pointer"
                                    >نشط</Label
                                >
                            </div>
                            <DialogFooter class="gap-2">
                                <DialogClose as-child>
                                    <Button variant="outline" type="button"
                                        >إلغاء</Button
                                    >
                                </DialogClose>
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="gap-2"
                                >
                                    {{ editingWarehouse ? 'تحديث' : 'إنشاء' }}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead class="font-semibold">الاسم</TableHead>
                            <TableHead class="font-semibold">الحالة</TableHead>
                            <TableHead class="text-right font-semibold"
                                >الإجراءات</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="warehouse in props.warehouses.data"
                            :key="warehouse.id"
                            class="hover:bg-muted/50"
                        >
                            <TableCell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <Warehouse
                                            class="h-4 w-4 text-primary"
                                        />
                                    </div>
                                    {{ warehouse.name }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <span
                                    :class="[
                                        'inline-flex items-center rounded px-2 py-0.5 text-xs font-medium',
                                        warehouse.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-gray-100 text-gray-800',
                                    ]"
                                >
                                    {{
                                        warehouse.is_active ? 'نشط' : 'غير نشط'
                                    }}
                                </span>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link :href="`/warehouses/${warehouse.id}`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="عرض"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        title="تعديل"
                                        @click="openEdit(warehouse)"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="text-destructive hover:text-destructive"
                                        title="حذف"
                                        @click="deleteWarehouse(warehouse.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Pagination
                    v-if="props.warehouses.links"
                    :links="props.warehouses.links"
                />
            </div>
        </div>
    </AppLayout>
</template>
