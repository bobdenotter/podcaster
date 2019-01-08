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

        $finder->files()->in(__DIR__ . '/../../public/files/good-omens')->name('*.mp3');

        $baseUrl = 'http://' . $request->server->get('HTTP_HOST') . '/files/kkc-01';
        $title = "Good Omens";
        $link = "phpnews.io";

//        dd($request->server->get('HTTP_HOST'));

        $feed = new \Castanet_Feed($title, $link, 'Een boek');
        $feed->setImage('cover.jpg', 1440, 960);

        foreach ($finder as $file) {

            $item = new \Castanet_Item();

            $info = $getID3->analyze($file->getRealPath());

            $url = $baseUrl . "/" . $file->getRelativePathname();
//            dd($info);

            $item->setTitle($info['id3v1']['title'] ? $info['id3v1']['title'] : $info['filename']);
            $item->setLink($url);
            $item->setMediaUrl($url);
            $item->setMediaMimeType('audio/mpeg');
            $item->setMediaSize($info['filesize']);

            $feed->addItem($item);
            // dumps the absolute path
//            dump($file->getRealPath());

            // dumps the relative path to the file, omitting the filename
//            dump($file->getRelativePath());

            // dumps the relative path to the file
//            dump($file->getRelativePathname());
        }

        // this looks exactly the same
//        return new Response((string) $feed);

//        dd((string) $feed);


        $response = new Response((string) $feed);

        $response->headers->set('Content-Type', 'xml');

        return $response;

        return new Response("<html><body>foo $slug</body></html>");

    }
}