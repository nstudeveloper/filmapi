<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Actor;
use ApiBundle\Form\ActorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $data = $this->serializeActor($actor);

        $response = new JsonResponse(json_encode($data), 201);
//        $actorUrl = $this->generateUrl(
//            'actor_show',
//            [
//                'firstName' => $actor->getFirstname(),
//                'lastName' => $actor->getLastname()
//            ]);
//        $response->headers->set('Location', $actorUrl);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function serializeActor(Actor $actor)
    {
        return [
            'id' => $actor->getId(),
            'firstName' => $actor->getFirstname(),
            'lastName' => $actor->getLastname()
        ];
    }

    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
    }
}