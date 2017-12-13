<?php

namespace CoreBundle\Controller;

use ApiBundle\Entity\Customers;
use ApiBundle\Entity\Products;
use CoreBundle\Form\Type\CustomersType;
use CoreBundle\Form\Type\ProductsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/add-products", name="addProducts")
     */
    public function addProductsAction(Request $request)
    {

        $products = new Products();

        $form = $this->createForm(ProductsType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'success!');

            return $this->render('@Api/Products/products.html.twig',array(
                'form' => $form->createView(),
            ));
        }

        return $this->render('@Api/Products/products.html.twig',array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/add-customers", name="addCustomers")
     */
    public function addCustomersAction(Request $request)
    {

        $customers = new Customers();

        $form = $this->createForm(CustomersType::class, $customers);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em =  $this->getDoctrine()->getManager();
            $task = $form->getData();
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'success!');

            return $this->render('ApiBundle:Customers:customers.html.twig',array(
                'form' => $form->createView(),
            ));
        }



        return $this->render('@Api/Customers/customers.html.twig',array(
            'form' => $form->createView(),
        ));
    }


}
