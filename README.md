# Statamic Icon Sets

> ⚠️ **Deprecated — no longer maintained.**
> This addon has been *sherlocked* by Statamic 6: the native `icon` fieldtype combined with
> `Icon::register()` now covers everything this package did (custom SVG sets, visual picker,
> raw-SVG output via `{{ icon }}`) — with a better UI. New projects should use the native
> fieldtype. See **[Migration](#migration-to-the-native-icon-fieldtype)** below.

A Statamic fieldtype that allows selecting icons from multiple folders/sets with visual preview and grouping.

## Migration to the native `icon` fieldtype

Statamic 6 replaced the old `directory:` option with registered icon **sets**.

1. Register your set in `app/Providers/AppServiceProvider.php` (`boot()`):

   ```php
   use Statamic\Facades\Icon;

   Icon::register('myset', base_path('resources/svg/icons/<folder>'));
   ```

2. Update the blueprint field — replace:

   ```yaml
   icon:
     type: iconset
     folders:
       - <folder>
   ```

   with:

   ```yaml
   icon:
     type: icon
     set: myset
   ```

3. **Multiple sets in one field** was this addon's only feature core doesn't replicate.
   Use one native field per set, or register a single combined set directory.

4. **Stored values differ.** This addon stored `folder/filename`; the native `icon`
   fieldtype stores its own value. Existing entries must be re-selected (or migrated with a
   one-off script). Template output via `{{ icon }}` (raw SVG) is unchanged.

## Features

- Select icons from multiple folders/sets
- Visual icon preview in dropdown
- Folder-based grouping for better organization
- Forward-compatible with Statamic 6's icon set pattern
- Searchable and filterable

## Installation

```bash
composer require pixelkode/statamic-iconsets
php artisan vendor:publish --tag=statamic-iconsets
```

The publish command copies the compiled JavaScript and CSS assets to your `public/vendor/statamic-iconsets/` directory.

## Usage

Add the fieldtype to your blueprint:

```yaml
icon:
  type: iconset
  display: Icon
  instructions: Select an icon from available sets
  directory: resources/svg/icons  # Optional, defaults to resources/svg/icons
  folders:
    - statamic
    - streamline
  default: statamic/add
```

## Configuration

- **directory**: Base directory containing icon folders (default: `resources/svg/icons`)
- **folders**: Array of folder names to include
- **default**: Default icon in `folder/filename` format

## Icon Directory Structure

Organize your SVG icons in folders within your configured directory:

```
resources/svg/icons/
├── statamic/
│   ├── add.svg
│   ├── edit.svg
│   └── delete.svg
├── streamline/
│   ├── arrow-right.svg
│   ├── check.svg
│   └── user.svg
└── custom/
    ├── logo.svg
    └── brand-icon.svg
```

Each folder becomes a group in the icon picker dropdown.

## Template Usage

The fieldtype stores the icon value as `folder/filename` (e.g., `streamline/check`). In your Antlers templates, use the `{{ icon }}` tag to render the raw SVG:

```antlers
{{ icon }}
```

This outputs the raw SVG markup, allowing you to style it with CSS:

```antlers
<span class="w-6 h-6 text-blue-500">
    {{ icon }}
</span>
```

## Development

For contributors working on this package:

```bash
# Install dependencies
npm install

# Build assets for development
npm run dev

# Build assets for production
npm run build
```

After making changes to the Vue components, run `npm run build` to compile the assets.

## Requirements

- PHP 8.3+
- Statamic 6.0+

## License

MIT License. See LICENSE.md for details.
