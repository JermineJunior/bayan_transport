<script setup lang="ts">
import { Form, Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { type BreadcrumbItem } from '@/types';

type Props = {
    mustVerifyEmail: boolean;
    status?: string;
};

defineProps<Props>();

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: computed(
            () => t.value.profile || 'الملف الشخصي',
        ) as unknown as string,
        href: edit().url,
    },
];

const user = page.props.auth.user;
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="t.profile || 'الملف الشخصي'" />

        <SettingsLayout>
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ t.update_profile || 'تحديث الملف الشخصي' }}
                    </h2>
                    <p class="text-sm text-muted-foreground">
                        {{
                            t.update_profile_desc ||
                            'قم بتحديث اسمك وعنوان بريدك الإلكتروني'
                        }}
                    </p>
                </div>

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-4"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">{{ t.name || 'الاسم' }}</Label>
                        <Input
                            id="name"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            :placeholder="t.name || 'الاسم'"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">{{
                            t.email || 'البريد الإلكتروني'
                        }}</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            :placeholder="t.email || 'البريد الإلكتروني'"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="text-sm text-muted-foreground">
                            {{
                                t.email_unverified ||
                                'بريدك الإلكتروني غير مُفعَّل.'
                            }}
                            <Link
                                :href="send()"
                                as="button"
                                class="text-primary underline-offset-4 hover:underline"
                            >
                                {{
                                    t.resend_verification ||
                                    'إعادة إرسال رابط التحقق'
                                }}
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            {{
                                t.verification_sent ||
                                'تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني'
                            }}
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <Button :disabled="processing">
                            {{ t.save || 'حفظ' }}
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-green-600"
                            >
                                {{ t.saved_successfully || 'تم الحفظ بنجاح' }}
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
