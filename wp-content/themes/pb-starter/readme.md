# PB Starter Theme

A WordPress Full Site Editing (FSE) theme built with a design system-first approach that balances flexibility for content editors with long-term maintainability and visual consistency.

---

## Quick Start

### Prerequisites
- Node.js 18+
- npm

### Setup

```bash
# Install dependencies
npm install

# Start development (watch mode)
npm run watch

# Build for production
npm run build
```

---

## Build System

### Technology Stack
- **CSS Processing**: PostCSS with plugins for variables, nesting, and autoprefixing
- **JavaScript**: esbuild for fast bundling
- **Asset Copying**: Automated copying of fonts, images, and SVGs

### Source & Output

```
src/                          → build/
├── css/                      → css/
│   ├── _variables.css       (SCSS-style variables)
│   ├── frontend.css         → frontend.css
│   ├── editor.css           → editor.css
│   ├── 01-settings/
│   ├── 02-base/
│   ├── 03-components/
│   ├── 04-templates/
│   └── blocks/
│       └── core/
│           └── button.css   → blocks/core/button.css
├── js/                      → js/
│   └── editor.js            → editor.js
├── fonts/                   → fonts/
├── images/                  → images/
└── svg/                     → svg/
```

### NPM Commands

```bash
# Development
npm run watch          # Watch all files, auto-rebuild on save

# Production
npm run build          # Build minified/optimized files

# Cleanup
npm run clean          # Delete build/ directory

# Utilities
npm audit              # Check for security vulnerabilities
```

### CSS Features

**PostCSS Plugins:**
- `postcss-simple-vars` - SCSS-style variables (`$variable`)
- `postcss-nesting` - CSS nesting support
- `postcss-import` - Combine CSS files
- `autoprefixer` - Vendor prefixes
- `cssnano` - Minification (production only)

**Variables for Media Queries:**

```css
/* _variables.css */
$breakpoint-mobile: 768px;
$breakpoint-tablet: 1024px;

/* Use in your CSS */
@media (max-width: $breakpoint-mobile) {
    /* Mobile styles */
}
```

**CSS Nesting:**

```css
.site-header {
    background: var(--wp--preset--color--base);
    
    & .logo {
        height: 60px;
        
        &:hover {
            opacity: 0.8;
        }
    }
}
```

---

## Guiding Principles

### 1. Design System First
All layout, spacing, colors, and typography are defined through a consistent design system using `theme.json`. Editors choose from defined presets rather than creating custom values.

### 2. Component-Based Structure
CSS and patterns are organized around reusable components and sections rather than page-specific layouts. This supports modular design and makes future extension more manageable.

### 3. Block Editor Alignment
Editor styles are carefully matched to front-end styles to ensure WYSIWYG editing. Shared styles are included in both environments. Front-end-only styles are scoped and structured separately.

### 4. Guardrails Over Total Freedom
The theme intentionally limits design freedom in the Block Editor to maintain visual coherence. Block styles, variations, and patterns are provided to guide content creation without overwhelming editors with too many options.

### 5. Developer Collaboration, Not Lock-In
When new design patterns or custom sections are needed beyond the current system, they can be added by development. This ensures scalability without compromising design quality.

---

## CSS Architecture

### Entry Files

**frontend.css** - Main stylesheet loaded on all pages
```css
@import "02-base/reset.css";
@import "02-base/global.css";
@import "02-base/layout.css";
@import "02-base/forms.css";
```

**editor.css** - Editor-only styles for WYSIWYG
```css
/* Imports editor-specific styles */
```

### Base Styles (02-base/)
- `reset.css` - CSS reset/normalize
- `global.css` - Body, typography, links, buttons
- `layout.css` - Contextual spacing and layout rules
- `forms.css` - Form elements

### Block Styles (blocks/)
Per-block CSS that loads only when the block is used:
```
blocks/
├── core/
│   ├── button.css
│   └── image.css
├── cf/
│   └── custom-block.css
└── outermost/
    └── plugin-block.css
```

### Contextual Spacing Philosophy

CSS handles spacing automatically based on context - editors don't need to adjust spacing controls:

```css
/* Standard spacing between content blocks */
.site-content > * {
    margin-block-start: var(--wp--preset--spacing--large);
}

/* Full-width blocks get extra breathing room */
.site-content > .alignfull {
    margin-block-start: var(--wp--preset--spacing--x-large);
}

/* When sections with backgrounds touch, remove gap */
.alignfull.has-background + .alignfull.has-background {
    margin-block-start: 0;
}
```

---

## File Structure

```
pb-starter/
├── src/                      # Source files (edit these)
│   ├── css/
│   ├── js/
│   ├── fonts/
│   ├── images/
│   └── svg/
├── build/                    # Compiled files (auto-generated)
│   ├── css/
│   ├── js/
│   ├── fonts/
│   ├── images/
│   └── svg/
├── inc/                      # PHP functionality
│   ├── setup/
│   │   ├── setup.php
│   │   ├── scripts.php       # Enqueue styles/scripts
│   │   └── block-editor.php
│   ├── blocks/
│   ├── excerpt.php
│   └── ...
├── patterns/                 # Block patterns
├── parts/                    # Template parts
├── templates/                # Page templates
├── functions.php
├── theme.json
├── package.json
├── postcss.config.js
└── .gitignore
```

---

## Theme.json Integration

CSS custom properties from `theme.json` work alongside SCSS-style variables:

```css
/* Use theme.json variables (recommended) */
.element {
    color: var(--wp--preset--color--primary);
    padding: var(--wp--preset--spacing--medium);
}

/* Use $ variables only for media queries */
@media (max-width: $breakpoint-mobile) {
    /* $ variables compile to actual values */
}
```

---

## Block Patterns & Reuse

* The theme provides **predefined Block Patterns** for common layout sections
* Patterns include semantic structure, spacing rules, and styling presets
* These patterns serve as the primary method for creating new content layouts, reducing the need for one-off styling

---

## Editor Customizations

### Hidden Blocks
Unnecessary blocks are hidden to keep the inserter clean (configured in `inc/setup/block-editor.php`)

### Custom Block Styles
Semantic variations instead of custom blocks:
- Primary/Secondary button styles
- List style variations (checkmarks, arrows)
- Navigation link styles

### Utility Block Styles
Registered block styles for common layout needs:
- `.is-style-large-gap` - Increase gap between elements
- `.is-style-hidden-mobile` - Hide on mobile devices
- `.is-style-reverse-mobile` - Reverse column order on mobile

---

## Development Workflow

### Daily Development

```bash
# Start watch mode
npm run watch

# Edit files in src/
# - CSS in src/css/
# - JS in src/js/
# - Assets in src/fonts/, src/images/, src/svg/

# Files auto-compile to build/
# Refresh browser to see changes
```

### Before Committing

```bash
# Build production files
npm run build

# Git add both source and build
git add src/ build/

# Commit
git commit -m "Update styles"
```

### Deployment

The `/build/` directory is committed to git for simple deployment. Just push your changes and the built files are ready to use.

---

## Git Strategy

**Committed to git:**
- `/src/` - Source files
- `/build/` - Built files (for deployment)
- `package.json` - Dependencies
- `postcss.config.js` - Build configuration

**Ignored (in .gitignore):**
- `node_modules/` - npm packages (too large)
- Build logs
- Editor settings

---

## Security

Run regular security audits:

```bash
npm audit
```

All dependencies are kept up-to-date to avoid vulnerabilities.

---

## Browser Support

```
last 2 versions
not dead
not IE 11
```

Configured in `package.json` → `browserslist`

---

## When Development is Needed

Although the theme supports flexible content creation, truly new layout systems, visual elements, or patterns require development. This ensures:

* New design elements are consistent with the system
* CSS and theme.json tokens are reused
* Editor experience stays clean and easy to use

---

## Summary

This theme is designed to:

* **Empower editors** to create rich layouts safely
* **Reduce development overhead** through reuse and clear structure
* **Support long-term growth** by evolving the system, not bypassing it
* **Maintain consistency** through a design system and intelligent CSS
* **Stay maintainable** with modern build tools and clear organization

---

## Resources

- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [theme.json Documentation](https://developer.wordpress.org/themes/global-settings-and-styles/theme-json/)
- [PostCSS Documentation](https://postcss.org/)
- [esbuild Documentation](https://esbuild.github.io/)

---

**Version:** 1.0.0  
**Author:** Patrick Boehner  
**License:** GNU General Public License v3