<?php

use Homeful\KwYCCheck\KwYCCheck;

it('has configs', function () {
    expect(config('kwyc-check.campaign_url'))->toBe('https://kwyc-check.net/campaign-checkin/9ccef822-4209-4e0a-bb40-232da5cafdf1');
});

it ('has QrCode', function(){
    expect((new KwYCCheck)->getQrCode())->toBeString();
});
