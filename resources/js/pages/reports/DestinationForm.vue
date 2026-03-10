<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { FileText, Calendar, MapPin } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'التقارير', href: '/reports/destination' },
    { title: 'تقرير وجهة', href: '/reports/destination' },
];

const destination = ref('');
const startDate = ref('');
const endDate = ref('');

const generateReport = () => {
    const params: Record<string, string> = {
        destination: destination.value,
    };

    if (startDate.value) {
        params.start_date = startDate.value;
    }
    if (endDate.value) {
        params.end_date = endDate.value;
    }

    router.get('/reports/destination/generate', params);
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
                            تقرير الوجهة
                        </h3>
                    </div>
                    <p class="text-sm text-muted-foreground">
                        عرض الطلبات المتجهة إلى وجهة معينة
                    </p>
                </div>
                <div class="p-6 pt-0">
                    <form @submit.prevent="generateReport" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="destination">الوجهة</Label>
                            <div class="relative">
                                <MapPin
                                    class="absolute start-3 top-3 h-4 w-4 text-muted-foreground"
                                />
                                <Input
                                    id="destination"
                                    v-model="destination"
                                    type="text"
                                    placeholder="أدخل اسم الوجهة"
                                    class="ps-9"
                                    required
                                />
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
