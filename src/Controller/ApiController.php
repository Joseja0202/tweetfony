<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Tweet;
use App\Entity\User;

class ApiController extends AbstractController
{
    function getTweet($id) {
        // Obtenemos el tweet
    $entityManager = $this->getDoctrine()->getManager();
    $tweet = $entityManager->getRepository(Tweet::class)->find($id);
    // Si el tweet no existe devolvemos un error con código 404.
    if ($tweet == null) {
        return new JsonResponse([
            'error' => 'Tweet not found'
        ], 404);
    }
    // Creamos un objeto genérico y lo rellenamos con la información.
    $result = new \stdClass();
    $result->id = $tweet->getId();
    $result->date = $tweet->getDate();
    $result->text = $tweet->getText();
    // Para enlazar al usuario, añadimos el enlace API para consultar su información.
    $result->user = $this->generateUrl('api_get_user', [
        'id' => $tweet->getUser()->getId(),
    ], UrlGeneratorInterface::ABSOLUTE_URL);
    // Para enlazar a los usuarios que han dado like al tweet, añadimos sus enlaces API.
    $result->likes = array();
    foreach ($tweet->getLikes() as $user) {
        $result->likes[] = $this->generateUrl('api_get_user', [
            'id' => $user->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
    // Al utilizar JsonResponse, la conversión del objeto $result a JSON se hace de forma automática.
    return new JsonResponse($result);
    }




    function getTweetfonyUser($id) {
        // Obtenemos el user
    $entityManager = $this->getDoctrine()->getManager();
    $user = $entityManager->getRepository(User::class)->find($id);
    // Si el user no existe devolvemos un error con código 404.
    if ($user == null) {
        return new JsonResponse([
            'error' => 'User not found'
        ], 404);
    }
    // Creamos un objeto genérico y lo rellenamos con la información.
    $result = new \stdClass();
    $result->id = $user->getId();
    $result->name = $user->getName();
    $result->user_name = $user->getUserName();
    // Para enlazar al tweet, añadimos el enlace API para consultar su información.
    $result->tweets = array();
    foreach ($user->getTweets() as $tweet) {
        $result->tweets[] = $this->generateUrl('api_get_tweet', [
            'id' => $tweet->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
    // Para enlazar a los tweets que han dado like al tweet, añadimos sus enlaces API.
    $result->likes = array();
    foreach ($user->getLikes() as $tweet) {
        $result->likes[] = $this->generateUrl('api_get_tweet', [
            'id' => $tweet->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
    // Al utilizar JsonResponse, la conversión del objeto $result a JSON se hace de forma automática.
    return new JsonResponse($result);
    }


    function getTweets(){

    $entityManager = $this->getDoctrine()->getManager();
    $tweets = $entityManager->getRepository(Tweet::class)->findAll();

    foreach ($tweets as $tweet) {
        $listaTweets[] = $this->generateUrl('api_get_tweet', [
            'id' => $tweet->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
    return new JsonResponse($listaTweets);
    }

    function getUsers(){

        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
    
        foreach ($users as $user) {
            $listaUsers[] = $this->generateUrl('api_get_user', [
                'id' => $user->getId(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }
        return new JsonResponse($listaUsers);
        }




    function index() {
       $result = array();
       $result['users'] = $this->generateUrl('api_get_users',array(),
                                             UrlGeneratorInterface::ABSOLUTE_URL);
       $result['tweets'] = $this->generateUrl('api_get_tweets',array(),
                                             UrlGeneratorInterface::ABSOLUTE_URL);
       return new JsonResponse($result);
     }

    

}