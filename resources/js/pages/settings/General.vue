<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    settings: {
        id: number;
        name: string;
        location: string | null;
        phone: string | null;
        phone_2: string | null;
        email: string | null;
        logo: string | null;
        note: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'الإعدادات',
        href: '/settings/general',
    },
];

const form = useForm({
    _method: 'PUT',
    name: props.settings.name || '',
    location: props.settings.location || '',
    phone: props.settings.phone || '',
    phone_2: props.settings.phone_2 || '',
    email: props.settings.email || '',
    note: props.settings.note || '',
    logo: null as File | null,
});

function submit() {
    form.post('/settings/general', {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="الإعدادات العامة" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-2xl p-6">
            <h1 class="mb-6 text-2xl font-bold">الإعدادات العامة</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="name">اسم الشركة</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="location">الموقع</Label>
                        <Input
                            id="location"
                            v-model="form.location"
                            type="text"
                        />
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <Label for="phone">الهاتف</Label>
                        <Input id="phone" v-model="form.phone" type="text" />
                    </div>

                    <div class="space-y-2">
                        <Label for="phone_2">الهاتف 2</Label>
                        <Input
                            id="phone_2"
                            v-model="form.phone_2"
                            type="text"
                        />
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="email">البريد الإلكتروني</Label>
                    <Input id="email" v-model="form.email" type="email" />
                </div>

                <div class="space-y-2">
                    <Label for="logo">الشعار</Label>
                    <Input
                        id="logo"
                        type="file"
                        accept="image/*"
                        @change="
                            (e) =>
                                (form.logo =
                                    (e.target as HTMLInputElement).files?.[0] ||
                                    null)
                        "
                    />
                    <p v-if="settings.logo" class="text-sm text-gray-500">
                        الشعار الحالي: {{ settings.logo }}
                    </p>
                </div>

                <div class="space-y-2">
                    <Label for="note">ملاحظات</Label>
                    <textarea
                        id="note"
                        v-model="form.note"
                        rows="4"
                        class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="الملاحظة ستظهر في التقارير"
                    ></textarea>
                </div>

                <Button type="submit" :disabled="form.processing">
                    <Spinner v-if="form.processing" />
                    حفظ الإعدادات
                </Button>
            </form>
        </div>
    </AppLayout>
</template>
