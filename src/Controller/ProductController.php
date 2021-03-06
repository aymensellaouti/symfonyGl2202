<?php

namespace App\Controller;

use App\Entity\Product;
use App\Events\AddProductEvents;
use App\Form\ProductType;
use App\Service\Helper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    public function __construct(private EventDispatcherInterface $dispatcher)
    {}

    /**
     * @Route("/list/{page<\d+>?1}/{number<\d+>?6}", name="product.list")
     */
    public function index($page, $number): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Product');
        $user = $this->getUser();
        $conditions = [];
        if($user && !in_array('ROLE_ADMIN',$user->getRoles())) {
            $conditions = ['user' => $user];
        }
        $products = $repository->findBy($conditions, ['price'=> 'asc'],$number, ($page - 1) * $number);
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/add/{product?0}", name="product.add")
     */
    public function addProduct(
        EntityManagerInterface $manager,
        Request  $request,
        Product $product = null,
        LoggerInterface $logger,
        Helper $helper
    ) {
        $helper->sayHello();
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $logger->info('Access to AddProduct Action');
        $operation = 'update';
        if(!$product) {
            $product = new Product();
            $operation = 'add';
        } else {
            if ($user && $product->getUser()->getId() != $user->getId()) {
                return $this->redirectToRoute('product.list');
            }
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
          $product->setUser($user);
          $manager->persist($product);
          $manager->flush();
          if($operation == 'add') {
              $addProductEvent = new AddProductEvents($product);
              $this->dispatcher->
              dispatch($addProductEvent, AddProductEvents::ADD_PRODUCT_EVENT);
          }
          $this->addFlash('success', "le produit ".$product->getName()." a ??t?? ajout?? avec succ??s");
          return $this->redirectToRoute('product.list');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

//    /**
//     * @Route("/update/{product}/{name}/{description}/{price<\d+>}", name="product.update")
//     */
//    public function updateProduct(Product $product = null, $name, $description, $price, EntityManagerInterface $manager) {
//        if ($product) {
//            $product->setName($name);
//            $product->setDescription($description);
//            $product->setPrice($price);
//
//            $manager->persist($product);
////        $manager->persist($product2);
//            $manager->flush();
//        }
//
//        return $this->render('product/detail.html.twig', [
//            'product' => $product
//        ]);
//    }
    /**
     * @Route("/delete/{product}", name="product.delete")
     */
    public function deleteProduct(Product $product = null, EntityManagerInterface $manager) {

        if ($product) {
            $productName = $product->getName();
            $manager->remove($product);
//        $manager->persist($product2);
            $manager->flush();
            $this->addFlash('success', "le produit $productName  a ??t?? supprim?? avec succ??s");
        } else {
            $this->addFlash('error', "le produit  est innexistant");
        }

        return $this->redirectToRoute('product.list');
    }
    /**
     * @Route("/product/{product}", name="product.detail")
     */
    public function detailProduct(Product $product = null) {
        return $this->render('product/detail.html.twig', [
            'product' => $product
        ]);
    }

    }
