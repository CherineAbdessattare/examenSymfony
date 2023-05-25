<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ProductController extends AbstractController{
 /**
 * @Route("/product/new", name="new_product")
 * Method({"GET", "POST"})
 */
public function new(Request $request) {
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
public function home(Request $request)
{
    $propertySearch = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class,$propertySearch);
    $form->handleRequest($request);
    //initialement le tableau des articles est vide,
    //c.a.d on affiche les articles que lorsque l'utilisateur
    //clique sur le bouton rechercher
    $products= [];
    if($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire
            $nom = $propertySearch->getNom();
        if ($nom!="")
            //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
            $products= $this->getDoctrine()->getRepository(Product::class)->findBy(['nom' => $nom] );
        else
            //si si aucun nom n'est fourni on affiche tous les articles
            $products= $this->getDoctrine()->getRepository(Product::class)->findAll();
    }
    return $this->render('products/index.html.twig',[ 'form' =>$form->createView(), 'products' => $products]);
 }



/**
 * @Route("/product/{id}", name="product_show")
*/
public function show($id) {
    $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
    return $this->render('products/show.html.twig',array('product' => $product));
    }}
    ?>
