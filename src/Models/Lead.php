<?php

namespace Homeful\KwYCCheck\Models;

use Homeful\Common\Traits\HasPackageFactory as HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Homeful\KwYCCheck\Traits\HasMetaAttributes;
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
 * @property string $id_type
 * @property string $id_number
 * @property string $id_image_url
 * @property string $selfie_image_url
 * @property string $id_mark_url
 * @property SchemalessAttributes $meta
 *
 * @method Model create()
 * @method int getKey()
 */
class Lead extends Model
{
    use HasFactory;
    use HasMeta;
    use HasMetaAttributes;

    const EMAIL_FIELD = 'email';
    const MOBILE_FIELD = 'mobile';
    const CODE_FIELD = 'code';

    protected $fillable = [
        'name',
        'address',
        'birthdate',
        'id_type',
        'id_number',
        'id_image_url',
        'selfie_image_url',
        'id_mark_url'
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
