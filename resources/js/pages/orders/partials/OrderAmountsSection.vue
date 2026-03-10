<script setup lang="ts">
import { computed, watch } from 'vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

interface Props {
    form: Record<string, any>;
}

const props = defineProps<Props>();

const amountFields = [
    'gasoline',
    'benzin',
    'fleet',
    'transit',
    'window_fee',
    'manfisto',
    'freightage',
    'tax',
    'commission',
];

const parseNumber = (value: any): number => {
    if (!value) return 0;
    const num = parseFloat(value.toString().replace(/,/g, ''));
    return isNaN(num) ? 0 : num;
};

const calculatedAmount = computed(() => {
    const fleet = parseNumber(props.form.fleet);
    const transit = parseNumber(props.form.transit);
    const windowFee = parseNumber(props.form.window_fee);
    const manfisto = parseNumber(props.form.manfisto);
    const tax = parseNumber(props.form.tax);
    const commission = parseNumber(props.form.commission);

    return fleet + transit + windowFee + manfisto + tax + commission;
});

const formattedAmount = computed(() => {
    const value = calculatedAmount.value;
    if (value === 0) return '';
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(value);
});

watch(calculatedAmount, (newValue) => {
    props.form.amount = formattedAmount.value;
});

const handleBlur = (field: string, event: Event) => {
    const target = event.target as HTMLInputElement;
    let value = target.value.replace(/,/g, '');
    if (value) {
        const num = parseFloat(value);
        if (!isNaN(num)) {
            props.form[field] = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(num);
        }
    } else {
        props.form[field] = '';
    }
};

const handleFocus = (field: string, event: Event) => {
    const target = event.target as HTMLInputElement;
    if (props.form[field]) {
        target.value = props.form[field].toString().replace(/,/g, '');
    }
};

const fields = [
    { key: 'gasoline', label: 'الجاز' },
    { key: 'benzin', label: 'البنزين' },
    { key: 'fleet', label: 'الطوف' },
    { key: 'transit', label: 'العبور' },
    { key: 'window_fee', label: 'رسوم النافذة' },
    { key: 'manfisto', label: 'المنفيستو' },
    { key: 'freightage', label: 'النولون' },
    { key: 'tax', label: 'الضريبة' },
    { key: 'commission', label: 'العمولة' },
    { key: 'amount', label: 'المبلغ الإجمالي', readonly: true },
];
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>المبالغ</CardTitle>
            <CardDescription>تفاصيل المبالغ والرسوم</CardDescription>
        </CardHeader>
        <CardContent class="space-y-4">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                <div v-for="field in fields" :key="field.key">
                    <Label :for="field.key">
                        {{ field.label }}
                        <span
                            v-if="field.readonly"
                            class="text-muted-foreground"
                            >(محسوب)</span
                        >
                    </Label>
                    <Input
                        :id="field.key"
                        v-model="props.form[field.key]"
                        @blur="handleBlur(field.key, $event)"
                        @focus="handleFocus(field.key, $event)"
                        type="text"
                        class="mt-1.5"
                        :class="{
                            'border-red-500': props.form.errors[field.key],
                            'bg-muted': field.readonly,
                        }"
                        placeholder="0.00"
                        :disabled="field.readonly"
                    />
                    <p
                        v-if="props.form.errors[field.key]"
                        class="mt-1 text-sm text-red-500"
                    >
                        {{ props.form.errors[field.key] }}
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
