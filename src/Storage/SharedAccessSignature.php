<?php

namespace Azure\Storage;

class SharedAccessSignature {

    protected $defaultProtocol = 'https';

    protected $container;

    protected $resourceName;

    protected $url;

    protected $signature;

    protected $params;

    protected $baseUri = '.blob.core.windows.net/';

    protected $start;

    protected $expiry;

    protected $options = [
        'permissions' => 'rw',
        'signedResource' => 'b',
        'signedIdentifier' => '',
        'signedIP' => '',
        'signedProtocol' => 'https',
        'signedVersion' => '2014-02-14',
        'rscc' => '',
        'rscd' => '',
        'rsce' => '',
        'rscl' => '',
        'rsct' => '',
    ];

    public function __construct($container, $resource = null, array $options = [], $expiresIn=86400)
    {
        $this->accountName = config('azure.account_name');
        $this->accountKey = config('azure.account_key');
        $this->container = $container;
        $this->resourceName = $resource;
        $this->setOptions($options);
        $this->setExpiry($expiresIn);
        $this->setSignature();
        $this->setParams();
    }

    public function getBaseUrl()
    {
        return $this->defaultProtocol.'://'.$this->accountName.$this->baseUri.$this->container;
    }

    public function getUrl()
    {
        $url = $this->getBaseUrl();

        if($this->resourceName) {
            $url .= '/'.$this->resourceName;
        }

        return $this->url  = $url.'?'.$this->getParams();
    }

    public function getOptions()
    {
        //dd($this->options);
        return $this->options;
    }

    public function setOptions($options)
    {
        if(count($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function setExpiry($seconds)
    {
        $this->start = gmdate('Y-m-d\TH:i:s\Z', time() - 1800);
        $this->expiry = gmdate('Y-m-d\TH:i:s\Z', time() + $seconds);
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getResourceName()
    {
        return $this->resourceName;
    }

    public function getPath()
    {
        $path = '/'.$this->accountName.'/'.$this->container;

        if ($this->resourceName) {
            $path .= '/'.$this->resourceName;
        }

        return $path;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    private function setSignature()
    {
        $stringToSign = $this->options['permissions']."\n".
            $this->start."\n".
            $this->expiry."\n".
            $this->getPath()."\n".
            $this->options['signedIdentifier']."\n".
            $this->options['signedVersion']."\n".
            $this->options['rscc']."\n".
            $this->options['rscd']."\n".
            $this->options['rsce']."\n".
            $this->options['rscl']."\n".
            $this->options['rsct'];

        return $this->signature = base64_encode(
            hash_hmac('sha256', $stringToSign, base64_decode($this->accountKey), true)
        );
    }

    public function getParams()
    {
        return implode('&', $this->params);
    }

    public function setParams()
    {
        $params = [
            'sv='.urlencode($this->options['signedVersion']),
            'st='.urlencode($this->start),
            'se='.urlencode($this->expiry),
            'sr='.urlencode($this->options['signedResource']),
            'sp='.urlencode($this->options['permissions']),
            'sig='.urlencode($this->getSignature())
        ];
        if($this->options['rscd']) {
            $params[] = 'rscd='.urlencode($this->options['rscd']);
        }
        if($this->options['rsct']) {
            $params[] = 'rsct='.urlencode($this->options['rsct']);
        }
        //dd($params);
        return $this->params = $params;
    }
}

