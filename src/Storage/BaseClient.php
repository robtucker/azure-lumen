<?php

namespace Azure\Storage;

use App\Core\Contracts\StorageInterface;

/**
 * Class BlobClient
 * @package App\Services\Azure
 */
abstract class BaseClient implements StorageInterface {

    /**
     * @var
     */
    protected $accountName;

    /**
     * @var
     */
    protected $accountKey;

    /**
     * @var
     */
    protected $protocol;

    /**
     * @var
     */
    protected $connectionString;

    /**
     * @var string
     */
    protected $storagePath = 'storage/';

    /**
     *
     */
    public function __construct()
    {
        $this->_configure();
        $this->_setClient();
    }


    /**
     * Configure the Azure settings
     *
     * @return $this
     */
    private function _configure()
    {
        $this->accountName = env('AZURE_STORAGE_ACCOUNT_NAME');
        $this->accountKey = env('AZURE_STORAGE_ACCOUNT_KEY');
        $this->protocol = env('AZURE_STORAGE_PROTOCOL');
        $this->blobEndpoint = env('AZURE_STORAGE_BLOB_ENDPOINT');
        $this->tableEndpoint = env('AZURE_STORAGE_TABLE_ENDPOINT');
        $this->queueEndpoint = env('AZURE_STORAGE_QUEUE_ENDPOINT');
        $this->fileEndpoint = env('AZURE_STORAGE_FILE_ENDPOINT');
        $this->connectionString = "DefaultEndpointsProtocol=".$this->protocol.
            ";AccountName=".$this->accountName.
            ";AccountKey=".$this->accountKey;


        return $this;
    }

    abstract function _setClient();
}
