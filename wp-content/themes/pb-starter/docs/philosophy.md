# Theme Development Philosophy

The reasoning behind the rules in [../CLAUDE.md](../CLAUDE.md). This is the "why" — read
it once to understand the approach; the rules file is the day-to-day reference.

## Overall Philosophy

Keep things simple, explainable, and maintainable. A theme that's straightforward to
understand and modify beats something "clever" that becomes a black box later. Every
component should be small enough to understand at a glance and easy to adjust without
breaking everything else.

When a client calls at 2 PM asking for a change (they always do), I want to find where
that code lives, what it does, and how to modify it safely — without untangling a web of
dependencies. Build themes like the next developer might be you on a bad day.

## Trust WordPress Core and FSE

Work with WordPress, not against it. WordPress has spent years developing the block
editor and FSE — use it as intended:

- Use core blocks whenever possible — don't build custom blocks when a core block works.
- Trust theme.json — it's powerful and well-designed.
- Follow WordPress conventions — hooks, filters, template hierarchy, coding standards.
- Leverage built-in features — block styles, patterns, template parts.

"Trust WordPress" doesn't mean "accept everything." Still: no frameworks on top of
WordPress, no over-complicated architectures, no bloat when simple works, procedural code
in themes.

When you find yourself writing elaborate PHP to override core block behavior, step back:
"Is there a simpler way using theme.json or CSS that works *with* WordPress?" Ask of any
filter: "Will this still make sense in 2 years when WordPress updates?" If uncertain,
you're probably fighting WordPress. Let WordPress handle what already works (e.g. skip
links) — only override when the default conflicts with the design system or hurts
accessibility.

## FSE Philosophy: Minimize Customization

We build themes for clients and teams, not products for the masses. The block editor
makes it *possible* to customize every detail — spacing, colors, typography per block,
per page. But just because you *can* doesn't mean you *should*.

**The goal is to minimize customization, not maximize it.** When clients edit, their
focus should be:

1. Content updates — writing, editing, updating information.
2. Page building with patterns — assembling pre-built patterns into new pages.

That's it. They shouldn't be fiddling with margins, padding, or colors block by block.

### CSS does the heavy lifting

CSS is incredibly powerful for contextual styling. When a block lands in the main
container, spacing should just *work* — no sliders. If a Group block sits at full width in
the main content area, CSS handles top/bottom margins, padding, spacing between children,
and responsive behavior automatically. Block settings are **overrides**, used only when
truly necessary. The default should be sensible and cover 90% of cases. (Concrete spacing
examples live in [../readme.md](../readme.md#contextual-spacing-philosophy).)

### Limit options, increase consistency

Remove as many block options as possible — hide unused blocks, disable granular controls,
remove off-system colors and hierarchy-breaking typography controls. Replace them with
patterns (common layouts), block styles (semantic variations), and section styles
(layout contexts). Editors choose "large gap," not "60px gap"; "hidden on mobile," not a
display property. Consistency by design.

But don't over-customize. WordPress has sensible defaults. If you're writing elaborate JS
to rebuild the editor, you've gone too far — enhance the editor, don't replace it.

## theme.json as Universal Defaults

theme.json is the design system. Everything visual is defined there: colors (semantic
names), typography (fluid values), a ratio-based spacing scale, layout widths, and custom
properties for reused values. Never hardcode a value in CSS that belongs in theme.json —
if you write the same color or spacing twice, it's a token. theme.json doesn't need every
possible style for every block, though; use CSS for specific and contextual styling.

## CSS Architecture Reasoning

Write CSS that makes intelligent contextual decisions — target blocks by **location**
(`.site-content > *` vs `.site-header > *`), **type** (`.alignfull` vs `.alignwide`),
**state** (`.has-background`), and **adjacency** (what's next to this block). This is what
lets editors never touch spacing sliders.

Keep selectors simple and shallow, use custom properties for repeated values, avoid
specificity wars and expensive operations (complex filters, heavy shadows). Separate
files so browsers cache compiled/minified CSS.

Give utility block styles a semantic, purpose-driven vocabulary rather than pixel
controls — they maintain consistency while still allowing flexibility when truly needed.

## PHP Reasoning: Procedural, Not OOP

Procedural code here because it's simpler to understand, easier to debug (you see exactly
what happens in what order), more maintainable (anyone can jump in), and WordPress-native
(theme functions work procedurally with hooks). Prefix everything to prevent conflicts.
Organize `/inc` by feature, not by technical type — `navigation.php`, `excerpt.php`,
`security.php`, not `filters.php` / `actions.php` / `functions.php`.

`template-tags.php` is for pure helpers only: same input → same output, no hooks, no
global-state changes, no side effects (no DB writes, no enqueueing). Anything with side
effects belongs in a feature file.

## What Success Looks Like

A theme succeeds when:

1. Anyone can understand it — clear structure, good comments, logical organization.
2. It works *with* WordPress — core features, conventions, update-safe.
3. Editors find it intuitive — limited options, good patterns, clear purpose.
4. Editors focus on content — not margins, colors, or spacing.
5. Patterns are robust — common layouts pre-built and easy to use.
6. CSS handles context — spacing and layout work automatically.
7. It's maintainable — change parts without breaking everything.
8. It's accessible — keyboard, screen readers, proper semantics.
9. It performs well — fast loading, efficient code, proper caching.
10. It's secure — escaped output, security headers, no vulnerabilities.
11. It works everywhere — desktop, tablet, phone.
12. The design system is enforced — theme.json and CSS control consistency.

## Things That Drive Me Crazy

- Fighting WordPress instead of working with it.
- Elaborate custom solutions when WordPress has a built-in feature.
- Over-engineering the block editor experience.
- JS frameworks where vanilla works fine.
- Hardcoded colors/spacing instead of theme.json values.
- Inline styles instead of external files.
- Classes everywhere instead of procedural PHP.
- All-or-nothing architectures that can't be changed.
- Clever code that's hard to understand.
- Cramped designs with no breathing room.
- Blocks in the post editor that belong only in the site editor.
- Patterns that don't use theme.json values.
- Exposing every block setting to editors.
- Requiring clients to adjust margins/padding manually for proper spacing.
- Themes that expect per-block, per-page customization instead of systematic design.

## Final Thought

Good theme development is like good writing — clear, purposeful, as simple as possible
while still doing the job. Every decision should have a reason better than "it's trendy."
A maintainable theme that works with WordPress beats a "perfect" custom solution every
time. When WordPress updates, your themes should benefit, not break.
