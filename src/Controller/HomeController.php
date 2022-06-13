<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use DateInterval;
use Symfony\Contracts\Cache\CacheInterface;

class HomeController extends AbstractController
{
    public function __construct(private CacheInterface  $cache)
    {
    }

    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        $cacheKey = 'thisIsACacheKey';
        $item = $this->cache->getItem($cacheKey);

        $itemCameFromCache = true;
        if (!$item->isHit()) {
            $itemCameFromCache = false;
            $item->set('this is some data to cache');
            $item->expiresAfter(new DateInterval('PT10S')); // the item will be cached for 10 seconds
            $this->cache->save($item);

        }

        return $this->render('home/index.html.twig', ['isCached' => $itemCameFromCache ? 'true' : 'false']);
    }
}
