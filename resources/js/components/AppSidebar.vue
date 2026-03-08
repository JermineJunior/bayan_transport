<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Calculator,
    Settings,
    Shield,
    ShoppingCart,
    User,
    ChevronDown,
    Home,
} from 'lucide-vue-next';
import { dashboard } from '@/routes';

const page = usePage();
const generalSettings = computed(() => page.props.generalSettings);

const isOpen = ref(true);
const activeMenu = ref<string | null>(null);

const toggleMenu = (menu: string) => {
    if (activeMenu.value === menu) {
        activeMenu.value = null;
    } else {
        activeMenu.value = menu;
    }
};

const logoUrl = computed(() => {
    const settings = generalSettings.value;
    const logo = settings?.logo;
    return logo ? `/image/logo/${logo}` : '/image/login/bayan.jpg';
});

const sidebarNavItems = [
    {
        title: 'الإعدادات العامة',
        icon: Settings,
        items: [
            { title: 'الإعدادات العامة', href: '/settings/general' },
            { title: 'الفروع', href: '/admin/branches' },
            { title: 'الوحدات', href: '#' },
            { title: 'البنوك', href: '#' },
            { title: 'الخزائن', href: '#' },
        ],
    },
    {
        title: 'المنتجات والمخزون',
        icon: ShoppingCart,
        items: [
            { title: 'المنتجات', href: '#' },
            { title: 'المخزون', href: '#' },
        ],
    },
    {
        title: 'العملاء والموردون',
        icon: User,
        items: [
            { title: 'العملاء', href: '/entity/customers' },
            { title: 'الموردون', href: '/entity/suppliers' },
            { title: 'المندوبين', href: '/entity/representatives' },
        ],
    },
    {
        title: 'المحاسبة',
        icon: Calculator,
        items: [
            { title: 'المستوى الأول', href: '/accounting/level-1' },
            { title: 'المستوى الثاني', href: '/accounting/level-2' },
            { title: 'المستوى الثالث', href: '/accounting/level-3' },
            { title: 'المستوى الرابع', href: '/accounting/level-4' },
        ],
    },
];

const footerNavItems = [
    { title: 'المستخدمون', href: '/admin/users', icon: User },
    { title: 'الأدوار', href: '/admin/roles', icon: Shield },
];
</script>

<template>
    <div class="sidebar-container">
        <div class="sidebar-header">
            <Link :href="dashboard()">
                <img :src="logoUrl" class="main-logo" alt="logo" />
            </Link>
        </div>

        <div class="sidebar-user">
            <img :src="logoUrl" class="user-avatar" alt="user" />
            <div class="user-info">
                <h4>{{ page.props.auth?.user?.name || 'المستخدم' }}</h4>
                <span>{{ page.props.auth?.user?.email || '' }}</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-category">برنامج نظام المبيعات</div>

            <Link :href="dashboard()" class="nav-item">
                <Home class="nav-icon" />
                <span>الرئيسية</span>
            </Link>

            <div
                v-for="(item, index) in sidebarNavItems"
                :key="index"
                class="nav-group"
            >
                <a
                    class="nav-item"
                    href="#"
                    @click.prevent="toggleMenu(item.title)"
                >
                    <component :is="item.icon" class="nav-icon" />
                    <span>{{ item.title }}</span>
                    <ChevronDown
                        class="nav-arrow"
                        :class="{ rotated: activeMenu === item.title }"
                    />
                </a>
                <div class="nav-submenu" v-show="activeMenu === item.title">
                    <Link
                        v-for="(subItem, subIndex) in item.items"
                        :key="subIndex"
                        :href="subItem.href"
                        class="nav-subitem"
                    >
                        {{ subItem.title }}
                    </Link>
                </div>
            </div>

            <div class="nav-category">المستخدمين والصلاحيات</div>

            <Link
                v-for="(item, index) in footerNavItems"
                :key="index"
                :href="item.href"
                class="nav-item"
            >
                <component :is="item.icon" class="nav-icon" />
                <span>{{ item.title }}</span>
            </Link>
        </nav>
    </div>
</template>

<style scoped>
.sidebar-container {
    padding: 1rem;
}

.sidebar-header {
    padding: 1rem;
    text-align: center;
}

.sidebar-header img {
    max-width: 150px;
    height: auto;
}

.sidebar-user {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 1rem;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.user-info h4 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.user-info span {
    font-size: 0.75rem;
    color: #6b7280;
}

.sidebar-nav {
    padding: 0;
}

.nav-category {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-top: 1rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.2s;
    cursor: pointer;
}

.nav-item:hover {
    background: #f3f4f6;
    color: #0162e8;
}

.nav-icon {
    width: 20px;
    height: 20px;
}

.nav-arrow {
    width: 16px;
    height: 16px;
    margin-right: auto;
    transition: transform 0.2s;
}

.nav-arrow.rotated {
    transform: rotate(180deg);
}

.nav-submenu {
    padding-right: 2rem;
}

.nav-subitem {
    display: block;
    padding: 0.5rem 1rem;
    color: #6b7280;
    text-decoration: none;
    font-size: 0.85rem;
    border-radius: 0.375rem;
}

.nav-subitem:hover {
    color: #0162e8;
    background: #f3f4f6;
}
</style>
