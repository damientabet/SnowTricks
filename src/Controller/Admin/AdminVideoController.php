<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminVideoController extends AbstractController
{
    /**
     * @Route("admin/video/delete/{id}", name="video.delete")
     * @param Video $video
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Video $video)
    {
        $id_trick = $video->getIdTrick()->getId();
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($video);
        $entityManager->flush();
        //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' à été supprimé');
        return $this->redirectToRoute('trick.edit', ['id' => $id_trick]);
    }
}
