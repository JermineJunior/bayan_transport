<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { FileText, Calendar, User } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Customer {
    id: number;
    name: string;
}

interface Props {
    customers: Customer[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'التقارير', href: '/reports/customer' },
    { title: 'تقرير العملاء', href: '/reports/customer' },
];

const customerId = ref<number | null>(null);
const startDate = ref(new Date().toISOString().split('T')[0]);
const endDate = ref(new Date().toISOString().split('T')[0]);

const generateReport = () => {
    const params: Record<string, string | number | null> = {
        customer_id: customerId.value,
    };

    if (startDate.value) {
        params.start_date = startDate.value;
    }
    if (endDate.value) {
        params.end_date = endDate.value;
    }

    router.get('/reports/customer/generate', params);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl py-6">
            <div
                class="rounded-lg border bg-card text-card-foreground shadow-sm"
            >
                <div class="flex flex-col space-y-1.5 p-6">
                    <div class="flex items-center gap-2">
                        <FileText class="h-5 w-5 text-primary" />
                        <h3
                            class="text-xl leading-none font-semibold tracking-tight"
                        >
                            تقرير العملاء
                        </h3>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        عرض طلبات عميل معين خلال فترة زمنية
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <form @submit.prevent="generateReport" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="customer_id">العميل</Label>
                            <div class="relative">
                                <User
                                    class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                />
                                <select
                                    id="customer_id"
                                    v-model="customerId"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 ps-9 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="" disabled>
                                        اختر العميل
                                    </option>
                                    <option
                                        v-for="customer in customers"
                                        :key="customer.id"
                                        :value="customer.id"
                                    >
                                        {{ customer.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="start_date">من تاريخ</Label>
                                <div class="relative">
                                    <Calendar
                                        class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                    />
                                    <Input
                                        id="start_date"
                                        v-model="startDate"
                                        type="date"
                                        class="ps-9"
                                    />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="end_date">إلى تاريخ</Label>
                                <div class="relative">
                                    <Calendar
                                        class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                    />
                                    <Input
                                        id="end_date"
                                        v-model="endDate"
                                        type="date"
                                        class="ps-9"
                                    />
                                </div>
                            </div>
                        </div>

                        <Button type="submit" class="w-full">
                            عرض التقرير
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
