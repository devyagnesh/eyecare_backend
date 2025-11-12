# Eyecare Admin Panel - Module Structure Plan

## Overview
Complete integration of Zynix theme with Laravel 11, following best practices and project rules.

## Module Architecture

### 1. Core Modules (Existing - To Enhance)
- ✅ **Users** - User management (needs theme integration)
- ✅ **Roles** - Role management (needs theme integration)
- ✅ **Permissions** - Permission management (needs theme integration)
- ✅ **Stores** - Store management (API only, needs admin panel)
- ✅ **Customers** - Customer management (API only, needs admin panel)
- ✅ **Eye Examinations** - Eye exam management (API only, needs admin panel)

### 2. New Modules (To Build)
- 🔨 **Settings** - System settings (key-value pairs)
- 🔨 **Products** - Product catalog management
- 🔨 **Categories** - Product categories
- 🔨 **Orders** - Order management system

## File Structure Per Module

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── {Module}Controller.php (RESTful)
│   │   │   └── Api/
│   │   │       └── {Module}Controller.php (API)
│   │   ├── Requests/
│   │   │   ├── Admin/
│   │   │   │   ├── Store{Module}Request.php
│   │   │   │   └── Update{Module}Request.php
│   │   │   └── Api/
│   │   │       ├── Store{Module}Request.php
│   │   │       └── Update{Module}Request.php
│   │   └── Resources/
│   │       └── {Module}Resource.php (API Resource)
│   ├── Policies/
│   │   └── {Module}Policy.php
│   └── Services/
│       └── {Module}Service.php
├── Models/
│   └── {Module}.php
├── Repositories/
│   └── {Module}Repository.php
└── database/
    └── migrations/
        └── YYYY_MM_DD_HHMMSS_create_{modules}_table.php

resources/
└── views/
    ├── admin/
    │   └── {module}/
    │       ├── index.blade.php (DataTables)
    │       ├── create.blade.php (Modal/Form)
    │       ├── edit.blade.php (Modal/Form)
    │       └── show.blade.php (Detail view)
    └── components/
        ├── datatable.blade.php (Reusable DataTable)
        ├── modal.blade.php (Reusable Modal)
        ├── form-input.blade.php (Reusable Input)
        └── form-select.blade.php (Reusable Select)
```

## Theme Components to Extract

### From `data-tables.html`:
- DataTables initialization
- Export buttons (CSV, Excel, PDF)
- Responsive table structure
- Search and filter UI

### From `form_advanced.html`:
- Form layouts
- Input groups
- Select2/Choices.js integration
- Date/time pickers
- File uploads
- Validation styles

### From `modals_closes.html`:
- Modal structures
- Modal sizes
- Modal animations

### From `sweet_alerts.html`:
- SweetAlert2 integration
- Confirmation dialogs
- Success/Error notifications

## API Structure

### Base Path: `/api/v1/`

### Response Format:
```json
{
    "success": true,
    "data": {},
    "message": "Success",
    "errors": null,
    "timestamp": "2024-01-15T10:30:00.000000Z"
}
```

### Error Format:
```json
{
    "success": false,
    "error_code": "VALIDATION_ERROR",
    "message": "The provided data is invalid.",
    "errors": {
        "field": ["Error message"]
    },
    "timestamp": "2024-01-15T10:30:00.000000Z"
}
```

## Implementation Order

1. **Phase 1: Foundation**
   - Create reusable Blade components
   - Extract theme patterns
   - Set up base structure

2. **Phase 2: Settings Module**
   - Simple key-value settings
   - Test component reusability

3. **Phase 3: Categories Module**
   - Hierarchical categories
   - Parent-child relationships

4. **Phase 4: Products Module**
   - Full CRUD with images
   - Category relationships
   - Stock management

5. **Phase 5: Orders Module**
   - Order creation
   - Status management
   - Product relationships

6. **Phase 6: Enhance Existing**
   - Update Users/Roles/Permissions with theme
   - Add admin panels for Stores/Customers/Examinations

7. **Phase 7: API & Documentation**
   - Create API Resources
   - Generate OpenAPI/Swagger specs
   - Complete API documentation

## Database Schema

### Settings
- id, key (unique), value, type, group, description, created_at, updated_at

### Categories
- id, name, slug, description, parent_id, image, sort_order, is_active, created_at, updated_at

### Products
- id, name, slug, description, sku, price, cost_price, stock_quantity, category_id, image, images (JSON), is_active, created_at, updated_at

### Orders
- id, order_number, customer_id, status, total_amount, tax_amount, shipping_amount, notes, created_at, updated_at

### Order Items
- id, order_id, product_id, quantity, price, total, created_at, updated_at

## Security & Authorization

- All admin routes protected by `auth` middleware
- Policies for each module
- Permission-based access control
- API uses Sanctum tokens
- Form requests for validation

## Next Steps

1. Create reusable components
2. Build Settings module (simplest)
3. Build Categories module
4. Build Products module
5. Build Orders module
6. Enhance existing modules
7. Generate OpenAPI documentation

