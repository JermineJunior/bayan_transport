<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Props {
    form: Record<string, any>;
}

const props = defineProps<Props>();
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>معلومات الطلب</CardTitle>
            <CardDescription>البيانات الأساسية للطلب</CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <Label for="date"
                        >التاريخ <span class="text-destructive">*</span></Label
                    >
                    <Input
                        id="date"
                        v-model="props.form.date"
                        type="date"
                        required
                        class="mt-1.5"
                        :class="{ 'border-red-500': props.form.errors.date }"
                    />
                    <p
                        v-if="props.form.errors.date"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ props.form.errors.date }}
                    </p>
                </div>
                <div>
                    <Label for="car_number"
                        >رقم السيارة
                        <span class="text-destructive">*</span></Label
                    >
                    <Input
                        id="car_number"
                        v-model="props.form.car_number"
                        required
                        class="mt-1.5"
                        :class="{
                            'border-red-500': props.form.errors.car_number,
                        }"
                        placeholder="أدخل رقم السيارة"
                    />
                    <p
                        v-if="props.form.errors.car_number"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ props.form.errors.car_number }}
                    </p>
                </div>
                <div>
                    <Label for="company">الشركة</Label>
                    <Input
                        id="company"
                        v-model="props.form.company"
                        class="mt-1.5"
                        placeholder="أدخل اسم الشركة"
                    />
                </div>
                <div>
                    <Label for="destination">الوجهة</Label>
                    <Input
                        id="destination"
                        v-model="props.form.destination"
                        class="mt-1.5"
                        placeholder="أدخل الوجهة"
                    />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
