<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import {
    Eye,
    Pencil,
    Trash2,
    Plus,
    User,
    Mail,
    UserCircle,
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    created_at: string;
}

interface Link {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    users: {
        data: User[];
        links: Link[];
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        total: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستخدمون', href: '/admin/users' },
];

const form = useForm({});

const deleteUser = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        form.delete(`/admin/users/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">المستخدمون</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        إدارة حسابات المستخدمين
                    </p>
                </div>
                <Link href="/admin/users/create">
                    <Button class="gap-2">
                        <Plus class="h-4 w-4" />
                        إضافة مستخدم
                    </Button>
                </Link>
            </div>

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead class="w-[200px] font-semibold"
                                >الاسم</TableHead
                            >
                            <TableHead class="w-[150px] font-semibold"
                                >اسم المستخدم</TableHead
                            >
                            <TableHead class="w-[250px] font-semibold"
                                >البريد الإلكتروني</TableHead
                            >
                            <TableHead class="text-left font-semibold"
                                >الإجراءات</TableHead
                            >
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="user in props.users.data"
                            :key="user.id"
                            class="hover:bg-muted/50"
                        >
                            <TableCell class="font-medium">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10"
                                    >
                                        <User class="h-4 w-4 text-primary" />
                                    </div>
                                    {{ user.name }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <UserCircle class="h-4 w-4" />
                                    {{ user.username }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center gap-2 text-muted-foreground"
                                >
                                    <Mail class="h-4 w-4" />
                                    {{ user.email }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div
                                    class="flex items-center justify-end gap-1"
                                >
                                    <Link :href="`/admin/users/${user.id}`">
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="عرض"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link
                                        :href="`/admin/users/${user.id}/edit`"
                                    >
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            title="تعديل"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="text-destructive hover:text-destructive"
                                        title="حذف"
                                        @click="deleteUser(user.id)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <Pagination
                    v-if="props.users.links"
                    :links="props.users.links"
                />
            </div>
        </div>
    </AppLayout>
</template>
