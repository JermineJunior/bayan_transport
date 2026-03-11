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
    Building2,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
    roles: Role[];
    branches: Branch[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'الرئيسية', href: '/dashboard' },
    { title: 'المستخدمون', href: '/admin/users' },
];

const isOpen = ref(false);
const editingUser = ref<User | null>(null);

const form = useForm({
    name: '',
    email: '',
    password: '',
    branch_id: null as number | null,
    roles: [] as number[],
    branches: [] as number[],
});

const openCreate = () => {
    editingUser.value = null;
    form.name = '';
    form.email = '';
    form.password = '';
    form.branch_id = null;
    form.roles = [];
    form.branches = [];
    isOpen.value = true;
};

const openEdit = (user: User) => {
    editingUser.value = user;
    form.name = user.name;
    form.email = user.email;
    form.password = '';
    form.branch_id = user.branch?.id ?? null;
    form.roles = user.roles.map((r) => r.id);
    form.branches = user.branches.map((b) => b.id);
    isOpen.value = true;
};

const submit = () => {
    if (editingUser.value) {
        form.put(`/admin/users/${editingUser.value.id}`, {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    } else {
        form.post('/admin/users', {
            onSuccess: () => {
                isOpen.value = false;
            },
        });
    }
};

const deleteUser = (id: number) => {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        form.delete(`/admin/users/${id}`);
    }
};

const toggleRole = (roleId: number) => {
    const index = form.roles.indexOf(roleId);
    if (index === -1) {
        form.roles.push(roleId);
    } else {
        form.roles.splice(index, 1);
    }
};

const toggleUserBranch = (branchId: number) => {
    const index = form.branches.indexOf(branchId);
    if (index === -1) {
        form.branches.push(branchId);
    } else {
        form.branches.splice(index, 1);
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
                        إدارة حسابات المستخدمين والصلاحيات
                    </p>
                </div>
                <Dialog v-model:open="isOpen">
                    <DialogTrigger as-child>
                        <Button @click="openCreate" class="gap-2">
                            <Plus class="h-4 w-4" />
                            إضافة مستخدم
                        </Button>
                    </DialogTrigger>
                    <DialogContent class="max-h-[85vh] max-w-2xl">
                        <DialogHeader>
                            <DialogTitle>
                                {{
                                    editingUser
                                        ? 'تعديل المستخدم'
                                        : 'إضافة مستخدم جديد'
                                }}
                            </DialogTitle>
                            <DialogDescription>
                                {{
                                    editingUser
                                        ? 'تعديل بيانات المستخدم'
                                        : 'إنشاء حساب مستخدم جديد'
                                }}
                            </DialogDescription>
                        </DialogHeader>
                        <form @submit.prevent="submit" class="space-y-6 py-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label for="name">الاسم</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        required
                                        class="mt-1.5"
                                        placeholder="أدخل اسم المستخدم"
                                    />
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
                                </div>
                            </div>
                            <div>
                                <Label for="password">
                                    كلمة المرور
                                    {{
                                        editingUser
                                            ? '(اتركها فارغة للإبقاء على الحالية)'
                                            : ''
                                    }}
                                </Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    :type="editingUser ? 'password' : 'text'"
                                    :required="!editingUser"
                                    class="mt-1.5"
                                    :placeholder="
                                        editingUser
                                            ? '••••••••'
                                            : 'أدخل كلمة المرور'
                                    "
                                />
                            </div>
                            <div>
                                <Label>الفرع الأساسي</Label>
                                <select
                                    v-model="form.branch_id"
                                    class="mt-1.5 flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option :value="null">اختر الفرع</option>
                                    <option
                                        v-for="branch in props.branches"
                                        :key="branch.id"
                                        :value="branch.id"
                                    >
                                        {{ branch.name }} ({{ branch.code }})
                                    </option>
                                </select>
                            </div>
                            <div>
                                <Label>الأدوار</Label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div
                                        v-for="role in props.roles"
                                        :key="role.id"
                                        class="flex items-center gap-2"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="`role-${role.id}`"
                                            :checked="
                                                form.roles.includes(role.id)
                                            "
                                            @change="toggleRole(role.id)"
                                            class="h-4 w-4"
                                        />
                                        <Label
                                            :for="`role-${role.id}`"
                                            class="cursor-pointer text-sm"
                                        >
                                            {{ role.name }}
                                        </Label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <Label>الفروع الإضافية</Label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div
                                        v-for="branch in props.branches"
                                        :key="branch.id"
                                        class="flex items-center gap-2"
                                    >
                                        <input
                                            type="checkbox"
                                            :id="`branch-${branch.id}`"
                                            :checked="
                                                form.branches.includes(
                                                    branch.id,
                                                )
                                            "
                                            @change="
                                                toggleUserBranch(branch.id)
                                            "
                                            class="h-4 w-4"
                                        />
                                        <Label
                                            :for="`branch-${branch.id}`"
                                            class="cursor-pointer text-sm"
                                        >
                                            {{ branch.name }}
                                        </Label>
                                    </div>
                                </div>
                            </div>
                            <DialogFooter class="gap-2">
                                <DialogClose as-child>
                                    <Button variant="outline" type="button"
                                        >إلغاء</Button
                                    >
                                </DialogClose>
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="gap-2"
                                >
                                    {{ editingUser ? 'تحديث' : 'إنشاء' }}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow class="hover:bg-transparent">
                            <TableHead class="w-[200px] font-semibold"
                                >الاسم</TableHead
                            >
                            <TableHead class="w-[250px] font-semibold"
                                >البريد الإلكتروني</TableHead
                            >
                            <TableHead class="w-[150px] font-semibold"
                                >الفرع</TableHead
                            >
                            <TableHead class="w-[200px] font-semibold"
                                >الأدوار</TableHead
                            >
                            <TableHead class="text-right font-semibold"
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
                                    <Mail class="h-4 w-4" />
                                    {{ user.email }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div class="flex items-center gap-2">
                                    <Building2
                                        class="h-4 w-4 text-muted-foreground"
                                    />
                                    {{ user.branch?.name ?? '-' }}
                                </div>
                            </TableCell>
                            <TableCell>
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        class="inline-flex items-center rounded bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"
                                    >
                                        {{ role.name }}
                                    </span>
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
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        title="تعديل"
                                        @click="openEdit(user)"
                                    >
                                        <Pencil class="h-4 w-4" />
                                    </Button>
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
