<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class Paginator
{

    private $entityClass;

    private $limit = 10;

    private $currentPage = 1;

    private $manager;

    private $twig;

    private $route;

    private $templatePath;

    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $requestStack, $templatePath)
    {

        $this->manager = $manager;
        $this->twig = $twig;
        $this->route = $requestStack->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    /**
     * @return mixed
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * @param mixed $templatePath
     * @return Paginator
     */
    public function setTemplatePath($templatePath)
    {
        $this->templatePath = $templatePath;
        return $this;
    }


    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->getRoute()
        ]);
    }

    /**
     * @return object[]
     * @throws \Exception
     */
    public function getData()
    {

        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifier l'entité sur laquelle nous devons paginer !
            Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }

        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        //2) Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        //3) Renvoyer les éléments en question
        return $data;
    }

    /**
     * @return float
     * @throws \Exception
     */
    public function getPages()
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifier l'entité sur laquelle nous devons paginer !
            Utilisez la méthode setEntityClass() de votre objet Paginator !");
        }

        //1) Connaître le total des enregistrements de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        //2) Faire la division, l'arrondi et le renvoyer
        $pages = ceil($total / $this->limit);

        return $pages;
    }


    /**
     * @param mixed $entityClass
     * @return Paginator
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param int $limit
     * @return Paginator
     */
    public function setLimit(int $limit): Paginator
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $currentPage
     * @return Paginator
     */
    public function setCurrentPage(int $currentPage): Paginator
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $route
     * @return Paginator
     */
    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

}