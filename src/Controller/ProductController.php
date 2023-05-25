<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProductController extends AbstractController
{
     /**
 * @Route("/product/new", name="new_product")
 * Method({"GET", "POST"})
 */
    // #[Route('/product/new', name: 'new_product',Method : 'GET','POST')]
        public function new(Request $request){
            $product = new Product();
            $form = $this->createForm(ProductType::class,$product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_list');
        }
        return $this->render('products/new.html.twig',['form' => $form->createView()]);
    }

    /**
 *@Route("/",name="product_list")
 */
// #[Route('/', name: 'product_list',Method : 'GET','POST')]
public function home(Request $request)
{
    $propertySearch = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class,$propertySearch);
    $form->handleRequest($request);
    $products= [];
    if($form->isSubmitted() && $form->isValid()) {
            $nom = $propertySearch->getNom();
        if ($nom!="")
            $products= $this->getDoctrine()->getRepository(Product::class)->findBy(['nom' => $nom] );
        else
            $products= $this->getDoctrine()->getRepository(Product::class)->findAll();
    }
    return $this->render('products/index.html.twig',[ 'form' =>$form->createView(), 'products' => $products]);
 }



/**
 * @Route("/product/{id}", name="product_show")
*/
// #[Route('/product/{id}', name: 'product_show',Method : 'GET','POST')]
public function show($id) {
    $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
    return $this->render('products/show.html.twig',array('product' => $product));
    }
}
