<?php

namespace Homeful\KwYCCheck\Models;

use Homeful\KwYCCheck\Traits\{HasCheckinExtractedFieldsAttributes, HasCheckinInputFieldsAttributes, HasMetaAttributes};
use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Illuminate\Database\Eloquent\Model;
use Homeful\Contacts\Models\Contact;
use Homeful\Common\Traits\HasMeta;

/**
 * Class Contract
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
 *
 * @method Model create()
 * @method int getKey()
 */
class Lead extends Model
{
    use HasFactory;
    use HasMeta;
    use HasCheckinExtractedFieldsAttributes;
    use HasCheckinInputFieldsAttributes;
    use HasMetaAttributes;

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function setContactAttribute(Contact $contact): self
    {
        $this->contact()->associate($contact);
        $this->load('contact');

        return $this;
    }
}
