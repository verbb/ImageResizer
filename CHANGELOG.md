# Changelog

## 4.0.1 - 2024-07-24

### Added
- Add “Manage Resize for All Volumes” plugin setting and re-organise Resize settings template to better differentiate between all-volume and volume-specific settings

### Changed
- Update English translations.

### Fixed
- Fix images not being resized when volume-specific settings weren’t set

## 4.0.0 - 2024-05-13

### Changed
- Now requires PHP `8.2.0+`.
- Now requires Craft `5.0.0+`.

## 3.0.12 - 2024-07-24

### Added
- Add “Manage Resize for All Volumes” plugin setting and re-organise Resize settings template to better differentiate between all-volume and volume-specific settings

### Changed
- Update English translations.

### Fixed
- Fix images not being resized when volume-specific settings weren’t set

## 3.0.11 - 2024-01-30

### Fixed
- Fix bulk resize not working for non-local filesystems.
- Fix an error when uploading an asset in some instances.
- Fix bulk resize volume name formatting.
- Fix asset volume-specific settings not being properly set.

## 3.0.10 - 2024-01-23

### Fixed
- Fix an error when downloading temporary files for remote volumes when images exist within a subfolder.

## 3.0.9 - 2023-12-14

### Fixed
- Fix file size estimate checks to rely on real file sizes.

## 3.0.8 - 2023-10-10

### Changed
- Improve performance when resizing images and larger-than checks.

### Fixed
- Fix an error when trying to process remote-filesystem assets.
- Fix filesize estimation to detect resulting larger images not being correct.

## 3.0.7 - 2023-08-17

### Fixed
- Fix an issue when using with Servd Assets.

## 3.0.6 - 2023-05-08

### Fixed
- Fix an error when uploading assets.

## 3.0.5 - 2023-05-02

### Changed
- Only admins are now allowed to access plugin settings.

### Fixed
- Fix an error when fetching asset source settings.

## 3.0.4 - 2022-11-08

### Fixed
- Fix an error introduced in 3.0.3.

## 3.0.3 - 2022-10-29

### Fixed
- Fix an error when asset-indexing resized images.

## 3.0.2 - 2022-09-16

### Fixed
- Fix bulk resize actions not working correctly.

## 3.0.1 - 2022-07-15

### Added
- Add translations.

### Changed
- Replace deprecated `Craft.postActionRequest()` for JS.

### Fixed
- Fix an error with `ImageHelper` class. (thanks @nicolaskrebs).

## 3.0.0 - 2022-07-10

### Added
- Add checks for registering events for performance.

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.
- Rename service classes.
- Rename base plugin methods.

## 2.2.2 - 2023-10-10

### Fixed
- Fix an error when trying to process remote-filesystem assets.
- Fix filesize estimation to detect resulting larger images not being correct.

## 2.2.1 - 2023-08-17

### Fixed
- Fix an issue when using with Servd Assets.

## 2.2.0 - 2022-12-06

### Changed
- Improve performance when resizing images and larger-than checks.

## 2.1.1 - 2022-04-04

### Fixed
- Fix images not being rotated correctly, according to the `rotateImagesOnUploadByExifData` Craft config setting.

## 2.1.0 - 2021-02-19

### Changed
- Now requires Craft 3.6+.

### Fixed
- Fix `createDir` deprecation message for asset volumes in Craft 3.6+.

## 2.0.10 - 2020-07-14

### Fixed
- Move plugin settings to class, instead of composer.json. May help with plugin settings not appearing.

## 2.0.9 - 2020-05-22

### Fixed
- Fix stored XSS in Bulk Resize action. Thanks Paweł Hałdrzyński (Limpid Security).
- Fix CSRF issues with log-clear controller action. Thanks Paweł Hałdrzyński (Limpid Security).

## 2.0.8 - 2020-04-16

### Fixed
- Fix logging error `Call to undefined method setFileLogging()`.

## 2.0.7 - 2020-04-15

### Added
- Craft 3.4 compatibility.

### Changed
- File logging now checks if the overall Craft app uses file logging.
- Log files now only include `GET` and `POST` additional variables.
- Add back image-cleaning of EXIF data.

## 2.0.6 - 2019-09-18

### Fixed
- Fix incorrect action URL for clear-tasks.
- Remove forced asset validation, which skips image processing.

## 2.0.5 - 2019-06-04

### Added
- Add override notice for settings fields.

### Fixed
- Fix deprecation error for `removeFile()`.
- Prevent images being processed twice on upload when a conflicting asset was found.

## 2.0.4 - 2018-10-24

### Fixed
- Add asset indexing when creating files in `originals`. This will make the folder actually appear in Assets.
- Fix processing image multiple times on remote volumes.

## 2.0.3 - 2018-05-08

### Fixed
- Fix PHP 7.2 `count()` issue
- Fix handling when using dynamic paths in asset field

## 2.0.2 - 2018-02-25

### Changed
- Set minimum requirement to `^3.0.0-RC11`

### Fixed
- Fix resize event occurring on asset-save

## 2.0.1 - 2018-02-12

### Fixed
- Fix plugin icon in some circumstances

## 2.0.0 - 2018-02-10

### Added
- Craft 3 initial release.

## 1.0.1 - 2017-10-17

### Added
- Verbb marketing (new plugin icon, readme, etc).

### Fixed
- Ensure custom order for crop options is respected (and automatically selected).
- Fix issue for remote images not correctly reading image dimensions (causing incorrect cropping).
- Use ImagesLoaded.js for better UI feedback when dealing with large or remote images.
- Fix issue when clearing logs.

## 1.0.0 - 2017-03-25

### Added
- Added brand new logging system showing success/skips/error. Also shows summary after bulk-resizing images (in the modal window).
- Added non-destructive options for resizing and cropping. Originals will be saved in an `originals` folder relative to your asset.
- Added a `Clear pending tasks` button to Settings > Other.

### Changed
- Cropping and resizing now preserves EXIF and other metadata for images.
- Resizing tasks refactored, which makes better sense rather than re-using the `assets.onBeforeUploadAsset` event.
- Refactor plugin templates for common layout.

### Fixed
- Properly implement translations throughout the plugin.
- Fixes for Craft 2.6.2962 and Craft 2.6.2951.

## 0.1.4 - 2016-06-28

### Added
- Permissions added for crop and resize element actions. Choose whether your users have access to these functions.

### Changed
- Non-admins can now crop or resize images.

## 0.1.3 - 2016-06-23

### Added
- You can now specify width and height sizes on-demand in the Resizing modal window.
- Resizing can be done on an entire folder through Image Resizer settings (Bulk Resize tab).
- Allow custom aspect ratios to be defined for cropping.

### Changed
- Resizing now checks if the resulting file size is larger than the original. If larger, no action is taken.
- You can now specify per-asset source settings, with fallbacks to your global settings.

## 0.1.2 - 2016-02-24

### Added
- Added support for Amazon S3, Rackspace Cloud Files, and Google Cloud Storage asset sources.

### Changed
- Using `assets.onBeforeUploadAsset` instead of `assets.onSaveAsset`.
- Elements now auto-refresh after crop or resize.
- Refactoring for better performance.

## 0.1.1 - 2016-01-13

### Fixed
- Fixed issue with plugin release feed url.

## 0.1.0 - 2015-12-01

### Added
- Craft 2.5 support, including release feed and icons.

## 0.0.7 - 2015-10-02

### Changed
- Better error-catching for resizing.

### Fixed
- Fix to ensure images uploaded are both an image, and manipulatable.

## 0.0.6 - 2015-09-24

### Added
- Added cropping option to Element Actions.

## 0.0.5 - 2015-09-23

### Changed
- Performance improvements - now uses Tasks to handle batch resizing.

## 0.0.4 - 2015-09-21

### Fixed
- Fix to make sure environment variables are parsed for asset sources.

## 0.0.3 - 2015-09-20

### Added
- Added batch processing for existing assets.
- Added image quality option.

## 0.0.2 - 2015-09-19

### Added
- Added option to restrict resizing to specific Asset sources.

### Changed
- Moved hook from `onBeforeSaveAsset` to `onSaveAsset`.
- Asset record is updated after resize, reflecting new image width/height/size.

## 0.0.1 - 2015-09-18

- Initial release
