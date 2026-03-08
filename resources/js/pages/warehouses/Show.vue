<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    Warehouse,
    Calendar,
    Pencil,
    Trash2,
} from 'lucide-vue-next';

interface Warehouse {
    id: number;
    name: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    warehouse: Warehouse;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستودعات', href: '/warehouses' },
    { title: props.warehouse.name, href: `/warehouses/${props.warehouse.id}` },
];

const form = useForm({});

const deleteWarehouse = () => {
    if (confirm('هل أنت متأكد من حذف هذا المستودع؟')) {
        form.delete(`/warehouses/${props.warehouse.id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10"
                    >
                        <Warehouse class="h-8 w-8 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ warehouse.name }}</h1>
                        <p class="text-sm text-muted-foreground">
                            تفاصيل المستودع
                        </p>
                    </div>
                </div>
                <Link href="/warehouses">
                    <Button variant="outline" class="gap-2">
                        <ArrowRight class="h-4 w-4" />
                        العودة للمستودعات
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-1">
                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <Warehouse class="h-4 w-4" />
                            معلومات المستودع
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    الاسم
                                </p>
                                <p class="font-medium">{{ warehouse.name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    الحالة
                                </p>
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 md:col-span-2">
                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <Calendar class="h-4 w-4" />
                            معلومات الحساب
                        </h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    تاريخ الإنشاء
                                </p>
                                <p class="font-medium">
                                    {{
                                        new Date(
                                            warehouse.created_at,
                                        ).toLocaleDateString('en-SA')
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    آخر تحديث
                                </p>
                                <p class="font-medium">
                                    {{
                                        new Date(
                                            warehouse.updated_at,
                                        ).toLocaleDateString('en-SA')
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
