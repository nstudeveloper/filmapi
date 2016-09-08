<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Actor;
use ApiBundle\Form\ActorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ActorController extends Controller
{
    public function newAction(Request $request)
    {
        $actor = new Actor();
        $form = $this->createForm(new ActorType(), $actor);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actor);
        $em->flush();

        $json = $this->serialize($actor);

        $response = new Response($json, 201);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function showAction($name)
    {
        $actor = $this->getDoctrine()
            ->getRepository('ApiBundle:Actor')
            ->findOneByName($name);

        if (!$actor) {
            throw $this->createNotFoundException('Actor with name ' . $name . 'not found');
        }

        $json = $this->serialize($actor);

        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function listAction()
    {
        $actors = $this->getDoctrine()
            ->getRepository('ApiBundle:Actor')
            ->findAll();

        $json = $this->serialize(['actor' => $actors]);

        $response = new Response($json, 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function updateAction($name, Request $request)
    {
        $actor = $this->getDoctrine()
            ->getRepository('ApiBundle:Actor')
            ->findOneByName($name);

        if (!$actor) {
            throw $this->createNotFoundException(sprintf(
                'No actor found with name "%s"',
                $name
            ));
        }

        $form = $this->createForm(new ActorType(), $actor);
        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actor);
        $em->flush();

        $json = $this->serialize($actor);
        $response = new Response($json, 200);
        return $response;
    }

    public function deleteAction($name)
    {
        $category = $this->getDoctrine()
            ->getRepository('ApiBundle:Actor')
            ->findOneByName($name);

        if ($category) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return new Response(null, 204);
    }

    protected function serialize($data, $format = 'json')
    {
        return $this->container->get('jms_serializer')
            ->serialize($data, $format);
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
    }
}