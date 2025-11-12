# Views Verification Checklist

This document helps ensure all required Blade views are created and maintained.

## Complete View List

### Layout Views
- ✅ `resources/views/layouts/dashboard.blade.php` - Main dashboard layout
- ✅ `resources/views/layouts/partials/_head.blade.php` - Head section
- ✅ `resources/views/layouts/partials/_scripts.blade.php` - Scripts section
- ✅ `resources/views/layouts/partials/_header.blade.php` - Header navigation
- ✅ `resources/views/layouts/partials/_sidebar.blade.php` - Sidebar menu
- ✅ `resources/views/layouts/partials/_footer.blade.php` - Footer
- ✅ `resources/views/layouts/partials/_switcher.blade.php` - Theme switcher

### Authentication Views
- ✅ `resources/views/auth/login.blade.php`
- ✅ `resources/views/auth/forgot-password.blade.php`
- ✅ `resources/views/auth/reset-password.blade.php`
- ✅ `resources/views/auth/password-reset-success.blade.php`
- ✅ `resources/views/auth/verify-email-success.blade.php`
- ✅ `resources/views/auth/verify-email-failed.blade.php`
- ✅ `resources/views/auth/verify-email-already.blade.php`

### Dashboard Views
- ✅ `resources/views/dashboard/index.blade.php`

### Admin - Users Views
- ✅ `resources/views/admin/users/index.blade.php` - List all users
- ✅ `resources/views/admin/users/create.blade.php` - Create new user
- ✅ `resources/views/admin/users/show.blade.php` - View user details
- ✅ `resources/views/admin/users/edit.blade.php` - Edit user

### Admin - Roles Views
- ✅ `resources/views/admin/roles/index.blade.php` - List all roles
- ✅ `resources/views/admin/roles/create.blade.php` - Create new role
- ✅ `resources/views/admin/roles/show.blade.php` - View role details
- ✅ `resources/views/admin/roles/edit.blade.php` - Edit role

### Admin - Permissions Views
- ✅ `resources/views/admin/permissions/index.blade.php` - List all permissions
- ✅ `resources/views/admin/permissions/create.blade.php` - Create new permission
- ✅ `resources/views/admin/permissions/show.blade.php` - View permission details
- ✅ `resources/views/admin/permissions/edit.blade.php` - Edit permission

### Admin - API Documentation Views
- ✅ `resources/views/admin/api-documentation/index.blade.php`

## How to Verify All Views Exist

Run this PowerShell command to check:
```powershell
Get-ChildItem -Path "resources\views" -Recurse -Filter "*.blade.php" | Select-Object FullName
```

## How to Find Missing Views

1. Search for all `return view()` calls in controllers:
```powershell
grep -r "return view(" app/Http/Controllers
```

2. Compare with existing views:
```powershell
Get-ChildItem -Path "resources\views" -Recurse -Filter "*.blade.php" | Select-Object Name
```

3. Check for missing views by comparing controller view calls with actual files.

## Best Practices

1. **Always create views when adding new controllers/routes**
2. **Use consistent naming**: `resource.action.blade.php` (e.g., `users.index.blade.php`)
3. **Follow RESTful conventions**: index, create, show, edit for resources
4. **Extend the dashboard layout** for admin views: `@extends('layouts.dashboard')`
5. **Use theme components** from `public/assets/` only
6. **Test views after creation** to ensure they render correctly

## Theme Integration Rules

- ✅ All views must use `@extends('layouts.dashboard')` for admin pages
- ✅ All views must use theme assets from `public/assets/`
- ✅ No custom CSS - use theme classes only
- ✅ No CDN links - all assets must be local
- ✅ Use theme's form components and styling
- ✅ Follow theme's card and table structures

