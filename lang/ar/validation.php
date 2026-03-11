<?php

return [
    'accepted' => 'يجب قبول :attribute.',
    'active_url' => ':attribute ليس عنوان URL صالحًا.',
    'after' => 'يجب أن يكون :attribute تاريخًا بعد :date.',
    'alpha' => 'يجب أن يحتوي :attribute على أحرف فقط.',
    'alpha_dash' => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات فقط.',
    'alpha_num' => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط.',
    'array' => 'يجب أن يكون :attribute مصفوفة.',
    'before' => 'يجب أن يكون :attribute تاريخًا قبل :date.',
    'between' => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max.',
        'file' => 'يجب أن يكون حجم :attribute بين :min و :max كيلوبايت.',
        'string' => 'يجب أن يكون عدد أحرف :attribute بين :min و :max.',
        'array' => 'يجب أن يحتوي :attribute على عناصر بين :min و :max.',
    ],
    'boolean' => 'يجب أن يكون حقل :attribute صحيحًا أو خطأ.',
    'confirmed' => 'تأكيد :attribute غير متطابق.',
    'date' => ':attribute ليس تاريخًا صالحًا.',
    'email' => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالح.',
    'exists' => ':attribute المحدد غير صالح.',
    'file' => 'يجب أن يكون :attribute ملفًا.',
    'filled' => 'يجب أن يحتوي حقل :attribute على قيمة.',
    'image' => 'يجب أن يكون :attribute صورة.',
    'in' => ':attribute المحدد غير صالح.',
    'integer' => 'يجب أن يكون :attribute عددًا صحيحًا.',
    'ip' => 'يجب أن يكون :attribute عنوان IP صالحًا.',
    'json' => 'يجب أن يكون :attribute سلسلة JSON صالحة.',
    'max' => [
        'numeric' => 'لا يجوز أن يكون :attribute أكبر من :max.',
        'file' => 'لا يجوز أن يكون حجم :attribute أكبر من :max كيلوبايت.',
        'string' => 'لا يجوز أن يتجاوز عدد أحرف :attribute :max.',
        'array' => 'لا يجوز أن يحتوي :attribute على أكثر من :max عناصر.',
    ],
    'min' => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min.',
        'file' => 'يجب أن يكون حجم :attribute على الأقل :min كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على الأقل :min أحرف.',
        'array' => 'يجب أن يحتوي :attribute على الأقل :min عناصر.',
    ],
    'not_in' => ':attribute المحدد غير صالح.',
    'numeric' => 'يجب أن يكون :attribute رقمًا.',
    'present' => 'يجب أن يكون حقل :attribute موجودًا.',
    'regex' => 'تنسيق :attribute غير صالح.',
    'required' => 'حقل :attribute مطلوب.',
    'same' => 'يجب أن يتطابق :attribute مع :other.',
    'size' => [
        'numeric' => 'يجب أن يكون :attribute :size.',
        'file' => 'يجب أن يكون حجم :attribute :size كيلوبايت.',
        'string' => 'يجب أن يحتوي :attribute على :size أحرف.',
        'array' => 'يجب أن يحتوي :attribute على :size عناصر.',
    ],
    'string' => 'يجب أن يكون :attribute سلسلة.',
    'unique' => 'تم اتخاذ :attribute بالفعل.',
    'url' => 'تنسيق :attribute غير صالح.',
    'custom' => [
        'order_number' => [
            'unique' => 'رقم الطلب :input مستخدم بالفعل.',
        ],
    ],
    'attributes' => [
        // Auth
        'name' => 'الاسم',
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'current_password' => 'كلمة المرور الحالية',
        'new_password' => 'كلمة المرور الجديدة',
        'remember' => 'تذكرني',

        // Common
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'phone' => 'رقم الهاتف',
        'phone_2' => 'رقم الهاتف الثاني',
        'address' => 'العنوان',
        'is_active' => 'الحالة',
        'note' => 'ملاحظة',

        // Customer
        'customer_id' => 'العميل',

        // Driver
        'driver_id' => 'السائق',

        // Warehouse
        'warehouse_id' => 'المستودع',

        // Order
        'date' => 'التاريخ',
        'order_number' => 'رقم الطلب',
        'car_number' => 'رقم السيارة',
        'gasoline' => 'البنزين',
        'benzin' => 'البنزين',
        'fleet' => 'الأسطول',
        'transit' => 'الترانزيت',
        'window_fee' => 'رسوم النافذة',
        'manfisto' => 'المانيفستو',
        'freightage' => 'الأجرة',
        'tax' => 'الضريبة',
        'commission' => 'العمولة',
        'amount' => 'المبلغ',
        'company' => 'الشركة',
        'destination' => 'الوجهة',

        // General Settings
        'location' => 'الموقع',
        'logo' => 'الشعار',
    ],
];
