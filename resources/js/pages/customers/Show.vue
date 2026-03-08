<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import {
    ArrowRight,
    User,
    Phone,
    Calendar,
    Pencil,
    Trash2,
} from 'lucide-vue-next';

interface Customer {
    id: number;
    name: string;
    phone: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    customer: Customer;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'العملاء', href: '/customers' },
    { title: props.customer.name, href: `/customers/${props.customer.id}` },
];

const form = useForm({});

const deleteCustomer = () => {
    if (confirm('هل أنت متأكد من حذف هذا العميل؟')) {
        form.delete(`/customers/${props.customer.id}`);
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
                        <User class="h-8 w-8 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ customer.name }}</h1>
                        <p class="text-sm text-muted-foreground">
                            تفاصيل العميل
                        </p>
                    </div>
                </div>
                <Link href="/customers">
                    <Button variant="outline" class="gap-2">
                        <ArrowRight class="h-4 w-4" />
                        العودة للعملاء
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="space-y-6 md:col-span-1">
                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <User class="h-4 w-4" />
                            المعلومات الشخصية
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    الاسم
                                </p>
                                <p class="font-medium">{{ customer.name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-muted-foreground">
                                    رقم الهاتف
                                </p>
                                <p class="font-medium">
                                    {{ customer.phone || '-' }}
                                </p>
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
                                            customer.created_at,
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
                                            customer.updated_at,
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
