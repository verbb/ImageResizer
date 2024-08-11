<?php
namespace verbb\imageresizer\models;

use craft\base\Model;

class Settings extends Model
{
    // Properties
    // =========================================================================

    public bool $useGlobalSettings = true;
    public bool $enabled = true;
    public int $imageWidth = 2048;
    public int $imageHeight = 2048;
    public int $imageQuality = 100;
    public array $assetSourceSettings = [];
    public bool $skipLarger = true;
    public bool $nonDestructiveResize = false;
    public bool $nonDestructiveCrop = false;

}