# Navigation Quick Reference

## Quick Customization Cheat Sheet

### 🎨 Colors

| Element | Location | Line | Default Value |
|---------|----------|------|---------------|
| Nav link color | `ascolour-megamenu.css` | 37 | `#000` |
| Nav hover bg | `ascolour-megamenu.css` | 55 | `#f5f5f5` |
| Border color | `ascolour-megamenu.css` | 12 | `#e5e5e5` |
| Active border | `ascolour-megamenu.css` | 204 | `#000` |
| Backdrop | `ascolour-megamenu.css` | 173 | `rgba(0,0,0,0.3)` |
| Mega title | `ascolour-megamenu.css` | 116 | `#666` |
| Mega link | `ascolour-megamenu.css` | 136 | `#333` |
| Mega hover | `ascolour-megamenu.css` | 147 | `#000` |

### 📏 Spacing

| Element | Location | Line | Default Value |
|---------|----------|------|---------------|
| Nav padding | `ascolour-megamenu.css` | 36 | `18px 20px` |
| Mega padding | `ascolour-megamenu.css` | 96 | `40px 20px` |
| Column gap | `ascolour-megamenu.css` | 99 | `40px` |
| Link spacing | `ascolour-megamenu.css` | 131 | `8px` |

### 🔤 Typography

| Element | Location | Line | Default Value |
|---------|----------|------|---------------|
| Nav font size | `ascolour-megamenu.css` | 38 | `13px` |
| Nav font weight | `ascolour-megamenu.css` | 39 | `600` |
| Nav letter spacing | `ascolour-megamenu.css` | 40 | `0.5px` |
| Mega title size | `ascolour-megamenu.css` | 113 | `11px` |
| Mega link size | `ascolour-megamenu.css` | 138 | `14px` |

### ⚙️ Configuration

| Setting | Location | Line | Default Value |
|---------|----------|------|---------------|
| Categories shown | `nav.blade.php` | 208 | `8` |
| Subcategories shown | `nav.blade.php` | 241 | `20` |
| Number of columns | `nav.blade.php` | 242 | `3` |
| Column min width | `ascolour-megamenu.css` | 98 | `200px` |
| Max container width | `ascolour-megamenu.css` | 18 | `1400px` |

## Common Tasks

### Change Navigation Background
```css
/* ascolour-megamenu.css line 11 */
.ascolour-nav-bar {
    background: #f8f8f8; /* Change from #fff */
}
```

### Add More Categories
```php
/* nav.blade.php line 208 */
$level_zero_categories = get_level_zero_categories()->take(12); // Was 8
```

### Change Column Headers
```php
/* nav.blade.php lines 248-254 */
@if($chunkIndex == 0)
    New Arrivals    // Was "Categories"
@elseif($chunkIndex == 1)
    Best Sellers   // Was "Popular"
@else
    Sale Items     // Was "Featured"
@endif
```

### Adjust Hover Animation Speed
```css
/* ascolour-megamenu.css line 85 */
.ascolour-mega-menu {
    transition: all 0.2s ease; /* Was 0.3s */
}
```

### Change Mobile Breakpoint
```css
/* ascolour-megamenu.css line 222 */
@media (max-width: 767px) { /* Was 991px */
    .ascolour-nav-bar {
        display: none !important;
    }
}
```

### Add 4 Columns Instead of 3
```php
/* nav.blade.php line 242 */
$chunks = $children->chunk(ceil($children->count() / 4)); // Was 3
```

### Reduce Backdrop Opacity
```css
/* ascolour-megamenu.css line 173 */
.ascolour-backdrop {
    background: rgba(0,0,0,0.15); /* Was 0.3 */
}
```

### Increase Font Size
```css
/* ascolour-megamenu.css line 38 */
.ascolour-nav-link {
    font-size: 15px; /* Was 13px */
}
```

### Remove Text Transform
```css
/* ascolour-megamenu.css line 41 */
.ascolour-nav-link {
    text-transform: none; /* Was uppercase */
}
```

### Add Shadow to Mega Menu
```css
/* ascolour-megamenu.css line 76 */
.ascolour-mega-menu {
    box-shadow: 0 8px 24px rgba(0,0,0,0.15); /* Was 0.08 */
}
```

## File Structure

```
Project Root
├── resources/views/frontend/inc/
│   └── nav.blade.php              ← Main navigation markup
├── public/assets/css/
│   └── ascolour-megamenu.css      ← Navigation styles
└── docs/
    ├── ASCOLOUR_NAVIGATION.md     ← Full documentation
    └── NAVIGATION_QUICK_REFERENCE.md ← This file
```

## Key Classes

| Class | Purpose |
|-------|---------|
| `.ascolour-nav-bar` | Main navigation container |
| `.ascolour-nav-container` | Width-constrained wrapper |
| `.ascolour-nav-list` | Flex container for nav items |
| `.ascolour-nav-item` | Individual navigation item |
| `.ascolour-nav-link` | Navigation link element |
| `.ascolour-mega-menu` | Mega menu dropdown container |
| `.ascolour-mega-content` | Grid container for columns |
| `.ascolour-mega-column` | Individual column |
| `.ascolour-mega-title` | Column header |
| `.ascolour-mega-links` | List of links in column |
| `.ascolour-backdrop` | Dark overlay behind mega menu |

## Debug Checklist

- [ ] Categories exist in database
- [ ] Categories have subcategories
- [ ] CSS file is loaded
- [ ] JavaScript is running (check console)
- [ ] Z-index conflicts resolved
- [ ] Breakpoints are correct
- [ ] No CSS overrides from other files

## Browser DevTools Tips

### Inspect Hover State
```
1. Open DevTools (F12)
2. Right-click navigation item
3. Select "Inspect"
4. Click :hov in Styles panel
5. Check :hover
```

### Check if JavaScript Loaded
```javascript
// Run in console
document.querySelectorAll('.ascolour-nav-item').length
// Should return number of nav items
```

### Test Backdrop
```javascript
// Run in console
document.querySelector('.ascolour-backdrop').classList.add('active');
// Backdrop should appear
```

## Performance Tips

1. **Limit Categories**: Don't show more than 10 in navigation
2. **Limit Subcategories**: Cap at 20-30 per category
3. **Use CSS Transforms**: Already implemented for smooth animations
4. **Avoid Inline Styles**: Keep styles in CSS file
5. **Minify CSS**: For production, minify ascolour-megamenu.css

## Integration Checklist

- [x] Navigation file updated (`nav.blade.php`)
- [x] CSS file created (`ascolour-megamenu.css`)
- [x] Documentation created
- [ ] Test with real categories
- [ ] Test on different screen sizes
- [ ] Test hover interactions
- [ ] Test keyboard navigation
- [ ] Cross-browser testing
- [ ] Mobile fallback working
- [ ] Performance optimization
- [ ] Production deployment

## Quick Links

- [Full Documentation](./ASCOLOUR_NAVIGATION.md)
- [ASColour Website](https://ascolour.com/)
- [Laravel Blade Docs](https://laravel.com/docs/blade)
- [CSS Grid Guide](https://css-tricks.com/snippets/css/complete-guide-grid/)

---

**Version**: 1.0.0  
**Last Updated**: November 5, 2025

