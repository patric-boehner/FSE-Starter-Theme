/**
 * Theme Build Script
 *
 * Handles CSS, JS, SVG, and asset compilation.
 *
 * Usage:
 *   node build.js              Build all (development)
 *   node build.js --production Build all (minified)
 *   node build.js --watch      Build all, then rebuild on change
 *   node build.js css          Build CSS only
 *   node build.js js           Build JS only
 *   node build.js svg          Build SVG only
 *   node build.js assets       Copy fonts/images only
 */

const { bundle, browserslistToTargets, Features } = require('lightningcss');
const esbuild = require('esbuild');
const { optimize } = require('svgo');
const fs = require('fs');
const path = require('path');

const flags = process.argv.slice(2).filter(arg => arg.startsWith('--'));
const isProduction = flags.includes('--production');
const isWatch = flags.includes('--watch');
const task = process.argv.slice(2).find(arg => !arg.startsWith('--'));

// Debounce window for file events. Editors and asset tools often emit several
// writes per save; without this a single save triggers duplicate rebuilds.
const WATCH_DEBOUNCE_MS = 50;

// Browser targets from browserslist
const targets = browserslistToTargets(['last 2 versions', 'not dead']);

/**
 * Features always compiled away, regardless of browser support.
 *
 * Nesting: WordPress runs editor styles through postcss-prefix-selector to
 * scope them to .editor-styles-wrapper. Its selector tokenizer does not
 * understand the `&` nesting selector, and transformStyles discards the
 * ENTIRE stylesheet on any error (returning null and logging
 * "wp.blockEditor.transformStyles Failed to transform CSS." to the console).
 *
 * The browsers we target support nesting fine — this exists purely so authors
 * can keep writing nested CSS without silently killing every editor style.
 */
const include = Features.Nesting;

/**
 * Ensure directory exists
 */
function ensureDir(dir) {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
}

/**
 * Build CSS with Lightning CSS
 */
function buildCSS() {
    console.log('Building CSS...');

    // Main entry points
    const entries = ['frontend.css', 'editor.css'];

    ensureDir('build/css');

    entries.forEach(entry => {
        const inputPath = path.join('src/css', entry);
        const outputPath = path.join('build/css', entry);

        if (!fs.existsSync(inputPath)) {
            console.warn(`  Skipping ${entry} (not found)`);
            return;
        }

        const result = bundle({
            filename: inputPath,
            minify: isProduction,
            targets,
            include,
            drafts: {
                customMedia: true
            }
        });

        fs.writeFileSync(outputPath, result.code);
        console.log(`  ${entry}`);
    });

    // Block CSS files (each compiled separately)
    const blocksDir = 'src/css/blocks';

    if (fs.existsSync(blocksDir)) {
        ensureDir('build/css/blocks');

        const blockFiles = fs.readdirSync(blocksDir).filter(f => f.endsWith('.css'));

        blockFiles.forEach(file => {
            const inputPath = path.join(blocksDir, file);
            const outputPath = path.join('build/css/blocks', file);

            const result = bundle({
                filename: inputPath,
                minify: isProduction,
                targets,
                include,
                drafts: {
                    customMedia: true
                }
            });

            fs.writeFileSync(outputPath, result.code);
            console.log(`  blocks/${file}`);
        });
    }

    console.log('CSS done.\n');
}

/**
 * Build JS with esbuild
 */
function buildJS() {
    console.log('Building JS...');

    const jsDir = 'src/js';

    if (!fs.existsSync(jsDir)) {
        console.log('  No JS files found.\n');
        return;
    }

    ensureDir('build/js');

    const jsFiles = fs.readdirSync(jsDir).filter(f => f.endsWith('.js'));

    if (jsFiles.length === 0) {
        console.log('  No JS files found.\n');
        return;
    }

    esbuild.buildSync({
        entryPoints: jsFiles.map(f => path.join(jsDir, f)),
        outdir: 'build/js',
        bundle: true,
        minify: isProduction,
        sourcemap: !isProduction,
        target: ['es2020']
    });

    jsFiles.forEach(f => console.log(`  ${f}`));
    console.log('JS done.\n');
}

/**
 * Recursively find all files with extension in a directory
 */
function findFilesRecursive(dir, ext) {
    const files = [];

    if (!fs.existsSync(dir)) {
        return files;
    }

    const entries = fs.readdirSync(dir, { withFileTypes: true });

    entries.forEach(entry => {
        const fullPath = path.join(dir, entry.name);

        if (entry.isDirectory()) {
            files.push(...findFilesRecursive(fullPath, ext));
        } else if (entry.name.endsWith(ext)) {
            files.push(fullPath);
        }
    });

    return files;
}

/**
 * Optimize SVGs with SVGO
 */
function buildSVG() {
    console.log('Optimizing SVGs...');

    const svgDir = 'src/svg';

    if (!fs.existsSync(svgDir)) {
        console.log('  No SVG directory found.\n');
        return;
    }

    const svgFiles = findFilesRecursive(svgDir, '.svg');

    if (svgFiles.length === 0) {
        console.log('  No SVG files found.\n');
        return;
    }

    svgFiles.forEach(inputPath => {
        // Preserve directory structure: src/svg/decorative/icon.svg -> build/svg/decorative/icon.svg
        const relativePath = path.relative(svgDir, inputPath);
        const outputPath = path.join('build/svg', relativePath);

        // Ensure output directory exists
        ensureDir(path.dirname(outputPath));

        const input = fs.readFileSync(inputPath, 'utf8');

        const result = optimize(input, {
            path: inputPath,
            multipass: true,
            plugins: [
                'preset-default',
                'removeDimensions',
                {
                    name: 'removeAttrs',
                    params: {
                        attrs: ['data-name']
                    }
                }
            ]
        });

        fs.writeFileSync(outputPath, result.data);
        console.log(`  ${relativePath}`);
    });

    console.log('SVGs done.\n');
}

/**
 * Copy static assets (fonts, images)
 */
function buildAssets() {
    console.log('Copying assets...');

    const assetDirs = ['fonts', 'images'];

    assetDirs.forEach(dir => {
        const srcDir = path.join('src', dir);
        const destDir = path.join('build', dir);

        if (!fs.existsSync(srcDir)) {
            return;
        }

        copyDirRecursive(srcDir, destDir);
        console.log(`  ${dir}/`);
    });

    console.log('Assets done.\n');
}

/**
 * Recursively copy a directory
 */
function copyDirRecursive(src, dest) {
    ensureDir(dest);

    const entries = fs.readdirSync(src, { withFileTypes: true });

    entries.forEach(entry => {
        const srcPath = path.join(src, entry.name);
        const destPath = path.join(dest, entry.name);

        if (entry.isDirectory()) {
            copyDirRecursive(srcPath, destPath);
        } else {
            fs.copyFileSync(srcPath, destPath);
        }
    });
}

/**
 * Task table
 *
 * `dirs` and `ext` describe what each task watches, so build and watch stay
 * driven by the same definitions.
 */
const TASKS = [
    { name: 'css', run: buildCSS, dirs: ['src/css'], ext: '.css' },
    { name: 'js', run: buildJS, dirs: ['src/js'], ext: '.js' },
    { name: 'svg', run: buildSVG, dirs: ['src/svg'], ext: '.svg' },
    { name: 'assets', run: buildAssets, dirs: ['src/fonts', 'src/images'] }
];

/**
 * Run a task, converting a thrown error into a logged failure
 *
 * Used in watch mode only: a CSS syntax error should print and leave the
 * watcher running, not kill the dev process. One-shot builds let the error
 * propagate so `npm run build` exits non-zero.
 */
function runSafely(taskDef) {
    try {
        taskDef.run();
    } catch (error) {
        console.error(`\n${taskDef.name} build failed:`);
        console.error(`  ${error.message}\n`);
    }
}

/**
 * Watch source directories and rebuild the affected task
 *
 * Uses Node's built-in recursive fs.watch (FSEvents on macOS) rather than a
 * watcher dependency.
 */
function watch(taskDefs) {
    console.log('Watching for changes (Ctrl-C to stop)...\n');

    taskDefs.forEach(taskDef => {
        let timer = null;

        const queueRebuild = () => {
            clearTimeout(timer);
            timer = setTimeout(() => runSafely(taskDef), WATCH_DEBOUNCE_MS);
        };

        taskDef.dirs.forEach(dir => {
            if (!fs.existsSync(dir)) {
                return;
            }

            fs.watch(dir, { recursive: true }, (event, filename) => {
                // Ignore unrelated files (editor swap files, .DS_Store, etc.)
                if (taskDef.ext && filename && !filename.endsWith(taskDef.ext)) {
                    return;
                }

                queueRebuild();
            });

            console.log(`  watching ${dir}`);
        });
    });

    console.log('');
}

/**
 * Run build tasks
 */
function build() {
    let selected = TASKS;

    if (task) {
        selected = TASKS.filter(taskDef => taskDef.name === task);

        if (selected.length === 0) {
            console.error(`Unknown task "${task}". Expected: ${TASKS.map(t => t.name).join(', ')}`);
            process.exit(1);
        }
    }

    console.log(`\nBuilding theme (${isProduction ? 'production' : 'development'})...\n`);

    selected.forEach(taskDef => isWatch ? runSafely(taskDef) : taskDef.run());

    console.log('Build complete.\n');

    if (isWatch) {
        watch(selected);
    }
}

build();
