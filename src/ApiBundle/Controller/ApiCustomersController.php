<?php

namespace ApiBundle\Controller;

//use ApiBundle\Entity\Customers;
use ApiBundle\Entity\Customers;
use CoreBundle\Form\Type\CustomersType;
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

/**
 * Class ApiCustomersController
 * @package ApiBundle\Controller
 *
 * @RouteResource("api/customers")
 */
class ApiCustomersController extends FOSRestController implements ClassResourceInterface
{


    /**
     * Gets an individual Customers
     *
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @ApiDoc(
     *     output="ApiBundle\Entity\Customers",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     *
     * )
     */
    public function getAction($id)
    {

        $customer = $this->getDoctrine()->getRepository('ApiBundle:Customers')->find($id);

        if ($customer === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        return $customer;
    }


    /**
     * Gets a collection of Customers
     *
     * @return array
     *
     * @ApiDoc(
     *     output="ApiBundle\Entity\Customers",
     *     statusCodes={
     *         200 = "Returned when successful",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function cgetAction()
    {
        return  $this->getDoctrine()->getRepository('ApiBundle:Customers')->findAll();
    }

    /**
     * @param Request $request
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="ApiBundle\Form\Type\CustomersType",
     *     output="ApiBundle\Entity\Customers",
     *     statusCodes={
     *         201 = "Returned when a new Products has been successful created",
     *         404 = "Return when not found"
     *     }
     * )
     */
    public function postAction(Request $request)
    {

        $form = $this->createForm(CustomersType::class, null, [
            'csrf_protection' => false,
        ]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        /**
         * var $customers Customers
         */
        $customers = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($customers);
        $em->flush();

        $routeOptions = [
            'id' => $customers->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('cget_customers', $routeOptions, Response::HTTP_CREATED);
    }


    /**
     * @param Request $request
     * @param int     $id
     * @return View|\Symfony\Component\Form\Form
     *
     * @ApiDoc(
     *     input="ApiBundle\Form\Type\CustomersType",
     *     output="ApiBundle\Entity\Customers",
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
         * @var $customers Customers
         */
        $customers = $this->getDoctrine()->getRepository('ApiBundle:Customers')->find($id);
        if ($customers === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ProductsType::class, $customers, [
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
            'id' => $customers->getId(),
            '_format' => $request->get('_format'),
        ];

        return $this->routeRedirectView('get_customers', $routeOptions, Response::HTTP_CREATED);

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
         * @var $customer Customers
         */
        $customer = $this->getDoctrine()->getRepository('ApiBundle:Customers')->find($id);

        if ($customer === null) {
            return new View(null, Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        $customer->setStatus("deleted");
        $customer->setDeletedAt(new \DateTime());
        $em->persist($customer);
        $em->flush();

        return new View(null, Response::HTTP_CREATED);
    }



}
