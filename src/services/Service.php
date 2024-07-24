<?php
namespace verbb\imageresizer\services;

use verbb\imageresizer\ImageResizer;
use verbb\imageresizer\elementactions\ResizeImage;
use verbb\imageresizer\models\Settings;

use Craft;
use craft\base\Component;
use craft\base\Image;
use craft\helpers\Image as ImageHelper;
use craft\events\AssetEvent;
use craft\events\RegisterElementActionsEvent;
use craft\image\Raster;

use Throwable;

class Service extends Component
{
    // Public Methods
    // =========================================================================

    public function beforeHandleAssetFile(AssetEvent $event): void
    {
        $asset = $event->sender;
        $filename = $asset->filename;
        $path = $asset->tempFilePath ?? $asset->getImageTransformSourcePath();

        if (!$path) {
            ImageResizer::$plugin->getLogs()->resizeLog(null, 'error', $filename, ['message' => 'Unable to find path: ' . $path]);

            return;
        }

        // Because this is fired on the before-save event, and validation hasn't kicked in, yet
        // we check it here. Otherwise, we potentially process it twice when there's a conflict.
        // if (!$asset->validate()) {
        //     ImageResizer::$plugin->getLogs()->resizeLog(null, 'error', $filename, ['message' => Json::encode($asset->getErrors())]);

        //     return;
        // }

        // Should we be modifying images in this source?
        if (!ImageResizer::$plugin->getService()->getSettingForAssetSource($asset->volumeId, 'enabled')) {
            ImageResizer::$plugin->getLogs()->resizeLog(null, 'skipped-volume-disabled', $filename);

            return;
        }

        // Resize the image
        ImageResizer::$plugin->getResize()->resize($asset, $filename, $path);
    }

    public function registerAssetActions(RegisterElementActionsEvent $event): void
    {
        if (Craft::$app->getUser()->checkPermission('imageResizer-resizeImage')) {
            $event->actions[] = new ResizeImage();
        }
    }

    public function getSettingForAssetSource($sourceId, string $setting): mixed
    {
        /* @var Settings $settings */
        $settings = ImageResizer::$plugin->getSettings();

        // If the plugin itself is disabled, return nothing.
        if (!$settings->enabled) {
            return null;
        }

        // Check if there's a specific setting for the source - so long as not empty, it takes precendence
        $sourceSettings = $settings->assetSourceSettings[$sourceId] ?? [];

        if (array_key_exists($setting, $sourceSettings) && $sourceSettings[$setting] !== '') {
            return $sourceSettings[$setting];
        }

        // Otherwise, fall back to the default, global setting for all sources.
        return $settings->$setting;
    }

    /**
     * @param int|null $quality
     *
     */
    public function getImageQuality(string $path, int $quality = null): int
    {
        $desiredQuality = $quality ?: ImageResizer::$plugin->getSettings()->imageQuality;
        $desiredQuality = $desiredQuality ?: Craft::$app->getConfig()->getGeneral()->defaultImageQuality;

        if (@pathinfo($path, PATHINFO_EXTENSION) == 'png') {
            // Valid PNG quality settings are 0-9, so normalize and flip, because we're talking about compression
            // levels, not quality, like jpg and gif.
            $quality = (int)round(($desiredQuality * 9) / 100);
            $quality = 9 - $quality;

            if ($quality < 0) {
                $quality = 0;
            }

            if ($quality > 9) {
                $quality = 9;
            }
        } else {
            $quality = $desiredQuality;
        }

        return $quality;
    }

    public function getAssetFolders(array $tree, array &$folderOptions): void
    {
        foreach ($tree as $folder) {
            $folderOptions[] = ['label' => $folder->name, 'value' => $folder->id];

            $children = $folder->getChildren();

            if ($children) {
                $this->getAssetFolders($children, $folderOptions);
            }
        }
    }

    /**
     * Our own custom save function that respects EXIF data. Using image->saveAs strips EXIF data!
     *
     */
    public function saveAs(Image|Raster &$image, string $filePath): void
    {
        // Get the current orientation from Exif - we might need this later to rotate
        $orientation = $image->getImagineImage()->metadata()->get('ifd0.Orientation');

        $degrees = false;

        switch ($orientation) {
            case ImageHelper::EXIF_IFD0_ROTATE_180:
                $degrees = 180;
                break;
            case ImageHelper::EXIF_IFD0_ROTATE_90:
                $degrees = 90;
                break;
            case ImageHelper::EXIF_IFD0_ROTATE_270:
                $degrees = 270;
                break;
        }

        // Save the resized image. Note that this can potentially strip all Exif metadata (with `preserveExifData = false`).
        // We need to do this ASAP, because this is the in-memory, resized image. All other operations such as stripping
        // Exif data or rotating images need an on-file, saved image to mess around with.
        $image->saveAs($filePath);

        // If we want to `rotateImagesOnUploadByExifData` we will need to do this manually, rather than rely on
        // `rotateImageByExifData()` because that will try and load the image again, but because it's aready saved
        // above, there's no Exif orientation data to look at. Fortunately, we've captured that already before the save.
        if ($degrees && Craft::$app->getConfig()->getGeneral()->rotateImagesOnUploadByExifData) {
            // Load in the image again, fresh (it's been resized after all)
            $image = Craft::$app->getImages()->loadImage($filePath);

            // Perform the rotate and save again
            $image->rotate($degrees);
            $image->saveAs($filePath);
        }
    }
}
