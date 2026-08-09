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

CSS handles spacing automatically based on context - editors don't need to adjust spacing controls.

#### The rhythm scale

Spacing steps up as the relationship between two blocks gets looser. **Text and heading spacing
is the base rhythm** - that is why the root `blockGap` is `small` rather than the `large` that
content sections use:

| Step | Sits between | Set by |
|---|---|---|
| `small` | paragraph to paragraph - the base prose rhythm | `theme.json` root `blockGap` |
| `medium` | a **container** following a sibling, at any depth - plus a heading or image interrupting prose | the structural and prose rules |
| `large` | top-level blocks in the content area | `.site-content > *`, `article.type-* > *` |
| `x-large` | full-width sections | the section rule below |

The `medium` step splits along **containers vs. elements**. A `group`, `columns`, `cover` or
`query` is a structural boundary wherever it sits, so it steps up at any depth - inside a section,
a column, a card or a grid cell. A heading or image only steps up when it is breaking up prose in
an article; inside a service card an image and its heading are one unit and stay tight.

The container rule is deliberately loose - no depth limit, no list of parent contexts - so nesting
a pattern one level deeper never silently loses its rhythm. Tighten it by excluding a specific
case, not by enumerating the allowed ones.

Paragraph-to-paragraph inside `core/post-content` therefore sits at `small`. That is the intended
base, not drift - the looser relationships step up from it rather than the base stepping down.

**A full-width block sitting directly in the content area is a section.** That is the whole model:

```css
/* Standard spacing between content blocks */
.site-content > * {
    margin-block-start: var(--wp--preset--spacing--large);
}

/* A section - a full-width container, scoped to the content area,
   so headers and footers never match and need no reset */
:where(
    .site-content,
    .entry-content,
    .is-root-container:not(.wp-site-blocks)
) > :where(.wp-block-group, .wp-block-cover).alignfull {
    margin-block-start: var(--wp--preset--spacing--x-large);
}

/* When sections with backgrounds touch, remove gap */
.alignfull:where(.has-background, [class*="is-style-section"], .wp-block-cover)
+ .alignfull:where(.has-background, [class*="is-style-section"], .wp-block-cover) {
    margin-block-start: 0;
}
```

Padding follows the surface, not the tag. A container insets its contents only when it has a
background, a cover image, or a section style variation. A plain group is a grouping device,
not a box, so it stays flush and padding never compounds however deeply blocks nest.

#### The three-layer cascade

Spacing resolves in a deliberate order, and the order is enforced by specificity:

| Layer | Form | Specificity |
|---|---|---|
| 1. Automatic defaults | `:where(context) > .thing:where(qualifier)` | (0,1,0) |
| 2. Component / chrome | `.header-base`, `.footer-base`, `.is-style-card` | (0,1,0), declared later |
| 3. Editor controls | inline `style=""` from the spacing panel | inline |

> **Invariant: a default rule never exceeds (0,1,0).** Put contexts and qualifiers inside
> `:where()`, which contributes nothing, and leave a single class on the thing being styled.
>
> This is not stylistic. A default that reaches (0,2,0) silently outranks the component rule
> meant to override it, and no amount of source reordering will fix it. A nested
> `.wp-block-group { &.alignfull { padding-block: x-large } }` compiles to (0,2,0) and once gave
> every header and footer roughly six times the padding they asked for.

There are two documented exceptions, both marked in the CSS. Each exists because a **core** rule
sits at the same specificity, and a tie is decided by source order — which inverts between the
front end and the editor, so matching specificity is not enough:

- **Section rhythm in the post editor.** Core scopes its editor blockGap to
  `.block-editor-block-list__layout.is-root-container` (two classes).
- **Inline padding on nested surfaces.** Core zeroes it on nested constrained containers to stop
  root padding compounding; a surfaced box owns its own inset, so the theme restores it at
  `.has-global-padding.has-background` and friends.

Before adding a third, check that the thing you are fighting really is a core rule at equal
specificity — the fix for a theme-vs-theme conflict is source order, not more classes.

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
- `.is-style-large-gap` - Increase gap between elements
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
