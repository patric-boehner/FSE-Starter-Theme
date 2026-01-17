# Core Functionality Plugin

A companion plugin for WordPress sites that contains site-specific functionality independent of the theme. Keeps critical features active even when switching themes.

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Advanced Custom Fields Pro (for Content Areas and Icon Block features)

## Installation

1. Upload the `core-functionality` folder to `/wp-content/plugins/`
2. Activate the plugin through the WordPress admin
3. Configure features as needed (see below)

## Features

The plugin uses a modular architecture. Features can be enabled or disabled by editing the `$cf_features` array in the main plugin file, or by using the `core_functionality_features` filter.

### Last Login Column

Tracks and displays the last login time for users in the WordPress admin.

- Adds a sortable "Last Login" column to the Users list
- Records login timestamps via `wp_login` hook
- Supports two-factor authentication plugins

### Admin Bar Notice

Displays a colored environment indicator in the WordPress admin bar.

| Environment | Color |
|-------------|-------|
| Local | Green |
| Development | Yellow |
| Staging | Red |
| Production | Blue |

Detects environment via URL patterns and `WP_ENVIRONMENT_TYPE` constant.

### Content Areas

A system for managing reusable block content that can be inserted into templates.

- Custom Post Type for content storage (admin-only access)
- Location taxonomy for placement control
- Category-based conditional display
- ACF block for template insertion

### Related Posts

Extends the core Query Loop block with related posts functionality.

- Block variation for easy editor setup
- Automatic filtering by current post's categories
- Excludes current post from results
- Works with REST API for block preview

### Icon Block

Custom ACF block for inserting SVG icons.

- Scans theme's `/build/svg/` directory for available icons
- Size options (small, medium, large)
- Accessibility labels (aria-label support)
- Icon caching with version-based invalidation

### Recovery Mode Emails

Routes WordPress recovery mode emails to additional recipients.

**Configuration** (add to `wp-config.php`):

```php
// Send recovery emails to additional addresses
define( 'RECOVERY_EMAILS', 'dev@example.com, support@example.com' );

// Optional: Only send to custom addresses (exclude site admin)
define( 'RECOVERY_EMAILS_ONLY', true );
```

### Email Testing

Daily cron job that sends a test email to verify email delivery is working.

**Configuration** (add to `wp-config.php`):

```php
define( 'CRON_EMAIL', 'monitoring@example.com' );
```

- Only runs on production environments
- Integrates with Site Health status
- Useful for care plan monitoring

## Utility Functions

### Environment Detection

```php
cf_is_local_dev_site()        // Returns true on local development
cf_is_development_staging_site() // Returns true on dev staging
cf_is_staging_site()          // Returns true on staging
cf_get_environment_slug()     // Returns: 'local', 'development', 'staging', or 'production'
```

### SVG Icons

```php
cf_icon( 'icon-name' );                    // Output icon inline
cf_icon( 'icon-name', [ 'size' => 'lg' ]); // With size class
cf_icon( 'icon-name', [ 'label' => 'Menu' ]); // With aria-label
```

### Site Health Integration

```php
cf_register_site_health_test( $tests );  // Register custom Site Health tests
cf_register_site_health_info( $fields ); // Register Site Health info fields
```

## Customization

### Modifying Features

Use the `core_functionality_features` filter to add or remove features:

```php
add_filter( 'core_functionality_features', function( $features ) {
    // Remove a feature
    $features = array_diff( $features, [ 'last-login-column' ] );

    // Add a custom feature
    $features[] = 'my-custom-feature';

    return $features;
});
```

### Adding Custom Features

1. Create a folder in `inc/pluggable/your-feature-name/`
2. Add a `plugin.php` file with your feature code
3. Add the feature name to the `$cf_features` array or use the filter

## Constants

| Constant | Description |
|----------|-------------|
| `CORE_DIR` | Plugin directory path |
| `CORE_URL` | Plugin directory URL |
| `CORE_FILE` | Main plugin file path |
| `CORE_VERSION` | Current plugin version |

## Hooks

### Filters

- `core_functionality_features` - Modify the list of enabled features

### Actions

- `cf_cron_email_test` - Cron action for email testing

## License

GPL-2.0+

## Credits

Inspired by [Bill Erickson's Core Functionality plugin](https://github.com/billerickson/Core-Functionality).
