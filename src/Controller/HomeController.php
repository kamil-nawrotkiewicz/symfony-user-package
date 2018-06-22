<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Sedoc;

class HomeController extends Controller {

    /**
     * @Route("/home", name="home_page")
     * @param Sedoc $sedoc
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(Sedoc $sedoc){
        //set page url
        $taskImg = 'https://www.sedoc.pl/images/news/10.jpg';
        $sedoc->setPageURL('https://www.sedoc.pl');
        $sedoc->setComparedURL($taskImg);

        /**
         * id="review" id can not be repeated
         * class="last-news" get news section
         * class="image" get image class
         */
        $sedoc->setImageFilter('#review .last-news .image');
        $comparison = $sedoc->compareImages();

        return $this->render('sedoc/index.html.twig',
            [
                'taskImg'    => $taskImg,
                'actualImg'  => $sedoc->getImageURL(),
                'comparison' => $comparison
            ]
        );
    }
}