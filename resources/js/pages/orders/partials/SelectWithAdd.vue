<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
import { Plus } from 'lucide-vue-next';

interface Option {
    id: number;
    name: string;
}

interface Props {
    modelValue: string | number | null;
    label: string;
    options: Option[];
    placeholder?: string;
    required?: boolean;
    createEndpoint: string;
    createFields: Record<string, any>;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'اختر',
    required: false,
});

const emit = defineEmits<{
    'update:modelValue': [value: string | number | null];
}>();

const dialogOpen = ref(false);

const form = useForm({ ...props.createFields });

const handleSelect = (value: string | number | null) => {
    emit('update:modelValue', value);
};

const save = () => {
    form.post(props.createEndpoint, {
        onSuccess: () => {
            dialogOpen.value = false;
        },
    });
};
</script>

<template>
    <div>
        <Label
            >{{ label }}
            <span v-if="required" class="text-destructive">*</span></Label
        >
        <div class="mt-1.5 flex items-center gap-2">
            <select
                :value="modelValue"
                @change="
                    handleSelect(($event.target as HTMLSelectElement).value)
                "
                class="flex h-10 flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                :required="required"
            >
                <option value="">{{ placeholder }}</option>
                <option
                    v-for="option in options"
                    :key="option.id"
                    :value="option.id"
                >
                    {{ option.name }}
                </option>
            </select>
            <Dialog v-model:open="dialogOpen">
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
                        <DialogTitle>إضافة جديد</DialogTitle>
                        <DialogDescription
                            >أدخل البيانات المطلوبة</DialogDescription
                        >
                    </DialogHeader>
                    <form @submit.prevent="save" class="space-y-4">
                        <slot name="form" :form="form" />
                        <DialogFooter>
                            <DialogClose as-child>
                                <Button variant="outline" type="button"
                                    >إلغاء</Button
                                >
                            </DialogClose>
                            <Button type="submit" :disabled="form.processing"
                                >حفظ</Button
                            >
                        </DialogFooter>
                    </form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
