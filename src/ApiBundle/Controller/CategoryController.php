<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use ApiBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\Annotation as Serializer;

class CategoryController extends Controller
{
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(new CategoryType(), $category);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $json = $this->serialize($category);

        $response = new Response($json, 201);
        $categoryUrl = $this->generateUrl(
            'category_show',
            ['name' => $category->getName()]
        );
        $response->headers->set('Location', $categoryUrl);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function showAction($name)
    {
        $category = $this->getDoctrine()
            ->getRepository('ApiBundle:Category')
            ->findOneByName($name);

        if (!$category) {
            throw $this->createNotFoundException('Category with name ' . $name . 'not found');
        }

        $json = $this->serialize($category);

        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function listAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('ApiBundle:Category')
            ->findAll();

        $json = $this->serialize(['categories' => $categories]);

        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updateAction($name, Request $request)
    {
        $category = $this->getDoctrine()
            ->getRepository('ApiBundle:Category')
            ->findOneByName($name);

        if (!$category) {
            throw $this->createNotFoundException(sprintf(
                'No category found with name "%s"',
                $name
            ));
        }

        $form = $this->createForm(new CategoryType(), $category);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $json = $this->serialize($category);
        $response = new Response($json, 200);
        return $response;
    }

    public function deleteAction($name)
    {
        $category = $this->getDoctrine()
            ->getRepository('ApiBundle:Category')
            ->findOneByName($name);

        if ($category) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return new Response(null, 204);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
    }

    private function serialize($data)
    {
        return $this->container->get('jms_serializer')
            ->serialize($data, 'json');
    }
}