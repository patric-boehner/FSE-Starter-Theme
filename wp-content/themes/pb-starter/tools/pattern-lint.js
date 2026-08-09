#!/usr/bin/env node
/**
 * Pattern linter.
 *
 * Checks patterns in /patterns against the rules in docs/pattern-library.md:
 * header validity, preset slugs that actually exist in theme.json, the
 * class/attribute sync contract, and the strip list.
 *
 * Usage:
 *   node tools/pattern-lint.js                 # all patterns
 *   node tools/pattern-lint.js patterns/library # a subdirectory or file
 *
 * Exits non-zero when any error is found.
 */

const fs = require('fs');
const path = require('path');

const THEME = path.resolve(__dirname, '..');

/* -- Rules ------------------------------------------------------------- */

// Blocks unregistered in inc/setup/block-editor.php. One of these in a pattern
// renders as an "unsupported block" in the editor.
const FORBIDDEN_BLOCKS = [
	'spacer', 'media-text', 'table', 'html', 'code', 'pullquote',
	'preformatted', 'verse', 'audio', 'video', 'file',
];

// Everything the theme actually defines. See docs/pattern-library.md §6.
const ALLOWED_STYLES = new Set([
	'primary', 'secondary',                                  // core/button
	'no-bullets', 'checkmarks', 'arrows',                    // core/list
	'button',                                                // core/navigation-link
	'section-light', 'section-dark',                         // styles/sections
	'card',                                                  // theme components
	'large-gap', 'hidden-mobile', 'columns-reverse',         // utilities
	// Core ships the CSS for these. inc/setup/block-editor.php hides them from
	// the style picker, but they still render, so a pattern may use them.
	'logos-only', 'pill-shape', 'rounded',
]);

// Attributes whose theme.json setting is disabled, so they render nothing.
const FORBIDDEN_ATTRS = [
	['"padding"', 'padding — spacing is contextual, strip it'],
	['"margin"', 'margin — spacing is contextual, strip it'],
	['"shadow"', 'shadow — no presets defined in theme.json'],
	['"fontWeight"', 'fontWeight — disabled in theme.json, use <strong>'],
	['"fontStyle"', 'fontStyle — disabled in theme.json'],
	['"lineHeight"', 'lineHeight — disabled in theme.json'],
	['"letterSpacing"', 'letterSpacing — disabled in theme.json'],
	['"textTransform"', 'textTransform — disabled in theme.json'],
	['"textDecoration"', 'textDecoration — disabled in theme.json'],
];

const KNOWN_CATEGORIES = new Set([
	'banner', 'call-to-action', 'services', 'testimonials', 'contact', 'team',
	'about', 'text', 'columns', 'gallery', 'posts', 'header', 'footer',
	'featured', 'media', 'query', 'audio', 'video',
	'pb-starter/features', 'pb-starter/pricing',
]);

// Below this viewport width a pattern is a component (a card dropped into an
// existing layout), not a full-width section. See docs/pattern-library.md §2.
const COMPONENT_MAX_VIEWPORT = 1000;

/* -- theme.json presets ------------------------------------------------- */

function loadPresets() {
	const tj = JSON.parse(fs.readFileSync(path.join(THEME, 'theme.json'), 'utf8'));
	const s = tj.settings;
	const slugs = (arr) => new Set((arr || []).map((x) => x.slug));
	return {
		color: slugs(s.color && s.color.palette),
		spacing: slugs(s.spacing && s.spacing.spacingSizes),
		'font-size': slugs(s.typography && s.typography.fontSizes),
		'font-family': slugs(s.typography && s.typography.fontFamilies),
		gradient: slugs(s.color && s.color.gradients),
		duotone: slugs(s.color && s.color.duotone),
	};
}

/* -- Parsing ------------------------------------------------------------ */

// Opening block comments that carry attributes, plus the markup that follows.
const BLOCK_RE = /<!--\s+wp:([a-z0-9/-]+)\s+(\{.*?\})\s+(\/)?-->/g;

function parseHeader(src) {
	const header = {};
	const block = src.match(/\/\*\*([\s\S]*?)\*\//);
	if (!block) return header;
	for (const line of block[1].split('\n')) {
		const m = line.match(/^\s*\*\s*([A-Za-z ]+):\s*(.*)$/);
		if (m) header[m[1].trim()] = m[2].trim();
	}
	return header;
}

/* -- Checks ------------------------------------------------------------- */

function lintFile(file, presets) {
	const src = fs.readFileSync(file, 'utf8');
	const rel = path.relative(THEME, file);
	const errors = [];
	const lineOf = (idx) => src.slice(0, idx).split('\n').length;
	const err = (idx, msg) => errors.push({ line: lineOf(idx), msg });

	const header = parseHeader(src);
	const isLibrary = rel.includes(path.join('patterns', 'library'));
	const viewport = parseInt(header['Viewport Width'] || '0', 10);
	const isComponent = viewport > 0 && viewport < COMPONENT_MAX_VIEWPORT;
	const inserter = (header.Inserter || '').toLowerCase() !== 'false';

	/* Header */
	for (const field of ['Title', 'Slug']) {
		if (!header[field]) err(0, `header is missing "${field}"`);
	}
	// Hidden template partials never reach the inserter, so a description
	// would have nowhere to show.
	if (inserter && !header.Description) err(0, 'inserter pattern is missing "Description"');
	if (header.Slug && !header.Slug.startsWith('pb-starter/')) {
		err(0, `slug "${header.Slug}" must start with "pb-starter/"`);
	}
	if (isLibrary && header.Slug && !header.Slug.startsWith('pb-starter/lib-')) {
		err(0, `library slug "${header.Slug}" must start with "pb-starter/lib-"`);
	}
	if (inserter && !header.Categories) {
		err(0, 'inserter pattern has no Categories — it lands uncategorized');
	}
	for (const cat of (header.Categories || '').split(',').map((c) => c.trim()).filter(Boolean)) {
		if (!KNOWN_CATEGORIES.has(cat)) err(0, `unknown pattern category "${cat}"`);
	}
	// metadata.patternName must agree with the header, or the editor mislabels it.
	const pn = src.match(/"patternName":"([^"]+)"/);
	if (pn && header.Slug && pn[1] !== header.Slug) {
		err(pn.index, `metadata.patternName "${pn[1]}" does not match header slug "${header.Slug}"`);
	}

	/* Forbidden blocks */
	for (const block of FORBIDDEN_BLOCKS) {
		const re = new RegExp(`<!--\\s+wp:${block}[\\s{/]`, 'g');
		let m;
		while ((m = re.exec(src))) {
			err(m.index, `core/${block} is unregistered in this theme — renders as an unsupported block`);
		}
	}

	/* Forbidden attributes */
	for (const [needle, why] of FORBIDDEN_ATTRS) {
		let i = src.indexOf(needle);
		while (i !== -1) {
			err(i, `remove ${why}`);
			i = src.indexOf(needle, i + 1);
		}
	}
	// border.* is disabled, but borderColor as a *class* is caught below too.
	if (/"border":\s*\{/.test(src)) {
		err(src.search(/"border":\s*\{/), 'remove border — disabled in theme.json, use is-style-card');
	}

	/* Media hygiene */
	for (const [re, msg] of [
		[/https?:\/\/[^"'\s]+/g, 'absolute URL — use get_theme_file_uri()'],
		[/wp-image-\d+/g, 'stale attachment class — remove'],
		[/"id":\d+/g, 'stale attachment id — remove'],
		[/#[0-9a-fA-F]{3,8}\b/g, 'raw hex colour — use a palette slug'],
	]) {
		let m;
		while ((m = re.exec(src))) {
			// Licence/attribution URLs in the docblock are fine.
			if (m.index < (src.indexOf('?>') === -1 ? 0 : src.indexOf('?>'))) continue;
			err(m.index, `${msg}: ${m[0].slice(0, 60)}`);
		}
	}

	/* is-style-* allowlist */
	{
		const re = /is-style-([a-z0-9-]+)/g;
		let m;
		const seen = new Set();
		while ((m = re.exec(src))) {
			if (seen.has(m[1])) continue;
			seen.add(m[1]);
			if (!ALLOWED_STYLES.has(m[1])) {
				err(m.index, `unknown block style "is-style-${m[1]}" — not defined in this theme`);
			}
		}
	}

	/* Preset slugs referenced as var:preset|type|slug */
	{
		const re = /var:preset\|([a-z-]+)\|([a-z0-9-]+)/g;
		let m;
		while ((m = re.exec(src))) {
			const [, type, slug] = m;
			const set = presets[type];
			if (set && !set.has(slug)) {
				err(m.index, `var:preset|${type}|${slug} — "${slug}" is not defined in theme.json`);
			}
		}
	}

	/* alignfull discipline */
	{
		const count = (src.match(/"align":"full"/g) || []).length;
		if (isComponent) {
			if (count > 0) err(0, 'component pattern should not be alignfull (see docs §2)');
		} else if (inserter) {
			if (count === 0) err(0, 'section pattern has no align:full wrapper — spacing will collapse');
			if (count > 1) err(0, `${count} align:full blocks — nested full-width doubles the vertical padding`);
		}
	}

	/* Class / attribute sync */
	{
		let m;
		BLOCK_RE.lastIndex = 0;
		while ((m = BLOCK_RE.exec(src))) {
			const [full, name, json, selfClosing] = m;
			if (selfClosing) continue; // no serialized tag follows

			let attrs;
			try {
				attrs = JSON.parse(json);
			} catch {
				err(m.index, 'block attributes are not valid JSON');
				continue;
			}

			// The opening tag that follows this comment. The name pattern must
			// allow digits or <h2> never matches and the check silently
			// validates against the *next* block's markup.
			const after = src.slice(m.index + full.length);
			const tag = after.match(/<([a-z][a-z0-9]*)[\s>][^>]*>?/);
			if (!tag) continue;

			// Dynamic blocks (core/navigation and friends) serialize no markup
			// of their own — an inner block comment arrives before any tag.
			// Anchoring to the next tag would compare against a later block.
			const nextBlock = after.search(/<!--\s+wp:/);
			if (nextBlock !== -1 && nextBlock < tag.index) continue;
			const cls = (tag[0].match(/class="([^"]*)"/) || [, ''])[1].split(/\s+/).filter(Boolean);
			const at = m.index + full.length + tag.index;

			const pairs = [
				['textColor', (v) => `has-${v}-color`, 'has-text-color', 'color'],
				['backgroundColor', (v) => `has-${v}-background-color`, 'has-background', 'color'],
				['fontSize', (v) => `has-${v}-font-size`, null, 'font-size'],
				['fontFamily', (v) => `has-${v}-font-family`, null, 'font-family'],
			];

			for (const [attr, toClass, flag, presetType] of pairs) {
				const v = attrs[attr];
				if (v) {
					if (!presets[presetType].has(v)) {
						err(at, `${attr}:"${v}" is not a ${presetType} slug in theme.json`);
					}
					if (!cls.includes(toClass(v))) {
						err(at, `${attr}:"${v}" but class "${toClass(v)}" is missing from the markup`);
					}
					if (flag && !cls.includes(flag)) {
						err(at, `${attr}:"${v}" but class "${flag}" is missing from the markup`);
					}
				}
			}

			// A block style in the markup must be backed by the className
			// attribute, or the editor drops it on the next save.
			const declared = (attrs.className || '').split(/\s+/).filter(Boolean);
			for (const c of cls) {
				if (c.startsWith('is-style-') && !declared.includes(c)) {
					err(at, `class "${c}" is not in the block's className attribute`);
				}
			}

			// The reverse direction: a has-* class with no attribute behind it.
			// "icon" is core/social-links' own iconColor/iconBackgroundColor,
			// and text/link/background are flags rather than slugs.
			const NOT_SLUGS = new Set(['text', 'link', 'icon', 'background']);
			for (const c of cls) {
				let mm;
				if ((mm = c.match(/^has-(.+)-background-color$/))) {
					if (NOT_SLUGS.has(mm[1])) continue;
					if (attrs.backgroundColor !== mm[1]) {
						err(at, `class "${c}" has no matching backgroundColor attribute`);
					}
				} else if ((mm = c.match(/^has-(.+)-font-size$/))) {
					if (attrs.fontSize !== mm[1]) {
						err(at, `class "${c}" has no matching fontSize attribute`);
					}
				} else if ((mm = c.match(/^has-(.+)-color$/))) {
					if (NOT_SLUGS.has(mm[1])) continue;
					// core/separator skips the standard colour serialization and
					// emits has-text-color + has-{slug}-color from backgroundColor.
					const viaBackground = name === 'separator' && attrs.backgroundColor === mm[1];
					if (attrs.textColor !== mm[1] && !viaBackground) {
						err(at, `class "${c}" has no matching textColor attribute`);
					}
				}
			}
		}
	}

	/* i18n: visible text must be translated */
	{
		const offset = src.indexOf('?>') + 2;
		// Blank out PHP spans so an echoed value can't read as a text node.
		// Same-length replacement keeps offsets, and so line numbers, honest.
		const body = src
			.slice(offset)
			.replace(/<\?php[\s\S]*?\?>/g, (s) => s.replace(/[^\n]/g, ' '));
		const re = />([^<>]+)</g;
		let m;
		while ((m = re.exec(body))) {
			const text = m[1].trim();
			if (!text || /^&\w+;$/.test(text)) continue;
			err(offset + m.index, `untranslated text "${text.slice(0, 40)}" — wrap in esc_html_e()`);
		}
	}

	return errors;
}

/* -- Runner ------------------------------------------------------------- */

function collect(target) {
	const stat = fs.statSync(target);
	if (stat.isFile()) return [target];
	return fs.readdirSync(target, { withFileTypes: true }).flatMap((e) => {
		const p = path.join(target, e.name);
		if (e.isDirectory()) return collect(p);
		return e.name.endsWith('.php') ? [p] : [];
	});
}

const target = path.resolve(THEME, process.argv[2] || 'patterns');
const presets = loadPresets();
const files = collect(target).sort();

let total = 0;
for (const file of files) {
	const errors = lintFile(file, presets);
	if (!errors.length) continue;
	total += errors.length;
	console.log(`\n${path.relative(THEME, file)}`);
	for (const e of errors) console.log(`  ${e.line}: ${e.msg}`);
}

console.log(
	total
		? `\n${total} problem${total === 1 ? '' : 's'} in ${files.length} pattern${files.length === 1 ? '' : 's'}.`
		: `\nClean — ${files.length} pattern${files.length === 1 ? '' : 's'} checked.`
);
process.exit(total ? 1 : 0);
