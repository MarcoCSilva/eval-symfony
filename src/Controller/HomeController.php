<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Department;
use App\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/",name="index")
     */

    public function index(): Response
    {
        $regions = $this->getDoctrine()->getRepository(Region::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'regions'=>$regions
        ]);
    }

    /**
     * @Route("/department/{slug}",name="list_department")
     */
    public function listDepartment(Department $department)
    {
        $cities = $department->getCities();
        return $this->render('home/cities.html.twig',[
            'cities'=> $cities
        ]);
}

}
