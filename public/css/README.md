# Dashboard Styling Documentation

## 📁 File Structure
```
public/
├── css/
│   └── dashboard.css       # Main dashboard styles
├── js/
│   └── dashboard.js        # Dashboard interactions
└── README.md              # This documentation
```

## 🎨 CSS Classes Reference

### Layout Classes
- `.dashboard-container` - Main container
- `.dashboard-header` - Header section with gradient
- `.stats-row` - Statistics cards grid
- `.info-section` - Information tables grid

### Card Components
- `.stat-card` - Statistics card
- `.stat-icon` - Icon container in stat card
- `.stat-info` - Text content in stat card
- `.info-card` - Information table card

### Table Styling
- `.info-table` - Main table class
- `.table-responsive` - Responsive table wrapper
- `.table-icon` - Icons in table cells

### Badge Components
- `.category-badge` - Green badge for categories
- `.quantity-badge` - Red badge for quantities with shimmer effect

### Utility Classes
- `.fade-in` - Fade in animation
- `.shadow-hover` - Enhanced hover shadow
- `.text-center` - Center aligned text

## 🎯 Color Scheme

### Primary Colors
- **Primary Gradient**: `#667eea` → `#764ba2`
- **Secondary Gradient**: `#f093fb` → `#f5576c`
- **Success Gradient**: `#10b981` → `#059669`
- **Warning Gradient**: `#f59e0b` → `#d97706`
- **Danger Gradient**: `#ef4444` → `#dc2626`
- **Info Gradient**: `#4facfe` → `#00f2fe`

### Background Colors
- **Main Background**: `#f5f7fa`
- **Card Background**: `#ffffff`
- **Table Header**: `#6366f1`

## 📱 Responsive Breakpoints

### Desktop (1024px+)
- Full grid layout
- All animations enabled
- Maximum padding and spacing

### Tablet (768px - 1024px)
- Reduced grid columns
- Adjusted padding
- Maintained animations

### Mobile (480px - 768px)
- Single column layout
- Reduced font sizes
- Simplified spacing

### Small Mobile (< 480px)
- Minimal padding
- Compact badges
- Smaller icons

## ⚡ JavaScript Features

### Core Functions
- `updateDateTime()` - Updates date display
- `initFadeInAnimation()` - Card entrance animations
- `initHoverEffects()` - Enhanced hover interactions
- `initTableEffects()` - Table row interactions
- `createRippleEffect()` - Click ripple animations

### Global Functions
- `window.refreshDashboard()` - Refresh entire dashboard
- `window.DashboardJS` - Access to core functions

## 🔧 Customization

### Changing Colors
Edit the CSS variables in `dashboard.css`:
```css
:root {
    --primary-gradient: linear-gradient(135deg, #your-color1, #your-color2);
    --secondary-gradient: linear-gradient(135deg, #your-color3, #your-color4);
}
```

### Adding New Badge Types
```css
.your-badge {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #color1, #color2);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
}
```

### Custom Animations
```css
@keyframes your-animation {
    from { /* start state */ }
    to { /* end state */ }
}

.your-element {
    animation: your-animation 0.5s ease;
}
```

## 📋 Usage Examples

### Basic Card
```html
<div class="stat-card">
    <div class="stat-icon">
        <i class="fas fa-icon"></i>
    </div>
    <div class="stat-info">
        <h3>Title</h3>
        <p>Value</p>
    </div>
</div>
```

### Badge Usage
```html
<span class="category-badge">
    <i class="fas fa-tag"></i>
    Category Name
</span>

<span class="quantity-badge">123</span>
```

### Table Structure
```html
<div class="info-card">
    <h2><i class="fas fa-icon"></i> Table Title</h2>
    <div class="table-responsive">
        <table class="info-table">
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Data 1</td>
                    <td>Data 2</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

## 🚀 Performance Tips

1. **CSS is optimized** with minimal redundancy
2. **JavaScript uses event delegation** for better performance
3. **Animations are GPU-accelerated** using transform properties
4. **Images and icons** are loaded from CDN for faster delivery
5. **Print styles** are included for better printing experience

## 🔍 Browser Support

- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile browsers**: iOS Safari 14+, Chrome Mobile 90+

## 📝 Notes

- All animations respect `prefers-reduced-motion` for accessibility
- Colors have sufficient contrast ratios for WCAG compliance
- Responsive design tested on multiple device sizes
- Print styles preserve important information when printing

## 🆘 Troubleshooting

### Styles not loading?
1. Check if `dashboard.css` path is correct
2. Verify Laravel asset compilation
3. Clear browser cache

### JavaScript not working?
1. Check if `dashboard.js` path is correct
2. Open browser console for error messages
3. Ensure DOM is fully loaded before script execution

### Responsive issues?
1. Check viewport meta tag in layout
2. Test on actual devices, not just browser resize
3. Verify CSS media queries are not overridden
