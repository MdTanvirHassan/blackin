# Sidebar Style Mega Menu Setup Guide

## Overview
Updated the navigation to display subcategories in a **270px sidebar-style menu** that appears on hover, with category icons and dynamic subcategory loading.

## What Was Implemented

### 1. **Sidebar Style Mega Menu** (270px wide)
- White background with shadow
- Category icons (16x16px)
- Clean, vertical list layout
- Smooth hover animations

### 2. **Dynamic Subcategory Loading**
- Shows loading spinner initially
- Loads subcategories via AJAX on hover
- Caches loaded data (loads only once)
- Error handling included

### 3. **Visual Design**
```
Navigation Bar: [Logo] [Men] [Women] [About] | [Search] [Cart]
                         ↓ (on hover)
                    ┌──────────────┐
                    │ 🖼 T-Shirts   │ → [Sub-sub categories panel]
                    │ 🖼 Longsleeves│
                    │ 🖼 Sweatshirts│
                    │ 🖼 Jackets    │
                    └──────────────┘
```

## File Modified

### `resources/views/frontend/inc/nav.blade.php`

**Key Changes:**

#### 1. Menu Structure (Lines 66-99)
```php
<div class="ascolour-mega-menu">
    <div class="aiz-category-menu bg-white" style="width:270px;">
        <ul class="list-unstyled categories">
            // Category items with icons
        </ul>
    </div>
</div>
```

#### 2. Category Items
- Shows category icon
- Category name
- Hover reveals sub-category panel
- Loading spinner for AJAX

#### 3. CSS Styling (Lines 314-411)
- Sidebar positioning (left: 0)
- 270px fixed width
- Shadow effects
- Hover states
- Scrollbar styling

#### 4. JavaScript (Lines 554-634)
- Backdrop control
- AJAX subcategory loading
- Loading spinner management
- Error handling

## Backend Setup Required

You need to create a route and controller method to load subcategories dynamically.

### Step 1: Add Route

**File:** `routes/web.php`

Add this route:
```php
Route::post('/categories/subcategories', [CategoryController::class, 'getSubcategories'])->name('categories.subcategories');
```

### Step 2: Add Controller Method

**File:** `app/Http/Controllers/CategoryController.php`

Add this method:
```php
public function getSubcategories(Request $request)
{
    try {
        $categoryId = $request->category_id;
        $category = Category::find($categoryId);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ]);
        }
        
        $subCategories = $category->children;
        
        if ($subCategories->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No subcategories found'
            ]);
        }
        
        // Build HTML for subcategories
        $html = '<div class="p-3">';
        $html .= '<div class="row gutters-5">';
        
        foreach ($subCategories as $subCat) {
            $html .= '<div class="col-6 mb-3">';
            $html .= '<a href="' . route('products.category', $subCat->slug) . '" class="d-block text-reset">';
            $html .= '<div class="p-2 border rounded text-center hov-shadow-sm">';
            
            // Category icon if exists
            if (isset($subCat->catIcon->file_name)) {
                $html .= '<img src="' . my_asset($subCat->catIcon->file_name) . '" class="img-fluid mb-2" style="max-height: 40px;">';
            }
            
            $html .= '<div class="fs-13 fw-600 text-truncate">' . $subCat->getTranslation('name') . '</div>';
            $html .= '</div>';
            $html .= '</a>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error loading subcategories',
            'error' => $e->getMessage()
        ], 500);
    }
}
```

### Alternative: Simple Version

If you prefer a simpler version without AJAX, you can load all subcategories upfront:

**Update nav.blade.php (Line 88-94):**
```php
@if ($subCategoryChildren->count() > 0)
    <div class="sub-cat-menu more c-scrollbar-light border p-3 shadow-none">
        <div class="row gutters-5">
            @foreach($subCategoryChildren as $subCategory)
                <div class="col-6 mb-2">
                    <a href="{{ route('products.category', $subCategory->slug) }}" 
                       class="d-block p-2 border rounded text-center text-reset hov-shadow-sm">
                        <div class="fs-13 fw-600 text-truncate">
                            {{ $subCategory->getTranslation('name') }}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
```

And **remove the AJAX JavaScript** (Lines 589-633).

## Features

### ✅ Sidebar Style Menu
- **270px fixed width**
- White background
- Clean, vertical layout
- Subtle shadow

### ✅ Category Display
- Category icon (16x16px)
- Category name
- Hover effect (background changes)
- Padding animation on hover

### ✅ Sub-Category Panel
- Appears to the right (left: 100%)
- **400px minimum width**
- Max height: 500px (scrollable)
- Grid layout (2 columns)
- Loading spinner initially

### ✅ Interactions
- Hover over main category → Shows sidebar
- Hover over subcategory → Shows sub-sub categories
- Click anywhere → Closes menu
- Smooth animations throughout

### ✅ Responsive Design
- Desktop only (hidden on mobile)
- Mobile uses sidebar menu
- Smooth transitions

## CSS Classes Used

### Main Classes
- `.ascolour-mega-menu` - Dropdown container
- `.aiz-category-menu` - Sidebar wrapper (270px)
- `.category-nav-element` - Individual category item
- `.cat-image` - Category icon
- `.cat-name` - Category name
- `.sub-cat-menu` - Sub-category panel
- `.c-preloader` - Loading spinner

### Utility Classes
- `.no-scrollbar` - Hides scrollbar
- `.c-scrollbar-light` - Custom scrollbar styling
- `.hov-column-gap-1` - Hover effect
- `.text-truncate` - Truncate long text

## JavaScript Functions

### 1. Mega Menu Hover
```javascript
navItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
        backdrop.classList.add('show');
    });
});
```

### 2. AJAX Subcategory Loading
```javascript
element.addEventListener('mouseenter', function() {
    if (!loaded) {
        // Fetch subcategories
        fetch('/categories/subcategories', {...})
    }
});
```

### 3. Loading State
- Shows spinner while loading
- Replaces with content when loaded
- Shows error message if fails
- Caches result (loads only once)

## Customization

### Change Sidebar Width
```css
/* Line 67 */
<div class="aiz-category-menu" style="width:270px;">
<!-- Change 270px to desired width -->
```

### Change Sub-Category Panel Width
```css
/* Line 372 */
.sub-cat-menu {
    min-width: 400px; /* Change this */
}
```

### Change Number of Categories
```php
/* Line 52 */
$mainCategories = get_level_zero_categories()->take(2);
// Change 2 to desired number
```

### Customize Colors
```css
/* Category hover background */
.category-nav-element:hover {
    background: #f8f9fa; /* Change color */
}

/* Category text */
.cat-name {
    color: #333; /* Change color */
}
```

## Testing Checklist

- [ ] Hover over main category
- [ ] Sidebar appears (270px width)
- [ ] Category icons display
- [ ] Hover over subcategory
- [ ] Sub-panel appears to the right
- [ ] Loading spinner shows
- [ ] Subcategories load correctly
- [ ] Click category link works
- [ ] Click subcategory link works
- [ ] Backdrop appears/closes
- [ ] No console errors
- [ ] Mobile view hides navigation

## Troubleshooting

### Issue: Subcategories not loading

**Solution:**
1. Check if route exists: `php artisan route:list | grep subcategories`
2. Verify CSRF token is valid
3. Check browser console for errors
4. Verify controller method exists

### Issue: Menu appears off-screen

**Solution:**
```css
.ascolour-mega-menu {
    left: 0; /* Align to left */
    /* or */
    right: 0; /* Align to right */
}
```

### Issue: Icons not showing

**Solution:**
1. Verify category has icon uploaded
2. Check file path in `catIcon->file_name`
3. Verify `my_asset()` helper works
4. Check image error fallback

### Issue: Menu too tall

**Solution:**
```css
.aiz-category-menu {
    max-height: 500px;
    overflow-y: auto;
}
```

## Performance Tips

1. **Lazy Load Subcategories**: Use AJAX (already implemented)
2. **Cache Results**: JavaScript caches loaded data
3. **Limit Categories**: Show only featured categories
4. **Optimize Icons**: Use SVG or optimized images
5. **Limit Subcategories**: Show max 12 per category

## Browser Compatibility

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (hidden, uses sidebar)

---

## Result

A modern, clean sidebar-style mega menu that:
- Shows 2 main categories
- Displays subcategories in a 270px sidebar on hover
- Loads sub-subcategories dynamically via AJAX
- Includes category icons and smooth animations
- Fully responsive and performant

