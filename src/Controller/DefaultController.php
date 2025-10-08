<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use App\Form\FeedbackForm;
use App\Form\RegistrationType;
use App\Repository\PostRepository;
use App\Service\ExportInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(Request $request, SessionInterface $session, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {
        $list = $em->getRepository(Post::class)->getPostListQuery();
        $posts = $paginator->paginate($list, max(0, $request->get('page', 1)), 3);

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

            $message = (new Email())
            ->from('nissensy@gmail.com')            // отправитель = ваш Gmail
            ->to('nissensy@gmail.com')           // получатель
            ->subject('Feedback form: ['.$feedback->getSubject().']')
            ->replyTo($feedback->getEmail() ?: 'nissensy@gmail.com')
            ->text('Plain text body')
            ->html($this->renderView('mail/feedback.html.twig', [
                'name' => $feedback->getName(),
                'message' => $feedback->getMessage(),
                'contact' => $feedback->getEmail(),
            ]));

            try {
                $mailer->send($message);
                // ok
            } catch (TransportExceptionInterface $e) {
                throw $e;
            }

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

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $user->setLoginCnt(0);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Registration successful! You can now log in.');

            // do anything else you need here, like send an email

            return $this->redirectToRoute('login');
        }

        return $this->render('default/register.html.twig', [
            'form' => $form,
        ]);
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
