<?php
namespace Mediashare\Mail;

use Mediashare\Kernel\Kernel;
use Symfony\Component\DomCrawler\Crawler;
Class InBox {
    private $request;
    private $username;
    private $domain;
    private $url = 'https://generator.email';

    public function __construct(string $mail) {
        $kernel = new Kernel();
        $this->request = $kernel->get('Curl');
        $mail = explode('@', $mail);
        $this->username = $mail[0];
        $this->domain = $mail[1];
    }

    public function run() {
        // Check Mail Support
        $checkAddress = $this->checkAddress(); 
        if ($checkAddress['status'] !== 'good'):
            return $checkAddress;
        endif;

        $headers = [
            'Cookie: embx=%5B%22sjakke%40mafiken.gq%22%2C%226chestonzlifeline%40xiaoyonn.xyz%22%5D; surl='.$this->domain.'/'.$this->username
        ];
        $response = $this->request->get($this->url, $headers);
        $result = $this->Crawl($response);
        return $result;
    }

    private function checkAddress() {
        $response = $this->request->post('https://generator.email/check_adres_validation3.php', ['usr' => $this->username, 'dmn' => $this->domain]);
        return \json_decode($response, true);
    }

    private function Crawl(string $html) {
        $domCrawler = new Crawler($html);
        // One Email
        $mail = $domCrawler->filter('#email-table > div.e7m.list-group-item-info');
        if ($mail->count() > 0):
            $results[] = $this->getEmail($domCrawler);
        endif;
        // Multi Emails
        $mails = $domCrawler->filter('#email-table > a.e7m')->extract(['href']);
        foreach ($mails as $mail):
            $url = $this->url.$mail;
            $response = $this->request->get($url);
            $domCrawler = new Crawler($response);
            $results[] = $this->getEmail($domCrawler);
        endforeach;

        return $results ?? [];
    }

    private function getEmail(Crawler $domCrawler) {
        $result= [
            'from' => $domCrawler->filter('#email-table > div.e7m.list-group-item-info > div.e7m.from_div_45g45gg')->text(),
            'subject' => $domCrawler->filter('#email-table > div.e7m.list-group-item-info > div.e7m.subj_div_45g45gg')->text(),
            'datetime' => $domCrawler->filter('#email-table > div.e7m.list-group-item-info > div.e7m.time_div_45g45gg')->text(),
            'message' => $domCrawler->filter('#email-table div.e7m.mess_bodiyy')->html(),
        ];
        return $result;
    }
}