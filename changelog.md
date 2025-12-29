# RxAnte PHP App Bootstrap Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.2.1 - 2025-12-29
### Fixed
- Fixed a bug where the `registerEventSubscribers` boot method would attempt to "load" a callable as a filesystem path

## 1.2.0 - 2025-12-03
### Added
- Added methods `getBoolean` and `findBoolean` to `\RxAnte\AppBootstrap\Request\TypedArrayAttributes`

## 1.1.1 - 2025-11-26
### Added
- Added a `RuntimeConfig` class

## 1.1.0 - 2025-11-26
### Added
- The `buildContainer` method argument `register` during the boot chain can now receive a directory path or array of directory paths with callable classes to register container bindings (in addition to still being able to receive a callable)
- The `registerEventSubscribers` method argument `register` during the boot chain can now receive a directory path or array of directory paths with callable classes to register events (in addition to still being able to receive a callable)
- Added a new, custom ServerRequest—`\RxAnte\AppBootstrap\Request\ServerRequest`—which is supplied to the route callable as the first argument. This custom server request has four typed properties to aid in stronger typing: `$serverParams`, `$cookieParams`, `$queryParams`, and `$parsedBody`. These are instances of `\RxAnte\AppBootstrap\Request\TypedArrayAttributes`

## 1.0.1 - 2024-11-22
### Fixed
- Fixed an issue where json post body was not parsed and added to the request

## 1.0.0 - 2024-11-20
### Added
- Initial release
