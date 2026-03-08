<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import { ChevronRight, ChevronDown } from 'lucide-vue-next';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import { type NavItem } from '@/types';

defineProps<{
    items: NavItem[];
    title?: string;
}>();

const { isCurrentUrl } = useCurrentUrl();

const openItems = ref<Set<string>>(new Set());

const toggleItem = (title: string) => {
    if (openItems.value.has(title)) {
        openItems.value.delete(title);
    } else {
        openItems.value.add(title);
    }
};

const isOpen = (title: string) => openItems.value.has(title);
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel v-if="title">{{ title }}</SidebarGroupLabel>
        <SidebarGroupLabel v-else>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">
                <Collapsible
                    v-if="item.items && item.items.length > 0"
                    :default-open="isOpen(item.title)"
                    as-child
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :is-active="isCurrentUrl(item.href)"
                                :tooltip="item.title"
                                class="flex w-full items-center justify-between"
                            >
                                <Link
                                    :href="item.href"
                                    class="flex flex-1 items-center gap-2"
                                >
                                    <component
                                        :is="item.icon"
                                        v-if="item.icon"
                                    />
                                    <span>{{ item.title }}</span>
                                </Link>
                                <component
                                    :is="
                                        isOpen(item.title)
                                            ? ChevronDown
                                            : ChevronRight
                                    "
                                    class="h-4 w-4"
                                    @click.prevent="toggleItem(item.title)"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.title"
                                >
                                    <SidebarMenuSubButton
                                        as-child
                                        :is-active="isCurrentUrl(subItem.href)"
                                        :tooltip="subItem.title"
                                    >
                                        <Link :href="subItem.href">
                                            <span>{{ subItem.title }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>
                <SidebarMenuItem v-else>
                    <SidebarMenuButton
                        as-child
                        :is-active="isCurrentUrl(item.href)"
                        :tooltip="item.title"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" v-if="item.icon" />
                            <span>{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
