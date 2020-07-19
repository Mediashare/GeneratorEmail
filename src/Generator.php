<?php
namespace Mediashare\Mail;

use Mediashare\Kernel\Kernel;
use Symfony\Component\DomCrawler\Crawler;
Class Generator {
    private $request;
    public function __construct() {
        $kernel = new Kernel();
        $this->request = $kernel->get('Curl');
    }

    public function run() {
        $response = $this->request->get('https://generator.email');
        $result = $this->Crawl($response);
        return $result;
    }

    private function Crawl(string $html) {
        $domCrawler = new Crawler($html);
        $email = $domCrawler->filter('#email_ch_text')->html();
        return [
            'email' => $email,
            'inbox' => 'https://generator.email/'.$email
        ];
    }
}