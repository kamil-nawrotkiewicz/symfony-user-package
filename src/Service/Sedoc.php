<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;

class Sedoc {

    private $pageURL;
    private $imageURL;
    private $comparedURL;

    /**
     * @param mixed $pageURL
     */
    public function setPageURL($pageURL): void
    {
        $this->pageURL = $pageURL;
    }

    /**
     * Set image
     *
     * @param $filter
     * @return string
     */
    public function setImageFilter($filter)
    {
        if($this->pageURL && $filter){
            //get html page by curl
            $curl = curl_init($this->pageURL);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            $html = curl_exec($curl);
            curl_close($curl);
            //end - get html page by curl

            //crawler search image
            $crawler = new Crawler($html);
            $imageURL = $crawler
                ->filter($filter)
                ->filterXPath('//img[@src]')
                ->first()
                ->attr('src');

            $this->imageURL = $this->pageURL.$imageURL;
        } else {
            return 'page url is not exist';
        }
    }

    /**
     * Get image
     *
     * @return mixed
     */
    public function getImageURL()
    {
        return $this->imageURL;
    }

    /**
     * @param mixed $comparedURL
     */
    public function setComparedURL($comparedURL): void
    {
        $this->comparedURL = $comparedURL;
    }

    /**
     * Compare images
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function compareImages(){
        if($this->imageURL && $this->comparedURL)
        {
            if($this->compareETag() === false) return false;
            if($this->compareControlSum() === false) return false;

            return true;
        } else {
            return 'image or compared image url is not exist';
        }
    }


    /**
     * Compare element by etag
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function compareETag(){
        $client = new Client();
        $compared = $client->request('GET', $this->comparedURL);
        $actual = $client->request('GET', $this->imageURL);

        if($compared->getHeaderLine('Etag') !== $actual->getHeaderLine('Etag'))
            return false;
    }

    /**
     * Compare elements by control sum
     */
    private function compareControlSum(){
        $md5imageCompared = md5(file_get_contents($this->comparedURL));
        $md5imageActual = md5(file_get_contents($this->imageURL));
        if ($md5imageCompared !== $md5imageActual) {
            return false;
        }
    }
}