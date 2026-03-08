<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase description="تـسجيل الـدخول">
        <Head title="تسجيل الدخول" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="form-group">
                <label>البريد الالكتروني او رقم التلفون</label>
                <Input
                    id="email"
                    type="text"
                    name="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="off"
                    placeholder="رقم الهاتف او البريد الالكتروني"
                    class="@error('email') is-invalid @enderror"
                />
                <InputError :message="errors.email" />
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <Input
                    id="password"
                    type="password"
                    name="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    placeholder="من فضلك ادخل كلمة المرور"
                    class="@error('password') is-invalid @enderror"
                />
                <InputError :message="errors.password" />

                <div class="form-group row">
                    <div class="col-md-6 offset-md-4">
                        <div class="form-check">
                            <Checkbox
                                id="remember"
                                name="remember"
                                :tabindex="3"
                            />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <Label for="remember" class="form-check-label">
                                تذكرني
                            </Label>
                        </div>
                    </div>
                </div>
            </div>

            <Button
                type="submit"
                class="btn btn-main-primary btn-block"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
            >
                <Spinner v-if="processing" />
                تسجيل الدخول
            </Button>

            <div
                class="text-center text-sm text-muted-foreground"
                v-if="canRegister"
            >
                ليس لديك حساب؟
                <TextLink :href="register()" :tabindex="5">إنشاء حساب</TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
