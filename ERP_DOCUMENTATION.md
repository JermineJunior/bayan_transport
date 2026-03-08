# ERP Template Documentation

## نظرة عامة | Overview

هذا قالب ERP شامل مبني باستخدام Laravel 12, Inertia.js, Vue 3, و Tailwind CSS مع دعم كامل للـ RTL.

This is a comprehensive ERP template built with Laravel 12, Inertia.js, Vue 3, and Tailwind CSS with full RTL support.

---

## المتطلبات | Requirements

- PHP 8.2+
- Laravel 12
- Node.js 18+
- SQLite (default) or MySQL/PostgreSQL

---

## التثبيت | Installation

```bash
# Clone the project
git clone <repo-url>
cd VueErp

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database with chart of accounts
php artisan db:seed

# Install frontend dependencies
npm install

# Build frontend
npm run build
```

---

## هيكل المشروع | Project Structure

### Models

```
app/Models/
├── User.php              # المستخدم مع دعم الأدوار والصلاحيات
├── Branch.php            # الفروع متعددة
├── MasterData/
│   ├── Category.php      # التصنيفات (يمكن أن تكون متعددة المستويات)
│   ├── Brand.php         # العلامات التجارية
│   ├── Product.php      # المنتجات معpricing والـ stock
│   └── Unit.php         # وحدات القياس (UOM)
├── Entity/
│   ├── Supplier.php     # الموردين (مرتبط بحساب محاسبي)
│   ├── Customer.php     # العملاء (مرتبط بحساب محاسبي)
│   └── Representative.php # المندوبين (مرتبط بحساب محاسبي)
└── Accounting/
    ├── Account.php      # شجرة الحسابات (4 مستويات)
    ├── Invoice.php      # فواتير المبيعات/المشتريات
    ├── InvoiceItem.php  # بنود الفاتورة
    ├── JournalEntry.php # القيود المحاسبية
    └── JournalLine.php  # سطور القيد
```

### Services

```
app/Services/
├── AccountingService.php  # خدمات المحاسبة
│   ├── createAccountForEntity() - إنشاء حساب جديد لكيان
│   ├── getAccountTree() - الحصول على شجرة الحسابات
│   └── getAccountsByType() - الحصول على الحسابات حسب النوع
│
└── InvoiceService.php    # خدمات الفواتير
    ├── createInvoice() - إنشاء فاتورة
    └── confirmInvoice() - تأكيد الفاتورة وإنشاء قيد محاسبي
```

### Traits

```
app/Traits/
└── BelongsToBranch.php  # Trait للـ automatic branch scoping
    ├── bootBelongsToBranch() - تعيين branch_id تلقائياً
    └── scopeAllowedBranches() - تصفية الاستعلامات حسب الفرع
```

### Middleware

```
app/Http/Middleware/
└── ActiveBranch.php      # إدارة الفرع النشط في الجلسة
```

---

## شجرة الحسابات | Chart of Accounts

### المستويات | Levels

1. **المستوى 1** - الأصول، الخصوم، حقوق الملكية، الإيرادات، المصروفات
2. **المستوى 2** - التصنيفات الرئيسية (مثل: الأصول المتداولة)
3. **المستوى 3** - الحسابات الفرعية (مثل: النقدية، البنك، العملاء)
4. **المستوى 4** - الحسابات التفصيلية (تُنشأ تلقائياً للكيانات)

### أكواد الحسابات | Account Codes

```
1xxxx - الأصول (Assets)
  11xx - الأصول المتداولة
    1101 - النقدية
    1102 - البنك
    1103 - العملاء
    1104 - المخزون
  12xx - الأصول الثابتة

2xxxx - الخصوم (Liabilities)
  21xx - الخصوم المتداولة
    2101 - الموردين
  22xx - الخصوم طويلة الأجل

3xxxx - حقوق الملكية (Equity)
  31xx - رأس المال
  32xx - الأرباح المحتجزة

4xxxx - الإيرادات (Revenue)
  41xx - إيرادات المبيعات
  42xx - إيرادات أخرى

5xxxx - المصروفات (Expenses)
  51xx - تكلفة البضاعة المباعة
  52xx - المصروفات التشغيلية
  53xx - المصروفات المالية
```

---

## نظام الفواتير | Invoice System

### أنواع الفواتير | Invoice Types

- `sale` - فاتورة مبيعات
- `purchase` - فاتورة مشتريات
- `sale_return` - مردودات مبيعات
- `purchase_return` - مردودات مشتريات

### العمليات | Workflow

1. **إنشاء فاتورة** - Create Invoice

```php
$invoiceService = app(InvoiceService::class);

$invoice = $invoiceService->createInvoice([
    'type' => 'sale',
    'customer_id' => 1,
    'invoice_date' => now(),
    'discount_percentage' => 10,
    'tax_percentage' => 15,
    'items' => [
        [
            'product_id' => 1,
            'quantity' => 10,
            'unit_price' => 100,
        ]
    ]
]);
```

2. **تأكيد الفاتورة** - Confirm Invoice (يُنشئ قيد محاسبي تلقائياً)

```php
$journalEntry = $invoiceService->confirmInvoice($invoice);
```

---

## إنشاء حساب لكيان | Create Account for Entity

```php
$accountingService = app(AccountingService::class);

// إنشاء حساب عميل
$customerAccount = $accountingService->createAccountForEntity(
    '1103',  // parent account code
    'Customer Name',
    'اسم العميل'  // Arabic name (optional)
);

// إنشاء حساب مورد
$supplierAccount = $accountingService->createAccountForEntity(
    '2101',  // parent account code
    'Supplier Name',
    'اسم المورد'
);
```

---

## الواجهة الأمامية | Frontend

### الصفحات | Pages

```
resources/js/pages/
├── admin/
│   ├── roles/Index.vue    # إدارة الأدوار والصلاحيات
│   ├── users/Index.vue    # إدارة المستخدمين
│   └── branches/Index.vue # إدارة الفروع
```

### المكونات | Components

```
resources/js/components/
├── BranchSwitcher.vue     # مكون تغيير الفرع
└── AppSidebar.vue        # القائمة الجانبية المحدثة
```

---

## المستخدم الافتراضي | Default User

بعد الـ seed، يتم إنشاء مستخدم اختبار:

- **Email**: test@example.com
- **Password**: password

---

## أوامر مفيدة | Useful Commands

```bash
# تشغيل السيرفر
php artisan serve

# تشغيل السيرفر مع Vite
npm run dev

# تشغيل الاختبارات
php artisan test

# تنسيق الكود
vendor/bin/pint --format agent

# عرض الجداول
php artisan tinker

# التحقق من الراوت
php artisan route:list
```

---

## ملاحظات | Notes

1. **الـ Branch Scoping**: جميع النماذج تستخدم `BelongsToBranch` trait الذي يضيف `branch_id` تلقائياً
2. **الـ RTL**: الـ layout يدعم RTL بالكامل مع `dir="rtl"`
3. **bcmath**: العمليات الحسابية المالية تستخدم Decimal types
4. **Spatie Permissions**: تم إعداد الأدوار والصلاحيات مسبقاً

---

## الترخيص | License

MIT License
