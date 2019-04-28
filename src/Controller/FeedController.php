<?php

namespace App\Controller;

use App\Config\Configuration;
use getID3;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tightenco\Collect\Support\Collection;

class FeedController
{

    /** @var Collection */
    private $config;

    private $modifiedAt;

    public function __construct(Configuration $config)
    {
        $this->config = new Collection($config->get());
        $this->modifiedAt = $config->modifiedAt();
    }

    /**
     * @Route("/feed/{slug}")
     */
    public function feed(string $slug, Request $request): Response
    {
        $finder = new Finder();
        $getID3 = new getID3;

        $current = $this->config[$slug];


        $baseUrl = 'https://' . $request->server->get('HTTP_HOST') . '/files/' . $current['path'];

        $finder->files()
            ->in(__DIR__ . '/../../public/files/' . $current['path'])
            ->name('*.mp3')
            ->sortByName();

        $feed = new \Castanet_Feed($current['title'], '', '');
        $feed->setImage($baseUrl . '/cover.jpg', 1440, 960);

        foreach ($finder as $file) {

            $item = new \Castanet_Item();

            $info = $getID3->analyze($file->getRealPath());

            $url = $baseUrl . "/" . $file->getRelativePathname();

            $item->setTitle($info['id3v1']['title'] ? $info['id3v1']['title'] : $info['filename']);
            $item->setLink($url);
            $item->setMediaUrl($url);
            $item->setMediaMimeType('audio/mpeg');
            $item->setMediaSize($info['filesize']);

            $feed->addItem($item);
            dump($item);
        }

        $response = new Response((string) $feed);
        $response->headers->set('Content-Type', 'application/xml ');

        return $response;
    }
}