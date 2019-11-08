<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class AdminImageController extends AbstractController
{
    /**
     * @Route("admin/image/delete/{id}", name="image.delete")
     * @param Image $image
     * @return RedirectResponse
     */
    public function delete(Image $image)
    {
        $filesystem = new Filesystem();
        $id_trick = $image->getIdTrick()->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $imagePath = 'images/tricks/'.$image->getName();

        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
        $entityManager->remove($image);
        $entityManager->flush();
        //$this->addFlash('success', 'Figure N°' . $trick->getId() . ' à été supprimé');
        return $this->redirectToRoute('trick.edit', ['id' => $id_trick]);
    }
}
