# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

- `[Unreleased]` for upcoming features.
- `Added` for new features.
- `Changed` for changes in existing functionality.
- `Deprecated` for soon-to-be removed features.
- `Removed` for now removed features.
- `Fixed` for any bug fixes.
- `Security` in case of vulnerabilities

## [1.5.1] - Upcoming

### Added

- Added `ACTION_*` constants in `VeilData`
- Added `sanitize` method to `VeilData`

### Changed

- Updated `redirectTo` method to trim trailing slashes which reduces number of potential redirects

## [1.5.0] - 2025.03.07

### Added

- Added `getJsonBody` method to `WebAppController`

### Changed

- Changed array keys of Veil template data in `setWebAppData`

## [1.4.0] - 2025.01.10

### Added

- Added public `Translate` class to `WebAppService`

## [1.3.0] - 2025.01.19

### Added

- Added `WebAppService` config array to Veil data array
- Added locale functionality
- Added `@route` and `@say` template tags

### Changed

- Moved `respond` method from `WebAppService` to `WebAppController`

## [1.2.0] - 2025.01.09

### Added

- Added `VeilData` utility class

## [1.1.2] - 2025.01.08

### Fixed

- Fixed bug in `respond` method

## [1.1.1] - 2024.12.23

### Added

- Tested up to PHP v8.4

### Changed

- Updated all dependencies

## [1.1.0] - 2024.09.10

### Changed

- Updated Bones to v5.1

## [1.0.0] - 2024.05.31

### Added

- Initial release.