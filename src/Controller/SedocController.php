<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SedocController extends Controller {

    /**
     * @Route("/sedoc", name="sedoc")
     */
    public function index()
    {
        $page = "https://www.sedoc.pl"; //page
        $taskImg = "https://www.sedoc.pl/images/news/10.jpg"; //image to start the task
        $content = file_get_contents($page); //get content page

        /**
         * explode data to get only last news
         */
        $beginNews = explode("<div class=\"six columns last-news\">", $content);
        $endNews = explode("<section id=\"quote\">", $beginNews[1]);
        $news = $endNews[0];
        /**
         * End - explode data to get only last news
         */

        /**
         * Get first actual image of news
         */
        preg_match_all("/<span class=\"image\">.*<\/span>/", $news, $output);
        $actualImg = $output[0][0];
        $actualImg = str_replace('<span class="image"><img src=', '', $actualImg);
        $actualImg = str_replace('/></span>', '', $actualImg);
        $actualImg = str_replace('"', '', $actualImg);
        /**
         * End - Get first actual image of news
         */

        /**
         * Comparison of images
         */
        $comparison = false;
        if ($taskImg == $page . $actualImg)
        {
            $comparison = true;
        }

        /**
         * End - Comparison of images
         */

        return $this->render('sedoc/index.html.twig',
            [
                'taskImg'    => $taskImg,
                'actualImg'  => $page . $actualImg,
                'comparison' => $comparison
            ]
        );
    }
}
