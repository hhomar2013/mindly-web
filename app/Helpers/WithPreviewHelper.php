<?php
namespace App\Helpers;

trait WithPreviewHelper
{
    public function getPreviewUrl($photo)
    {
        if (! $photo) {
            return null;
        }

        try {
            if (method_exists($photo, 'getRealPath') && $photo->getRealPath()) {
                $extension = $photo->getClientOriginalExtension();
                $data      = file_get_contents($photo->getRealPath());
                return 'data:image/' . $extension . ';base64,' . base64_encode($data);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
