<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends Controller
{
    /**
     * @return Article[]
     */
    public function getArticlesAction()
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findAll();

        return $articles;
    }

    /**
     * ....
     * @return Article
     */
    public function getArticleAction(Article $article)
    {
        return $article;
    }

    /**
     * @return View
     */
    public function postArticlesAction(Request $request)
    {
        $article = new Article();

        $errors = $this->treatAndValidateRequest($article, $request);

        if (count($errors) > 0) {
            return new View(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->persistAndFlush($article);

        return new View($article, Response::HTTP_CREATED);
    }

    /**
     *
     */
    public function putArticleAction(Article $article, Request $request)
    {
        $id = $article->getId();
        $article = new Article();
        $article->setId($id);

        $errors = $this->treatAndValidateRequest($article, $request);

        if (count($errors) > 0) {
            return new View(
                $errors,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->persistAndFlush($article);

        return "";
    }

    /**
     * @return array
     */
    private function treatAndValidateRequest(Article $article, Request $request)
    {
        $form = $this->createForm(
            new ArticleType(),
            $article,
            array(
                'method' => $request->getMethod()
            )
        );

        $form->handleRequest($request);

        $errors = $this->get('validator')->validate($article);
        return $errors;
    }

    private function persistAndFlush(Article $article)
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();
    }
}
