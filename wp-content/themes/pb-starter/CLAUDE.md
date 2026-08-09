# PB Starter — Build Rules

Rules for building/maintaining this WordPress FSE theme. Terse on purpose. For the
reasoning behind these choices see [docs/philosophy.md](docs/philosophy.md); for build
tooling, CSS architecture, and workflow see [readme.md](readme.md).

Core stance: **work with WordPress and FSE, not against it.** Use core blocks,
theme.json, and CSS instead of custom solutions. Limit editor options — editors should
focus on content and patterns, not sliders.

## PHP

- Procedural only. No classes/OOP.
- Prefix everything `fse_` — functions, actions, filters. Never generic names.
- Hook everything; don't run code at file scope.
- Functions do one thing, ~20-30 lines max.
- Escape all output (`esc_html`, `esc_url`, `esc_attr`); sanitize all input.
- Comment the *why*, not the *what*.
- Follow WordPress Coding Standards.

### `/inc` organization (by feature, not type)

```
functions.php          # loader only: constants + require_once
inc/
  setup/setup.php       setup/scripts.php       setup/block-editor.php
  setup/block-bindings.php
  archive.php  comments.php  excerpt.php  media.php  security.php
  skip-links.php  wordpress-cleanup.php  login.php  user-profile.php
  acf.php  gravityforms.php
  content-slots.php     # declares slots for Core Functionality's Content Areas
  template-tags.php     # PURE helpers only — no hooks, no side effects, no DB/enqueue
  blocks/
```

`functions.php` stays a loader: define `THEME_*` constants, then `require_once` feature
files. No logic.

## FSE structure

- Templates → `/templates/*.html`, parts → `/parts/*.html`, patterns → `/patterns/*.php`.
- No PHP page templates. Use template files + patterns.
- Templates use semantic HTML5 (`<header> <main> <article> <footer>`) with skip-link IDs
  (`id="main-content"`, `id="footer"`).
- Patterns must use theme.json values only, be accessible, and carry proper metadata.

## theme.json is the design system

- All colors, typography (fluid), spacing scale, and layout widths live here.
- Never hardcode a value in CSS that belongs in theme.json. Repeated value = token.
- theme.json does NOT need every block style — use CSS for block/contextual styling.

## CSS

- Plain CSS only. No preprocessors, no Tailwind/Bootstrap, no CSS-in-JS.
- Build pipeline is Lightning CSS (native nesting, `@custom-media`, `@import` bundling).
- Source in `src/css/`, compiled to `build/css/`. Edit source, never `build/`.
- Structure: `01-settings · 02-base · 03-components · 04-templates · 05-editor · blocks/`.
- Mobile-first; enhance up with `@media (--tablet)` / `(--desktop)`.
- Per-block CSS via `wp_enqueue_block_style()` so it loads only when used.

**Contextual spacing is the core idea:** CSS decides spacing from context
(`.site-content > *`, `.alignfull`, `.has-background`, adjacent sections) so editors
never touch spacing controls. Block settings are overrides, used rarely. Full examples
in [readme.md](readme.md#contextual-spacing-philosophy).

Shared styles load in editor + front-end; keep editor and front-end visually matched
(WYSIWYG). Front-end-only and editor-only styles stay scoped separately.

## JavaScript

- Vanilla only. No jQuery, no React in the theme (blocks are fine).
- Enqueue from external files (`src/js/` → `build/js/`); never inline.

## Editor customizations — less is more

Be aggressive about hiding options; only customize when WordPress's default conflicts
with the design system or would confuse editors. Don't rebuild the editor.

- Hide unused blocks from the inserter; keep FSE blocks (Navigation, Query, Site Logo)
  out of the post/page editor.
- Provide **block styles** for semantic variants (primary/secondary button, list
  checkmarks/arrows) instead of custom blocks.
- Provide **utility block styles** (`.is-style-large-gap`, `.is-style-hidden-mobile`,
  `.is-style-columns-reverse`) instead of exposing granular controls.
- Hide confusing core block styles.
- Configured in `inc/setup/block-editor.php`.

## Accessibility (non-negotiable)

Semantic HTML · functional skip links · full keyboard access · visible focus states ·
WCAG AA contrast minimum (AAA preferred) · alt text · screen-reader context where visual
users have cues that SR users don't.

## Plugins

Theme styles plugin output to match the design; never depends on a plugin for core
function. ACF: disable CPT/taxonomy UI, hide in production, keep field groups in JSON.
Gravity Forms: match theme button styles, mobile-friendly, clear validation states.

## Build & git

- `npm run dev` (build + watch), `npm run build` (production) — details in
  [readme.md](readme.md).
- Both `src/` and `build/` are committed (build is the deploy artifact). Run
  `npm run build` before committing style/script changes.
- Cache-bust with `time()` when `WP_DEBUG`, else `THEME_VERSION`.
