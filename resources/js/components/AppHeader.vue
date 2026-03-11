<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu, Search, Maximize, Sun } from 'lucide-vue-next';
import { computed } from 'vue';
import { dashboard, logout } from '@/routes';
import { edit as profileEdit } from '@/routes/profile';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);
const generalSettings = computed(() => page.props.generalSettings);

const logoUrl = computed(() => {
    const settings = generalSettings.value;
    const logo = settings?.logo;
    return logo ? `/image/logo/${logo}` : '/image/login/bayan.jpg';
});

const emit = defineEmits<{
    (e: 'toggle-sidebar'): void;
}>();
</script>

<template>
    <header class="main-header">
        <div class="container-fluid">
            <div class="header-left">
                <button class="menu-toggle" @click="emit('toggle-sidebar')">
                    <Menu />
                </button>

                <Link :href="dashboard()" class="logo-link">
                    <img :src="logoUrl" class="header-logo" alt="logo" />
                </Link>
            </div>
            <div class="header-right">
                <button class="header-icon-btn" title="تكبير الشاشة">
                    <Maximize />
                </button>

                <button class="header-icon-btn" title="الوضع الفاتح">
                    <Sun />
                </button>

                <div class="user-menu">
                    <button class="user-btn">
                        <img :src="logoUrl" alt="user" />
                    </button>
                    <div class="user-dropdown">
                        <div class="user-dropdown-header">
                            <img :src="logoUrl" alt="user" />
                            <div>
                                <h6>{{ auth?.user?.name }}</h6>
                                <span>{{ auth?.user?.email }}</span>
                            </div>
                        </div>
                        <Link :href="profileEdit()" class="dropdown-item">
                            الملف الشخصي
                        </Link>
                        <Link
                            :href="logout()"
                            method="post"
                            as="button"
                            class="dropdown-item"
                        >
                            تسجيل خروج
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>

<style scoped>
.main-header {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 0.75rem 1rem;
    position: sticky;
    top: 0;
    z-index: 50;
}

.container-fluid {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.menu-toggle {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    border-radius: 0.375rem;
}

.menu-toggle:hover {
    background: #f3f4f6;
}

.header-logo {
    height: 40px;
    width: auto;
}

.header-center {
    flex: 1;
    max-width: 400px;
}

.search-box {
    display: flex;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    overflow: hidden;
}

.search-input {
    flex: 1;
    border: none;
    padding: 0.5rem 1rem;
    outline: none;
}

.search-btn {
    background: #f3f4f6;
    border: none;
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-icon-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    border-radius: 0.375rem;
    color: #6b7280;
}

.header-icon-btn:hover {
    background: #f3f4f6;
    color: #0162e8;
}

.user-menu {
    position: relative;
}

.user-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.user-btn img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.user-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    display: none;
}

.user-menu:hover .user-dropdown {
    display: block;
}

.user-dropdown-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    background: #0162e8;
    color: #fff;
}

.user-dropdown-header img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.user-dropdown-header h6 {
    margin: 0;
    font-size: 0.9rem;
}

.user-dropdown-header span {
    font-size: 0.75rem;
    opacity: 0.8;
}

.dropdown-item {
    display: block;
    padding: 0.75rem 1rem;
    color: #4b5563;
    text-decoration: none;
}

.dropdown-item:hover {
    background: #f3f4f6;
    color: #0162e8;
}

@media (max-width: 768px) {
    .header-center {
        display: none;
    }
}
</style>
