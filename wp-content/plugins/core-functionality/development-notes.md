# My Theme Development Preferences

**A guide to how I like WordPress themes built**

Hey! If you're building a WordPress theme for me, this document explains how I think about things and what I prefer. These aren't arbitrary rules - they come from years of experience seeing what works and what causes headaches down the road.

## My Overall Philosophy

I believe in keeping things simple, explainable, and maintainable. I'd rather have a theme that's straightforward to understand and modify than something that's "clever" but becomes a black box when you need to make changes. Every component should be small enough to understand at a glance and easy to adjust without breaking everything else.

When a client calls asking for a change at 2 PM (and they always do), I want to be able to figure out where that code lives, what it does, and how to modify it safely - without needing to untangle a web of dependencies.

### Trust WordPress Core and FSE

**Work with WordPress, not against it.** WordPress has spent years developing the block editor and FSE. Use it the way it's intended to be used.

This means:
- **Use core blocks** whenever possible - don't create custom blocks when a core block works
- **Trust theme.json** - It's powerful and well-designed, use it as intended
- **Follow WordPress conventions** - Hooks, filters, template hierarchy, coding standards
- **Leverage built-in features** - Block styles, block patterns, template parts
- **Don't reinvent the wheel** - If WordPress has a solution, use it

However, "trust WordPress" doesn't mean "accept everything." I still have standards:
- No frameworks on top of WordPress
- No over-complicated architectures
- No bloated solutions when simple works
- Procedural code in themes

The goal is **maintainable code that takes full advantage of WordPress's built-in way of doing things** while enforcing sensible limits that keep themes clean and client-focused.

## Visual Design & User Experience

### Text Readability is Critical

All text must be easy to read with good contrast. Use color combinations that meet WCAG AA standards at minimum (WCAG AAA preferred). If someone has to squint or strain to read your content, you've failed.

Define your color palette in `theme.json` and stick to it. Every color should have a purpose and a name. If you need variations, define them explicitly (primary, primary-dark, primary-light).

### Navigation & Layout

Keep navigation clean and semantic:

- Use the core Navigation block - don't build custom menu systems
- Primary navigation should be intuitive and not overly complex
- Mobile navigation should work well with WordPress's built-in responsive container
- Skip links should be functional and properly styled

Give everything room to breathe. I hate cramped designs. Use generous padding and margins defined in your spacing scale. Make it feel open and comfortable, not dense and overwhelming.

## Technical Preferences

### Keep It Vanilla

This is really important to me: **no frameworks**. I mean it.

- **HTML**: Use WordPress blocks and template parts. No custom templating engines.
- **CSS**: Plain CSS in separate files. No preprocessors, no Tailwind, no Bootstrap, no CSS-in-JS.
- **JavaScript**: Vanilla JavaScript when needed. No React in the theme itself (blocks are fine). No jQuery.

Why? Because in 5 years, vanilla code still works. Framework code often doesn't. Plus, anyone can jump in and understand vanilla code without learning a framework first.

Put your CSS and JavaScript in separate files (not inline) so browsers can cache them properly. Organize them logically by purpose, not by page.

## FSE & Block Theme Structure

### My FSE Philosophy

We're building themes for clients and teams, not products for the masses. This distinction matters.

The block editor makes it technically possible for people to customize every detail - adjust spacing on every block, change colors everywhere, tweak typography page by page. But just because you *can* doesn't mean you *should*.

**My goal is to minimize customization, not maximize it.**

When clients are editing, their focus should be on:
1. **Content updates** - Writing, editing, updating information
2. **Page building with patterns** - Using pre-built patterns to create new pages

That's it. They shouldn't be fiddling with margins, adjusting padding, or changing colors block by block, page by page.

### CSS Does the Heavy Lifting

Here's the thing: CSS is incredibly powerful for contextual styling. When you add a block to the main container, the spacing should just *work*. You shouldn't have to touch any sliders.

**Example**: If a Group block is inside the main content area at full width, CSS should handle:
- Appropriate top/bottom margins
- Proper padding
- Sensible spacing between child elements
- Responsive behavior

Block settings should only be used as **overrides** when absolutely necessary. The default should always be sensible and work for 90% of cases.

### Real-World Example: Contextual Spacing

Here's how I handle spacing in my themes - notice how CSS makes intelligent decisions based on context:

```css
/* Main content areas get consistent large spacing */
.site-content > *,
article.type-post > *,
article.type-page > * {
    margin-block-start: var(--wp--preset--spacing--large)
}

/* Full-width blocks get extra spacing */
.site-content > .alignfull {
    margin-block-start: var(--wp--preset--spacing--x-large)
}

/* But when two full-width sections touch, remove the gap */
.alignfull:where(.has-background, [class*="is-style-section"]) 
+ .alignfull:where(.has-background, [class*="is-style-section"]) {
    margin-block-start: 0;
}

/* Blocks with backgrounds get proper padding automatically */
.has-background:not(.alignfull) {
    padding: var(--wp--preset--spacing--small);
}

.has-background.alignfull {
    padding-block: var(--wp--preset--spacing--x-large);
}
```

**The result**: Editors just add blocks. The spacing is always correct. No sliders. No fiddling. It just works.

When would they need to override? Rarely - maybe once per project when they need something truly custom. That's the way it should be.

### Limit Options, Increase Consistency

Remove as many block options as possible:
- Hide unnecessary blocks entirely
- Disable granular controls that encourage inconsistency
- Remove color options that conflict with the design system
- Hide typography controls that break the hierarchy

Instead, provide:
- **Patterns** for common layouts and sections
- **Block styles** for semantic variations (primary button, secondary button)
- **Section styles** for different layout contexts

This keeps everything consistent and makes the client's job easier.

### theme.json as Universal Defaults

`theme.json` is your design system. Everything visual should be defined here:

- **Colors**: Define your complete palette with semantic names
- **Typography**: Font families, sizes with fluid values, line heights  
- **Spacing**: Use a consistent scale (I use a ratio-based scale)
- **Layout**: contentSize and wideSize for content width
- **Custom properties**: For anything you'll reuse (transitions, shadows, etc.)

**Never hardcode values in CSS that should be in theme.json.** If you find yourself writing the same color or spacing value twice, it belongs in theme.json.

However, `theme.json` does NOT need to contain every possible style for every possible block. Use CSS for specific block styling and contextual overrides.

### Embrace Full Site Editing

Build proper block themes using FSE the way WordPress intends:

- Use `theme.json` for ALL design tokens (colors, spacing, typography)
- Templates go in `/templates` as `.html` files
- Template parts go in `/parts` as `.html` files  
- Patterns go in `/patterns` as `.php` files
- No page templates - use custom template files instead

**Don't fight the system.** WordPress has built this infrastructure for a reason. When you work with it instead of around it:
- Updates don't break your theme
- Other developers can understand your code immediately
- New WordPress features work automatically
- Maintenance is straightforward

If you find yourself writing elaborate PHP to override core block behavior, step back and ask: "Is there a simpler way using theme.json or CSS that works with WordPress instead of against it?"

### Template Organization

Keep templates focused and semantic:

```
/templates
  - index.html         (blog home)
  - archive.html       (category/tag archives)
  - single.html        (blog posts)
  - page.html          (standard pages)
  - search.html        (search results)
  - 404.html           (error page)
  - blank.html         (custom template)
  - no-title.html      (custom template)
```

Each template should:
- Use clear, semantic HTML5 elements (`<header>`, `<main>`, `<article>`, `<footer>`)
- Include proper IDs for skip links (`id="main-content"`, `id="footer"`)
- Load appropriate patterns for content sections
- Be readable and self-documenting

### Block Patterns Are Your Friend

Create reusable patterns for common layouts:

- **Header patterns**: Different header layouts
- **Entry patterns**: Post meta, author boxes, pagination
- **Query patterns**: Grid layouts, list layouts
- **Section patterns**: CTAs, testimonials, features

Patterns should:
- Be semantic and accessible
- Use theme.json values only
- Include helpful metadata (title, description, categories)
- Be composable (patterns can include other patterns)

## PHP & WordPress Theme Development

### Follow WordPress Theme Standards

Use the official WordPress Coding Standards for everything - naming conventions, formatting, documentation. This makes the code immediately recognizable to any WordPress developer.

### Procedural, Not Object-Oriented

Write procedural PHP code, not classes and objects. Why?

- **Simpler to understand**: Functions are straightforward and easy to follow
- **Easier to debug**: You can see exactly what's happening in what order
- **More maintainable**: Anyone can jump in and understand procedural code
- **WordPress-friendly**: Theme functions work procedurally with hooks

### Prefix Everything

Use a consistent prefix for all functions to prevent conflicts:

Example: If your theme is called "FSE Starter", use `fse_` as your prefix:
- Functions: `fse_setup()`, `fse_enqueue_scripts()`
- Filters: `fse_excerpt_length`
- Actions: `fse_modify_navigation_block`

**Never use generic names like `setup()` or `enqueue_scripts()` - always prefix.**

### Organize by Feature, Not Type

Structure your `/inc` directory by what features do, not by technical type:

```
/inc
  /setup
    - setup.php          (theme setup and support)
    - scripts.php        (enqueue scripts/styles)
    - block-editor.php   (editor customizations)
  - excerpt.php          (excerpt modifications)
  - navigation.php       (navigation modifications)
  - archive.php          (archive customizations)
  - comments.php         (comment features)
  - security.php         (security headers)
  - wordpress-cleanup.php (WP cleanup)
  - skip-links.php       (accessibility)
  - template-tags.php    (pure helper functions)
```

Not like this:
```
/inc
  - filters.php          (all filters mixed together)
  - actions.php          (all actions mixed together)
  - functions.php        (everything else)
```

### Hook Everything Properly

- Use WordPress hooks (actions and filters) for everything
- Don't execute code directly - hook it to the appropriate action
- Name your hook callbacks clearly: `fse_setup()`, `fse_enqueue_scripts()`
- Document what hooks you're using and why
- Use appropriate priorities when order matters

Common theme hooks:
```php
add_action( 'after_setup_theme', 'fse_setup' );
add_action( 'wp_enqueue_scripts', 'fse_enqueue_scripts' );
add_action( 'enqueue_block_editor_assets', 'fse_enqueue_editor_scripts' );
add_filter( 'render_block', 'fse_modify_block_output', 10, 2 );
```

**When to use filters vs letting WordPress handle it:**
- **Use filters when**: WordPress's default conflicts with your design system or creates accessibility issues
- **Let WordPress handle it when**: The default behavior works fine, even if you could "improve" it

Example: WordPress's skip links work fine. Don't rewrite them unless they genuinely don't work for your theme.

Ask yourself: "Will this filter still make sense in 2 years when WordPress updates?" If the answer is uncertain, you might be fighting WordPress instead of working with it.

### Security First (Theme Context)

- **Sanitize input**: Use `sanitize_text_field()`, `esc_attr()`, etc.
- **Escape output**: Use `esc_html()`, `esc_url()` everywhere in templates
- **Nonces for forms**: If you create custom forms (rare in FSE)
- **Check capabilities**: Always verify permissions for admin functions
- **Security headers**: Set proper X-Frame-Options headers

Example:
```php
// Good - escaped output
echo '<a href="' . esc_url( home_url() ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';

// Bad - unescaped output
echo '<a href="' . home_url() . '">' . get_bloginfo( 'name' ) . '</a>';
```

### Keep Functions Small and Focused

Each function should do one thing well. If a function is more than 20-30 lines, consider breaking it up.

Good:
```php
function fse_add_footer_id( $block_content, $block ) {
    if ( isset( $block['attrs']['slug'] ) && $block['attrs']['slug'] === 'footer' ) {
        return str_replace( '<footer', '<footer id="footer"', $block_content );
    }
    return $block_content;
}
```

Bad:
```php
function fse_modify_everything( $block_content, $block ) {
    // 100 lines doing multiple different things
}
```

### Comment Your Code

- Explain WHY you're doing something, not WHAT you're doing
- Document function purposes with a brief description
- Add inline comments for complex logic
- Use clear variable names that reduce need for comments

Good:
```php
// Remove comments from admin bar since we've disabled comments site-wide
add_action( 'admin_bar_menu', 'fse_remove_admin_bar_comments', 999 );
```

Bad:
```php
// This removes comments
add_action( 'admin_bar_menu', 'fse_remove_admin_bar_comments', 999 );
```

## CSS Architecture & Organization

### File Structure

Organize CSS by purpose, not by page:

```
/assets/css/
  /01-base/
    - reset.css         (CSS reset/normalize)
    - global.css        (global elements: body, links, headings)
    - layout.css        (contextual spacing and layout rules)
    - forms.css         (form elements)
  /blocks/
    /core/
      - button-min.css  (core/button styles)
      - image-min.css   (core/image styles)
  - frontend-min.css    (main compiled stylesheet)
  - editor-min.css      (editor-only compiled stylesheet)
```

**What goes in layout.css:**
- Global layout spacing rules (`.wp-site-blocks > *`, `.site-content > *`)
- Full-width block spacing (`.alignfull`)
- Adjacent element rules (sections touching each other)
- Container-specific spacing (header, footer, main content)
- Utility block styles (`.is-style-large-gap`, `.is-style-hidden-mobile`)
- Responsive layout adjustments

This file is where all the "intelligent spacing" magic happens - where CSS makes contextual decisions so editors don't have to.

### CSS Strategy

- Write plain CSS - no preprocessors needed
- Keep selectors simple and specific
- Use CSS custom properties for repeated values
- Leverage theme.json for design tokens
- Organize files by feature/component
- Compile/minify for production (use -min.css naming)

**Key principle**: Write CSS that makes intelligent contextual decisions. Use selectors to target blocks based on:
- **Location**: `.site-content > *` vs `.site-header > *`
- **Type**: `.alignfull` vs `.alignwide`  
- **State**: `.has-background` vs plain blocks
- **Adjacent elements**: What's next to this block?

Example from my layout.css:
```css
/* Different spacing for different contexts */
.site-content > * {
    margin-block-start: var(--wp--preset--spacing--large)
}

/* Full-width gets more space */
.site-content > .alignfull {
    margin-block-start: var(--wp--preset--spacing--x-large)
}

/* Header/footer resets to zero */
.site-header > *,
.site-footer > * {
    margin-block-start: 0;
}
```

This approach means editors never touch spacing sliders - it's handled automatically based on context.

### Shared vs Editor-Only Styles

**Shared styles** (loaded in both front-end and editor):
- Core element styling (typography, links, buttons)
- Block customizations
- Layout systems
- Utility classes

**Front-end only** (loaded only on front-end):
- First/last child margin corrections
- Template-specific styling
- Conditional element visibility
- Skip link styling

**Editor only** (loaded only in editor):
- Editor interface adjustments
- Preview enhancements
- WYSIWYG improvements

### Block Styling Approach

1. **Use theme.json first** - Define colors, spacing, typography there
2. **Block styles second** - Create registered block styles for variations
3. **CSS last** - Only write CSS for what can't be done in theme.json

Load block styles properly:
```php
// Register block styles in theme.json or via wp_register_style
// Then enqueue using wp_enqueue_block_style for proper loading
```

### Utility Block Styles

Create utility block styles that solve common layout needs without exposing granular controls:

```css
/* Increase gap on specific blocks */
.is-style-large-gap {
    gap: var(--wp--preset--spacing--large);
}

/* Hide content on mobile */
.is-style-hidden-mobile {
    display: none !important;
}

/* Reverse column order on mobile */
.is-style-columns-reverse {
    flex-direction: column-reverse;
}
```

These give editors semantic, purpose-driven options rather than pixel-perfect control. They choose "large gap" not "60px gap". They choose "hidden on mobile" not fiddling with display properties.

Register these in your block editor JavaScript:
```javascript
wp.blocks.registerBlockStyle('core/group', {
    name: 'large-gap',
    label: 'Large Gap'
});
```

This maintains consistency while still providing flexibility when truly needed.

## Block Editor Customizations

### The Goal: Less Is More

Remember, we're limiting options to keep clients focused on content. Every block setting you expose is another decision they have to make. Every color picker is another opportunity for inconsistency.

Be aggressive about hiding things. If 90% of the time a setting shouldn't be touched, hide it.

**But don't over-customize.** WordPress has sensible defaults for most things. Only customize when:
- The default behavior conflicts with your design system
- A feature would genuinely confuse clients
- A block would never be used in this project

If you find yourself writing elaborate JavaScript to completely rebuild the editor experience, you've gone too far. Work with WordPress's editor, enhance it, don't replace it.

### Hide Unnecessary Blocks

Don't overwhelm editors with blocks they'll never use:

```php
function get_hidden_blocks() {
    return [
        'core/verse',
        'core/pullquote',
        'core/code',
        'core/audio',
        'core/calendar',
        // etc.
    ];
}
```

Hide FSE-specific blocks in post/page editor:
- Users shouldn't see Site Logo, Navigation, Query blocks when editing posts
- Only show them in the Site Editor

### Register Custom Block Styles

Provide semantic variations instead of custom blocks:

```php
function get_custom_block_styles() {
    return [
        'core/button' => [
            ['name' => 'primary', 'label' => 'Primary', 'isDefault' => true],
            ['name' => 'secondary', 'label' => 'Secondary'],
        ],
        'core/list' => [
            ['name' => 'checkmarks', 'label' => 'Checkmarks'],
            ['name' => 'arrows', 'label' => 'Arrows'],
        ],
    ];
}
```

### Hide Core Block Styles

Remove confusing or unused core block styles:

```php
function get_hidden_block_styles() {
    return [
        'core/button' => ['outline', 'squared'],
        'core/image' => ['circle-mask', 'rounded'],
        'core/separator' => ['dots'],
    ];
}
```

## File Structure & Organization

### Root Level Structure

```
/theme-root
  /assets/          (all assets)
    /css/
    /js/
    /fonts/
    /images/
  /inc/             (PHP functionality)
  /patterns/        (block patterns)
  /parts/           (template parts)
  /templates/       (page templates)
  - functions.php   (loads everything)
  - theme.json      (design system)
  - style.css       (theme header)
```

### functions.php as Loader

Keep `functions.php` clean - it should only:
1. Define global constants
2. Require feature files from `/inc`

```php
// Define constants
define( 'THEME_HANDLE', sanitize_title_with_dashes( wp_get_theme()->get( 'Name' ) ) );
define( 'THEME_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'THEME_PATH', get_template_directory() . '/' );
define( 'THEME_INC', THEME_PATH . 'inc/' );

// Load features
require_once THEME_INC . 'setup/setup.php';
require_once THEME_INC . 'setup/scripts.php';
require_once THEME_INC . 'excerpt.php';
// etc.
```

### Template Tags

Create a `template-tags.php` file for **pure helper functions only**:

```php
/**
 * Rules for this file:
 * - Functions MUST be pure (same input = same output)
 * - NO hooks, filters, or global state modifications
 * - NO side effects (no database writes, no enqueueing)
 * - Only templating helpers
 */

function get_fse_copyright() {
    $copyright_info = '&copy;' . esc_attr( gmdate( 'Y' ) );
    $site_name = get_bloginfo( 'name' );
    return $copyright_info . ' - ' . $site_name;
}
```

If a function has side effects, it belongs in a feature file, not template-tags.

## Development Practices

### Start With theme.json

Before writing any CSS or PHP:

1. Define your design system in `theme.json`
2. Set up color palette
3. Define typography scale with fluid values
4. Create spacing scale (this is critical for contextual CSS)
5. Configure block settings

Then write your contextual CSS:

6. Create `layout.css` with intelligent spacing rules
7. Define contextual spacing (`.site-content > *`, `.alignfull`, etc.)
8. Add utility block styles (`.is-style-large-gap`, etc.)
9. Test that blocks look good by default without any slider adjustments

Only after this foundation is solid should you write block-specific CSS or PHP customizations.

### Build Mobile-First

Everything must work on phones. Period.

- Test on actual phones, not just browser dev tools
- Use responsive spacing (clamp values in theme.json)
- Make touch targets at least 44x44 pixels
- Ensure navigation works well on mobile

### Make It Debuggable

Write code as if you'll have to debug it at 2 AM while half-asleep (because you probably will):

- Clear, descriptive function names
- Helpful comments for non-obvious logic
- Good error messages
- Sufficient logging for filters/actions

### Cache Busting Strategy

Use version numbers for cache busting:

```php
function cache_version_id() {
    if ( WP_DEBUG ) {
        return time(); // Always fresh in development
    } else {
        return THEME_VERSION; // Use theme version in production
    }
}
```

## Accessibility Requirements

### Non-Negotiable Standards

- **Semantic HTML**: Use proper elements (`<nav>`, `<article>`, `<aside>`)
- **Skip links**: Functional skip links to main content and footer
- **Keyboard navigation**: Everything must be keyboard accessible
- **Screen reader text**: Add context for screen readers when needed
- **Focus states**: Clear, visible focus indicators
- **Color contrast**: WCAG AA minimum (AAA preferred)
- **Alt text**: Images must have appropriate alt attributes

### Skip Links Implementation

Always include functional skip links:

```php
function fse_output_skip_links() {
    $links = [
        'main-content' => __( 'Skip to main content', 'theme-slug' ),
        'footer' => __( 'Skip to footer', 'theme-slug' ),
    ];
    // Render skip links
}
add_action( 'wp_body_open', 'fse_output_skip_links', 1 );
```

### Screen Reader Context

Add context for screen readers when visual users have it but screen reader users don't:

```php
// Add post title to "Read more" links
$screen_reader_text = sprintf(
    '<span class="screen-reader-text">: %s</span>',
    esc_html( get_the_title() )
);
```

## Performance Considerations

### Asset Loading

- **Separate files for caching**: CSS/JS in external files, not inline
- **Conditional loading**: Only load what's needed on each page
- **Block-specific styles**: Use `wp_enqueue_block_style()` for per-block CSS
- **Editor assets**: Enqueue editor scripts/styles separately
- **Font optimization**: Self-host fonts, load only needed weights

### CSS Performance

- Keep selectors simple and shallow
- Avoid expensive operations (complex filters, shadows)
- Use CSS custom properties (defined in theme.json when possible)
- Let browsers cache compiled/minified CSS files
- Minimize specificity wars

### JavaScript (When Needed)

- Load scripts in footer when possible
- Use vanilla JavaScript - no libraries unless absolutely necessary
- Keep JavaScript minimal in themes
- Use WordPress's built-in libraries when available
- Always enqueue, never inline (except critical scripts)

## WordPress Cleanup

### Remove Unnecessary Features

Clean up WordPress's default output:

- Remove emoji detection script/styles
- Remove RSS feed links (if not using)
- Remove RSD link
- Remove generator meta tags
- Disable XML-RPC (security)
- Remove dashboard widgets clients don't need

### Admin Interface

Keep the admin clean and focused:

- Remove WP logo from admin bar
- Remove unnecessary dashboard widgets
- Hide ACF in production environments
- Remove welcome panel
- Customize login page (optional but nice)

## Plugin Integration

### Gravity Forms

If using Gravity Forms:

- Match button styles to theme buttons
- Remove required field message (use better styling)
- Ensure forms are mobile-friendly
- Style validation states clearly

### ACF (Advanced Custom Fields)

If using ACF:

- Disable CPT/taxonomy UI (`add_filter( 'acf/settings/enable_post_types', '__return_false' )`)
- Hide in production (`fse_remove_acf_admin_menu()`)
- Don't output empty block messages
- Keep field groups in JSON (version control)

### Other Plugins

Themes should style plugin output to match the site design, but shouldn't depend on plugins for core functionality.

## What Success Looks Like

When you build a theme for me, success means:

1. **Anyone can understand it** - Clear structure, good comments, logical organization
2. **It works with WordPress** - Uses core features, follows conventions, updates won't break it
3. **Editors find it intuitive** - Limited options, good patterns, clear purpose
4. **Editors focus on content** - Not fiddling with margins, colors, or spacing
5. **Patterns are robust** - Common layouts are pre-built and easy to use
6. **CSS handles context** - Spacing and layout work automatically
7. **It's maintainable** - Update parts without breaking everything
8. **It's accessible** - Keyboard navigation, screen readers, proper semantics
9. **It performs well** - Fast loading, efficient code, proper caching
10. **It's secure** - Escaped output, security headers, no vulnerabilities
11. **It works everywhere** - Desktop, tablet, phone - it all works
12. **Design system is enforced** - theme.json and CSS control consistency

## Things That Drive Me Crazy

Just so we're clear, here are things I really don't want to see:

- Fighting WordPress instead of working with it
- Elaborate custom solutions when WordPress has a built-in feature
- Over-engineering the block editor experience
- JavaScript frameworks when vanilla would work fine
- Hardcoded colors/spacing instead of using theme.json values
- Inline styles (use external files)
- Classes everywhere (procedural PHP in themes)
- All-or-nothing architectures that can't be changed
- Clever code that's hard to understand
- Cramped designs with no breathing room
- Blocks available in post editor that should only be in site editor
- Custom block patterns that don't use theme.json values
- Exposing every possible block setting to editors
- Requiring clients to adjust margins/padding manually for proper spacing
- Themes that expect per-block, per-page customization instead of systematic design

## Final Thoughts

I believe good theme development is like good writing - it should be clear, purposeful, and as simple as possible while still doing the job. Every decision should have a reason, and that reason should be something better than "it's the trendy thing to do."

Build themes that you'd want to maintain yourself two years from now. Build them like you'll have to explain them to someone else. Build them like the next developer might be you on a bad day.

**Work with WordPress, not against it.** WordPress Core and FSE have been thoughtfully designed. Use them as intended. Trust theme.json for design tokens. Use CSS for contextual styling. Write procedural PHP. Keep CSS organized. Make it accessible. When WordPress has a solution, use it.

**Most importantly**: Build themes that empower clients to focus on content, not design. Give them patterns to build with, not sliders to fiddle with. Make the right thing the easy thing. And do it all using WordPress's built-in features whenever possible.

Remember: A maintainable theme that works with WordPress beats a "perfect" custom solution every time. When WordPress updates, your themes should benefit from those improvements, not break because you fought the system.

If you follow this document, you'll build themes I can actually maintain, that take full advantage of WordPress's capabilities, and that will continue working as WordPress evolves - and that's what matters most.
