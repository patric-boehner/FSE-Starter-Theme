# Spacing Architecture

A decision document. The question it answers: what is the right way to build automatic
vertical rhythm in an FSE theme, so a client can drop in a block or a pattern and the
spacing is simply correct, without ever opening a spacing control?

The reasoning behind `src/css/02-base/layout.css`, kept because the *why* is not recoverable
from the CSS — most of it is about what core already does. Read the
[findings](#what-wordpress-already-does) first; they change the shape of the answer.

**Status:** implemented.

---

## The problem

Spacing controls are the wrong default for a client-facing theme. They put a design system
decision in front of someone who is trying to write a page, they produce inline styles that
survive every later design change, and they guarantee that six months of edits leave a site
where no two sections are spaced alike. Hiding them is easy. Making the site look right
*with them hidden* is the actual work.

The requirement is narrow and worth stating precisely:

- A client drops a core block into a page, within reason, and the space above it is right.
- A pattern drops into any context — page content, a column, a card — and its internal
  rhythm is right at that depth.
- The editor and the front end agree, because a client who sees one thing and gets another
  stops trusting the editor.
- The rules are legible. Someone reading the CSS six months from now can tell what governs
  the space above a given block without running a specificity calculation.

That last one is where every previous attempt at this has degraded, in this theme and in
the two Genesis themes before it.

## What WordPress already does

WordPress ships the system. It is not documented as such, which is why three themes have
now hand-rolled it.

When `settings.spacing.blockGap` is set to anything other than `null`, core emits into the
global stylesheet (`wp-includes/class-wp-theme-json.php:1794`):

```css
:root :where(.is-layout-flow) > :first-child { margin-block-start: 0 }
:root :where(.is-layout-flow) > :last-child  { margin-block-end: 0 }
:root :where(.is-layout-flow) > *            { margin-block-start: <styles.spacing.blockGap>; margin-block-end: 0 }
```

…and the same three rules for `.is-layout-constrained`. That is the lobotomised owl — the
Stack, in Every Layout's terms. Core applies it to **every flow and constrained container,
at every nesting depth**, and `block-editor.js:37979` emits byte-identical selectors into
the editor canvas.

Three consequences follow, and together they decide the design.

**1. A hand-written system ties with it.** `:root` counts for specificity; `:where()` does
not. So core's rule is `(0,1,0)` — the same as `.site-content > *`. Every rule in the
theme's current spacing layer wins or loses against core on stylesheet load order, not on
specificity. That is the real origin of the `(0,1,0)` invariant the current code documents,
and of the header-padding bug the comments describe. The invariant is a workaround for a
tie nobody noticed.

**2. The gap value passes through verbatim.** `safecss_filter_attr` strips balanced CSS
functions recursively (`wp-includes/kses.php:2846`), so nested `var()` survives intact. The
theme already relies on this today with `"blockGap": "var(--wp--preset--spacing--small)"`.
The value does not have to be a length. It can be a lookup.

**3. The editor-parity duplicates are dead code.** The post editor's root container carries
`wp-block-post-content` (`wp-includes/js/dist/editor.js:54407`), and `core/post-content`
renders that same class on the front end (`wp-includes/blocks/post-content.php:73`). One
selector reaches both. Separately, the iframed editor canvas injects theme editor styles
unmodified, and the non-iframed path scopes them with `:where(.editor-styles-wrapper)` —
zero added specificity (`block-editor.js:40939`). The style-prefixing behaviour that the
`.block-editor-block-list__layout.is-root-container` twins were written to survive no longer
exists.

The conclusion is one sentence: **stop reimplementing the owl and start feeding it.**

## The model

Set `styles.spacing.blockGap` to a variable chain instead of a value. Core's rule becomes a
lookup, and the theme's entire spacing layer reduces to custom property declarations.

```json
"styles": {
  "spacing": {
    "blockGap": "var(--space-above, var(--space-between, var(--wp--preset--spacing--small)))"
  }
}
```

Two knobs, and deliberately only two:

| Property | Inherits | Means | Set on |
| --- | --- | --- | --- |
| `--space-between` | yes | the default rhythm inside this container | a container |
| `--space-above` | **no** | the space above this one element | an element |

`--space-between` is an ordinary custom property, so it inherits and a container can set the
rhythm for everything inside it. `--space-above` is registered with `@property` as
non-inheriting, so an element can override the space above *itself* without that value
leaking to its children.

```css
@property --space-above { syntax: "*"; inherits: false; }
```

The `syntax: "*"` with no `initial-value` is load-bearing and worth understanding rather
than copying. Per the Properties and Values API, `initial-value` is required *except* when
the syntax is universal; omitted, the property's initial value is the **guaranteed-invalid
value**. And `var(--x, fallback)` substitutes its fallback exactly when `--x` is
guaranteed-invalid. So on any element that has not declared `--space-above`, the chain falls
through to `--space-between`; on one that has, it doesn't. `initial-value: 0` would not work,
because `0` is a real value and the fallback would never fire. There is no way to do this
with an unregistered property — unregistered custom properties always inherit.

### Choosing a tier

Two framings are available, and the theme uses the second.

**By type** — a container is a bigger break than a sentence, wherever it sits. Depth-blind,
so arbitrarily nested patterns space correctly with no per-level rules. But every rule needs
`:not()` exclusions to stop the broad selectors colliding, and the exclusions describe
specificity rather than intent.

**By depth** — each structural level names a tier. Fewer rules, and they map onto things you
can point at in a template. The cost is that the level list has to be kept current.

The theme is depth-based with a type-based floor, which keeps nesting correct without giving
up readability.

### What the layer looks like

```css
@property --space-above { syntax: "*"; inherits: false; }

/* Every container resets to the prose default, so a value never leaks past one level. */
:where(.is-layout-flow, .is-layout-constrained, .is-layout-flex, .is-layout-grid) {
	--space-between: var(--wp--preset--spacing--small);
}

/* The floor. Below the named levels a container is still a bigger break than a
   sentence — four Q&A groups in a column shouldn't sit at prose spacing. */
:where(.wp-block-group, .wp-block-columns, .wp-block-cover, .wp-block-query) {
	--space-above: var(--wp--preset--spacing--medium);
}

.site-content { --space-above: var(--wp--preset--spacing--x-large); }
.site-footer  { --space-above: 0; }

/* The levels. */
:is(main, article.type-post, article.type-page, .wp-block-post-content) > * {
	--space-above: var(--wp--preset--spacing--large);
}

/* A full-width container in the content area is a section.
   .wp-block-post-content is both the front-end render and the editor root. */
:is(.site-content, .wp-block-post-content) > :is(.wp-block-group, .wp-block-cover).alignfull {
	--space-above: var(--wp--preset--spacing--x-large);
}

/* Two surfaced bands in a row read as one band, so the gap collapses. */
.alignfull:is(.has-background, [class*="is-style-section"], .wp-block-cover)
+ .alignfull:is(.has-background, [class*="is-style-section"], .wp-block-cover) {
	--space-above: 0;
}
```

The theme never writes `margin-block-start` again. Specificity climbs monotonically —
`(0,0,0)` for the floor, `(0,1,0)` and `(0,1,1)` for the levels, `(0,3,0)` for sections,
`(0,4,0)` for the collapse — so each tier outranks the one it overrides and the order of the
file carries no meaning. And there is no editor parity work at all, because the rule doing
the work is core's, and core emits it in both places.

The `:where()` on the floor is the piece that makes this hold. At zero specificity it is a
default rather than a competitor, so no rule below it needs an exclusion to get out of its
way — and `.is-style-flush` at `(0,1,0)` can still zero any container, which it could not do
when the floor was a `(0,4,0)` rule.

### Reading it

Three questions answer any spacing behaviour, in order, and all are answerable by inspection:

1. *Is this element a direct child of a named level?* Then it takes that level's tier.
2. *Is it a container?* Then it takes the floor.
3. *Otherwise* — what is the nearest ancestor that sets `--space-between`?

Compare that to a hand-written margin layer, where the answer requires knowing which of six
overlapping `:where()` selectors matched, and whether the theme's stylesheet happened to
load after core's.

### The maintenance point

A template that introduces a new wrapper between `main` and the content drops everything
inside it a tier. Container children still get the floor, but a non-container child — most
importantly `core/post-content` — falls all the way to the base rhythm. It fails *quietly*:
nothing errors, spacing just goes tight.

This is the one cost of the depth framing. Nothing enforces it, so the habit to build is:
**adding a template means checking that list**. The symptom is content that looks cramped in
one template and correct everywhere else.

## The sharp edge

**A container reads `--space-between` for its own top margin as well as its children's.**

Setting `--space-between` on a container therefore also sets the space above that container,
unless a `--space-above` rule covers it. This is not a bug to be fixed with a third property or
another layer of indirection — any declaration on an element is part of that element's own
computed value, so "receive rhythm from above" and "declare different rhythm below" cannot
be separated by inheritance alone.

In practice it is harmless, because every container is already covered by an explicit
`--space-above` rule: the first-child reset core emits, the floor, a level, or the section
step. But it is the one place the model does something other than what it looks like it
does, so it belongs here and not in a comment three files away.

## Coverage limits

**Flex and grid work differently.** `wp_get_layout_definitions()` maps flow and constrained
to `margin-block-start` on the *child*, but flex and grid to `gap` on the *container*. So
`--space-above` on `.wp-block-buttons` or `.wp-block-columns` would set the space *between* the
buttons rather than above the group — silently, and wrongly. One override rule hands those
layouts the rhythm token only:

```css
:is(.is-layout-flex, .is-layout-grid):not(.wp-block-gallery) {
	gap: var(--space-between, var(--wp--preset--spacing--small));
}
```

Gallery is excluded because it sizes its items from `--wp--style--unstable-gallery-gap` and
the two values have to stay in step.

**Template parts get no owl.** `core/template-part` has no `layout` support (verified in
`wp-includes/blocks/template-part/block.json`), so `.site-header` and `.site-footer` children
receive no margin rule from core at all. The existing `.site-header > *` and
`.site-footer > *` resets in `layout.css` are already no-ops today.

**Padding has no owl to hook.** Surface padding, full-bleed section padding, and chrome
padding stay as ordinary CSS rules — roughly four of them. Two of those legitimately need
`(0,2,0)` to beat a core rule at equal specificity, and that documented exception survives
unchanged. Padding is not rhythm and does not want to be modelled as rhythm.

**`--space-between` must always be a single length.** A two-value shorthand inherits through a
non-layout wrapper such as `li.wp-block-post` and makes every `margin-block-start` below it
invalid at computed-value time — which resolves to `0`, not to the fallback. Asymmetric grid
gaps get a real `row-gap` declaration instead.

## The escape hatch

**Open decision.** Clients need to make pattern-specific tweaks. How much rope?

First, the setting that governs it, because one of the three values is a trap:

| `settings.spacing.blockGap` | Owl emitted? | Control shown? |
| --- | --- | --- |
| `true` (current) | yes | **yes** — a preset slider on every block |
| `false` | yes | no |
| `null` / omitted | **no — mechanism gone** | no |

Core tests with `isset()` (`class-wp-theme-json.php:1717`), and `isset(false)` is `true`. So
`false` is the value this design needs: rule emitted, control hidden. **`null` deletes the
entire system** — which matters, because `null` is the obvious-looking way to hide the
control and it is the one value that breaks everything.

Three options, in increasing order of rope:

1. **Utility block styles only.** `blockGap`, `padding` and `margin` all `false`. Tweaks
   come from registered block styles that re-point the two properties — `is-style-tight`,
   `is-style-loose`, `is-style-flush`. Fully contextual, nothing can drift, and it extends
   the pattern already used for `is-style-loose`. *Recommended* — but it is also the
   option with the least give, and if it turns out to be too little the answer is to add a
   utility, not to open a control.
2. **Utilities, plus padding on Group and Cover.** A client can deepen one hero band.
   Preset values only. The cost is an inline-style layer that always wins, which means it
   also survives every later design change you make.
3. **Utilities, plus blockGap on Group and Columns.** A client can loosen one stack.
   Technically the cleaner of the two hatches: `blockGap` serialises to that block's own
   container rule, so it overrides `--space-between` for that subtree only, with no inline
   style on any child.

## Why not the alternatives

**Hand-writing the owl on `.entry-content`** — the cbt-counseling approach, and the best of
the three existing themes. Single direction, typography margins zeroed so the flow owns all
spacing, `:where()`-flattened so single rules override without `!important`. The technique
is right. The defect is that it duplicates a rule core already emits, ties with it at
`(0,1,0)`, and pays for editor parity with a hand-maintained `editor-layout.css` that has
already drifted — it references `--wp--custom--layout--side-paddingbar`, a token that does
not exist, left over from a rename. Every theme that hand-writes the owl pays that tax, and
every one of them has already drifted on it.

**Role-named tokens over a t-shirt scale** — the Genesis pair's `--spacing--block-gap`,
`--entry-content`, `--group-padding`, each a `clamp()` between two scale steps, plus the
re-pointing idiom (`.is-style-medium-gap { --grid-gap: … }`, `.narrow-content { --width--responsive: … }`).
This is the right instinct, and the model above is that idiom taken all the way. What those
themes lack is a *single rule consuming the tokens*. Without it they still hand-write
margins on typography, on `.content > * + *`, and on groups — so the actual gap between any
two blocks is a margin-collapse outcome rather than a declared value, and `expeditions` ships
a `.content > * + * { margin-bottom }` owl applied to the wrong side, where the gap between
the first two children comes from somewhere else entirely than every other pair.

**theme.json `blockGap` alone, no CSS.** Gives one uniform gap everywhere. It is why you
cannot stop there: rhythm is contextual, and context is exactly what the two knobs add.

## Risks

Each with the test that settles it.

1. **`@property` dropped by the build, or unsupported by a target browser.** `--space-above`
   reverts to inheriting, and every descendant of a section gets a section-sized top margin.
   *Test:* `grep '@property'` the built CSS; in the editor iframe console, `getComputedStyle`
   a paragraph inside an `.alignfull` group and confirm `--space-above` is empty. Baseline since
   July 2024, so this is a build-pipeline risk more than a browser one. Escape hatch if ever
   needed: `* { --space-above: initial }` — `initial` is the guaranteed-invalid value, whereas
   an empty value is *not*, which is the subtlety that bit Tailwind in
   [tailwindlabs/tailwindcss#13949](https://github.com/tailwindlabs/tailwindcss/pull/13949).
2. **`settings.spacing.blockGap` set to `null`** by a later edit, a style variation, or a
   Site Editor save. All rhythm vanishes at once. *Test:* confirm the front-end
   `global-styles-inline-css` contains
   `:root :where(.is-layout-flow) > *{margin-block-start:var(--space-above`. This is the single
   point of failure for the whole architecture, and it lives in a JSON file the Site Editor
   can rewrite — check it before anything else when spacing disappears.
3. **Name collisions with plugin CSS.** `--space-above` and `--space-between` are deliberately
   unprefixed, for readability — so this risk is accepted, not mitigated. A plugin setting
   either name on a container it owns would change spacing inside it. The names are specific
   enough that it is unlikely, and `@property { inherits: false }` limits the blast radius of
   `--space-above` to one element. *Test:* `grep -rn 'space-above\|space-between' wp-content/plugins/`.
4. **`.wp-block-post-content` moving in a future Gutenberg**, taking the shared
   editor/front-end hook with it. Cite `editor.js:54407` in a code comment so the next person
   knows where to look.
5. ~~**`.site-content` layout type is already inconsistent.**~~ **Corrected — do not
   normalise this.** `404.html`, `page.html`, `page-no-title.html` and `blank.html` omit the
   `layout` attribute, so `.site-content` renders `is-layout-flow` with no
   `has-global-padding`, while `index`, `archive`, `search`, `single` and `post-with-sidebar`
   are `constrained`. That split is **load-bearing, not drift.**

   Making `main` constrained gives it `has-global-padding`, which makes `.entry-content` a
   *nested* `has-global-padding` and triggers:

   ```css
   .has-global-padding :where(:not(.alignfull.is-layout-flow) >
     .has-global-padding:not(.wp-block-block, .alignfull)) > .alignfull
     { margin-left: 0; margin-right: 0 }
   ```

   That cancels the negative margins on every `alignfull` child of post content, and full-bleed
   sections stop bleeding. Tried during implementation, broke alignfull site-wide, reverted.

   The working rule is: **exactly one element on the path to the content may carry
   `has-global-padding`.** Templates whose patterns are direct children of `main` want
   `constrained` there; templates that route content through `core/post-content` must leave
   `main` as flow and let post-content own the padding.

   Two templates route content through `core/post-content` while `main` is `constrained`, so
   `alignfull` inside a post body does not bleed. Neither is a simple bug:

   - **`post-with-sidebar.html` — correct as-is.** The post body sits in a 65% column. If
     `alignfull` kept its negative margins there it would escape the column and run under the
     sidebar. Core's nested reset is what prevents that. Leave it.
   - **`single.html` — a genuine trade-off.** A full-bleed section in a post body would be
     reasonable here, but `main`'s direct children include the pagination, comments and
     related-posts patterns, which rely on `main` being `constrained` to centre them. Making
     `main` flow to gain post-body bleed would un-constrain those unless each pattern grows its
     own constrained wrapper. Not addressed here.

## What it replaced

Roughly 330 lines of hand-written margin rules became about 40 lines of custom property
declarations plus four padding rules. Gone with them: both sets of
`.block-editor-block-list__layout.is-root-container` editor twins, the `(0,1,0)` specificity
invariant and its two documented exceptions, and the `> * + *` owls in `cards.css` and
`core-query.css`.

The editor twins are the part worth remembering. They existed to keep the editor matching the
front end, and they were the first thing to drift every time. Pointing `blockGap` at a
variable chain deleted that whole category of work — core emits the rule in both places, so
there is nothing left to keep in sync.

---

## References

Core source, verified against WordPress 7.0.3 as installed in this site:

- `wp-includes/class-wp-theme-json.php:1717` — `isset()` gates the whole mechanism
- `wp-includes/class-wp-theme-json.php:1794` — the emitted layout gap selectors
- `wp-includes/block-supports/layout.php:52` — `wp_get_layout_definitions()`, per-layout `spacingStyles`
- `wp-includes/kses.php:2846` — nested `var()` survives `safecss_filter_attr`
- `wp-includes/js/dist/editor.js:54407` — `wp-block-post-content` on the editor root container
- `wp-includes/blocks/post-content.php:73` — the same class on the front-end render
- `wp-includes/js/dist/block-editor.js:40939` — editor styles scoped at zero specificity
- `wp-includes/blocks/template-part/block.json` — no `layout` support

External:

- [CSS Properties and Values API L1](https://drafts.css-houdini.org/css-properties-values-api/) — `initial-value` optional for `syntax: "*"`
- [CSS Custom Properties L1](https://drafts.csswg.org/css-variables/) — guaranteed-invalid triggers `var()` fallback
- [The Stack, Every Layout](https://every-layout.dev/layouts/stack/)
- [Managing Flow and Rhythm with CSS Custom Properties, Andy Bell](https://24ways.org/2018/managing-flow-and-rhythm-with-css-custom-properties/)
