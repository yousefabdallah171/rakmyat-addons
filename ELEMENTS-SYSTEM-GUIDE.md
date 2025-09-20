# Elements System Guide v2.0

**New Simplified Widget System - No Config Files Required**

## 🎯 Overview

The new Elements System eliminates the need for config files and provides a clean, organized structure for creating custom Elementor widgets. Simply create folders and files, and widgets automatically work!

## 📁 Folder Structure

```
Plugin Root/
└── elements/
    ├── assets/
    │   ├── js/
    │   │   ├── widget-name/
    │   │   │   ├── widget-name.js
    │   │   │   └── widget-name.min.js
    │   │   └── another-widget/
    │   │       └── another-widget.js
    │   ├── scss/
    │   │   ├── _variables.scss        # Global SCSS variables
    │   │   ├── widget-name/
    │   │   │   └── widget-name.scss
    │   │   └── another-widget/
    │   │       └── another-widget.scss
    │   └── css/                       # Auto-compiled from SCSS
    │       ├── widget-name/
    │       │   └── widget-name.css
    │       └── another-widget/
    │           └── another-widget.css
    ├── core/
    │   └── widget-manager.php         # System core
    └── widgets/
        ├── widget-name/
        │   └── widget-name.php        # Widget class
        └── another-widget/
            └── another-widget.php
```

## 🚀 Creating a New Widget (3 Steps)

### Step 1: Create Widget Folder & PHP File

**Path:** `elements/widgets/my-awesome-widget/my-awesome-widget.php`

```php
<?php
namespace RakmyatAddons\Widgets; // or RMT\Widgets for RMT Core

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class My_Awesome_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'rakmyat-my-awesome-widget'; // or 'rmt-my-awesome-widget'
    }

    public function get_title()
    {
        return __('My Awesome Widget', 'rakmyat-addons');
    }

    public function get_icon()
    {
        return 'eicon-star';
    }

    public function get_categories()
    {
        return ['rakmyat-addons']; // or ['rmt-addons']
    }

    public function get_style_depends()
    {
        return ['rakmyat-my-awesome-widget']; // or ['rmt-my-awesome-widget']
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'rakmyat-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'rakmyat-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Awesome Title', 'rakmyat-addons'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo '<div class="rakmyat-my-awesome-widget">';
        echo '<h3>' . esc_html($settings['title']) . '</h3>';
        echo '</div>';
    }
}
```

### Step 2: Create SCSS File (Optional)

**Path:** `elements/assets/scss/my-awesome-widget/my-awesome-widget.scss`

```scss
@import "_variables";

.rakmyat-my-awesome-widget {
    padding: $rakmyat-widget-padding;
    background: $rakmyat-widget-background;
    border-radius: $rakmyat-border-radius;
    box-shadow: $rakmyat-box-shadow-sm;

    h3 {
        color: $rakmyat-primary;
        font-size: $rakmyat-font-size-lg;
        margin-bottom: $rakmyat-spacer;
    }

    // Responsive design
    @media (max-width: $rakmyat-breakpoint-md) {
        padding: $rakmyat-spacer-sm;
    }
}
```

### Step 3: Create JavaScript File (Optional)

**Path:** `elements/assets/js/my-awesome-widget/my-awesome-widget.js`

```javascript
(function($) {
    'use strict';

    var MyAwesomeWidgetHandler = function($scope, $) {
        var $widget = $scope.find('.rakmyat-my-awesome-widget');

        if (!$widget.length) {
            return;
        }

        // Your widget functionality here
        $widget.on('click', function() {
            console.log('Widget clicked!');
        });
    };

    // Register with Elementor
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/rakmyat-my-awesome-widget.default',
            MyAwesomeWidgetHandler
        );
    });

})(jQuery);
```

## ✅ Done! Widget Automatically Works

That's it! Your widget will:
- ✅ Automatically appear in Elementor panel
- ✅ SCSS automatically compiles to CSS
- ✅ Assets automatically load when needed
- ✅ No manual registration required
- ✅ No config files needed

## 🎨 Available Global SCSS Variables

```scss
// Colors
$rakmyat-primary: #007cba;
$rakmyat-secondary: #6c757d;
$rakmyat-success: #28a745;
$rakmyat-danger: #dc3545;

// Spacing
$rakmyat-widget-padding: 1.5rem;
$rakmyat-spacer: 1rem;
$rakmyat-spacer-sm: 0.5rem;
$rakmyat-spacer-lg: 1.5rem;

// Typography
$rakmyat-font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
$rakmyat-font-size-base: 16px;
$rakmyat-font-size-lg: 18px;
$rakmyat-font-weight-bold: 700;

// Layout
$rakmyat-widget-background: #ffffff;
$rakmyat-border-radius: 0.375rem;
$rakmyat-box-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);

// Breakpoints
$rakmyat-breakpoint-sm: 768px;
$rakmyat-breakpoint-md: 992px;
$rakmyat-breakpoint-lg: 1200px;

// Transitions
$rakmyat-transition-base: all 0.3s ease;
```

## 📋 Naming Conventions

| Component | Pattern | Example |
|-----------|---------|---------|
| **Folder** | `kebab-case` | `my-awesome-widget` |
| **PHP File** | `widget-name.php` | `my-awesome-widget.php` |
| **Class Name** | `Pascal_Case` | `My_Awesome_Widget` |
| **Widget ID** | `prefix-name` | `rakmyat-my-awesome-widget` |
| **CSS Class** | `prefix-name` | `.rakmyat-my-awesome-widget` |
| **Asset Handle** | `prefix-name` | `rakmyat-my-awesome-widget` |

## 🔧 System Features

### Automatic Discovery
- Scans `elements/widgets/` folder
- Registers all valid widgets automatically
- No manual registration required

### Asset Management
- SCSS auto-compiles to CSS using ScssPhp
- Assets load only when widget is used
- Automatic cache busting with file modification times

### Performance Optimized
- Production-ready error handling
- Memory usage monitoring
- Efficient asset loading

### Developer Friendly
- Clear error messages in debug mode
- Hot reloading in development
- Comprehensive logging

## 🎯 Widget Examples

### Simple Text Widget
```php
// elements/widgets/simple-text/simple-text.php
class Simple_Text extends Widget_Base {
    public function get_name() { return 'rakmyat-simple-text'; }
    public function get_title() { return 'Simple Text'; }
    // ... rest of implementation
}
```

### Advanced Grid Widget
```php
// elements/widgets/advanced-grid/advanced-grid.php
class Advanced_Grid extends Widget_Base {
    public function get_name() { return 'rakmyat-advanced-grid'; }
    public function get_title() { return 'Advanced Grid'; }

    protected function register_controls() {
        // Responsive controls
        $this->add_responsive_control('columns', [
            'label' => 'Columns',
            'type' => Controls_Manager::SELECT,
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4'],
            'selectors' => [
                '{{WRAPPER}} .grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ]);
    }
}
```

## 🐛 Troubleshooting

### Widget Not Appearing?
1. Check folder name matches file name
2. Verify class extends `Widget_Base`
3. Ensure namespace is correct
4. Check PHP error logs

### SCSS Not Compiling?
1. Install ScssPhp: `composer require scssphp/scssphp`
2. Check file permissions
3. Verify SCSS syntax
4. Enable WP debug mode

### Assets Not Loading?
1. Check file paths are correct
2. Verify `get_style_depends()` returns correct handle
3. Clear browser cache
4. Check network tab in browser dev tools

## 📈 Migration from Old System

### Before (Config-based)
```
widgets/my-widget/
├── config.php          ❌ Remove
├── widget.php           ✅ Move to elements/widgets/my-widget/my-widget.php
└── assets/
    └── scss/widget.scss ✅ Move to elements/assets/scss/my-widget/my-widget.scss
```

### After (New System)
```
elements/
├── assets/scss/my-widget/my-widget.scss  ✅ New location
└── widgets/my-widget/my-widget.php       ✅ New location
```

## 🎉 Benefits of New System

- **🚀 Faster Development** - No config files to maintain
- **📁 Better Organization** - Assets separated from widgets
- **🔄 Auto Discovery** - Just create files and they work
- **🎨 Global Variables** - Consistent styling across widgets
- **⚡ Performance** - Optimized asset loading
- **🛠️ Production Ready** - Built for real-world use

---

## 📚 Quick Reference

**Create widget:** `elements/widgets/name/name.php`
**Add styles:** `elements/assets/scss/name/name.scss`
**Add scripts:** `elements/assets/js/name/name.js`
**Widget appears automatically in Elementor!**

**Version:** 2.0.0
**Compatible:** Elementor 3.0+, WordPress 5.0+, PHP 7.4+
**Performance:** Production optimized with caching