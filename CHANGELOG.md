# Changelog

All notable changes to `filament-postsimple` will be documented in this file.

## 1.2.0 - 2026-01-29

### Added
- Multi-language support (English and Dutch)
- Publishable translation files

### Fixed
- Fixed compatibility issue with `openUrlInNewTab()` method

## 1.1.0 - 2026-01-29

### Changed
- Simplified configuration: API key now configured via `.env` file (`POSTSIMPLE_API_KEY`)
- Removed dependency on `spatie/laravel-settings`
- Removed settings page (no longer needed)
- Removed migration requirement

## 1.0.0 - 2026-01-28

### Added
- Initial release
- `SendToPostSimpleAction` for use in resource view/edit pages
- `SendToPostSimpleTableAction` for use in resource tables
- Automatic title and URL detection
- Redirect to PostSimple after successful submission
- Error handling and user notifications
- Support for all Filament resources
- Comprehensive documentation
