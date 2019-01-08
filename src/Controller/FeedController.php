<?php

namespace App\Controller;

use getID3;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController
{
    /**
     * @Route("/feed/{slug}")
     */
    public function feed(string $slug, Request $request): Response
    {
        $finder = new Finder();
        $getID3 = new getID3;

        $config = [
            'path' => 'good-omens',
            'title' => "Good Omens",
            'link' => "https://phpnews.io",
            'description' => "This is a short description of the entire works. This is a short description of the entire works. This is a short description of the entire works. ",
        ];

        $baseUrl = 'https://' . $request->server->get('HTTP_HOST') . '/files/' . $config['path'];

        $finder->files()
            ->in(__DIR__ . '/../../public/files/' . $config['path'])
            ->name('*.mp3')
            ->sortByName();

        $feed = new \Castanet_Feed($config['title'], $config['link'], $config['description']);
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
        }

        $response = new Response((string) $feed);
        $response->headers->set('Content-Type', 'application/xml ');

        return $response;
    }
}