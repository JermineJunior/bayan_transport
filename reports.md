# التقارير Reports

## نظرة عامة

نظام التقارير يوفر مجموعة من التقارير المالية والتشغيلية لشركات النقل والترحيلات. يعرض كل تقرير بيانات المؤسسة العامة مع تفاصيل الطلبات وفقاً للتصفية المختارة.

## هيكل العمل (Workflow)

كل تقرير يعمل على مرحلتين:

1. **صفحة النموذج (Form)**: يُدخل المستخدم معايير التصفية
2. **صفحة التقرير (Report)**: يعرض النتائج مع الإجماليات

```
/reports/{type}          → Form (نموذج الاختيار)
/reports/{type}/generate → Report (عرض النتيجة)
```

## التقارير المتاحة

### 1. تقرير العملاء

- **الوصف**: يعرض جميع طلبات عميل معين خلال فترة زمنية محددة
- **المسار**: `/reports/customer`
- **الفلاتر**:
    - العميل (مطلوب)
    - من تاريخ (اختياري)
    - إلى تاريخ (اختياري)

### 2. تقرير السائقين

- **الوصف**: يعرض جميع طلبات سائق معين خلال فترة زمنية محددة
- **المسار**: `/reports/driver`
- **الفلاتر**:
    - السائق (مطلوب)
    - من تاريخ (اختياري)
    - إلى تاريخ (اختياري)

### 3. تقرير الفترة

- **الوصف**: يعرض جميع الطلبات خلال فترة زمنية محددة
- **المسار**: `/reports/period`
- **الفلاتر**:
    - من تاريخ (مطلوب)
    - إلى تاريخ (مطلوب)

### 4. تقرير الوجهة

- **الوصف**: يعرض جميع الطلبات المتجهة إلى وجهة معينة
- **المسار**: `/reports/destination`
- **الفلاتر**:
    - الوجهة (مطلوب)
    - من تاريخ (اختياري)
    - إلى تاريخ (اختياري)

### 5. تقرير الشركة

- **الوصف**: يعرض جميع الطلبات لشركة معينة
- **المسار**: `/reports/company`
- **الفلاتر**:
    - الشركة (مطلوب)
    - من تاريخ (اختياري)
    - إلى تاريخ (اختياري)

## هيكل الملفات

```
app/Http/Controllers/
└── ReportsController.php          # التحكم في التقارير

resources/js/pages/reports/
├── {Report}Form.vue               # نموذج اختيار المعايير
└── {Report}Report.vue             # صفحة عرض التقرير
```

## الراوت (Routes)

```php
// تقرير العملاء
GET /reports/customer          → customerIndex
GET /reports/customer/generate  → customerReport

// تقرير السائقين
GET /reports/driver             → driverIndex
GET /reports/driver/generate    → driverReport

// تقرير الفترة
GET /reports/period             → periodIndex
GET /reports/period/generate    → periodReport

// تقرير الوجهة
GET /reports/destination        → destinationIndex
GET /reports/destination/generate → destinationReport

// تقرير الشركة
GET /reports/company            → companyIndex
GET /reports/company/generate   → companyReport
```

## بيانات التقرير

كل تقرير يتضمن:

- **بيانات المؤسسة**: الاسم، الموقع، الهاتف، الشعار
- **معلومات التصفية**: التاريخ من، التاريخ إلى
- **جدول البيانات**: تفاصيل الطلبات
- **الإجماليات**: مجموع المبلغ، البنزين، الجازولين
- **عدد الطلبات**: إجمالي عدد الطلبات

## كيفية إضافة تقرير جديد

### 1. إضافة الراوت (routes/web.php)

```php
Route::prefix('reports')->group(function (): void {
    // راوت النموذج
    Route::get('new-report', [ReportsController::class, 'newReportIndex'])->name('reports.new.index');
    // راوت التقرير
    Route::get('new-report/generate', [ReportsController::class, 'newReportGenerate'])->name('reports.new.generate');
});
```

### 2. إضافة دوال التحكم (ReportsController.php)

```php
public function newReportIndex()
{
    // تجهيز البيانات للنموذج
    $options = SomeModel::query()->orderBy('name')->get(['id', 'name']);

    return Inertia::render('reports/NewReportForm', [
        'options' => $options,
    ]);
}

public function newReportGenerate(Request $request)
{
    // التحقق من البيانات
    $request->validate([
        'filter_field' => ['required', ...],
        'start_date' => ['nullable', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
    ]);

    // بناء الاستعلام
    $query = Order::query()->with(['customer', 'driver', 'warehouse']);

    // تطبيق الفلاتر
    if ($request->filter_field) {
        $query->where('field', 'like', '%' . $request->filter_field . '%');
    }

    if ($request->start_date) {
        $query->where('date', '>=', $request->start_date);
    }

    if ($request->end_date) {
        $query->where('date', '<=', $request->end_date);
    }

    $orders = $query->orderBy('date', 'desc')->get();

    // حساب الإجماليات
    $totalAmount = $orders->sum('amount');
    $totalBenzin = $orders->sum('benzin');
    $totalGasoline = $orders->sum('gasoline');

    return Inertia::render('reports/NewReport', [
        'settings' => $this->getSettings(),
        'filterData' => $request->filter_field,
        'orders' => $orders,
        'filters' => [
            'start_date' => $this->formatDate($request->start_date),
            'end_date' => $this->formatDate($request->end_date),
        ],
        'totals' => [
            'total_amount' => $this->formatMoney($totalAmount),
            'total_benzin' => $this->formatMoney($totalBenzin),
            'total_gasoline' => $this->formatMoney($totalGasoline),
            'orders_count' => $orders->count(),
        ],
    ]);
}
```

### 3. إنشاء نموذج Vue (NewReportForm.vue)

ينصح بالاطلاع على النماذج الموجودة مثل:

- `CompanyForm.vue` - لنماذج تتضمن اختيار شركة/عميل/سائق
- `PeriodForm.vue` - لنماذج تتضمن نطاق تاريخ فقط

### 4. إنشاء تقرير Vue (NewReport.vue)

ينصح بالاطلاع على التقارير الموجودة مثل `CompanyReport.vue` لمعرفية البنية.

## دوال المساعدة

| الدالة                 | الوصف                          |
| ---------------------- | ------------------------------ |
| `getSettings()`        | إرجاع الإعدادات العامة للمؤسسة |
| `formatDate($date)`    | تنسيق التاريخ إلى Y-m-d        |
| `formatMoney($amount)` | تنسيق الأرقام المالية          |

## أنماط الطباعة

جميع التقارير تدعم الطباعة عبر Tailwind classes:

- `print:hidden` - إخفاء العناصر في الطباعة
- `print:block` - إظهار العناصر فقط في الطباعة
- `print:text-xs` - حجم خط أصغر في الطباعة
- `@page { size: landscape }` - orientation أفقي

## أفضل الممارسات

1. **استخدم Eager Loading**: لتحميل العلاقات وتجنب N+1 queries

    ```php
    Order::with(['customer', 'driver', 'warehouse'])->...
    ```

2. **تحقق من المدخلات**: دائماً تحقق من بيانات الطلب

    ```php
    $request->validate([...]);
    ```

3. **استخدم `withQueryString()`**: للحفاظ على الفلاتر في Pagination

4. **نمط التنسيق**: استخدم دوال المساعدة للتنسيق المتسق

5. **ودية الطباعة**: أضف دائماً أنماط الطباعة للتقارير

## التنسيق

- الأرقام المالية: `1,234,567.00` (إنجليزي)
- التواريخ: `Y-m-d` (ميلادي)
- اللغة: العربية (RTL)
