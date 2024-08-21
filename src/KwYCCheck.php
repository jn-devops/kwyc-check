<?php

namespace Homeful\KwYCCheck;

use Homeful\KwYCCheck\Events\CampaignQRCodeGenerated;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Writer;

class KwYCCheck {
    /**
     * @param string|null $url
     * @return string
     * @throws \Exception
     */
    public function generateCampaignQRCOde(string $url = null): string
    {
        $url = $url ?? config('kwyc-check.campaign_url');
        $qr_code = $this->getQrCodeSvg($url);
        CampaignQRCodeGenerated::dispatch();

        return $qr_code;
    }

    /**
     * @deprecated
     *
     * @return string
     * @throws \Exception
     */
    public function getQrCode(): string
    {
        return $this->generateCampaignQRCOde();
    }

    /**
     * @param string $url
     * @return string
     * @throws \Exception
     */
    protected function getQrCodeSvg(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL))
            throw new \Exception($url . ' not a valid url.');

        $svg = (new Writer(
            new ImageRenderer(
                new RendererStyle(192, 0, null, null, Fill::uniformColor(new Rgb(255, 255, 255), new Rgb(45, 55, 72))),
                new SvgImageBackEnd
            )
        ))->writeString($url);

        return trim(substr($svg, strpos($svg, "\n") + 1));
    }
}
