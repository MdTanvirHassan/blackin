# Navigation Mega Menu Update

## Summary
Updated the navigation in `nav.blade.php` to display **2 categories** from the database with their subcategories appearing in a mega menu on hover, following the AS Colour dark navigation design.

## What Was Changed

### File: `resources/views/frontend/inc/nav.blade.php` (Lines 32-176)

### Key Updates:

#### 1. **Main Navigation Structure** (Lines 50-118)
```php
// Get first 2 categories
$mainCategories = get_level_zero_categories()->take(2);

// Loop through and display with mega menu
foreach ($mainCategories as $category)
```

#### 2. **Mega Menu Hierarchy** (Lines 62-103)
- **Level 1**: Main category (shown in nav bar)
- **Level 2**: Child categories (column titles with `.` accent)
- **Level 3**: Sub-subcategories (links under each column)

Structure:
```
Category 1 (Nav Item)
├── Mega Menu Dropdown
    ├── Column 1: Child Category 1
    │   ├── Sub-sub Category 1.1
    │   ├── Sub-sub Category 1.2
    │   └── ...
    ├── Column 2: Child Category 2
    │   └── ...
    └── ...
```

#### 3. **Search Modal** (Lines 135-176)
- Added full-screen search overlay
- Dark background with blur effect
- Clean, modern search input
- ESC key to close
- Click outside to close

#### 4. **Dynamic Column Layout**
- Automatically distributes subcategories into **up to 5 columns**
- Adjusts based on number of child categories
- Responsive grid layout

## Features Implemented

### ✅ Category Display
- Shows **exactly 2 main categories** from database
- Fetches them using `get_level_zero_categories()->take(2)`
- Displays their full category hierarchy

### ✅ Mega Menu on Hover
- Appears when hovering over category items
- Smooth fade-in animation
- Full-width dropdown with dark background (#2b2b2b)
- Backdrop overlay effect

### ✅ Visual Design
- **Dark theme** (#2b2b2b background)
- **Gold accents** (#b8860b) for hover states and dots
- **Column titles** with `.` accent in gold
- **Underline animation** on nav items
- **Smooth transitions** throughout

### ✅ Additional Menu Items
- Custom header menu items still display
- "Outlet" link highlighted in warning color
- Maintains all original functionality

### ✅ Search Functionality
- Click "Search" to open modal
- Full-screen overlay
- Clean search input with submit button
- Close with ESC key or click outside

### ✅ User Authentication
- Shows username if logged in
- Shows "Sign In" and "Create Account" if not logged in
- Links to appropriate pages

## CSS Styling

### Key Classes:
- `.ascolour-nav-item`: Navigation item wrapper
- `.ascolour-mega-menu`: Mega menu dropdown container
- `.ascolour-mega-content`: Grid layout for columns
- `.ascolour-mega-column`: Individual column wrapper
- `.ascolour-column-title`: Column headings with dot accent
- `.ascolour-column-links`: List of links in each column

### Hover Effects:
- Gold underline on nav items
- Color change to #b8860b
- Left padding on submenu links
- Backdrop overlay appears

## JavaScript Functionality

### Mega Menu:
```javascript
// Shows backdrop when hovering mega menu
navItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
        backdrop.classList.add('show');
    });
});
```

### Search Modal:
```javascript
function toggleSearch() {
    // Opens/closes search modal
    // Auto-focuses input field
}
```

### Banner Close:
```javascript
// Closes top banner on click
bannerClose.addEventListener('click', function() {
    document.querySelector('.ascolour-top-banner').style.display = 'none';
});
```

## Responsive Design

### Desktop (≥992px):
- Full navigation with mega menu
- 5-column grid layout
- All features enabled

### Tablet (992px - 1200px):
- 4-column grid layout
- Reduced padding on nav items
- Smaller font size

### Mobile (<992px):
- Navigation hidden
- Falls back to mobile sidebar menu
- Search modal still works

## Category Hierarchy Example

If you have this category structure:
```
Men (Level 0)
├── Apparel (Level 1)
│   ├── T-Shirts (Level 2)
│   ├── Longsleeves (Level 2)
│   └── Sweatshirts (Level 2)
├── Accessories (Level 1)
│   ├── Hats (Level 2)
│   └── Bags (Level 2)
└── ...

Women (Level 0)
├── Apparel (Level 1)
│   ├── T-Shirts (Level 2)
│   └── Dresses (Level 2)
└── ...
```

Navigation displays:
```
Logo | Men | Women | About | Journal | Outlet | Search | Cart | Sign In
```

Hover over "Men" shows:
```
┌─────────────────────────────────────────────────────────────┐
│  APPAREL.           ACCESSORIES.                             │
│  - T-Shirts         - Hats                                   │
│  - Longsleeves      - Bags                                   │
│  - Sweatshirts                                               │
└─────────────────────────────────────────────────────────────┘
```

## Testing Checklist

- [x] Navigation displays 2 categories
- [x] Mega menu appears on hover
- [x] All category links work
- [x] Column layout is balanced
- [x] Hover effects work smoothly
- [x] Search modal opens/closes
- [x] Banner can be closed
- [x] Backdrop appears/disappears
- [x] Responsive design works
- [x] No linter errors

## Configuration

### Change Number of Categories:
In `nav.blade.php` line 52:
```php
$mainCategories = get_level_zero_categories()->take(2); // Change 2 to desired number
```

### Customize Colors:
- Gold accent: `#b8860b`
- Dark background: `#2b2b2b`
- Hover text: `#fff`
- Link color: `#999`

### Adjust Columns:
Line 67:
```php
$totalColumns = min(5, $childCategories->count()); // Max 5 columns
```

## Notes

- Categories are fetched dynamically from database
- No database changes required
- Maintains all existing functionality
- Mobile menu unchanged
- Works with multi-language setups
- Translation ready

---

**Result**: A modern, professional navigation with mega menu showing 2 main categories and their complete subcategory hierarchy, matching the AS Colour design aesthetic with dark theme and gold accents.

