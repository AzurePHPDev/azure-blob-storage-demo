<?php
declare(strict_types=1);

namespace AzurePHP\Service;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ContainerACL;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use Psr\Http\Message\UploadedFileInterface;

class AzureBlobService
{
    public const ACL_NONE = '';
    public const ACL_BLOB = 'blob';
    public const ACL_CONTAINER = 'container';

    private BlobRestProxy $blobClient;

    /**
     * @param BlobRestProxy $blobClient
     */
    public function __construct(BlobRestProxy $blobClient)
    {
        $this->blobClient = $blobClient;
    }

    public function addBlobContainer(string $containerName): void
    {
        $this->blobClient->createContainer(strtolower($containerName));
    }

    public function setBlobContainerAcl(string $containerName, string $acl = self::ACL_BLOB): bool
    {
        if (! in_array($acl, [self::ACL_NONE, self::ACL_BLOB, self::ACL_CONTAINER])) {
            return false;
        }
        $blobAcl = new ContainerACL();
        $blobAcl->setPublicAccess($acl);
        $this->blobClient->setContainerAcl(
            strtolower($containerName),
            $blobAcl
        );
        return true;
    }

public function uploadBlob(string $containerName, array $uploadedFile, string $prefix = ''): string
{
    $contents = file_get_contents($uploadedFile['tmp_name']);
    $blobName = $uploadedFile['name'];
    if ('' !== $prefix) {
        $blobName = sprintf(
            '%s/%s',
            rtrim($prefix, '/'),
            $blobName
        );
    }
    $this->blobClient->createBlockBlob(strtolower($containerName), $blobName, $contents);
    $blobOptions = new SetBlobPropertiesOptions();
    $blobOptions->setContentType($uploadedFile['type']);
    $this->blobClient->setBlobProperties(
        strtolower($containerName),
        $blobName,
        $blobOptions
    );
    return $blobName;
}
}
