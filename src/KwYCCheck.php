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
     * @param array|null $query_params associative array of default values
     * @param string|null $url
     * @return string
     * @throws \Exception
     */
    public function generateCampaignQRCOde(array $query_params = null, string $url = null): string
    {
        $url = $url ?? config('kwyc-check.campaign_url');
        if ($query_params) {
            $query = http_build_query($query_params);
            $url = $url . '?' . $query;
        }
        $qr_code = $this->getQrCodeSvg($url);
        CampaignQRCodeGenerated::dispatch();

        return $qr_code;
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
