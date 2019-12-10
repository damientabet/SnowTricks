<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminVideoController extends AbstractController
{
    /**
     * @Route("admin/trick/{trick}/video/add", name="video.add")
     * @param Request $request
     * @param Trick $trick
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function add(Request $request, Trick $trick)
    {
        $video = new Video();
        $form_video = $this->createForm(VideoType::class, $video);
        $form_video->handleRequest($request);

        if ($form_video->isSubmitted() && $form_video->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $video->setIdTrick($trick);
            $video->setCreatedAt(new \DateTime());

            $entityManager->persist($video);
            $entityManager->flush();

            $this->addFlash('success', 'Vidéo ajoutée');

            return $this->redirectToRoute('video.add', ['trick' => $trick->getId()]);
        }

        return $this->render('admin/trick/video.html.twig', [
            'trickId' => $trick->getId(),
            'videos' => $trick->getVideos(),
            'form_video' => $form_video->createView(),
        ]);
    }

    /**
     * @Route("admin/trick/video/delete/{id}", name="video.delete")
     * @param Video $video
     * @return void
     */
    public function delete(Video $video)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($video);
        $entityManager->flush();
        $this->addFlash('success', 'Vidéo supprimée');
        die('ok');
    }
}
