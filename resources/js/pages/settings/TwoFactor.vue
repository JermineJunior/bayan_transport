<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { computed } from 'vue';
import { onUnmounted, ref } from 'vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { disable, enable, show } from '@/routes/two-factor';
import type { BreadcrumbItem } from '@/types';

type Props = {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
};

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: computed(
            () => t.value.two_factor || 'المصادقة الثنائية',
        ) as unknown as string,
        href: show.url(),
    },
];

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="t.two_factor || 'المصادقة الثنائية'" />

        <SettingsLayout>
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ t.two_factor || 'المصادقة الثنائية' }}
                    </h2>
                    <p class="text-sm text-muted-foreground">
                        {{
                            t.two_factor_desc ||
                            'إدارة إعدادات المصادقة الثنائية'
                        }}
                    </p>
                </div>

                <div
                    v-if="!twoFactorEnabled"
                    class="flex flex-col items-start gap-4"
                >
                    <Badge variant="destructive">
                        {{ t.disabled || 'معطَّل' }}
                    </Badge>

                    <p class="text-muted-foreground">
                        {{
                            t.two_factor_disabled_desc ||
                            'عند تفعيل المصادقة الثنائية، ستتم مطالبتك بإدخال رمز PIN آمن أثناء تسجيل الدخول. يمكن الحصول على هذا الرمز من تطبيق يدعم TOTP على هاتفك.'
                        }}
                    </p>

                    <div>
                        <Button
                            v-if="hasSetupData"
                            @click="showSetupModal = true"
                        >
                            <ShieldCheck />
                            {{ t.continue_setup || 'متابعة الإعداد' }}
                        </Button>
                        <Form
                            v-else
                            v-bind="enable.form()"
                            @success="showSetupModal = true"
                            #default="{ processing }"
                        >
                            <Button type="submit" :disabled="processing">
                                <ShieldCheck />
                                {{
                                    t.enable_two_factor ||
                                    'تفعيل المصادقة الثنائية'
                                }}
                            </Button>
                        </Form>
                    </div>
                </div>

                <div v-else class="flex flex-col items-start gap-4">
                    <Badge variant="default">
                        {{ t.enabled || 'مفعَّل' }}
                    </Badge>

                    <p class="text-muted-foreground">
                        {{
                            t.two_factor_enabled_desc ||
                            'عند تفعيل المصادقة الثنائية، ستتم مطالبتك بإدخال رمز PIN عشوائي وآمن أثناء تسجيل الدخول، والذي يمكنك الحصول عليه من التطبيق المدعوم على هاتفك.'
                        }}
                    </p>

                    <TwoFactorRecoveryCodes />

                    <div class="relative inline">
                        <Form v-bind="disable.form()" #default="{ processing }">
                            <Button
                                variant="destructive"
                                type="submit"
                                :disabled="processing"
                            >
                                <ShieldBan />
                                {{
                                    t.disable_two_factor ||
                                    'تعطيل المصادقة الثنائية'
                                }}
                            </Button>
                        </Form>
                    </div>
                </div>

                <TwoFactorSetupModal
                    v-model:isOpen="showSetupModal"
                    :requiresConfirmation="requiresConfirmation"
                    :twoFactorEnabled="twoFactorEnabled"
                />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
