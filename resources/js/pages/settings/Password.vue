<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import { edit } from '@/routes/user-password';

const page = usePage<{ translations?: Record<string, string> }>();
const t = computed(() => page.props.translations ?? {});

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: computed(
            () => t.value.password || 'كلمة المرور',
        ) as unknown as string,
        href: edit().url,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="t.password || 'كلمة المرور'" />

        <SettingsLayout>
            <div class="space-y-6">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ t.update_password || 'تحديث كلمة المرور' }}
                    </h2>
                    <p class="text-sm text-muted-foreground">
                        {{
                            t.update_password_desc ||
                            'تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية'
                        }}
                    </p>
                </div>

                <Form
                    v-bind="PasswordController.update.form()"
                    :options="{
                        preserveScroll: true,
                    }"
                    reset-on-success
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    class="space-y-4"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="current_password">{{
                            t.current_password || 'كلمة المرور الحالية'
                        }}</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                            autocomplete="current-password"
                            :placeholder="
                                t.current_password || 'كلمة المرور الحالية'
                            "
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">{{
                            t.new_password || 'كلمة المرور الجديدة'
                        }}</Label>
                        <Input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            :placeholder="
                                t.new_password || 'كلمة المرور الجديدة'
                            "
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">{{
                            t.confirm_password || 'تأكيد كلمة المرور'
                        }}</Label>
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            :placeholder="
                                t.confirm_password || 'تأكيد كلمة المرور'
                            "
                        />
                        <InputError :message="errors.password_confirmation" />
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
