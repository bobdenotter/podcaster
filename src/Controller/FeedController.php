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
        $finder->files()->in(__DIR__ . '/../../public/files/kkc-01')->name('*.mp3');

        $baseUrl = 'http://' . $request->server->get('HTTP_HOST') . '/files/kkc-01';

//        dd($request->server->get('HTTP_HOST'));

        $feed = new \Castanet_Feed('Title', 'https://nos.nl', 'Een boek');
        $getID3 = new getID3;

        foreach ($finder as $file) {

            $item = new \Castanet_Item();

            $info = $getID3->analyze($file->getRealPath());

            $url = $baseUrl . "/" . $file->getRelativePathname();
            dump($info);

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


        return new Response((string) $feed);

        return new Response("<html><body>foo $slug</body></html>");

    }
}