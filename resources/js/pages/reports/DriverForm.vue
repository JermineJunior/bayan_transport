<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { FileText, Calendar, Truck } from 'lucide-vue-next';

interface Driver {
    id: number;
    name: string;
}

interface Props {
    drivers: Driver[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'التقارير', href: '/reports/driver' },
    { title: 'تقرير السائقين', href: '/reports/driver' },
];

const driverId = ref<number | null>(null);
const startDate = ref(new Date().toISOString().split('T')[0]);
const endDate = ref(new Date().toISOString().split('T')[0]);

const generateReport = () => {
    const params: Record<string, string | number | null> = {
        driver_id: driverId.value,
    };

    if (startDate.value) {
        params.start_date = startDate.value;
    }
    if (endDate.value) {
        params.end_date = endDate.value;
    }

    router.get('/reports/driver/generate', params);
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
                            تقرير السائقين
                        </h3>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        عرض طلبات سائق معين خلال فترة زمنية
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <form @submit.prevent="generateReport" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="driver_id">السائق</Label>
                            <div class="relative">
                                <Truck
                                    class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                />
                                <select
                                    id="driver_id"
                                    v-model="driverId"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 ps-9 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="" disabled>
                                        اختر السائق
                                    </option>
                                    <option
                                        v-for="driver in drivers"
                                        :key="driver.id"
                                        :value="driver.id"
                                    >
                                        {{ driver.name }}
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
