<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Department;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/",name="_index")
     */

    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/init",name="-init")
     */
    public function init(SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $data = $this->getParameter('app.folder.data');
        $regions = Yaml::parseFile($data . "/regions.yaml");
        $deps = Yaml::parseFile($data . "/departments.yaml");

        $regionsRepository = $this->getDoctrine()->getRepository(Region::class);
        $depRepository = $this->getDoctrine()->getRepository(Department::class);

        if ($regionsRepository->findAll() == null) {
            foreach ($regions as $region) {
                $r = new Region();
                $r->setName($region['name']);
                $r->setSlug(strtolower($slugger->slug($region['name'])));
                $em->persist($r);
                $em->flush();
            }
        }
        if ($depRepository->findAll() == null) {
            foreach ($deps as $dep) {
                $d = new Department();
                $d->setName($dep['name']);
                $d->setSlug(strtolower($slugger->slug($dep['name'])));
                $d->setCode($dep['code']);
                $d->setRegion($regionsRepository->find($dep['region_id']));
                $em->persist($d);
                $em->flush();
            }

        }
        return $this->redirectToRoute('home_index');
    }

}
