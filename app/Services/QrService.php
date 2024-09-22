<?php

namespace App\Services;

use App\Models\Child;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class QrService {
    public function getChildQrImageUri(Child $child)
    {
        if (Storage::exists("public/qrs/{$child->id}.png"))
        {
            return Str::of(config('app.url'))->rtrim('/') . Storage::url("public/qrs/{$child->id}.png");
        }
        if (!$child->qr) return '';
        $writer = new PngWriter();
        // Create QR code
        $qrCode = QrCode::create($child->qr)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic logo
        // $logo = Logo::create(__DIR__.'/assets/symfony.png')
        // ->setResizeToWidth(50);
        $logo = null;

        // Create generic label
        // $label = Label::create('Label')
        // ->setTextColor(new Color(255, 0, 0));
        $label = null;

        $result = $writer->write($qrCode, $logo, $label);
        $result->saveToFile(Storage::path("public/qrs/{$child->id}.png"));

        $baseUrl = config('app.url');

        return Str::of($baseUrl)->rtrim('/') . Storage::url("public/qrs/{$child->id}.png");
    }
    public function getChildQrImageTag(Child $child)
    {
        $qrUrl = $this->getChildQrImageUri($child);
        return "<img src='{$qrUrl}' />";
    }
}
