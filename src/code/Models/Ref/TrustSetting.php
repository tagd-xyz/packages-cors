<?php

// phpcs:disable Symfony.Commenting.FunctionComment.Missing
// phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

namespace Tagd\Core\Models\Ref;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;
use Illuminate\Support\Str;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Exceptions\CantRequestUpload;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Models\Model;

class TrustSetting extends Model
{
    public const TABLE = 'trust_settings';

    public const MODIFIER_MIN = 0;

    public const MODIFIER_MAX = 100;

    public const MODIFIER_DEFAULT = 0;

    protected $fillable = [
        'name',
        'setting',
    ];

    protected $casts = [
        'name' => TrustSettingName::class,
        'setting' => 'array',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // /**
    //  * Get the parent uploader model (Reseller).
    //  */
    // public function resellers()
    // {
    //     return $this->morphedByMany(Reseller::class, 'uploads');
    // }

    // /**
    //  * Get the parent uploader model (Item).
    //  */
    // public function items()
    // {
    //     return $this->morphedByMany(Item::class, 'uploads');
    // }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // /**
    //  * get folderClean attribute
    //  */
    // public function getFolderCleanAttribute(): string
    // {
    //     return trim(rtrim($this->folder ?? '', '/'));
    // }

    // /**
    //  * get fileClean attribute
    //  */
    // public function getFileCleanAttribute(): string
    // {
    //     return trim(rtrim($this->file, '/'));
    // }

    // /**
    //  * get fullPath attribute
    //  */
    // public function getFullPathAttribute(): string
    // {
    //     return ltrim($this->folderClean . '/' . $this->fileClean, '/');
    // }

    // /**
    //  * get url attribute
    //  */
    // public function getUrlAttribute(): string
    // {
    //     $cloudfront = trim(rtrim(config('tagd.aws.cloudfront.url'), '/'));

    //     return $cloudfront . '/' . $this->fullPath;
    // }

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    // /**
    //  * Gives the upload a random file name
    //  */
    // public function randomFileName(): self
    // {
    //     $this->update([
    //         'file' => (string) Str::uuid(),
    //     ]);

    //     return $this;
    // }

    // /**
    //  * Builds a S3 upload url
    //  */
    // public function requestUrl(): ?string
    // {
    //     $s3Key = config('tagd.aws.s3.key');
    //     $s3Secret = config('tagd.aws.s3.secret');
    //     $s3Region = config('tagd.aws.s3.region');

    //     if (is_null($s3Region)) {
    //         throw new CantRequestUpload('Missing required AWS region');
    //     }

    //     if (is_null($s3Secret) && ! is_null($s3Key)) {
    //         throw new CantRequestUpload('Missing required AWS S3 secret');
    //     }

    //     if (! is_null($s3Secret) && is_null($s3Key)) {
    //         throw new CantRequestUpload('Missing required AWS S3 key');
    //     }

    //     if (is_null($this->bucket)) {
    //         throw new CantRequestUpload('Missing bucket name');
    //     }

    //     $needsCredentials = ! is_null($s3Key) && ! is_null($s3Region);

    //     try {
    //         if (is_null($this->file)) {
    //             $this->randomFileName();
    //         }

    //         $s3Options = [
    //             'version' => 'latest',
    //             'region' => $s3Region,
    //         ];

    //         if ($needsCredentials) {
    //             $s3Options['credentials'] = new Credentials($s3Key, $s3Secret);
    //         }

    //         $s3Client = new S3Client($s3Options);

    //         $cmd = $s3Client->getCommand('PutObject', [
    //             'Bucket' => $this->bucket,
    //             'Key' => $this->fullPath,
    //         ]);

    //         $ttl = config('tagd.uploads.link_expires_in', '+ 20 minutes');
    //         $request = $s3Client
    //             ->createPresignedRequest($cmd, $ttl)
    //             ->withMethod('PUT');

    //         return (string) $request->getUri();
    //     } catch (\Exception $e) {
    //         throw new CantRequestUpload($e->getMessage());
    //     }

    //     return null;
    // }
}
