<?php

use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\{RefreshDatabase, WithFaker};
use Homeful\KwYCCheck\Events\CampaignQRCodeGenerated;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\KwYCCheck\Actions\ProcessLeadAction;
use Homeful\KwYCCheck\Events\LeadProcessed;
use Homeful\Contacts\Data\ContactData;
use Illuminate\Support\Facades\Event;
use Homeful\Contacts\Models\Contact;
use Homeful\KwYCCheck\Data\LeadData;
use Homeful\KwYCCheck\Facades\KYC;
use Homeful\KwYCCheck\Models\Lead;
use Homeful\KwYCCheck\Actions\AttachLeadMediaAction;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Homeful\KwYCCheck\KwYCCheck;
use Zxing\QrReader;
use SVG\SVG;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    $migration = include 'vendor/jn-devops/contacts/database/migrations/create_contacts_table.php.stub';
    $migration->up();
    $migration = include 'vendor/spatie/laravel-medialibrary/database/migrations/create_media_table.php.stub';
    $migration->up();
});

it('has configs', function () {
    expect(config('kwyc-check.campaign_url'))->toBe('https://kwyc-check.net/campaign-checkin/9ccef822-4209-4e0a-bb40-232da5cafdf1');
});

it('can generate campaign QR code', function(){
    Event::fake();
    $svg = app(KwYCCheck::class)->generateCampaignQRCOde(query_params: ['code' => 'ABC-123','identifier'=>'DEF-456','choice'=>'GHI-789']);
    expect($svg)->toBeString();
    Event::assertDispatched(CampaignQRCodeGenerated::class);
    //    $image = SVG::fromString($svg);
    //    $qrcode = new QrReader(Storage::get('image.svg'), QrReader::SOURCE_TYPE_FILE);
    //    $text = $qrcode->text();
    //TODO: test generated qr code text using Zxing\QrReader and SVG\SVG
});

it('has qr code api end point', function () {
    $inputs_with_defaults = ['code' => 'ABC-111','identifier'=>'DEF-222','choice'=>'GHI-333'];
    $booking_server_response = $this->postJson(route('generate-qr'), $inputs_with_defaults);
    $booking_server_response->assertStatus(200);
    with($booking_server_response->json(), function (array $json) {
        expect(Arr::get($json, 'qr_code'))->toBeString();
        expect(strlen(Arr::get($json, 'qr_code')))->toBeGreaterThan(5000);
    });
});

test('lead has attributes', function () {
    $lead = Lead::factory()->create();
    if ($lead instanceof Lead) {
        expect($lead->name)->toBeString();
        expect($lead->address)->toBeString();
        expect($lead->birthdate)->toBeString();
        expect($lead->email)->toBeString();
        expect($lead->mobile)->toBeString();
        expect($lead->code)->toBeString();
        expect($lead->id_type)->toBeString();
        expect($lead->id_number)->toBeString();
        expect($lead->id_image_url)->toBeString();
        expect($lead->selfie_image_url)->toBeString();
//        expect($lead->id_mark_url)->toBeString();
        expect($lead->meta)->toBeInstanceOf(SchemalessAttributes::class);
        expect($lead->checkin)->toBeArray();
    }
});

test('lead has a settable contact relation', function () {
    $lead = Lead::factory()->create();
    $contact = Contact::factory()->create([
        'idImage' => null,
        'selfieImage' => null,
        'payslipImage' => null,
        'voluntarySurrenderFormDocument' => null,
        'usufructAgreementDocument' => null,
        'contractToSellDocument' => null,
    ]);
    if ($lead instanceof Lead) {
        expect($lead->contact)->toBeNull();
        $lead->contact = $contact;
        $lead->save();
        expect($lead->contact->is($contact))->toBeTrue();
    }
});

test('lead has data', function () {
    $lead = Lead::factory()->forContact()->create();
    $data = LeadData::fromModel($lead);
    expect($data->name)->toBe($lead->name);
    expect($data->address)->toBe($lead->address);
    expect($data->birthdate)->toBe($lead->birthdate);
    expect($data->email)->toBe($lead->email);
    expect($data->mobile)->toBe($lead->mobile);
    expect($data->code)->toBe($lead->code);
    expect($data->id_type)->toBe($lead->id_type);
    expect($data->id_image_url)->toBe($lead->id_image_url);
    expect($data->selfie_image_url)->toBe($lead->selfie_image_url);
    expect($data->id_mark_url)->toBe($lead->id_mark_url);
    expect($data->contact)->toBeInstanceOf(ContactData::class);
});

test('process lead action', function () {
    Event::fake(LeadProcessed::class);
    $email = $this->faker()->email();
    $mobile = '09171234567';
    $code = $this->faker()->word();
    $identifier = $this->faker()->word();
    $choice = $this->faker()->word();
    $fullName = $this->faker()->name();
    $address = $this->faker()->city();
    $dateOfBirth = '1999-03-17';
    $idType = 'phl_dl';
    $idNumber = 'ID-123456';
    $checkin_payload  = Lead::factory()->getCheckinPayload(compact(  'email', 'mobile', 'code', 'identifier', 'choice', 'fullName', 'address', 'dateOfBirth', 'idType', 'idNumber'));
    $action = app(ProcessLeadAction::class);
    $lead = $action->run($checkin_payload);
    if ($lead instanceof Lead) {
        expect($lead->email)->toBe($email);
        expect($lead->mobile)->toBe($mobile);
        expect($lead->code)->toBe($code);
        expect($lead->identifier)->toBe($identifier);
        expect($lead->choice)->toBe($choice);
        expect($lead->name)->toBe($fullName);
        expect($lead->address)->toBe($address);
        expect($lead->birthdate)->toBe($dateOfBirth);
        expect($lead->id_type)->toBe($idType);
        expect($lead->id_number)->toBe($idNumber);
        expect($lead->id_image_url)->toBeUrl();
        expect($lead->selfie_image_url)->toBeUrl();
        expect($lead->id_mark_url)->toBeNull();
    }
    Event::assertDispatched(LeadProcessed::class);
});

test('process-lead api end point', function () {
    $email = 'mary.cruz@gmail.com';
    $mobile = '09171234567';
    $checkin_payload  = Lead::factory()->getCheckinPayload(compact('email', 'mobile'));
    $booking_server_response = $this->postJson(route('process-lead'), $checkin_payload);
    $booking_server_response->assertStatus(200);
    with(app(Lead::class)->where(['meta->checkin->body->inputs->email' => $email, 'meta->checkin->body->inputs->mobile' => $mobile])->first(), function (Lead $lead) use ($booking_server_response) {
        $booking_server_response->assertJson(['code' => $lead->code, 'status' => true]);
    });
});

dataset('media-attribs', function () {
   return [
       [
           fn() => [
               'idImage' => 'https://jn-img.enclaves.ph/Test/idImage.jpg',
               'selfieImage' => 'https://jn-img.enclaves.ph/Test/selfieImage.jpg'
           ]
       ]
   ];
});

test('lead has media ', function(array $attribs) {
    $lead = Lead::factory()->create(['idImage' => null, 'selfieImage' => null]);
    if ($lead instanceof Lead) {
        $lead->update($attribs);
        $lead->save();
        expect($lead->idImage)->toBeInstanceOf(Media::class);
        expect($lead->selfieImage)->toBeInstanceOf(Media::class);
    }
})->with('media-attribs');

test('attach lead media action ', function(array $attribs) {
    $lead = Lead::factory()->create(['idImage' => null, 'selfieImage' => null]);
    if ($lead instanceof Lead) {
        $lead = app(AttachLeadMediaAction::class)->run($lead, $attribs);
        if ($lead instanceof Lead) {
            expect($lead->idImage)->toBeInstanceOf(Media::class);
            expect($lead->selfieImage)->toBeInstanceOf(Media::class);
        }
    }
})->with('media-attribs');

test('attach lead media has api end point ', function(array $attribs) {
    $lead = Lead::factory()->create(['idImage' => null, 'selfieImage' => null]);
    if ($lead instanceof Lead) {
        $booking_server_response = $this->postJson(route('attach-media', ['lead' => $lead->id]), $attribs);
        $booking_server_response->assertStatus(200);
        $lead->refresh();
        expect($lead->idImage)->toBeInstanceOf(Media::class);
        expect($lead->selfieImage)->toBeInstanceOf(Media::class);
        expect($lead->uploads)->toHaveCount(2);
        //improve test
    }
})->with('media-attribs');
