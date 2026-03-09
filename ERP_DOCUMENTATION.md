# ERP Template Documentation

## نظرة عامة | Overview

هذا نظام ERP لإدارة الترحيلات والنقل مبني باستخدام Laravel 12, Inertia.js, Vue 3, و Tailwind CSS مع دعم كامل للـ RTL.

This is a Transport ERP system built with Laravel 12, Inertia.js, Vue 3, and Tailwind CSS with full RTL support.

---

## المتطلبات | Requirements

- PHP 8.2+
- Laravel 12
- Node.js 18+
- MySQL/PostgreSQL (default) or SQLite

---

## التثبيت | Installation

```bash
# Clone the project
git clone <repo-url>
cd transport

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed

# Install frontend dependencies
npm install

# Build frontend
npm run build

# Start development server
php artisan serve

# In another terminal, run Vite
npm run dev
```

---

## المستخدم الافتراضي | Default User

بعد الـ seed، يتم إنشاء مستخدم اختبار:

- **Email**: admin@example.com
- **Password**: password

---

## هيكل المشروع | Project Structure

### النماذج | Models

```
app/Models/
├── User.php              # المستخدم مع دعم المصادقة
├── Customer.php         # العملاء
├── Driver.php          # السائقين
├── Warehouse.php       # المستودعات
└── Order.php          # طلبات الترحيل
```

###\Controllers

```
app/Http/Controllers/
├── DashboardController.php    # لوحة التحكم
├── CustomerController.php     # إدارة العملاء
├── DriverController.php      # إدارة السائقين
├── WarehouseController.php   # إدارة المستودعات
└── OrderController.php      # إدارة الطلبات
```

### Form Requests

```
app/Http/Requests/
├── StoreOrderRequest.php    # التحقق من إنشاء طلب
└── UpdateOrderRequest.php  # التحقق من تحديث طلب
```

---

## الواجهة الأمامية | Frontend

### الصفحات | Pages

```
resources/js/pages/
├── Dashboard.vue              # لوحة التحكم
├── customers/
│   ├── Index.vue           # قائمة العملاء
│   ├── Create.vue          # إضافة عميل
│   ├── Edit.vue           # تعديل عميل
│   └── Show.vue           # عرض تفاصيل العميل
├── drivers/
│   ├── Index.vue           # قائمة السائقين
│   ├── Create.vue          # إضافة سائق
│   ├── Edit.vue           # تعديل سائق
│   └── Show.vue           # عرض تفاصيل السائق
├── warehouses/
│   ├── Index.vue          # قائمة المستودعات
│   ├── Create.vue         # إضافة مستودع
│   ├── Edit.vue          # تعديل مستودع
│   └── Show.vue          # عرض تفاصيل المستودع
└── orders/
    ├── Index.vue          # قائمة الطلبات مع الترتيب
    ├── Create.vue         # إضافة طلب
    ├── Edit.vue          # تعديل طلب
    ├── Show.vue         # عرض تفاصيل الطلب
    └── partials/        # مكونات الوحدة
        ├── OrderInfoSection.vue      # معلومات الطلب
        ├── OrderPartiesSection.vue   # الأطراف (عميل، سائق، مستودع)
        ├── OrderAmountsSection.vue  # المبالغ والرسوم
        └── SelectWithAdd.vue        # اختيار مع إضافة سريعة
```

### المكونات | Components

```
resources/js/components/
├── ToastContainer.vue     # عرض الإشعارات
└── ui/                  # مكونات UI
```

### Layouts

```
resources/js/layouts/
├── AppLayout.vue              # Layout الرئيسي
└── app/
    └── AppSidebarLayout.vue  # Layout مع الشريط الجانبي
```

---

## المميزات | Features

### 1. إدارة العملاء | Customer Management

- إضافة/تعديل/حذف العملاء
- عرض تفاصيل العميل
- تخزين اسم العميل ورقم الهاتف

### 2. إدارة السائقين | Driver Management

- إضافة/تعديل/حذف السائقين
- عرض تفاصيل السائق
- تخزين اسم السائق ورقم الهاتف

### 3. إدارة المستودعات | Warehouse Management

- إضافة/تعديل/حذف المستودعات
- تفعيل/إلغاء المستودع
- عرض قائمة المستودعات النشطة فقط في نماذج الطلبات

### 4. إدارة الطلبات | Order Management

- إنشاء رقم طلب تلقائي
- إضافة/تعديل/حذف الطلبات
- ترتيب الجدول حسب أي حقل
- عرض جميع حقول الطلب في الجدول
- نموذج مقسم إلى أقسام:
    - معلومات الطلب (التاريخ، رقم السيارة، الشركة، الوجهة)
    - الأطراف (العميل، السائق، المستودع)
    - المبالغ (الجاز، البنزين، الطوف، العبور، إلخ)

### 5. إضافة سريعة | Quick Add

- في نموذج الطلب، يمكن إضافة عميل/سائق/مستودع جديد بسرعة
- بعد الإضافة، يتم التوجيه إلى صفحة الإنشاء مع تحديد القيمة الجديدة

### 6. الإشعارات | Toast Notifications

- إشعارات نجاح/خطأ عند عمليات الإنشاء والتحديث والحذف
- عرض تلقائي مع إمكانية الإغلاق اليدوي

### 7. التحقق | Validation

- التحقق من البيانات في جانب الخادم (Form Requests)
- عرض أخطاء التحقق تحت الحقول في النماذج

### 8. لوحة التحكم | Dashboard

- عرض إحصائيات:
    - طلبات الشهر الحالي
    - إجمالي الطلبات
    - عدد السائقين
    - عدد المستودعات

---

## تنسيق الأرقام | Number Formatting

- إدخال الأرقام: można wpisywać normalnie
- عرض الأرقام: `1,234,567.00` (تنسيق أمريكي)
- تحويل تلقائي عند مغادرة الحقل

---

## ترتيب الجدول | Table Sorting

- النقر على رأس العمود للترتيب
- دعم الترتيب تصاعدياً وتنازلياً
- حفظ حالة الترتيب في URL

---

## أوامر مفيدة | Useful Commands

```bash
# تشغيل السيرفر
php artisan serve

# تشغيل السيرفر مع Vite
npm run dev

# بناء الواجهة
npm run build

# تشغيل الاختبارات
php artisan test

# تنسيق الكود PHP
vendor/bin/pint --format

# عرض الجداول
php artisan tinker

# عرض الراوت
php artisan route:list

# عرض الراوت مع URLs
php artisan route:list --path=orders
```

---

## ملاحظات | Notes

1. **الـ RTL**: الـ layout يدعم RTL بالكامل
2. **localStorage**: حالة الشريط الجانبي يتم حفظها في localStorage
3. **Inertia.js**: يستخدم للنقل بين الصفحات بدون إعادة تحميل
4. **Tailwind CSS v4**: يستخدم للتنسيق

---

## الترخيص | License

MIT License
