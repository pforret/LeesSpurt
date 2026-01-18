# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

LeesTrainer is a reading skills trainer for 5-year-olds built with Laravel 12 + Vue 3 + Inertia.js. Features language selection (English/Dutch) with browser persistence and word thesaurus files split by word length.

## Commands

### Development
```bash
composer dev          # Start all dev processes (server, queue, logs, Vite)
npm run dev           # Vite dev server only
```

### Testing
```bash
composer test         # Full test suite (clear config + lint + Pest)
./vendor/bin/pest     # Run Pest tests directly
./vendor/bin/pest --filter=TestName  # Single test
```

### Code Style
```bash
composer lint         # Fix PHP style (Pint)
composer test:lint    # Check PHP style without fixing
npm run lint          # Fix JS/TS/Vue (ESLint)
npm run format        # Format with Prettier
npm run format:check  # Check formatting
```

### Build
```bash
npm run build         # Production build
composer setup        # Initial project setup
```

## Architecture

**Stack:** Laravel 12 (PHP 8.2+) + Vue 3 + Inertia.js + Tailwind CSS + Vite

**Key Paths:**
- `app/` - PHP: Models, Http/Controllers, Actions
- `resources/js/pages/` - Vue page components (routed via Inertia)
- `resources/js/components/` - Reusable Vue components (shadcn-vue based)
- `routes/web.php` - Main route definitions
- `database/migrations/` - Schema definitions

**Patterns:**
- Inertia.js SPA: Controllers return `Inertia::render('PageName', $props)`
- TypeScript with path alias: `@/*` → `resources/js/*`
- Pest for testing (not PHPUnit syntax)
- SQLite database (in-memory for tests)

## Code Style

- PHP: Laravel preset via Pint (`pint.json`)
- JS/TS/Vue: ESLint + Prettier, 4-space tabs, single quotes
- Tailwind class ordering enforced by prettier-plugin-tailwindcss
