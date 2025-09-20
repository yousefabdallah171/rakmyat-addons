# Rakmyat Addons Plugin

![Rakmyat Addons](https://img.shields.io/badge/Version-2.0.0-blue?style=flat-square) ![WordPress](https://img.shields.io/badge/WordPress-5.0+-green?style=flat-square) ![Elementor](https://img.shields.io/badge/Elementor-3.0+-orange?style=flat-square) ![PHP](https://img.shields.io/badge/PHP-7.4+-purple?style=flat-square)

**Rakmyat Addons** is an advanced WordPress plugin that extends Elementor with powerful custom widgets. Built with modern architecture, automatic widget discovery, and professional SCSS compilation for enhanced website functionality.

## 🚀 Overview

Rakmyat Addons provides a collection of premium Elementor widgets designed to enhance your website's functionality and visual appeal. With automatic widget discovery and SCSS compilation, creating and managing widgets has never been easier.

## ✨ Key Features

- 🎯 **Automatic Widget Discovery** - Zero configuration widget system
- 🎨 **SCSS Compilation** - Professional styling workflow with global variables
- 📱 **Responsive Design** - Mobile-first, fully responsive widgets
- ♿ **Accessibility Ready** - WCAG compliant with proper ARIA labels
- 🌐 **RTL Support** - Full right-to-left language support
- ⚡ **Performance Optimized** - Lightweight, fast-loading widgets
- 🔧 **Developer Friendly** - Clean code, well-documented

## 📦 Included Widgets

### 📝 Advanced Heading Widget
- **Typography controls** - Complete font styling options
- **Gradient text effects** - Beautiful gradient overlays
- **Animation support** - Smooth entrance animations
- **Responsive design** - Perfect on all devices

### 📰 Blog Posts Widget
- **Grid and list layouts** - Flexible display options
- **Advanced filtering** - Category, tag, and custom filters
- **Pagination support** - Load more and numeric pagination
- **Custom post types** - Support for any post type

### 🎬 Video Widget
- **Multiple sources** - YouTube, Vimeo, self-hosted
- **Custom thumbnails** - Beautiful video previews
- **Lightbox support** - Popup video playback
- **Responsive embed** - Perfect scaling on all devices

## 🛠️ Installation

1. Upload the plugin files to `/wp-content/plugins/rakmyat-addons/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Ensure Elementor is installed and activated
4. Find widgets in Elementor under "Rakmyat" category

## 📋 Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.4 or higher
- Modern browser support

## 🔧 Development Guide

### Creating New Widgets

The plugin uses an automatic widget discovery system. To create a new widget:

#### 1. Create Widget Folder
```
elements/widgets/my-widget/
```

#### 2. Create Widget File
```php
// elements/widgets/my-widget/my-widget.php
<?php
namespace RakmyatAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class My_Widget extends Widget_Base {

    public function get_name() {
        return 'rakmyat-my-widget';
    }

    public function get_title() {
        return __('My Widget', 'rakmyat-addons');
    }

    public function get_icon() {
        return 'eicon-star';
    }

    public function get_categories() {
        return ['rakmyat'];
    }

    protected function register_controls() {
        // Add widget controls here
    }

    protected function render() {
        // Widget frontend output
    }
}
```

#### 3. Add SCSS (Optional)
```scss
// elements/assets/scss/my-widget/my-widget.scss
@import '../variables';

.rakmyat-my-widget {
    padding: $spacing-md;
    background: $bg-primary;

    &__title {
        color: $primary-color;
        font-family: $font-family-primary;
    }

    @media (max-width: $breakpoint-md) {
        padding: $spacing-sm;
    }
}
```

#### 4. Add JavaScript (Optional)
```javascript
// elements/assets/js/my-widget/my-widget.js
(function($) {
    'use strict';

    $(document).ready(function() {
        $('.rakmyat-my-widget').each(function() {
            // Widget initialization
        });
    });

})(jQuery);
```

### Widget Requirements

- **Namespace:** `RakmyatAddons\Widgets`
- **Class Name:** PascalCase with underscores (e.g., `My_Widget`)
- **File Name:** Hyphenated matching folder (e.g., `my-widget.php`)
- **Widget Name:** Prefix with `rakmyat-` (e.g., `rakmyat-my-widget`)
- **Category:** `'rakmyat'`

### Available SCSS Variables

Global SCSS variables in `elements/assets/scss/_variables.scss`:

```scss
// Brand Colors
$primary-color: #007cba;
$secondary-color: #666;
$accent-color: #f56565;
$success-color: #48bb78;

// Spacing
$spacing-xs: 0.25rem;
$spacing-sm: 0.5rem;
$spacing-md: 1rem;
$spacing-lg: 1.5rem;
$spacing-xl: 2rem;

// Typography
$font-family-primary: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
$font-size-md: 1rem;
$font-weight-normal: 400;

// Breakpoints
$breakpoint-sm: 576px;
$breakpoint-md: 768px;
$breakpoint-lg: 992px;
$breakpoint-xl: 1200px;

// Shadows
$shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
$shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
$shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);

// Transitions
$transition-fast: 0.15s ease-in-out;
$transition-normal: 0.3s ease-in-out;
```

## 🗂️ File Structure

```
rakmyat-addons/
├── elements/                           # Modern widget system
│   ├── core/
│   │   └── widget-manager.php          # Automatic discovery engine
│   ├── widgets/
│   │   ├── heading/
│   │   │   └── heading.php             # Advanced heading widget
│   │   ├── blog-posts/
│   │   │   └── blog-posts.php          # Blog posts widget
│   │   └── video/
│   │       └── video.php               # Video widget
│   └── assets/
│       ├── scss/
│       │   ├── _variables.scss         # Global SCSS variables
│       │   ├── heading/
│       │   ├── blog-posts/
│       │   └── video/
│       ├── css/                        # Compiled CSS output
│       └── js/                         # JavaScript files
├── includes/                           # Utility classes
│   ├── scss-compiler.php              # SCSS compilation engine
│   └── dark-mode.php                  # Dark mode support
├── assets/                             # Plugin assets
├── composer.json                       # SCSS compiler dependencies
├── rakmyat-addons.php                 # Main plugin file
└── README.md                           # This file
```

## ⚙️ Configuration

### SCSS Compilation

SCSS files are automatically compiled when:
- A widget is registered for the first time
- In admin area during development
- When CSS files don't exist

### Asset Loading

- CSS and JS files are automatically registered
- Assets only load on pages using the widgets
- Supports file versioning for cache busting
- Optimized for performance

## 🔍 Troubleshooting

### Widgets Not Appearing

1. **Check Elementor** - Ensure Elementor 3.0+ is active
2. **Clear Cache** - Clear all caching plugins
3. **Check Permissions** - Ensure file permissions are correct
4. **Enable Debug** - Set `WP_DEBUG` to `true` to see errors

### SCSS Not Compiling

1. **Composer Dependencies** - Run `composer install` in plugin directory
2. **Directory Writable** - Ensure `elements/assets/css/` is writable
3. **SCSS Syntax** - Check for syntax errors in SCSS files
4. **Server Requirements** - Ensure PHP 7.4+ is available

### Widget Issues

1. **Cache Clear** - Clear Elementor cache in Tools > General
2. **Regenerate CSS** - In Elementor > Tools > General
3. **Check Console** - Look for JavaScript errors in browser
4. **Update Elementor** - Ensure latest Elementor version

## 🚀 Performance

- **Lightweight Core** - Minimal performance impact
- **Conditional Loading** - Assets load only when needed
- **Optimized Output** - Compressed CSS and minified assets
- **Modern Standards** - ES6+ and CSS3+ compatible
- **Lazy Loading** - Images and videos load when needed

## 💡 Best Practices

### Widget Development

1. **Follow Naming Conventions** - Use consistent naming patterns
2. **Add Control Validation** - Validate user inputs
3. **Optimize Assets** - Minimize CSS and JavaScript
4. **Test Responsiveness** - Ensure mobile compatibility
5. **Add Accessibility** - Include ARIA labels and keyboard navigation

### SCSS Organization

1. **Use Variables** - Leverage global SCSS variables
2. **Nested Selectors** - Keep nesting logical and shallow
3. **Modular Approach** - Split complex styles into partials
4. **Mobile First** - Design for mobile, enhance for desktop

## 📞 Support

For support, feature requests, or bug reports:
- Contact the Rakmyat development team
- Check plugin documentation
- Review troubleshooting guide above
- Submit issues through the support system

## 🔄 Changelog

### Version 2.0.0
- ✨ **NEW:** Automatic widget discovery system
- ✨ **NEW:** SCSS compilation with global variables
- ✨ **NEW:** Modern elements architecture
- 🔧 **IMPROVED:** All widgets rebuilt with new system
- 🔧 **IMPROVED:** Performance optimizations
- 🔧 **IMPROVED:** Better responsive design
- 🐛 **FIXED:** Widget loading timing issues
- 🐛 **FIXED:** Asset loading optimization

### Version 1.x
- Legacy widget system
- Manual registration process
- Basic styling support

## 📄 License

This plugin is proprietary software developed by Rakmyat Team.

---

**Made with ❤️ by Rakmyat Team** | **Powered by Modern WordPress Architecture**