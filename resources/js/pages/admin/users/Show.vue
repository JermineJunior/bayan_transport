<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    User,
    Mail,
    Building2,
    Shield,
    Calendar,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface Branch {
    id: number;
    name: string;
    code: string;
}

interface Role {
    id: number;
    name: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    branch: Branch | null;
    roles: Role[];
    branches: Branch[];
    created_at: string;
    updated_at: string;
}

interface Props {
    user: User;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستخدمون', href: '/admin/users' },
    { title: props.user.name, href: `/admin/users/${props.user.id}` },
];
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
                        <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                        <p class="text-sm text-muted-foreground">
                            تفاصيل المستخدم والصلاحيات
                        </p>
                    </div>
                </div>
                <Link href="/admin/users">
                    <Button variant="outline" class="gap-2">
                        <ArrowRight class="h-4 w-4" />
                        العودة للمستخدمين
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
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-muted-foreground">
                                    الاسم
                                </dt>
                                <dd class="font-medium">{{ user.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-muted-foreground">
                                    البريد الإلكتروني
                                </dt>
                                <dd class="flex items-center gap-2 font-medium">
                                    <Mail
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ user.email }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-muted-foreground">
                                    الفرع الأساسي
                                </dt>
                                <dd class="flex items-center gap-2 font-medium">
                                    <Building2
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ user.branch?.name ?? 'غير محدد' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <Calendar class="h-4 w-4" />
                            التواريخ
                        </h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-muted-foreground">
                                    تاريخ الإنشاء
                                </dt>
                                <dd class="font-medium">
                                    {{ user.created_at }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-muted-foreground">
                                    آخر تحديث
                                </dt>
                                <dd class="font-medium">
                                    {{ user.updated_at }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-6 md:col-span-2">
                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <Shield class="h-4 w-4" />
                            الأدوار ({{ user.roles.length }})
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="role in user.roles"
                                :key="role.id"
                                class="inline-flex items-center rounded-lg bg-primary/10 px-3 py-1.5 font-medium text-primary"
                            >
                                <Shield class="ml-1 h-4 w-4" />
                                {{ role.name }}
                            </span>
                        </div>
                    </div>

                    <div class="rounded-lg border bg-card p-6">
                        <h3 class="mb-4 flex items-center gap-2 font-semibold">
                            <Building2 class="h-4 w-4" />
                            الفروع المتاحة ({{ user.branches.length }})
                        </h3>
                        <div class="space-y-2">
                            <div
                                v-for="branch in user.branches"
                                :key="branch.id"
                                class="flex items-center gap-2 rounded-lg bg-muted/50 p-3"
                            >
                                <Building2
                                    class="h-4 w-4 text-muted-foreground"
                                />
                                <span class="font-medium">{{
                                    branch.name
                                }}</span>
                                <span class="text-sm text-muted-foreground"
                                    >({{ branch.code }})</span
                                >
                            </div>
                            <div
                                v-if="user.branches.length === 0"
                                class="text-sm text-muted-foreground"
                            >
                                لا توجد فروع متاحة
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
