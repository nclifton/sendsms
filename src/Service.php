<?php


namespace SendSMS;


use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class Service
{
    const URL_PATTERN = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
    /**
     * @var Client
     */
    private $httpClient;
    private $config;
    private $to;
    private $message;
    /**
     * @var string
     */
    private $error;
    /**
     * @var \Hpatoio\Bitly\Client
     */
    private $bitly;


    public function __construct($httpClient, $bitlyClient, $config, $to, $message)
    {
        $this->httpClient = $httpClient;
        $this->bitly      = $bitlyClient;
        $this->config     = $config;
        $this->to         = $to;
        $this->message    = $message;

    }

    public static function message($to, $message)
    {

        $config = Dotenv::create(__DIR__ . '/../');
        $config = $config->load();
        $client = new Client();
        $bitly  = new \Hpatoio\Bitly\Client($config['BITLY_ACCESS_TOKEN']);
        return new static($client, $bitly, $config, $to, $message);
    }

    public function shortenUrls()
    {
        // todo replace URLS using bitly shortlink

        $preg_match = preg_match_all(self::URL_PATTERN, $this->message, $matches);
        if ($preg_match) {
            foreach ($matches[0] as $url) {
                $shortenedUrl  = $this->bitly->shorten(['longUrl' => $url]);
                $this->message = str_replace($url, $shortenedUrl['url'], $this->message);
            }
        }

        return $this;
    }

    public function send()
    {


        /** @var Client $client */
        $client   = $this->httpClient;

        try {
            $response = $client->post($this->config['BURSTSMS_SEND_SMS_ENDPOINT'],
                [
                    'auth'        => [
                        $this->config['BURSTSMS_KEY'],
                        $this->config['BURSTSMS_SECRET'],

                    ],
                    'form_params' => [
                        'to'      => $this->to,
                        'message' => $this->message
                    ]
                ]);
        } catch (ClientException $e){
            $response = $e->getResponse();
            $this->error = \GuzzleHttp\json_decode($response->getBody()->getContents());
            return false;
        } catch (ServerException $e){
            $response = $e->getResponse();
            $this->error = $response->getBody()->getContents();
            return false;
        }


        return true;

    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }


}