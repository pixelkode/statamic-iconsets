# Statamic Icon Sets

A Statamic fieldtype that allows selecting icons from multiple folders/sets with visual preview and grouping.

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

- PHP 8.1+
- Statamic 5.0+

## License

MIT License. See LICENSE.md for details.
