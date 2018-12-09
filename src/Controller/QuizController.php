<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use App\Entity\Quizz;
use App\Entity\Question;
use App\Entity\Reponse;
use Symfony\Component\HttpFoundation\Session\Session;

class QuizController extends AbstractController
{

    /**
     * @Route("/quiz", name="quiz")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);

        $categories = $repo->findAll();
        // dump($categories);
        
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'QuizController',
            'categories' => $categories
        ]);

    }

    /**
     * @Route("/quiz/{id}", name="quizshow")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Quizz::class);
        $quiz = $repo->findBy(['categorie' => $id]);
        // dump($quiz);

        return $this->render('quiz/show_quiz.html.twig', [
            'controller_name' => 'QuizController',
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("{id}/question/{idQuestion}", name="question")
    */
    public function question($id, $idQuestion)
    {
        $session = new Session(); 

        $quiz = $this->getDoctrine()->getRepository(Quizz::class)->find($id);
        $firstQuestion = $quiz->getReponseId()->first()->getId();
        $lastQuestion = $quiz->getReponseId()->last()->getId();

        // dump($firstQuestion,$lastQuestion);
        if(intval($firstQuestion) === intval($idQuestion))
        {
            $result = [];
            $session->set('key', $result);

        }
        elseif(intval($idQuestion) > intval($firstQuestion))
        {
            $val = $session->get('key');
            foreach($_POST as $key => $value)
            {
                $val[$key] = $value;
                $session->set('key', $val);
                // dump($_POST);
            }

        }

        if(intval($lastQuestion) < intval($idQuestion))
        {
            //Remove cookie
           return $this->redirectToRoute('finquiz');
        }



        $question = $this->getDoctrine()->getRepository(Question::class)->find($idQuestion);

        //SEt cookie
       // $question = $this->getDoctrine()->getRepository(Question::class)->findBy(['quizz' => $id]);
        // $q = [];

        // foreach ($question as $key => $value) {
        //     $q[$key]['question'] = $value;

        //     $q[$key]['reponses'] = $this->getDoctrine()
        //         ->getRepository(Reponse::class)
        //         ->findBy(['question' => $value->getId()]);

        // }

        // $key = $idQuestion;
        // // dump($q[$key]);

        // if($idQuestion > 9)
        // {
        //     return $this->redirectToRoute('finquiz');
        // }

        // return $this->render('quiz/question.html.twig', [
        //     'controller_name' => 'QuizController',
        //     'question' => $q[$key],
        //     'key' => $key,
        //     'id' => $id
        // ]);

        return $this->render('quiz/question.html.twig',[
            'question' => $question,
            'nextQuestion' => $idQuestion + 1,
            'id' => $id
        ]);
    }

    /**
     * @Route("/question/finquiz", name="finquiz")
    */
    public function finquiz()
    {
        $session = new Session(); 
        $a = $session->get('key');



        // dump($session->get('key'));
        $res = [];
        $score = 0;

        foreach($a as $key => $value)
        {
            $id = $value;
            $goodrep = $this->getDoctrine()->getRepository(Reponse::class)->find($id)->getReponseException();
            $res[$key][$value] = $goodrep;
        }


        // $test = ['abc', true, true,true, 'bca'];
        // dump($res[$key]);
        // dump($test);

        foreach($res as $key => $value)
        {
            foreach($value as $key => $value)
            {
                if($value === true )
                {
                    $score ++;
                }
            }

        }

        // dump($score);

        return $this->render('quiz/finquiz.html.twig', [
            'score' => $score,
        ]);
    }

}
