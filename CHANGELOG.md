# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 2026-02-24

### Added

- Statamic 6 support with Vue 3 Control Panel

### Changed

- Transfer repository to Pixelkode organization, package name and namespace update
- Migrated fieldtype component from Vue 2 Options API to Vue 3 `<script setup>`
- Replaced `v-select` (removed in Statamic 6) with a native `<select>` element
- Replaced `Statamic.$components.register` bare call with `Statamic.booting()` wrapper
- Minimum PHP version raised to 8.3
- Minimum Vite version raised to 6.0

### Removed

- Dropped Statamic 5 support
- Dropped PHP 8.1 and 8.2 support

## [1.0.0] - 2025-01-20

### Added

- Initial release of the Icon Sets fieldtype for Statamic
- Select icons from multiple folders/sets with visual preview
- Folder-based grouping for better organization
- Searchable and filterable icon dropdown
- Configurable base directory for icon folders
- Support for multiple icon folders in a single field
- Default icon configuration option
- Raw SVG output in Antlers templates via `{{ icon }}` tag
- Forward-compatible design with Statamic 6's icon set pattern

[Unreleased]: https://github.com/pixelkode/statamic-iconsets/compare/v2.0.0...HEAD
[2.0.0]: https://github.com/pixelkode/statamic-iconsets/compare/v1.0.0...v2.0.0
[1.0.0]: https://github.com/pixelkode/statamic-iconsets/releases/tag/v1.0.0
