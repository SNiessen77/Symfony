<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        $number = random_int(0, 100);
        $list = [1, 2, 534, 4564, 'Alex', 'test', 'ABUS'];

        // dd($number, $list);

        return $this->render('default/index.html.twig',
            [
                'controller_name' => 'SiegfriedsController',
                'number' => $number,
                'list' => $list,
            ]);
    }

    #[Route('/start/{name}', name: 'app_start', defaults: ['name' => 'Gast'])]
    public function start(Request $request, string $name): Response
    {
        $lang = $request->query->get('lang', 'de');

        return new Response("<h1>Willkommen {$name}! (Sprache: {$lang})</h1>");
    }

    // #[Route('/default/test', name: 'app_test')]
    // public function test(EntityManagerInterface $em): Response
    // {
    // 1. Добавление записи в базу данных
    // $user = new User();
    // $user->setFirstName('Daniel');
    // $user->setLastName('Niessen');
    // $user->setSex('männlich');
    // $user->setBirthday(new \DateTime('2003-07-31'));
    // $em->persist($user);
    // $em->flush();

    // 2. Изменение записи в базе данных
    // $user = $em->getRepository(User::class)->find(4);
    // $user->setFirstName('Evgeniia');
    // $em->flush();

    // 3. Удаление записи из базы данных
    // $user = $em->getRepository(User::class)->find(6);
    // $em->remove($user);
    // $em->flush();
    // dd($user);
    // exit('OK!');
    // }

    #[Route('default/test1', name: 'app_test1')]
    public function test1(): Response
    {
        return $this->render('default/test1.html.twig');
    }

    #[Route('default/test2', name: 'app_test2')]
    public function test2(): Response
    {
        return $this->render('default/test2.html.twig');
    }
}
