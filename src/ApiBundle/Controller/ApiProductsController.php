<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Products;
use CoreBundle\Form\Type\ProductsType;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\RouteRedirectView;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ApiProductsController
 * @package ApiBundle\Controller
 *
 * @RouteResource("api/products")
 */
class ApiProductsController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Gets an individual Products
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="ApiBundle\Entity\Products",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function getAction($id)
    {

        $products = $this->getDoctrine()->getRepository('ApiBundle:Products')->findOneBy(array('id' => $id));

        if ($products === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        return $products;
    }



    /**
     * Gets a collection of Products
     *
     * @return array
     *
     * @ApiDoc(
     *     output="ApiBundle\Entity\Products",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        return  $this->getDoctrine()->getRepository('ApiBundle:Products')->findAll();
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="ApiBundle\Form\Type\ProductsType",
     *     output="ApiBundle\Entity\Products",
     *     statusCodes={
     *         201 = "Returned when a new Products has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {

        $form = $this->createForm(ProductsType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        /**
         * var  $products Products
         */
        $products = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($products);
        $em->flush();

        $routeOptions = [
            'id' => $products->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('cget_products', $routeOptions, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="ApiBundle\Form\Type\ProductsType",
     *     output="ApiBundle\Entity\Products",
     *     statusCodes={
     *         204 = "Returned when an existing Products has been successful updated",
     *         400 = "Return when errors",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function putAction(Request $request, $id)
    {

        /**
         * @var $products Products
         */
        $products = $this->getDoctrine()->getRepository('ApiBundle:Products')->find($id);
        if ($products === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ProductsType::class, $products, [
            'csrf_protection' => false,
        ]);
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            return $form;
        }
        $task = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();
        $routeOptions = [
            'id' => $products->getId(),
            '_format' => $request->get('_format'),
        ];

        return "modify with Success!";
//        return $this->routeRedirectView('get_products', $routeOptions, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @return View
     *
     * @ApiDoc(
     *     statusCodes={
     *         204 = "Returned when an existing Products has been successful deleted",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function deleteAction($id)
    {
        /**
         * @var $products Products
         */
        $products = $this->getDoctrine()->getRepository('ApiBundle:Products')->find($id);

        if ($products === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
//        $em->remove($products);
        $products->setStatus("deleted");
        $products->setDeletedAt(new \DateTime());
        $em->persist($products);
        $em->flush();

        return new View(null, Response::HTTP_NO_CONTENT);
    }



}
