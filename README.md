# IT Asset Management - PHP Native OOP (AR/EN)

> تطبيق ويب بسيط لإدارة الأصول التقنية داخل المؤسسة باستخدام PHP Native بأسلوب OOP وهيكلة MVC خفيفة.

> A lightweight PHP Native OOP web app to manage IT assets in an organization, built with a minimal MVC structure.

---

## 🇸🇦 نظرة عامة (Arabic)

- **الهدف**: إدارة أجهزة الحاسوب، الطابعات، تراخيص البرمجيات، المواقع، والمستخدمين.
- **المزايا الأساسية**:
  - CRUD للأصول (إضافة/تعديل/حذف/عرض)
  - تتبّع الحالة، الموقع، والمسؤول عن الأصل
  - مستخدمون وتسجيل دخول (لاحقاً)
  - تقارير بسيطة (لاحقاً)
- **البنية**: MVC خفيفة مع `Router`, `Controller`, `View`, `Request`, `Response`, `Session`, `Database (PDO)`.

### المتطلبات
- PHP 8.1+
- ملحقات: `pdo`, وواحد من `pdo_sqlite` (افتراضي) أو `pdo_mysql`
- اختيارياً: SQLite أو MySQL

### التثبيت والتشغيل محلياً
1. استنسخ المشروع:
   ```bash
   git clone <your-repo-url> it-asset-mgmt && cd it-asset-mgmt
   ```
2. (اختياري) أنشئ ملف بيئة `.env` لتجاوز الإعدادات، أو عدّل ملفات `config/*`.
3. الوضع الافتراضي يستخدم SQLite داخل `storage/database/app.sqlite`.
4. شغّل الخادم المدمج:
   ```bash
   ./scripts/serve.sh 8000
   ```
5. افتح المتصفح: `http://localhost:8000`

### هيكل المجلدات
```
app/
  Controllers/
  Core/            # Router, Request, Response, Controller, View, Session, Database
  Models/
  Repositories/
  Views/
    layouts/
    home/
bootstrap/
config/
public/            # index.php (Front Controller)
routes/
scripts/           # serve.sh
storage/
```

---

## 🇬🇧 Overview (English)

- **Goal**: Manage computers, printers, software licenses, locations, and users.
- **Key Features**:
  - Asset CRUD (create/read/update/delete)
  - Track status, location, and custodian
  - Users and authentication (upcoming)
  - Simple reporting (upcoming)
- **Architecture**: Minimal MVC with `Router`, `Controller`, `View`, `Request`, `Response`, `Session`, `Database (PDO)`.

### Requirements
- PHP 8.1+
- Extensions: `pdo`, and one of `pdo_sqlite` (default) or `pdo_mysql`
- Optional: SQLite or MySQL

### Local Setup
1. Clone the repo:
   ```bash
   git clone <your-repo-url> it-asset-mgmt && cd it-asset-mgmt
   ```
2. (Optional) Create `.env` to override config or edit `config/*`.
3. By default the app uses SQLite at `storage/database/app.sqlite`.
4. Run the built-in server:
   ```bash
   ./scripts/serve.sh 8000
   ```
5. Open: `http://localhost:8000`

### Folder Structure
```
app/
  Controllers/
  Core/
  Models/
  Repositories/
  Views/
bootstrap/
config/
public/
routes/
scripts/
storage/
```

---

## ضبط قاعدة البيانات (Database)

- الوضع الافتراضي: SQLite، لا حاجة لإعدادات إضافية.
- لاستخدام MySQL: عدّل `config/database.php` واجعل `driver => 'mysql'` وأدخل معلومات الاتصال.

```php
return [
  'driver' => 'mysql',
  'host' => '127.0.0.1',
  'port' => 3306,
  'database' => 'it_assets',
  'username' => 'root',
  'password' => '',
  'charset' => 'utf8mb4',
];
```

## أسئلة شائعة (FAQ)
- لا يوجد Composer؟ لا يعتمد المشروع على مكتبات خارجية حالياً. يمكن إضافة Composer لاحقاً.
- لا يبدأ الخادم؟ تأكد من تثبيت PHP محلياً ثم استخدم `./scripts/serve.sh`.

