<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Category;
use ApiBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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

        $data = $this->serializeCategory($category);

        $response = new JsonResponse(json_encode($data), 201);
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

        $data = $this->serializeCategory($category);

        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function listAction()
    {
        $categories = $this->getDoctrine()
            ->getRepository('ApiBundle:Category')
            ->findAll();

        $data = array('categories' => array());

        foreach ($categories as $category) {
            $data['categories'][] = $this->serializeCategory($category);
        }

        $response = new Response(json_encode($data), 200);
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

        $data = $this->serializeCategory($category);
        $response = new JsonResponse($data, 200);
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

    private function serializeCategory(Category $category)
    {
        return [
            'id' => $category->getId(),
            'name' => $category->getName()
        ];
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
    }
}