# ASColour Style Navigation System

This documentation covers the implementation of an ASColour-inspired mega menu navigation system for the e-commerce platform.

## Overview

The navigation system is designed to mimic the professional and clean navigation found on [ASColour's website](https://ascolour.com/), featuring:

- Hover-activated mega menus
- Multi-column subcategory display
- Smooth animations and transitions
- Responsive design (desktop only, with mobile sidebar fallback)
- Dynamic category loading from database

## File Structure

```
├── resources/views/frontend/inc/
│   └── nav.blade.php                 # Main navigation component
├── public/assets/css/
│   └── ascolour-megamenu.css        # Navigation stylesheet
└── docs/
    └── ASCOLOUR_NAVIGATION.md       # This file
```

## Features

### 1. **Dynamic Category Integration**
- Automatically pulls categories from your database using `get_level_zero_categories()`
- Displays up to 8 top-level categories
- Shows subcategories in organized columns when hovering

### 2. **Mega Menu Structure**
Each category with subcategories displays a mega menu with:
- "All [Category]" link as first column
- Subcategories organized into multiple columns:
  - **Categories** - First group of subcategories
  - **Popular** - Second group of subcategories  
  - **Featured** - Third group of subcategories

### 3. **Styling Features**
- Clean, minimalist design
- Uppercase navigation links with proper letter spacing
- Smooth hover effects with background color transitions
- Active state indication with bottom border
- Semi-transparent backdrop overlay when mega menu is open

### 4. **Responsive Behavior**
- **Desktop (992px+)**: Full mega menu navigation
- **Tablet/Mobile (< 992px)**: Hidden, uses existing mobile sidebar navigation

## Customization Guide

### Changing Number of Displayed Categories

Edit line 208 in `nav.blade.php`:

```php
$level_zero_categories = get_level_zero_categories()->take(8); // Change 8 to desired number
```

### Modifying Column Headers

Edit lines 248-254 in `nav.blade.php`:

```php
@if($chunkIndex == 0)
    Categories           // First column header
@elseif($chunkIndex == 1)
    Popular             // Second column header
@else
    Featured            // Third column header
@endif
```

### Adjusting Number of Columns

Modify line 242 in `nav.blade.php`:

```php
$chunks = $children->chunk(ceil($children->count() / 3)); // Change 3 to desired number of columns
```

### Color Customization

Edit the CSS in `public/assets/css/ascolour-megamenu.css`:

```css
/* Navigation link color */
.ascolour-nav-link {
    color: #000;  /* Change this */
}

/* Hover background */
.ascolour-nav-link:hover {
    background-color: #f5f5f5;  /* Change this */
}

/* Active border */
.ascolour-nav-link.active::after {
    background: #000;  /* Change this */
}

/* Border colors */
.ascolour-nav-bar {
    border-bottom: 1px solid #e5e5e5;  /* Change this */
}
```

### Font Customization

```css
.ascolour-nav-link {
    font-size: 13px;        /* Link font size */
    font-weight: 600;       /* Link font weight */
    letter-spacing: 0.5px;  /* Letter spacing */
}

.ascolour-mega-links a {
    font-size: 14px;        /* Submenu font size */
    font-weight: 400;       /* Submenu font weight */
}
```

### Spacing Adjustments

```css
/* Navigation link padding */
.ascolour-nav-link {
    padding: 18px 20px;  /* Vertical | Horizontal */
}

/* Mega menu content padding */
.ascolour-mega-content {
    padding: 40px 20px;  /* Vertical | Horizontal */
    gap: 40px;           /* Gap between columns */
}
```

## JavaScript Functionality

The navigation includes JavaScript for:

1. **Backdrop Management**: Shows/hides backdrop overlay when hovering over items with mega menus
2. **Click-to-Close**: Clicking the backdrop closes the mega menu
3. **Event Listeners**: Attached on DOM ready for proper functionality

### Key JavaScript Functions

```javascript
// Show backdrop on hover
item.addEventListener('mouseenter', function() {
    backdrop.classList.add('active');
});

// Hide backdrop on leave
item.addEventListener('mouseleave', function() {
    backdrop.classList.remove('active');
});

// Close on backdrop click
backdrop.addEventListener('click', function() {
    this.classList.remove('active');
});
```

## Adding Additional Static Menu Items

The navigation supports additional menu items from your settings. These are automatically loaded from:

```php
get_setting('header_menu_labels')  // Menu labels
get_setting('header_menu_links')   // Menu links
```

These items appear after the dynamic category items and do not have mega menus.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (uses fallback sidebar)

## Performance Considerations

1. **Category Limit**: Limited to 8 categories to prevent horizontal overflow
2. **Subcategory Limit**: Shows up to 20 subcategories per category (line 241)
3. **CSS Animations**: Uses GPU-accelerated transforms for smooth performance
4. **Lazy Loading**: Mega menu content is only rendered when categories exist

## Accessibility Features

- Focus-visible indicators for keyboard navigation
- Semantic HTML structure
- ARIA-friendly markup
- High contrast text for readability
- Skip navigation support (inherited from main template)

## Troubleshooting

### Mega Menu Not Showing

**Issue**: Hover doesn't trigger mega menu
**Solution**: Check that categories have subcategories in database

```php
// Debug: Check if category has children
@if ($hasChildren)
    {{ $category->childrenCategories->count() }} subcategories found
@endif
```

### Styling Conflicts

**Issue**: Navigation styles conflict with existing CSS
**Solution**: Prefix is already included (.ascolour-*). Ensure specificity:

```css
/* Increase specificity if needed */
nav.ascolour-nav-bar .ascolour-nav-link {
    /* styles */
}
```

### Responsive Issues

**Issue**: Navigation shows on mobile
**Solution**: Ensure breakpoint media query is not overridden

```css
@media (max-width: 991px) {
    .ascolour-nav-bar {
        display: none !important;  /* !important ensures it's hidden */
    }
}
```

### JavaScript Not Working

**Issue**: Backdrop not appearing
**Solution**: Check that script runs after DOM ready

```javascript
// Ensure this wraps your code
document.addEventListener('DOMContentLoaded', function() {
    // Navigation code here
});
```

## Future Enhancements

Potential improvements for future versions:

1. **Keyboard Navigation**: Full keyboard support with arrow keys
2. **Mobile Mega Menu**: Adapted mega menu for tablet/mobile
3. **Category Images**: Add images to subcategory links
4. **Featured Products**: Show featured products in mega menu
5. **Search Integration**: Quick search within mega menu
6. **Analytics**: Track which categories are clicked most
7. **A/B Testing**: Test different mega menu layouts
8. **Lazy Load Images**: If category images are added
9. **RTL Support**: Right-to-left language support
10. **Dark Mode**: Alternative color scheme

## Support

For questions or issues related to this navigation system:

1. Check this documentation first
2. Review the inline comments in `nav.blade.php`
3. Inspect browser console for JavaScript errors
4. Check database for category structure

## Credits

Inspired by the navigation design from [ASColour](https://ascolour.com/), a premium blank apparel brand from New Zealand.

## Changelog

### Version 1.0.0 (Current)
- Initial implementation
- Dynamic category integration
- Hover-activated mega menus
- Multi-column layout
- Responsive design
- Backdrop overlay
- Active state indication
- Custom CSS animations

---

**Last Updated**: November 5, 2025
**Version**: 1.0.0

