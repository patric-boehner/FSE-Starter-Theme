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

# Start development (build + watch)
npm run dev

# Build for production
npm run build
```

---

## Build System

### Technology Stack
- **CSS Processing**: Lightning CSS for bundling, nesting, and autoprefixing
- **JavaScript**: esbuild for fast bundling
- **SVG Optimization**: SVGO for optimizing SVG files
- **Asset Copying**: Automated copying of fonts and images

### Source & Output

```
src/                          → build/
├── css/                      → css/
│   ├── frontend.css         → frontend.css
│   ├── editor.css           → editor.css
│   ├── 01-settings/
│   │   └── variables.css    (@custom-media definitions)
│   ├── 02-base/
│   ├── 03-components/
│   ├── 04-templates/
│   └── blocks/
│       └── core-button.css  → blocks/core-button.css
├── js/                      → js/
│   └── editor.js            → editor.js
├── fonts/                   → fonts/
├── images/                  → images/
└── svg/                     → svg/ (optimized)
```

### NPM Commands

```bash
# Development
npm run dev            # Build once + watch for changes
npm run build:dev      # Build without minification

# Production
npm run build          # Build minified/optimized files

# Watch (individual)
npm run watch          # Watch all files
npm run watch:css      # Watch CSS only
npm run watch:js       # Watch JS only
npm run watch:svg      # Watch SVGs only

# Cleanup
npm run clean          # Delete build/ directory
```

### CSS Features

**Lightning CSS provides:**
- CSS nesting (native syntax)
- `@custom-media` queries (CSS Media Queries Level 5)
- `@import` bundling
- Automatic vendor prefixes
- Minification (production only)

**Custom Media Queries (Mobile-first):**

```css
/* 01-settings/variables.css */
@custom-media --tablet (min-width: 600px);   /* Tablet and up */
@custom-media --desktop (min-width: 782px);  /* Desktop and up */

/* Write base styles for mobile, then enhance for larger screens */
.element {
    padding: 1rem;
}

@media (--tablet) {
    .element { padding: 2rem; }
}

@media (--desktop) {
    .element { padding: 3rem; }
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
@import "01-settings/variables.css";
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
├── core-button.css
├── core-list.css
├── core-navigation.css
└── ...
```

### Contextual Spacing Philosophy

CSS handles spacing automatically based on context - editors never open a spacing control.
The full design rationale and core source references are in [docs/spacing.md](docs/spacing.md).

**The theme does not write vertical rhythm.** WordPress already emits a lobotomised owl for
every flow and constrained container, at every nesting depth, into both the front end and the
editor canvas. `theme.json` points its `blockGap` at a variable chain rather than a length, so
core's rule becomes a lookup:

```jsonc
"styles": {
  "spacing": {
    "blockGap": "var(--space-above, var(--space-between, var(--wp--preset--spacing--small)))"
  }
}
```

Two knobs, and deliberately only two:

| Property | Inherits | Means | Set on |
|---|---|---|---|
| `--space-between` | yes | the default rhythm inside this container | a container |
| `--space-above` | **no** | the space above this one element | an element |

`--space-above` is registered with `@property { syntax: "*"; inherits: false }` so an element can
override the space above *itself* without that value leaking to its children. The missing
`initial-value` is load-bearing - it leaves the property guaranteed-invalid, which is exactly
what makes the `var()` fallback fire.

#### Reading it

Two questions answer any spacing behaviour, both by inspection:

1. *What is the nearest ancestor that sets `--space-between`?* That is the default gap.
2. *Does this element set `--space-above`?* If so, that wins, for this element only.

The rhythm scale itself is unchanged - `small` between text, `medium` for a structural break,
`large` at the top level of the content area, `x-large` between full-width sections.

#### Two things that will bite

> ⚠️ **`settings.spacing.blockGap` must be `false`, never `null`.** Core gates the entire
> mechanism with `isset()`, and `isset(false)` is `true` - so `false` emits the owl and hides
> the control, while `null` deletes the system and every gap on the site silently collapses.
> `null` is the obvious-looking way to hide a control, so check this first whenever spacing
> disappears wholesale.

> ⚠️ **A container reads `--space-between` for its own top margin as well as its children's.**
> Any declaration on an element is part of that element's own computed value, so "receive
> rhythm from above" and "declare different rhythm below" cannot be separated by inheritance
> alone. In practice it is harmless because every container is already covered by an explicit
> `--space-above` rule, but it is the one place the model does something other than what it looks
> like it does.

Flex and grid map `blockGap` to `gap` on the container rather than a margin on the child, so
they take `--space-between` only — otherwise a `--space-above` meaning "space above me" would be
used as the gutter *between* a block's children.

> ⚠️ **The theme writes `gap` in exactly one place**, and that rule needs `(0,2,0)` to beat
> core's own `:root :where(.is-layout-flex)`. Anything else that wants to change a gap must
> re-point `--space-between` instead. A `gap` declaration on a single class is `(0,1,0)` and
> loses to it silently — that is how `is-style-loose` stopped working.

Padding is not rhythm and stays as ordinary CSS - four rules, two of which legitimately need
`(0,2,0)` to beat a core rule at equal specificity.

#### The escape hatch

Spacing controls are hidden, so a pattern-specific tweak comes from a registered block style
that re-points one of the two properties, not from an editor control. `is-style-tight`,
`is-style-loose` and `is-style-flush` are offered on Group, Columns and Cover. If they prove
too few, add another - do not open a control.

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
├── build.js                  # Build script
└── .gitignore
```

---

## Theme.json Integration

CSS custom properties from `theme.json` are the primary way to access design tokens:

```css
/* Use theme.json variables for colors, spacing, typography */
.element {
    color: var(--wp--preset--color--primary);
    padding: var(--wp--preset--spacing--medium);
    font-size: var(--wp--preset--font-size--medium);
}

/* Use @custom-media for responsive breakpoints */
@media (--mobile) {
    .element {
        padding: var(--wp--preset--spacing--small);
    }
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
- `.is-style-loose` - Increase gap between elements
- `.is-style-hidden-mobile` - Hide on mobile devices
- `.is-style-columns-reverse` - Reverse column order on mobile

---

## Development Workflow

### Daily Development

```bash
# Start dev mode (build + watch)
npm run dev

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
- `build.js` - Build script

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
- [Lightning CSS Documentation](https://lightningcss.dev/)
- [esbuild Documentation](https://esbuild.github.io/)
- [SVGO Documentation](https://svgo.dev/)

---

**Version:** 1.0.0
**Author:** Patrick Boehner
**License:** GNU General Public License v3
