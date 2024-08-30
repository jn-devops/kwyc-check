<?php

namespace Homeful\KwYCCheck\Models;

use Homeful\KwYCCheck\Traits\{HasCheckinExtractedFieldsAttributes, HasCheckinInputFieldsAttributes, HasMetaAttributes};
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\KwYCCheck\Traits\HasMediaAttributes;
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
 * @property int $id
 * @property Contact $contact
 * @property string $name
 * @property string $address
 * @property string $birthdate
 * @property string $email
 * @property string $mobile
 * @property string $code
 * @property string $identifier
 * @property string $choice
 * @property string $id_type
 * @property string $id_number
 * @property string $id_image_url
 * @property string $selfie_image_url
 * @property string $id_mark_url
 * @property SchemalessAttributes $meta
 * @property array $checkin
 * @property array $media
 * @property Media $idImage
 * @property Media $selfieImage
 * @property array $uploads
 *
 * @method Model create()
 * @method int getKey()
 */
class Lead extends Model implements HasMedia
{
    use HasCheckinExtractedFieldsAttributes;
    use HasCheckinInputFieldsAttributes;
    use HasMediaAttributes;
    use InteractsWithMedia;
    use HasMetaAttributes;
    use HasFactory;
    use HasMeta;

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
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $collections = [
            'id-images' => ['image/jpeg', 'image/png', 'image/webp'],
            'selfie-images' => ['image/jpeg', 'image/png', 'image/webp'],
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
