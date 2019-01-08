<?php

namespace App\Controller;

use getID3;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController
{
    /**
     * @Route("/feed/{slug}")
     */
    public function feed(string $slug): Response
    {

        $finder = new Finder();

        $finder->files()->in(__DIR__ . '/../../public/files/kkc-01');

        $feed = new \Castanet_Feed('Title', 'https://nos.nl', 'Een boek');

        foreach ($finder as $file) {

            $item = new \Castanet_Item();

            $getID3 = new getID3;

            $ThisFileInfo = $getID3->analyze($file->getRealPath());

            dump($ThisFileInfo);

            $item->setTitle($file->getRealPath());
            $item->setLink($file->getRelativePathname());

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

        dd((string) $feed);



        return new Response("<html><body>foo $slug</body></html>");

    }
}