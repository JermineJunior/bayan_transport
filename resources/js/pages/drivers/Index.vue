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
import { Eye, Pencil, Trash2, Plus, User, Phone } from 'lucide-vue-next';

interface Driver {
    id: number;
    name: string;
    phone: string | null;
}

interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    drivers: {
        data: Driver[];
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
    { title: 'السائقين', href: '/drivers' },
];

const isOpen = ref(false);
const editingDriver = ref<Driver | null>(null);

const form = useForm({
    name: '',
    phone: '',
});

const openCreate = () => {
    editingDriver.value = null;
    form.name = '';
    form.phone = '';
    isOpen.value = true;
};

const openEdit = (driver: Driver) => {
    editingDriver.value = driver;
    form.name = driver.name;
    form.phone = driver.phone || '';
    isOpen.value = true;
};

const submit = () => {
    if (editingDriver.value) {
        form.put(`/drivers/${editingDriver.value.id}`, {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    } else {
        form.post('/drivers', {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    }
};

const deleteDriver = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا السائق؟')) {
        form.delete(`/drivers/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">السائقين</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        إدارة بيانات السائقين
                    </p>
                </div>
                <Dialog v-model:open="isOpen">
                    <DialogTrigger as-child>
                        <Button @click="openCreate" class="gap-2">
                            <Plus class="h-4 w-4" />
                            إضافة سائق
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="max-h-[85vh] max-w-md">
                        <DialogHeader>
                            <DialogTitle>
                                {{
                                    editingDriver
                                        ? 'تعديل السائق'
                                        : 'إضافة سائق جديد'
                                }}
                            </DialogTitle>
                            <DialogDescription>
                                {{
                                    editingDriver
                                        ? 'تعديل بيانات السائق'
                                        : 'إنشاء سائق جديد'
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
                                    placeholder="أدخل اسم السائق"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div>
                                <Label for="phone">رقم الهاتف</Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    class="mt-1.5"
                                    :class="{
                                        'border-red-500': form.errors.phone,
                                    }"
                                    placeholder="أدخل رقم الهاتف"
                                />
                                <p
                                    v-if="form.errors.phone"
                                    class="mt-1 text-sm text-red-500"
                                >
                                    {{ form.errors.phone }}
                                </p>
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
                                    {{ editingDriver ? 'تحديث' : 'إنشاء' }}
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
                            <TableHead class="font-semibold"
                                >رقم الهاتف</TableHead
                            >
                            <TableHead class="text-left font-semibold"
                                >الإجراءات</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-if="props.drivers.data.length === 0">
                            <TableCell colspan="3" class="h-32 text-center">
                                <div
                                    class="flex flex-col items-center justify-center gap-2 text-muted-foreground"
                                >
                                    <User class="h-8 w-8" />
                                    <p>لا يوجد سائقين</p>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow
                            v-for="driver in props.drivers.data"
                            :key="driver.id"
                            class="hover:bg-muted/50"
                        >
                            <TableCell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <User class="h-4 w-4 text-primary" />
                                    </div>
                                    {{ driver.name }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Phone class="h-4 w-4" />
                                    {{ driver.phone || '-' }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link :href="`/drivers/${driver.id}`">
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
                                        @click="openEdit(driver)"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="text-destructive hover:text-destructive"
                                        title="حذف"
                                        @click="deleteDriver(driver.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Pagination
                    v-if="props.drivers.links"
                    :links="props.drivers.links"
                />
            </div>
        </div>
    </AppLayout>
</template>
