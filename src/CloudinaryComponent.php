<?php

namespace nikosid\cloudinary;

use Cloudinary;
use Cloudinary\Uploader;
use yii\base\Component;
use yii\helpers\Url;

class CloudinaryComponent extends Component
{
    public $cloud_name;
    public $api_key;
    public $api_secret;
    public $cdn_subdomain;
    public $useSiteDomain = false;

    /** @var string */
    public $prefix = '';

    public function init()
    {
        parent::init();
        Cloudinary::config([
            'cloud_name' => $this->cloud_name,
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
            'cdn_subdomain' => $this->cdn_subdomain,
        ]);
    }

    /**
     * @param $publicId
     * @param array $options
     * @return string
     */
    public function getUrl($publicId, array $options = [])
    {
        $baseUrl = cloudinary_url($publicId, $options);
        if (true === $this->useSiteDomain) {
            $homeUrl = Url::home(true);
            $baseUrl = preg_replace('/^https?:\/\/\w+.cloudinary.com\//i', $homeUrl, $baseUrl);
        }
        return $baseUrl;
    }

    /**
     * @param $file
     * @param array $options
     * @return mixed
     */
    public function upload($file, array $options = [])
    {
        return Uploader::upload($file, $options);
    }

    /**
     * @param $publicId
     * @param array $options
     * @return string
     */
    public function destroy($publicId, array $options = [])
    {
        return Uploader::destroy($publicId, $options);
    }


}
