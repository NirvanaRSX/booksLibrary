<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $book = new Book();
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('year', IntegerType::class,[
                'required' => false
            ])
            ->add('description', TextareaType::class)
            ->add('image', ImageType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        return $this->render('@App/main/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /*
     * Json
     */
    public function dataAction ()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $this->getDoctrine()->getRepository('AppBundle:Book')->createQueryBuilder('b');
        $books = $qb->getQuery()->getArrayResult();

        return new JsonResponse($books);
    }

    /*
     * Edit book
     */
    public function editAction(Request $request,$id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneBy([
            'id' => $id
        ]);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('year', IntegerType::class,[
                'required' => false
            ])
            ->add('description', TextareaType::class)
            ->add('image', ImageType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }

        return $this->render('@App/main/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function deleteAction(Request $request,$id)
    {
        $book = $this->getDoctrine()->getRepository('AppBundle:Book')->findOneBy([
            'id' => $id
        ]);

        $em = $this->getDoctrine()->getManager();

        $em->remove($book);
        $em->flush();
        return $this->redirect($this->generateUrl('homepage'));
    }
}
