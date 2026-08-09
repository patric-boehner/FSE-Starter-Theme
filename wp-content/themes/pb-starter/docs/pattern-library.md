# Pattern Library — porting and normalization

How the opt-in section pattern library in `patterns/library/` works, and the checklist
for adapting a pattern from a donor theme into this theme's idiom.

Sources: [Ollie](https://olliewp.com) (GPLv3), Twenty Twenty-Five (GPLv2+), Twentig One
(GPLv3). Attribution and image licensing are in [readme.txt](../readme.txt).

---

## 1. Turning the library on

Off by default. Every project starts with only the theme's own patterns.

```php
// wp-config.php — per environment
define( 'FSE_PATTERN_LIBRARY', true );
```

Or programmatically:

```php
add_filter( 'fse_pattern_library_enabled', '__return_true' );
```

Implementation is in [inc/setup/patterns.php](../inc/setup/patterns.php). WordPress scans
`patterns/` **recursively**, so a subdirectory hides nothing on its own — the gate filters
`theme_block_pattern_files` before any pattern header is parsed.

### The transient gotcha

WordPress caches the pattern file list in a site transient for 30 minutes, invalidated only
when `style.css` `Version:` changes. **Flipping the constant does not take effect
immediately.** Three fixes, in order of preference:

1. **Dev:** `define( 'WP_DEVELOPMENT_MODE', 'theme' );` in `wp-config.php`. Bypasses and
   deletes the cache. Also fixes "I edited a pattern and nothing changed."
2. **Deploy:** bump `Version:` in `style.css` — you do this anyway for cache busting.
3. **Manual:** `wp transient delete --all`.

### Categories

Core registers its pattern categories unconditionally, so
`remove_theme_support( 'core-block-patterns' )` in [inc/setup/setup.php](../inc/setup/setup.php)
drops the core *patterns* but keeps the *categories*. Use core's wherever one fits:

`banner` · `call-to-action` · `services` · `testimonials` · `contact` · `team` · `about` ·
`text` · `columns` · `gallery` · `posts` · `header` · `footer`

The theme adds only two: `pb-starter/features` and `pb-starter/pricing`.

Tag each pattern with **one primary and at most one secondary** category. Ollie tags up to
four, and the result is that every category lists every pattern.

---

## 2. Section patterns vs. component patterns

This distinction drives most of the checklist, and it is not obvious from the donor themes.

**Section patterns** are full-width page sections. The outermost block *must* be a
`core/group` with `"align":"full"` and a constrained layout — the entire contextual-spacing
system keys off `.alignfull`:

```css
:where(
	.site-content,
	.entry-content,
	.is-root-container:not(.wp-site-blocks)
) > :where(.wp-block-group, .wp-block-cover).alignfull {
	margin-block-start: var(--wp--preset--spacing--x-large);
}
```

Two things are scoped here. A section is a full-width **container** — `core/group` or
`core/cover` — and it must sit **in the content area**. The same markup in a header or footer
part is chrome and gets none of this, which is why the rules need no resets; and a full-bleed
image or heading keeps the normal content rhythm rather than claiming a section's.

The third container in that list is the post editor canvas. `.entry-content` is produced by
`core/post-content` in PHP, so it does not exist while editing — without
`.is-root-container`, sections would lose their spacing in the editor only.

Miss `alignfull` and the section gets root `blockGap` (`small`) instead of `x-large`, which
reads as broken. There must be **exactly one** `alignfull` per pattern — nested ones double
the vertical padding.

Use a **constrained** layout on the section wrapper. Core adds `has-global-padding` only to
constrained containers, and that is what restores the inline padding a full-width block breaks
out of.

> ⚠️ `main.site-content` is constrained in `index`, `archive`, `search`, `single` and
> `post-with-sidebar`, but a plain flow layout in `404`, `page`, `page-no-title` and `blank`.
> On those four, a full-width block placed as a *direct* child of `main` gets no inline padding
> back. Patterns dropped into `core/post-content` are unaffected — post-content supplies its own
> constrained layout one level down.

**Component patterns** (cards, single testimonials) are dropped *inside* an existing column
or grid. They are **exempt** from the alignfull rule and use `Viewport Width: 600`.
`patterns/library/testimonial-card.php` is the reference.

---

## 3. Nested groups and padding

A container insets its contents only when it has a **surface of its own** — a background, a
section style, or a cover's image. See
[src/css/02-base/layout.css](../src/css/02-base/layout.css):

```css
.wp-block-group:where(.has-background, [class*="is-style-section"]),
.wp-block-cover {
	padding: var(--wp--preset--spacing--medium);
}
```

A full-width section then gets a deeper block inset on top of that:

```css
:where(.site-content, .entry-content) > .alignfull:where(
	.has-background,
	[class*="is-style-section"],
	.wp-block-cover
) {
	padding-block: var(--wp--preset--spacing--x-large);
}
```

A plain `core/group` is a grouping device, not a box, so it sits flush. That matters because
donor patterns nest three and four groups deep — if every group were padded, the inset would
compound into several rem. Nest as deeply as the layout needs; only the blocks that actually
look like boxes get padding.

Prefer `core/columns`/`core/column` for layout regardless. They are lighter than a group and
carry no padding behaviour at all.

---

## 4. The class/attribute sync contract

Every block is a comment holding JSON plus the serialized HTML it produced. Change one and
you must change the other, or the editor flags "unexpected or invalid content."

Gutenberg compares `class` as a **token set** and `style` as a **declaration set** — order
and whitespace are irrelevant, only presence. So you don't need core's exact serialization
order, which is what makes this tractable by hand.

| Attribute change | Required HTML change |
|---|---|
| remove `style.spacing.padding` / `margin` | remove matching decls; drop `style=""` if it empties |
| remove `style.spacing.blockGap` | **none** — blockGap emits no inline style |
| `textColor: a → b` | `has-a-color` → `has-b-color`, keep `has-text-color` |
| remove `textColor` | remove `has-a-color` **and** `has-text-color` |
| `backgroundColor: a → b` | `has-a-background-color` → `has-b-background-color`, keep `has-background` |
| remove `backgroundColor` | remove both **and** `has-background` |
| remove `borderColor` | remove `has-border-color` and `has-{slug}-border-color` |
| `fontSize: a → b` | `has-a-font-size` → `has-b-font-size` |
| remove `style.color.duotone` | **none** — duotone emits a render-time class |

Two distinct failure modes, worth keeping straight:

- **Wrong slug** → renders wrong, validates fine. Silent.
- **Desynced class/attribute** → invalid-block warning. Loud.

---

## 5. Token remapping

### Spacing and font sizes

**Ollie needs no remap** — its spacing scale (`small` … `xxxx-large`) and font-size scale
(`x-small` … `xx-large`) are identical to this theme's. This theme's `theme.json` is clearly
derived from Ollie's.

**Twenty Twenty-Five needs both remapped.** Its spacing scale is numeric and its font sizes
mean different things:

| TT5 spacing | → | TT5 font size | → | Why |
|---|---|---|---|---|
| `20`, `30` | `small` | `small` | `small` | |
| `40`, `50` | `medium` | **`medium`** | **`base`** | ⚠️ TT5 `medium` is 1rem body copy; ours is 1.65rem, an h3 |
| `60` | `large` | `large` | `medium` | |
| `70` | `x-large` | `x-large` | `medium` | |
| `80` | `xx-large` | `xx-large` | `large` | |

**Twentig** spacing runs `5`–`100` plus two invented slugs, `site-padding` and `auto`, which
have no equivalent here — delete them.

### Colors

| Ollie | → | Twenty Twenty-Five | → |
|---|---|---|---|
| `primary` | `primary` | `base` | `base` |
| `primary-accent` | `primary-light` ⚠️ | `contrast` | `contrast` |
| `primary-alt` | `primary-light` | `accent-1` | `secondary` |
| `primary-alt-accent` | `primary-dark` | `accent-2` | `primary-light` |
| `main` | `contrast` | `accent-3` | `primary` |
| `main-accent` | `border-light` | `accent-4` | `contrast-light` |
| **`secondary`** | **`contrast-light`** ⚠️ | `accent-5` | `tertiary` |
| `tertiary`, `base`, `border-*` | unchanged | `accent-6` | `border-light` |

⚠️ **`secondary` inverts meaning.** In Ollie and Twentig it is muted body text. Here it is
orange `#f4a261`, which is **2.06:1** on white — a WCAG failure. Never pass it through, and
never use it for text.

### Always contrast-check the result

A slug-for-slug remap can still produce an inaccessible pairing. The Ollie
`primary-accent → primary-light` mapping lands at **4.11:1** on a `primary` background —
below the 4.5:1 AA threshold. In `features-3-col.php` the fix was to drop the paragraph
colour entirely and inherit `base` (5.22:1).

Useful ratios on this palette:

| On `primary` (#16768f) | | On `base` (#ffffff) | |
|---|---|---|---|
| `base` | 5.22 ✓ | `contrast` | 18.26 ✓ |
| `tertiary` | 4.79 ✓ | `accent` | 9.74 ✓ |
| `primary-light` | 4.11 ✗ | `primary-dark` | 8.43 ✓ |
| `contrast` | 3.50 ✗ | `contrast-light` | 5.74 ✓ |
| `secondary` | 2.53 ✗ | `primary` | 5.22 ✓ |
| | | `secondary` | 2.06 ✗ |

---

## 6. Strip list

**Disabled in `theme.json`** — these attributes render nothing, so remove them and their
inline declarations:

- all `style.border.*` and `borderColor` → use `is-style-card` for card surfaces
- `style.shadow` → no presets exist
- `style.typography.fontWeight` / `fontStyle` / `lineHeight` / `letterSpacing` /
  `textTransform` / `textDecoration` → for emphasis use `<strong>` in the rich text instead

**Unregistered blocks** ([inc/setup/block-editor.php](../inc/setup/block-editor.php) removes
these via `unregisterBlockType()`, so leaving one in renders an *unsupported block*):
`core/spacer`, `core/media-text`, `core/table`, `core/html`, `core/code`, `core/pullquote`,
`core/preformatted`, `core/verse`, `core/audio`, `core/video`, `core/file`.

Convert `core/spacer` to a parent `blockGap`, or delete it and let contextual spacing work.

**Unknown `is-style-*`.** Anything not in the allowlist below. One is a silent trap:

> ⚠️ **`is-style-section-N` must be stripped.** It renders nothing here, but it still matches
> `[class*="is-style-section"]` in the adjacency-collapse rule in `layout.css`, silently
> killing the margin between two sections.

**Allowed classes**

| Class | Where defined |
|---|---|
| `is-style-primary`, `is-style-secondary` (button) | registered, `blocks/core-button.css` |
| `is-style-no-bullets`, `is-style-checkmarks`, `is-style-arrows` (list) | registered, `blocks/core-list.css` |
| `is-style-button` (navigation-link) | registered |
| `is-style-section-light`, `is-style-section-dark` | `styles/sections/*.json` |
| `is-style-card` | `03-components/cards.css` |
| `is-style-large-gap`, `is-style-hidden-mobile`, `is-style-columns-reverse` | `02-base/layout.css` |
| `is-style-logos-only`, `is-style-pill-shape`, `is-style-rounded` | core's own CSS — hidden from the style picker but still render |

The theme deliberately keeps this vocabulary small. Before adding a class, check whether
core already ships one (as with `is-style-rounded` for avatars) or whether a block attribute
does the job — a new class is the last resort, not the first.

Run `npm run lint:patterns` to check all of the above mechanically. The linter reads
`theme.json` at run time, so preset checks stay correct as the palette changes.

---

## 7. Images

Bundled CC0 files live in `src/images/patterns/`, copied to `build/images/patterns/` by the
existing asset step — no build changes needed. Named by **role**, not subject, because the
point of a starter is that projects swap them: `avatar-1..4`, `logo-1..5`, `landscape-1..3`,
`portrait-1`, `square-1`.

Reference form. `get_theme_file_uri()` (not `get_template_directory_uri()`) so a child theme
can override an image without touching the pattern:

```php
<img src="<?php echo esc_url( get_theme_file_uri( 'build/images/patterns/landscape-1.webp' ) ); ?>"
     alt="<?php echo esc_attr_x( 'Placeholder image', 'Alt text for service image.', 'pb-starter' ); ?>"
     style="aspect-ratio:4/3;object-fit:cover"/>
```

**No `"id"` attribute and no `wp-image-N` class** — donor patterns carry attachment IDs from
their demo sites that point at nothing here.

---

## 8. Per-pattern checklist

1. Section or component? Sets whether `align:full` is required (§2).
2. Outermost `core/group`, constrained layout, exactly one `alignfull`.
3. Prefer `core/columns` for layout; a plain nested group carries no padding (§3).
4. Remove every `core/spacer` and any other unregistered block (§6).
5. Strip all `padding` / `margin`; strip `blockGap` unless the pattern needs internal rhythm
   the CSS cannot infer (a card's quote-to-attribution gap is a legitimate keep).
6. Remap colours, spacing, font sizes (§5). **Contrast-check the result.**
7. Strip disabled attributes and unknown `is-style-*` (§6).
8. Replace images; delete attachment IDs (§7).
9. Wrap every visible string in `esc_html_e()` / `esc_attr_x()` with the `pb-starter` domain.
   Rewrite donor copy to generic placeholder text.
10. Add `metadata.name` to the outermost block and each meaningful inner group; add
    `metadata.categories` and `metadata.patternName` matching the file header.
11. Write the header: `Title`, `Slug` (`pb-starter/lib-*`), `Description`, `Categories`,
    `Keywords`, `Viewport Width`, `Inserter`, plus an attribution line. Omit empty
    `Block Types:` / `Post Types:` — WordPress discards them anyway.

---

## 9. Verifying a ported pattern

- `php -l` the file.
- Insert it on a page → **zero invalid-content warnings**. Then open the Code Editor view and
  confirm the markup round-trips unchanged; the editor re-serializes on save even with no
  edit, so this is the real desync test.
- Compare editor and front end at 375 / 768 / 1440. One difference is expected and fine: the
  `.site-content > *` rules have no `.site-content` ancestor in the post editor. `.alignfull`
  margins are unscoped and *do* apply.
- **Adjacency:** two sections back to back with backgrounds should collapse to no gap;
  without backgrounds they should sit `x-large` apart. This is how a missed
  `is-style-section-N` gets caught.
- **Nesting:** measure the horizontal inset. More than one `medium` means a nested container
  has a background it should not have.
- Tab through it; check heading levels run h2 → h3 without skipping.
- `npm run build`, then confirm any new `03-components/*.css` reached **both**
  `build/css/frontend.css` **and** `build/css/editor.css`. Adding a component to only
  `frontend.css` is the easiest mistake to make and it silently breaks WYSIWYG.
- Commit `src/` **and** `build/`.
