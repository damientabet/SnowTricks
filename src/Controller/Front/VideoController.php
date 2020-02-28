<?php

namespace App\Controller\Front;

use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use App\Service\VideoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    /**
     * @var VideoService
     */
    private $videoService;

    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    /**
     * @Route("trick/{trick}/video/add", name="video.add")
     * @param Request $request
     * @param Trick $trick
     * @return RedirectResponse|Response
     */
    public function add(Request $request, Trick $trick)
    {
        $video = new Video();
        $form_video = $this->createForm(VideoType::class, $video);
        $form_video->handleRequest($request);

        if ($form_video->isSubmitted() && $form_video->isValid()) {
            $this->videoService->addVideo($video, $trick);
        }

        return $this->render('front/trick/video.html.twig', [
            'trickId' => $trick->getId(),
            'videos' => $trick->getVideos(),
            'formVideo' => $form_video->createView(),
        ]);
    }

    /**
     * @Route("trick/video/delete/{id}", name="video.delete")
     * @param Video $video
     * @return void
     */
    public function delete(Video $video)
    {
        $this->videoService->deleteVideo($video);
        echo 'ok';
        exit;
    }
}
