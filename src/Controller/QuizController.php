<?php
namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizTForm;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    /**
     * @Route("/quiz", name="quiz_list")
     */
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }
    /**
     * @Route("/quiz/list", name="quiz_list_all")
     */
    public function list(QuizRepository $quizRepo): Response
    {
        // Récupère tous les Quiz
        $quizzes = $quizRepo->findAll();

        // Rend le template templates/quiz/list.html.twig
        return $this->render('quiz/list.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * @Route("/quiz/{id}", name="quiz_show", requirements={"id"="\d+"})
     */
    public function show(int $id, QuizRepository $quizRepo): Response
    {
        // Récupère un seul quiz ou 404
        $quiz = $quizRepo->find($id);
        if (!$quiz) {
            throw $this->createNotFoundException("Quiz #{$id} introuvable.");
        }

        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/quiz/new", name="quiz_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizTForm::class, $quiz);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($quiz);
            $em->flush();

            // message flash et redirection
            $this->addFlash('success', 'Quiz créé avec succès !');
            return $this->redirectToRoute('quiz_list_all');
        }

        return $this->render('quiz/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
