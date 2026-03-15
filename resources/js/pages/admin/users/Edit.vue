<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    username: string;
    email: string;
}

interface Props {
    user: User;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستخدمون', href: '/admin/users' },
    { title: 'تعديل المستخدم', href: `/admin/users/${props.user.id}/edit` },
];

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.email,
});

const submit = () => {
    form.put(`/admin/users/${props.user.id}`);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center gap-4">
                <Link href="/admin/users">
                    <Button variant="outline" size="icon">
                        <ArrowLeft class="h-4 w-4" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">تعديل المستخدم</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ props.user.name }}
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="max-w-2xl space-y-6">
                <div class="rounded-lg border bg-card p-6">
                    <h3 class="mb-4 font-semibold">معلومات الحساب</h3>
                    <div class="space-y-4">
                        <div>
                            <Label for="name">الاسم</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                required
                                class="mt-1.5"
                                placeholder="أدخل اسم المستخدم"
                            />
                            <p
                                v-if="form.errors.name"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>
                        <div>
                            <Label for="username">اسم المستخدم</Label>
                            <Input
                                id="username"
                                v-model="form.username"
                                required
                                class="mt-1.5"
                                placeholder="أدخل اسم المستخدم للدخول"
                            />
                            <p
                                v-if="form.errors.username"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.username }}
                            </p>
                        </div>
                        <div>
                            <Label for="email">البريد الإلكتروني</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                class="mt-1.5"
                                placeholder="example@email.com"
                            />
                            <p
                                v-if="form.errors.email"
                                class="mt-1 text-sm text-red-500"
                            >
                                {{ form.errors.email }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <Link href="/admin/users">
                        <Button variant="outline" type="button">إلغاء</Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing">
                        تحديث
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
