<?php

namespace Homeful\KwYCCheck\Models;

use Homeful\KwYCCheck\Traits\{HasCheckinFieldsAttributes, HasCheckinExtractedFieldsAttributes, HasCheckinInputFieldsAttributes, HasMetaAttributes};
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\KwYCCheck\Traits\HasMediaAttributes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Homeful\Contacts\Models\Contact;
use Homeful\Common\Traits\HasMeta;
use Spatie\MediaLibrary\{HasMedia,
    MediaCollections\Models\Media,
    MediaCollections\File,
    InteractsWithMedia};
use Spatie\Image\Enums\Fit;

/**
 * Class Lead
 *
 * @property string $id
 * @property string $checkin_code
 * @property Contact $contact
 * @property string $name
 * @property string $address
 * @property string $birthdate
 * @property string $email
 * @property string $mobile
 * @property string $code
 * @property string $identifier
 * @property string $choice
 * @property string $location
 * @property string $id_type
 * @property string $id_number
 * @property string $id_image_url
 * @property string $selfie_image_url
 * @property string $campaign_document_url
 * @property SchemalessAttributes $meta
 * @property array $checkin
 * @property array $media
 * @property Media $idImage
 * @property Media $selfieImage
 * @property Media $campaignDocument
 * @property array $uploads
 *
 * @method Model create()
 * @method int getKey()
 *
 * @note https://dev.to/adnanbabakan/implement-uuid-primary-key-in-laravel-and-its-benefits-55o3
 */
class Lead extends Model implements HasMedia
{
    use HasCheckinExtractedFieldsAttributes;
    use HasCheckinInputFieldsAttributes;
    use HasCheckinFieldsAttributes;
    use HasMediaAttributes;
    use InteractsWithMedia;
    use HasMetaAttributes;
    use Notifiable;
    use HasFactory;
    use HasMeta;

    protected $keyType = 'string';

    public $incrementing = false;

    public static function booted(): void
    {
        static::creating(function (Lead $lead) {
            $lead->id = $lead->checkin_code;
        });
    }

    /**
     * @return BelongsTo
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * @param Contact $contact
     * @return $this
     */
    public function setContactAttribute(Contact $contact): self
    {
        $this->contact()->associate($contact);
        $this->load('contact');

        return $this;
    }

    /**
     * @return string
     */
    public function routeNotificationForEngageSpark(): string
    {
        return $this->mobile;
    }

    /**
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $collections = [
            'id-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'selfie-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'campaign-documents' => ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
        ];

        foreach ($collections as $collection => $mimeTypes) {
            $this->addMediaCollection($collection)
                ->singleFile()
                ->acceptsFile(function (File $file) use ($mimeTypes) {
                    return in_array(
                        needle: $file->mimeType,
                        haystack: (array) $mimeTypes
                    );
                });
        }
    }

    /**
     * @param Media|null $media
     * @return void
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
