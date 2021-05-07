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
     * @Route("/", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', []);
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
        $isDeleted = false;
        if ($product) {
            $manager->remove($product);
//        $manager->persist($product2);
            $manager->flush();
            $isDeleted = true;
        }
        $product = null;
        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'isDeleted' => $isDeleted
        ]);
    }
    }
