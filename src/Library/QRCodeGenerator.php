<?php

namespace Codediesel\Library;

use Codediesel\Library\QRGenerator\QRCode;

/**
 * PHP QR Code Generator (No External APIs)
 * Uses PHP GD Library to generate QR codes locally
 * Save this as: qr_generator.php
 *
 * Requires: PHP GD extension (usually included by default)
 */
class QRCodeGenerator
{

    private $data;
    private $size;

    public function __construct($data, $size = 300)
    {
        $this->data = $data;
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function render(): string
    {

        $text = substr($this->data, 0, 100); // Limit to 100 chars for version 1
        $size = max(4, min(15, $this->size));

        $qr = new QRCode($text, QRCode::ERROR_CORRECT_M);
        $qrImage = $qr->renderImage($size, 2);

        ob_start();
        imagepng($qrImage);
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($qrImage);
        return base64_encode($imageData);
    }


}