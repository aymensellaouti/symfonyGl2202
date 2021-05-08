<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/list/{page<\d+>?1}/{number<\d+>?6}", name="product.list")
     */
    public function index($page, $number): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Product');
        $products = $repository->findBy([], ['price'=> 'asc'],$number, ($page - 1) * $number);
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/add/{name}/{description}/{price<\d+>}", name="product.add")
     */
    public function addProduct($name, $description, $price, EntityManagerInterface $manager) {
        echo 'in add';
//        $manager = $this->getDoctrine()->getManager();
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice($price);
//
//        $product2 = new Product();
//        $product2->setName($name);
//        $product2->setDescription($description);
//        $product2->setPrice($price);

        $manager->persist($product);
//        $manager->persist($product2);
        $manager->flush();

        return $this->render('product/detail.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/update/{product}/{name}/{description}/{price<\d+>}", name="product.update")
     */
    public function updateProduct(Product $product = null, $name, $description, $price, EntityManagerInterface $manager) {
        if ($product) {
            $product->setName($name);
            $product->setDescription($description);
            $product->setPrice($price);

            $manager->persist($product);
//        $manager->persist($product2);
            $manager->flush();
        }

        return $this->render('product/detail.html.twig', [
            'product' => $product
        ]);
    }
    /**
     * @Route("/delete/{product}", name="product.delete")
     */
    public function deleteProduct(Product $product = null, EntityManagerInterface $manager) {

        if ($product) {
            $productName = $product->getName();
            $manager->remove($product);
//        $manager->persist($product2);
            $manager->flush();
            $this->addFlash('success', "le produit $productName  a été supprimé avec succès");
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
