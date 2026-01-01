<?php

namespace App\Helpers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Writer\SvgWriter;

trait QrGeneration
{
    public function generateQrBase64($value, $size = 250)
    {
        $qrCode = new QrCode($value);
        $writer = new SvgWriter();
        $result = $writer->write($qrCode);

        $base64 = base64_encode($result->getString());
        return $base64;
    }
}
