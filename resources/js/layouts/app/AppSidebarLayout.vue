<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Settings,
    ChevronDown,
    Home,
    Menu,
    Search,
    Maximize,
    Minimize2,
    Sun,
    Moon,
    LogOut,
    User as UserIcon,
    BookKey,
    Truck,
    FileText,
} from 'lucide-vue-next';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { dashboard, logout } from '@/routes';
import { edit as profileEdit } from '@/routes/profile';
import type { BreadcrumbItem } from '@/types';
import { title } from 'process';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
    sidebarOpen?: boolean | null;
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    sidebarOpen: null,
});

const page = usePage();
const generalSettings = computed(() => page.props.generalSettings || {});
const auth = computed(() => page.props.auth || {});

const storedSidebar = localStorage.getItem('sidebarOpen');
const defaultOpen = storedSidebar ? storedSidebar === 'true' : true;

const sidebarOpen = ref(
    props.sidebarOpen !== null ? props.sidebarOpen : defaultOpen,
);

watch(
    () => props.sidebarOpen,
    (newVal) => {
        if (newVal !== null) {
            sidebarOpen.value = newVal;
            localStorage.setItem('sidebarOpen', String(newVal));
        }
    },
);

watch(sidebarOpen, (newVal) => {
    localStorage.setItem('sidebarOpen', String(newVal));
});
const activeMenu = ref<string | null>(null);
const isDark = ref(false);
const showProfileMenu = ref(false);

onMounted(() => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    } else if (
        !savedTheme &&
        window.matchMedia('(prefers-color-scheme: dark)').matches
    ) {
        isDark.value = true;
        document.documentElement.classList.add('dark');
    }
});

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleMenu = (menu: string) => {
    if (activeMenu.value === menu) {
        activeMenu.value = null;
    } else {
        activeMenu.value = menu;
    }
};

const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (typeof document !== 'undefined') {
        document.documentElement.classList.toggle('dark', isDark.value);
        localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
    }
};

const toggleFullscreen = () => {
    if (typeof document !== 'undefined') {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }
};

const isFullscreen = computed(() => {
    return typeof document !== 'undefined'
        ? !!document.fullscreenElement
        : false;
});

const logoUrl = computed(() => {
    const settings = generalSettings.value;
    const logo = settings?.logo;
    return logo ? `/image/logo/${logo}` : '/image/login/bayan.jpg';
});

const sidebarNavItems = [
    {
        title: 'البيانات اولية',
        icon: BookKey,
        items: [
            { title: 'قائمة العملاء', href: '/customers' },
            { title: 'قائمة السائقين', href: '/drivers' },
            { title: 'قائمة المستودعات', href: '/warehouses' },
        ],
    },
    {
        title: 'الترحيلات',
        icon: Truck,
        items: [{ title: 'قائمة الترحيلات', href: '/orders' }],
    },
    {
        title: 'التقارير',
        icon: FileText,
        items: [
            { title: 'تقرير العملاء', href: '/reports/customer' },
            { title: 'تقرير السائقين', href: '/reports/driver' },
            { title: 'تقرير فترة', href: '/reports/period' },
            { title: 'تقرير وجهة', href: '/reports/destination' },
            { title: 'تقرير شركة', href: '/reports/company' },
        ],
    },
    {
        title: 'الإعدادات العامة',
        icon: Settings,
        items: [{ title: 'بيانات الموؤسسة', href: '/settings/general' }],
    },
];
</script>

<template>
    <div
        class="app-layout"
        :class="{
            'sidebar-open': sidebarOpen,
            'dark-mode': isDark,
        }"
    >
        <!-- Sidebar -->
        <aside class="app-sidebar">
            <div class="main-sidebar-header">
                <Link :href="dashboard()" class="desktop-logo logo-light">
                    <img :src="logoUrl" class="main-logo" alt="logo" />
                </Link>
                <Link
                    :href="dashboard()"
                    class="logo-icon mobile-logo icon-light"
                >
                    <img :src="logoUrl" class="logo-icon" alt="logo" />
                </Link>
            </div>

            <div class="main-sidemenu">
                <div class="app-sidebar__user clearfix">
                    <div class="dropdown user-pro-body">
                        <div>
                            <img
                                :src="logoUrl"
                                class="avatar avatar-xl brround"
                                alt="user-img"
                            />
                            <span
                                class="avatar-status profile-status bg-green"
                            ></span>
                        </div>
                        <div class="user-info">
                            <h4 class="font-weight-semibold mt-3 mb-0">
                                {{ auth?.user?.name || 'المستخدم' }}
                            </h4>
                            <span class="mb-0 text-muted">{{
                                auth?.user?.email || ''
                            }}</span>
                        </div>
                    </div>
                </div>

                <ul class="side-menu">
                    <li class="side-item side-item-category">
                        برنامج نظام الترحيلات
                    </li>

                    <li class="slide">
                        <Link :href="dashboard()" class="side-menu__item">
                            <Home class="side-menu__icon" />
                            <span class="side-menu__label">الرئيسية</span>
                        </Link>
                    </li>

                    <li
                        v-for="(item, index) in sidebarNavItems"
                        :key="index"
                        class="slide"
                        :class="{ 'is-expanded': activeMenu === item.title }"
                    >
                        <a
                            class="side-menu__item"
                            href="#"
                            @click.prevent="toggleMenu(item.title)"
                        >
                            <component
                                :is="item.icon"
                                class="side-menu__icon"
                            />
                            <span class="side-menu__label">{{
                                item.title
                            }}</span>
                            <ChevronDown
                                class="angle"
                                :class="{ rotated: activeMenu === item.title }"
                            />
                        </a>
                        <ul
                            class="slide-menu"
                            v-show="activeMenu === item.title"
                        >
                            <li
                                v-for="(subItem, subIndex) in item.items"
                                :key="subIndex"
                            >
                                <Link :href="subItem.href" class="slide-item">{{
                                    subItem.title
                                }}</Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content app-content">
            <!-- Header -->
            <header class="main-header">
                <div class="container-fluid">
                    <div class="main-header-left">
                        <div class="app-sidebar__toggle" @click="toggleSidebar">
                            <a class="open-toggle" href="#">
                                <Menu class="header-icon" />
                            </a>
                        </div>
                    </div>

                    <div class="main-header-right">
                        <div class="nav nav-item navbar-nav-right ml-auto">
                            <div class="nav-item full-screen fullscreen-button">
                                <a
                                    class="new nav-link full-screen-link"
                                    href="#"
                                    @click.prevent="toggleFullscreen"
                                >
                                    <Maximize
                                        v-if="!isFullscreen"
                                        class="header-icon-svgs"
                                    />
                                    <Minimize2
                                        v-else
                                        class="header-icon-svgs"
                                    />
                                </a>
                            </div>

                            <div class="nav-item full-screen">
                                <a
                                    class="new nav-link"
                                    href="#"
                                    @click.prevent="toggleDarkMode"
                                    :title="
                                        isDark ? 'الوضع الفاتح' : 'الوضع الداكن'
                                    "
                                >
                                    <Sun
                                        v-if="isDark"
                                        class="header-icon-svgs"
                                    />
                                    <Moon v-else class="header-icon-svgs" />
                                </a>
                            </div>

                            <!-- Profile Dropdown -->
                            <div
                                class="dropdown main-profile-menu nav nav-item nav-link"
                                :class="{ show: showProfileMenu }"
                                @mouseleave="showProfileMenu = false"
                            >
                                <a
                                    class="profile-user d-flex"
                                    href="#"
                                    @click.prevent="
                                        showProfileMenu = !showProfileMenu
                                    "
                                >
                                    <img :src="logoUrl" alt="" />
                                </a>
                                <div
                                    class="dropdown-menu"
                                    :style="{
                                        display: showProfileMenu
                                            ? 'block'
                                            : 'none',
                                    }"
                                >
                                    <div
                                        class="main-header-profile bg-primary p-3"
                                    >
                                        <div class="d-flex wd-100p">
                                            <div class="main-img-user">
                                                <img :src="logoUrl" alt="" />
                                            </div>
                                            <div class="my-auto mr-3">
                                                <h6>{{ auth?.user?.name }}</h6>
                                                <span>{{
                                                    auth?.user?.email
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <Link
                                        :href="profileEdit()"
                                        class="dropdown-item"
                                    >
                                        <UserIcon class="mr-2" />
                                        الملف الشخصي
                                    </Link>
                                    <Link
                                        :href="logout()"
                                        method="post"
                                        as="button"
                                        class="dropdown-item"
                                    >
                                        <LogOut class="mr-2" />
                                        تسجيل خروج
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <!-- Page Content -->
            <main class="page-content">
                <div v-if="props.breadcrumbs.length > 1" class="page-header">
                    <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
                </div>
                <slot />
            </main>

            <!-- Footer -->
            <footer class="main-footer">
                <p>جميع الحقوق محفوظة بيان للبرمجيات 2026</p>
            </footer>
        </div>
    </div>
</template>

<style>
/* Factory-style Sidebar CSS */
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap');

.app-layout {
    font-family: 'Cairo', sans-serif;
}

.app-layout .app-sidebar {
    width: 280px;
    background: #fff;
    border-right: 1px solid #e5e7eb;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 100;
    transition: all 0.3s ease;
}

.app-layout .main-sidebar-header {
    padding: 1rem;
    display: flex;
    justify-content: center;
    border-bottom: 1px solid #e5e7eb;
}

.app-layout .desktop-logo {
    display: block;
}

.app-layout .logo-icon {
    display: none;
}

.app-layout .main-logo {
    max-height: 50px;
    max-width: 100%;
}

.app-layout .logo-icon {
    max-height: 40px;
}

.app-layout .main-sidemenu {
    height: calc(100vh - 70px);
    overflow-y: auto;
}

.app-layout .app-sidebar__user {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.app-layout .user-pro-body > div {
    position: relative;
    display: inline-block;
}

.app-layout .avatar-xl {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.app-layout .brround {
    border-radius: 50%;
}

.app-layout .profile-status {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.app-layout .bg-green {
    background-color: #10b981;
}

.app-layout .user-info {
    margin-top: 0.5rem;
}

.app-layout .font-weight-semibold {
    font-weight: 600;
}

.app-layout .text-muted {
    color: #6b7280;
    font-size: 0.85rem;
}

.app-layout .side-menu {
    list-style: none;
    padding: 0.5rem;
    margin: 0;
}

.app-layout .side-item {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    color: #6b7280;
}

.app-layout .side-item-category {
    font-weight: 600;
    color: #374151;
    margin-top: 1rem;
    padding: 0.5rem 0.75rem;
    text-transform: uppercase;
    font-size: 0.75rem;
}

.app-layout .slide {
    position: relative;
}

.app-layout .side-menu__item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}

.app-layout .side-menu__item:hover {
    background: #f3f4f6;
    color: #0162e8;
}

.app-layout .side-menu__icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.app-layout .side-menu__label {
    flex: 1;
    font-size: 0.9rem;
}

.app-layout .angle {
    font-size: 0.75rem;
    transition: transform 0.2s;
}

.app-layout .angle.rotated {
    transform: rotate(180deg);
}

.app-layout .slide-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    padding-right: 2rem;
    display: none;
}

.app-layout .slide.is-expanded .slide-menu {
    display: block;
}

.app-layout .slide-item {
    display: block;
    padding: 0.5rem 0.75rem;
    color: #6b7280;
    text-decoration: none;
    font-size: 0.85rem;
    border-radius: 0.375rem;
}

.app-layout .slide-item:hover {
    color: #0162e8;
    background: #f3f4f6;
}

/* Main Content */
.app-layout .main-content {
    margin-right: 280px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: margin-right 0.3s ease;
}

.app-layout .page-content {
    flex: 1;
}

/* Header */
.app-layout .main-header {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    z-index: 50;
}

.app-layout .container-fluid {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1.5rem;
}

.app-layout .main-header-left {
    display: flex;
    align-items: center;
}

.app-layout .main-header-right {
    display: flex;
    align-items: center;
    margin-right: auto;
}

.app-layout .navbar-nav-right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.app-layout .nav-link {
    display: flex;
    align-items: center;
}

.app-layout .app-sidebar__toggle {
    cursor: pointer;
}

.app-layout .header-icon {
    width: 24px;
    height: 24px;
    color: #4b5563;
}

.app-layout .header-icon-svgs {
    width: 20px;
    height: 20px;
}

.app-layout .navbar-form {
    position: relative;
}

.app-layout .input-group {
    display: flex;
    align-items: center;
}

.app-layout .form-control {
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    outline: none;
}

.app-layout .resp-btn {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-right: none;
    border-radius: 0.375rem 0 0 0.375rem;
    padding: 0.5rem;
    cursor: pointer;
}

.app-layout .nav-link {
    display: flex;
    align-items: center;
}

.app-layout .full-screen-button {
    padding: 0.5rem;
}

.app-layout .nav-item a {
    color: #4b5563;
    text-decoration: none;
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-radius: 0.375rem;
}

.app-layout .nav-item a:hover {
    background: #f3f4f6;
    color: #0162e8;
}

/* Profile Dropdown */
.app-layout .main-profile-menu {
    position: relative;
}

.app-layout .profile-user img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
}

.app-layout .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    z-index: 100;
    padding: 0;
    margin-top: 0.5rem;
}

.app-layout .main-profile-menu.show .dropdown-menu {
    display: block;
}

.app-layout .main-header-profile {
    background: #0162e8;
    color: #fff;
    padding: 1rem;
}

.app-layout .d-flex {
    display: flex;
}

.app-layout .wd-100p {
    width: 100%;
}

.app-layout .main-img-user img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.app-layout .my-auto {
    margin-top: auto;
    margin-bottom: auto;
}

.app-layout .mr-3 {
    margin-right: 0.75rem;
}

.app-layout .dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #4b5563;
    text-decoration: none;
    font-size: 0.875rem;
}

.app-layout .dropdown-item:hover {
    background: #f3f4f6;
    color: #0162e8;
}

.app-layout .bg-primary {
    background-color: #0162e8;
}

/* Page Content */
.app-layout .page-content {
    padding: 1.5rem;
}

.app-layout .page-header {
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

/* Footer */
.app-layout .main-footer {
    padding: 1rem;
    text-align: center;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    margin-top: auto;
}

.app-layout .main-footer p {
    margin: 0;
    font-size: 0.875rem;
    color: #6b7280;
}

.app-layout.dark-mode .main-footer {
    background: #1f2937;
    border-color: #374151;
}

.app-layout.dark-mode .main-footer p {
    color: #9ca3af;
}

/* Sidebar closed - icons only */
.app-layout:not(.sidebar-open) .app-sidebar {
    width: 80px;
}

.app-layout:not(.sidebar-open) .desktop-logo,
.app-layout:not(.sidebar-open) .user-info,
.app-layout:not(.sidebar-open) .side-menu__label,
.app-layout:not(.sidebar-open) .angle,
.app-layout:not(.sidebar-open) .side-item-category {
    display: none;
}

.app-layout:not(.sidebar-open) .logo-icon {
    display: block;
}

.app-layout:not(.sidebar-open) .side-menu__item {
    justify-content: center;
    padding: 0.75rem;
}

.app-layout:not(.sidebar-open) .main-content {
    margin-right: 80px;
}

/* Sidebar open - full width */
.app-layout.sidebar-open .app-sidebar {
    width: 280px;
}

.app-layout.sidebar-open .desktop-logo {
    display: block;
}

.app-layout.sidebar-open .logo-icon {
    display: none;
}

.app-layout.sidebar-open .user-info,
.app-layout.sidebar-open .side-menu__label,
.app-layout.sidebar-open .angle,
.app-layout.sidebar-open .side-item-category {
    display: block;
}

.app-layout.sidebar-open .side-menu__item {
    justify-content: flex-start;
}

.app-layout.sidebar-open .main-content {
    margin-right: 280px;
}

/* Dark Mode */
.app-layout.dark-mode .app-sidebar,
.app-layout.dark-mode .main-header {
    background: #1f2937;
    border-color: #374151;
}

.app-layout.dark-mode .main-sidebar-header {
    border-color: #374151;
}

.app-layout.dark-mode .app-sidebar__user {
    border-color: #374151;
}

.app-layout.dark-mode .text-muted {
    color: #9ca3af;
}

.app-layout.dark-mode .font-weight-semibold {
    color: #f3f4f6;
}

.app-layout.dark-mode .side-item-category {
    color: #9ca3af;
}

.app-layout.dark-mode .side-menu__item {
    color: #d1d5db;
}

.app-layout.dark-mode .side-menu__item:hover {
    background: #374151;
    color: #60a5fa;
}

.app-layout.dark-mode .slide-item {
    color: #9ca3af;
}

.app-layout.dark-mode .slide-item:hover {
    background: #374151;
    color: #60a5fa;
}

.app-layout.dark-mode .form-control {
    background: #1f2937;
    border-color: #374151;
    color: #f3f4f6;
}

.app-layout.dark-mode .resp-btn {
    background: #374151;
    border-color: #4b5563;
    color: #9ca3af;
}

.app-layout.dark-mode .nav-item a {
    color: #d1d5db;
}

.app-layout.dark-mode .nav-item a:hover {
    background: #374151;
    color: #60a5fa;
}

.app-layout.dark-mode .main-header {
    border-color: #374151;
}

.app-layout.dark-mode .dropdown-menu {
    background: #1f2937;
    border-color: #374151;
}

.app-layout.dark-mode .dropdown-item {
    color: #d1d5db;
}

.app-layout.dark-mode .dropdown-item:hover {
    background: #374151;
    color: #60a5fa;
}

@media (max-width: 768px) {
    .app-layout .app-sidebar {
        transform: translateX(100%);
    }

    .app-layout.sidebar-mini.sidenav-toggled .app-sidebar {
        transform: translateX(0);
    }

    .app-layout .main-content,
    .app-layout.sidebar-mini .main-content {
        margin-right: 0;
    }
}

/* Print Styles */
@media print {
    .app-layout .app-sidebar,
    .app-layout .main-header,
    .app-layout .main-footer,
    .no-print {
        display: none !important;
    }

    .app-layout .main-content {
        margin-right: 0 !important;
    }

    .app-layout .page-content {
        padding: 0 !important;
    }

    .app-layout {
        font-size: 12pt;
    }

    .app-layout .rounded-lg {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }

    .app-layout .shadow-sm {
        box-shadow: none !important;
    }

    body {
        background: white !important;
    }
}
</style>
