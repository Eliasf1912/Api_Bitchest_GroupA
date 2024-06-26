<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\UsersRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserController extends AbstractController
{

    private $entityManager;
    private $User;
    private $PasswordHasher;
    private $jwtManager;
    private $serializer;
    private $Wallet;

    public function __construct(EntityManagerInterface $entityManager, UsersRepository $User, JWTTokenManagerInterface $jwtManager, UserPasswordHasherInterface $PasswordHasher, SerializerInterface $serializer, WalletRepository $Wallet)
    {   
        // Fonctionalités et bundle utilisé dans le controller 
        $this->entityManager = $entityManager;
        $this->User = $User;
        $this->jwtManager = $jwtManager;
        $this->PasswordHasher = $PasswordHasher;
        $this->serializer = $serializer;
        $this->Wallet = $Wallet;
    }

    #[Route('api/register', name: 'app_users',methods: 'POST')]
    public function register(Request $request): Response
    {   
        // On décode les infos de l'utilisateur à ajouter qui à été envoyé en JSON
        $Data = json_decode($request->getContent(), true);

        // initialise un nouveau user 
        $NewUser = New User();

        $Email = $Data['email'];
        $UserName = $Data['username'];
        $Password = $Data['password'];
        // On hache le mot de passe pour qu'il apparraise pas en dur dans la base de donnée 
        $PasswordHashed = $this->PasswordHasher->hashPassword($NewUser, $Password);

        if($this->User->findOneBy(['username' => $UserName])){
            return New JsonResponse([
                'status' => false,
                'message' => 'Ce nom d\'utilisateur est déjà pris.'
            ],Response::HTTP_CONFLICT);
        }

        $NewUser->setUserName($UserName);
        $NewUser->setEmail($Email);
        $NewUser->setPassword($PasswordHashed);
        $NewUser->setRoles(['ROLE_USER']);

        // infos envoyé à l'utilisateur
        $token = $this->jwtManager->create($NewUser);
        var_dump($token);

        // Il faut auusi initailiser le wallet de l'utilisateur 
        $UserWallet = New Wallet;
        // On ajoute les 500 
        $UserWallet->setBalance(500);
        // on ajoute un Wallet 
        $NewUser->setWallet($UserWallet);

        // le faire persister en bdd
        $this->entityManager->persist($NewUser);
        $this->entityManager->persist($UserWallet);
        $this->entityManager->flush();
        
        // On lui renvoie un JSON
        return New JsonResponse([
            'status' => true,
            'message'=> 'Votre compte à bien été crée!',
            'Token' => $token,
            'UserName' => $UserName,
        ]);
        
    }

    #[Route('api/user', name: 'app_userInfos',methods: 'GET')]
    public function userInfos(): Response 
    {   
        // On récupère l'utilisateur connécté
        $UserConnected = $this->getUser();

        // On récupére les infos utilisateur via un group grâce au serializer
        $UserInfos = $this->serializer->serialize($UserConnected,'json',['groups' => 'user:read']);

        // On renvoie un json avec les infos utilisateur
        return New JsonResponse([
            'userInfos' => json_decode($UserInfos)
        ]);
    }

    #[Route('api/user/updateInformation', name: 'UserUpdateInformation', methods : 'POST')]
    public function UpdatePassword(Request $request): Response
    {
        $Data = json_decode($request->getContent(), true);      

        // on regarde si l'admin nous fournit c'est informations
        $UserNewEmail = $Data['email'] ?? null;
        $UserNewName = $Data['username'] ?? null;
        $UserNewPassword = $Data['password'] ?? null;

        // On récupère l'utilisateur connecté
        $UserConnected = $this->getUser();

        // On récupère l'utilisateur dans la base de donnée via son id 
        $UserToModify = $this->User->find($UserConnected->getId());

        // Dans le cas où on ne trouve pas l'utilisateur, on renvoie une erreur
        if(!$UserToModify){
            New JsonResponse([
                "Statut" => "False",
                "Message" => "Cette utilisateur n'existe pas !"
            ], Response::HTTP_NOT_FOUND);
        };
        
        // MDP
        if($UserNewPassword){
            // On hache le mdp pour le sécuriser 
            $UserPasswordHashed = $this->PasswordHasher->hashPassword($UserToModify, $UserNewPassword);
            // On set le mdp
            $UserToModify->setPassword($UserPasswordHashed);
            // On renvoie la réponse 
        }

        // Email
        if($UserNewEmail){
            $UserToModify->setEmail($UserNewEmail);
        }
        
        // Username
        if($UserNewName){
            if($this->User->findOneBy(['username'=> $UserNewName])){
               return New JsonResponse([
                    'status' => false,
                    'message' => 'Ce Nom d\'utilisateur existe déja, veuillez choisir un nouveau'
               ],Response::HTTP_CONFLICT);
            }
            $UserToModify->SetUserName($UserNewName);
        }

        $this->entityManager->flush();    

        return New JsonResponse([
            'status' => true,
            'message' => 'Les informations on bien été mis à jour'
       ]);

    }

}
