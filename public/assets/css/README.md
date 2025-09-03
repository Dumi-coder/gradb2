# CSS Variables & Font Size System

## Font Size Variables

We've implemented a comprehensive font size system using CSS variables for consistency across the entire project.

### Available Font Sizes

```css
/* Font Sizes - Typography Scale */
--font-xs: 0.75rem;      /* 12px - Extra Small */
--font-sm: 0.875rem;     /* 14px - Small */
--font-base: 1rem;       /* 16px - Base/Default */
--font-md: 1.125rem;     /* 18px - Medium */
--font-lg: 1.25rem;      /* 20px - Large */
--font-xl: 1.5rem;       /* 24px - Extra Large */
--font-2xl: 1.75rem;     /* 28px - 2X Large */
--font-3xl: 2rem;        /* 32px - 3X Large */
--font-4xl: 2.5rem;      /* 40px - 4X Large */
--font-5xl: 3rem;        /* 48px - 5X Large */
```

### Line Height Variables

```css
/* Line Heights */
--leading-tight: 1.25;    /* Compact spacing */
--leading-normal: 1.5;    /* Standard spacing */
--leading-relaxed: 1.75;  /* Comfortable spacing */
```

## Usage Examples

### Before (Hard-coded values)
```css
.card-title {
  font-size: 1.5rem;
  line-height: 1.6;
}

.description {
  font-size: 0.875rem;
  line-height: 1.5;
}
```

### After (Using variables)
```css
.card-title {
  font-size: var(--font-xl);
  line-height: var(--leading-relaxed);
}

.description {
  font-size: var(--font-sm);
  line-height: var(--leading-normal);
}
```

## Common Use Cases

- **Headings**: Use `--font-xl`, `--font-2xl`, `--font-3xl`
- **Body Text**: Use `--font-base` or `--font-md`
- **Small Text**: Use `--font-sm` or `--font-xs`
- **Buttons**: Use `--font-base` or `--font-md`
- **Captions**: Use `--font-xs` or `--font-sm`

## Benefits

✅ **Consistency** - All font sizes follow the same scale  
✅ **Maintainability** - Change sizes globally from one place  
✅ **Responsiveness** - Easy to adjust for different screen sizes  
✅ **Design System** - Professional typography hierarchy  
✅ **Reduced Scrolling** - Optimized for better content fit  

## Files Updated

- `Main.css` - Added font size variables
- `mentorship.css` - Updated to use variables
- `buttons.css` - Updated to use variables  
- `header.css` - Updated to use variables
- `dashboard.css` - Updated to use variables

## How to Add New Font Sizes

If you need a new font size, add it to the `:root` section in `Main.css`:

```css
:root {
  /* ... existing variables ... */
  --font-custom: 1.375rem;  /* 22px - Custom size */
}
```

Then use it in your CSS:

```css
.custom-text {
  font-size: var(--font-custom);
}
```
