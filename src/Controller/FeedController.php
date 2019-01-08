<?php

namespace App\Controller;

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

        $finder->files()->in(__DIR__ . '/../../files/bar/');

        foreach ($finder as $file) {
            // dumps the absolute path
            dump($file->getRealPath());

            // dumps the relative path to the file, omitting the filename
            dump($file->getRelativePath());

            // dumps the relative path to the file
            dump($file->getRelativePathname());
        }

        // this looks exactly the same
        return new Response("foo $slug");

    }
}