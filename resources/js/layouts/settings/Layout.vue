<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Separator } from '@/components/ui/separator';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const sidebarNavItems: NavItem[] = [
    {
        title: computed(
            () => t.value.profile || 'الملف الشخصي',
        ) as unknown as string,
        href: editProfile(),
    },
    {
        title: computed(
            () => t.value.password || 'كلمة المرور',
        ) as unknown as string,
        href: editPassword(),
    },
    {
        title: computed(
            () => t.value.two_factor || 'المصادقة الثنائية',
        ) as unknown as string,
        href: show(),
    },
    {
        title: computed(
            () => t.value.appearance || 'المظهر',
        ) as unknown as string,
        href: editAppearance(),
    },
];

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <div class="flex flex-col gap-6 lg:flex-row lg:gap-10">
        <aside class="w-full shrink-0 lg:w-56">
            <nav
                class="flex flex-row gap-1 overflow-x-auto pb-2 lg:flex-col lg:overflow-visible lg:pb-0"
            >
                <Link
                    v-for="item in sidebarNavItems"
                    :key="toUrl(item.href)"
                    :href="item.href"
                    class="shrink-0 rounded-lg px-4 py-2.5 text-sm font-medium transition-all"
                    :class="
                        isCurrentUrl(item.href)
                            ? 'bg-primary text-primary-foreground shadow-sm'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    "
                >
                    {{ item.title }}
                </Link>
            </nav>
        </aside>

        <Separator class="my-1 lg:hidden" />

        <div class="flex-1 space-y-8">
            <slot />
        </div>
    </div>
</template>
