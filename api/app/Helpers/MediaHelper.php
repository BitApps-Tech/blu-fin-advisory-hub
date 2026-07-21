<?php

namespace App\Helpers;

use App\Models\Media;
use App\Models\MenuItem;
use App\Models\Specialty;
use App\Models\GalleryItem;

class MediaHelper
{
    /**
     * Attach media to a menu item.
     *
     * @param MenuItem $menuItem
     * @param int $mediaId
     * @return bool
     */
    public static function attachToMenuItem(MenuItem $menuItem, int $mediaId): bool
    {
        $media = Media::findOrFail($mediaId);
        
        // Verify it's an image
        if (strpos($media->mime, 'image/') !== 0) {
            throw new \InvalidArgumentException('Media must be an image.');
        }

        $menuItem->image_id = $mediaId;
        return $menuItem->save();
    }

    /**
     * Attach media to a specialty.
     *
     * @param Specialty $specialty
     * @param int $mediaId
     * @return bool
     */
    public static function attachToSpecialty(Specialty $specialty, int $mediaId): bool
    {
        $media = Media::findOrFail($mediaId);
        
        if (strpos($media->mime, 'image/') !== 0) {
            throw new \InvalidArgumentException('Media must be an image.');
        }

        $specialty->image_id = $mediaId;
        return $specialty->save();
    }

    /**
     * Attach media to a gallery item.
     *
     * @param GalleryItem $galleryItem
     * @param int $mediaId
     * @return bool
     */
    public static function attachToGalleryItem(GalleryItem $galleryItem, int $mediaId): bool
    {
        $media = Media::findOrFail($mediaId);
        
        if (strpos($media->mime, 'image/') !== 0) {
            throw new \InvalidArgumentException('Media must be an image.');
        }

        $galleryItem->image_id = $mediaId;
        return $galleryItem->save();
    }

    /**
     * Detach media from a model.
     *
     * @param object $model
     * @return bool
     */
    public static function detach($model): bool
    {
        $model->image_id = null;
        return $model->save();
    }
}

