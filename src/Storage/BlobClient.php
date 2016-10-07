<?php

namespace Azure\Storage;

use WindowsAzure\Common\ServicesBuilder;

/**
 * Class BlobClient
 * @package App\Services\Azure
 */
class BlobClient extends BaseClient {

    /**
     * @var
     */
    protected $client;

    /**
     * Create a new instance of the Azure BlobClient
     *
     * @return \WindowsAzure\Common\WindowsAzure\Blob\Internal\IBlob
     */
    public function _setClient()
    {
        return $this->client = ServicesBuilder::getInstance()->createBlobService($this->connectionString);
    }

    /**
     * Fetch the contents of a blob
     *
     * @param $container
     * @param $name
     * @return string
     */
    public function get($container, $name)
    {
        $blob = $this->client->getBlob($container, $name);

        return stream_get_contents($blob->getContentStream());
    }

    /**
     * Update a blob
     *
     * @param $container
     * @param $name
     * @param $filePath
     * @return mixed
     */
    public function put($container, $name, $filePath)
    {
        return $this->client->createBlockBlob($container, $name, $filePath);
    }

    /**
     * Create a new blob
     *
     * @param $container
     * @param $name
     * @param $filePath
     * @return mixed
     */
    public function post($container, $name, $filePath)
    {
        $content = fopen($filePath, "r");

        return $this->client->createBlockBlob($container, $name, $content);
    }

    /**
     * Delete a blob
     *
     * @param $container
     * @param $name
     * @return mixed
     */
    public function delete($container, $name)
    {
        return $this->client->deleteBlob($container, $name);
    }

    /**
     * Create a new container
     *
     * @param $name
     * @return mixed
     */
    public function createContainer($name)
    {
        return $this->client->createContainer($name);
    }

    /**
     * List the contents of a container
     *
     * @param $name
     * @return mixed
     */
    public function listContainer($name)
    {
        $list = $this->client->listBlobs($name);

        return $blobs = $list->getBlobs();
    }

    /**
     * Delete a container
     *
     * @param $name
     * @return mixed
     */
    public function deleteContainer($name)
    {
        return $this->client->deleteContainer($name);
    }
}
