<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\FeedbackForm;
use App\Repository\PostRepository;
use App\Service\ExportInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $posts = $em->getRepository(Post::class)->findAll();

        $session->set('name', 'Symfony');

        return $this->render('default/homepage.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(SessionInterface $session): Response
    {
        $name = $session->get('name', 'Guest');

        return $this->render('default/about.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(SessionInterface $session, Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        $session->remove('name');
        $form = $this->createForm(FeedbackForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback = $form->getData();

            $em->persist($feedback);
            $em->flush();

            $message = new Email();
            $message->from('user@example.com');
            $message->to('admin@example.com');
            $message->text('You have a new feedback message from '.$feedback->getName().', email: '.$feedback->getEmail().'. Message: '.$feedback->getMessage());
            $message->html('<strong>New feedback message</strong>');
            $message->subject('New feedback message from '.$feedback->getName());

            $mailer->send($message);

            $this->addFlash('success', 'Your message has been successfully sent. Thanks for your feedback!');

            return $this->redirectToRoute('contact');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function categoriesWidget(EntityManagerInterface $em): Response
    {
        $list = $em->getRepository(Category::class)->getPopularList();

        return $this->render('default/widget/categories.html.twig', [
            'list' => $list,
        ]);
    }

    public function popularPostWidget(): Response
    {
        return $this->render('default/widget/popularPost.html.twig');
    }

    #[Route('/export', name: 'export')]
    public function ExportAction(ExportInterface $exporter, PostRepository $postRepository)
    {
        $list = $postRepository->getAllItems();
        $file = $exporter->export($list);

        $ressponse = new BinaryFileResponse($file);
        $ressponse->headers->set('Content-type', $exporter->getFileType());
        $ressponse->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $exporter->getFileName());

        return $ressponse;
    }

    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('default/login.html.twig', [
            'last_username' => '',   // принудительно пусто
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Этот метод не выполняется: запрос перехватывает firewall.
        // Можно оставить пустым или бросить исключение:
        throw new \LogicException('Logout is handled by the firewall.');
    }

    /* #[Route('/test', name: 'test')]
    public function test(EntityManagerInterface $em): Response
    { */
    // Добавление нового пользователя
    // $post = new Post();
    /* $post->setName('Beautiful Day With Friends In Paris');
    $post->setContent("<div>
<h2>What is Lorem Ipsum?</h2>
<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
</div>");
    $post->setPublischedAt(new \DateTimeImmutable('2025-01-07 10:00:00'));

    $em->persist($post);
    $em->flush(); */

    // Изменение пользователя
    /*  $post = $em->getRepository(Post::class)->find(1);
     $post->setContent("<div>
<h2>What is Lorem Ipsum?</h2>
<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
</div>
<div>
<h2>Why do we use it?</h2>
<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
</div>");
     $em->flush(); */

    // Удаление пользователя
    /*     $user = $em->getRepository(User::class)->find(7);
        $em->remove($user);
        $em->flush();

        dd($post);

        exit('Test created new User');
    } */
}
