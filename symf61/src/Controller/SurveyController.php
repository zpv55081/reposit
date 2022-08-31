<?php

namespace App\Controller;

use App\Entity\Reply;
use App\Repository\QuestionRepository;
use App\Repository\ReplyRepository;
use App\Repository\VariantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class SurveyController extends AbstractController
{
    #[Route('/survey', name: 'app_survey')]
    public function index(
        Environment $twig,
        QuestionRepository $questionRepository,
        VariantRepository $variantRepository,
    ): Response {

        // для простого использования преобразовываем вопросы в ассоциативный массив
        foreach ($questionRepository->findAll() as $question) {
            $questions[$question->getId()] = $question->getText();
        }

        return new Response($twig->render('survey/index.html.twig', [
            'questions' => $questions,
            // варианты ответов на первый вопрос
            'variants_for_1' => $variantRepository->findBy(['question_id' => '1']),
            // варианты ответов на второй вопрос
            'variants_for_2' => $variantRepository->findBy(['question_id' => '2'])
        ]));
    }
    
    #[Route('/survey/interviewed', name: 'app_survey_interviewed')]
    public function handleReply(
        Request $request,
        ManagerRegistry $doctrine,
        MailerInterface $mailer,
        Environment $twig,
    ): Response {

        $entityManager = $doctrine->getManager();

        // сохраняем выбранный вариант ответа на первый вопрос   
        $reply = new Reply;
        $reply->setUserIpv4($_SERVER['REMOTE_ADDR']);
        $reply->setQuestionId(1);
        $reply->setVariantId($request->request->all()['q1_variant']);
        $entityManager->persist($reply);
        $entityManager->flush();
        
        // если есть ответ на второй вопрос, то сохраняем выбранные варианты
        if (isset($request->request->all()['q2_variant'])) {
            foreach ($request->request->all()['q2_variant'] as $q2_variant_id) {
                $reply = new Reply;
                $reply->setUserIpv4($_SERVER['REMOTE_ADDR']);
                $reply->setQuestionId(2);
                $reply->setVariantId($q2_variant_id);
                $entityManager->persist($reply);
                $entityManager->flush();
            }
        }

        $email = (new TemplatedEmail())
            ->subject('Survey report')
            ->htmlTemplate('survey/report.html.twig')
            ->context([
                'report_array' => ReplyRepository::getReport($doctrine),
                'headline' => 'Проведено новое интервью'
            ]);
        $mailer->send($email);
        
        return new Response($twig->render('survey/report.html.twig', [
            'report_array' => ReplyRepository::getReport($doctrine),
            'headline' => 'Благодарим за участие в опросе!'
        ]));
    }
}
