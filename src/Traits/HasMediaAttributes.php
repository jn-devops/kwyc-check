<?php

namespace Homeful\KwYCCheck\Traits;

use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

trait HasMediaAttributes
{
    public function initializeHasMediaAttributes(): void
    {
        $this->mergeFillable(['idImage', 'selfieImage', 'campaignDocument']);
    }

    public function getIdImageAttribute(): ?Media
    {
        return $this->getFirstMedia('id-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setIdImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('idImage')
                ->toMediaCollection('id-images');
        }

        return $this;
    }

    public function getSelfieImageAttribute(): ?Media
    {
        return $this->getFirstMedia('selfie-images');
    }

    /**
     * @return $this
     *
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setSelfieImageAttribute(?string $url): static
    {
        if ($url) {
            $this->addMediaFromUrl($url)
                ->usingName('selfieImage')
                ->toMediaCollection('selfie-images');
        }

        return $this;
    }

    public function getCampaignDocumentAttribute(): ?Media
    {
        return $this->getFirstMedia('campaign-documents');
    }


    public function setCampaignDocumentAttribute(?string $url): static
    {
//        $file = file_exists($file) ? $file : Storage::path($file);
//
//        $this->addMedia(file: $file)
//            ->toMediaCollection('campaign-documents');

//        $file = file_exists($file) ? $file : Storage::path($file);

        if ($url) {
            $this->addMediaFromUrl(url: $url)
                ->usingName('campaignDocument')
                ->toMediaCollection('campaign-documents');
        }

        return $this;
    }

    public function getUploadsAttribute(): array
    {
        return collect($this->media)
            ->mapWithKeys(function ($item, $key) {
                $collection_name = $item['collection_name'];
                $name = Str::camel(Str::singular($collection_name));
                $url = $item['original_url'];

                return [
                    $key => [
                        'name' => $name,
                        'url' => $url,
                    ],
                ];
            })
            ->toArray();
    }

    /**
     * Helper function to get all media field names registered in the media collection i.e.,
     *
     * id-images => idImage
     * selfie-images => selfieImage
     */
    public function getMediaFieldNames(): array
    {
        return $this->getRegisteredMediaCollections()
            ->pluck('name')
            ->map(function ($key) {
                return Str::singular(Str::camel($key));
            })
            ->toArray();
    }
}
