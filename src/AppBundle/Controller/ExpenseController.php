<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Expense controller.
 *
 * @Route("expense")
 */
class ExpenseController extends Controller
{
    /**
     * Lists all expense entities.
     *
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="expense_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $expenses = $em->getRepository('AppBundle:Expense')->findBy([
            'userId' => $this->getUser()->getId()
        ]);

        $catRepo = $this->getDoctrine()->getRepository('AppBundle:Category');
        foreach ($expenses as $expense) {
            $expense->getCategoryId();
            $category = $catRepo->find($expense->getCategoryId());
            $expense->setCategory($category);
        }

        return $this->render('expense/index.html.twig', array(
            'expenses' => $expenses,
        ));
    }

    /**
     * Creates a new expense entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="expense_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $expense = new Expense();
        $form = $this->createForm(
            'AppBundle\Form\ExpenseType',
            $expense
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $expense->setUserId($this->getUser()->getId());
            $em->persist($expense);
            $em->flush();

            return $this->redirectToRoute('expense_show', array('id' => $expense->getId()));
        }

        return $this->render('expense/new.html.twig', array(
            'expense' => $expense,
            'form' => $form->createView(),
            'type' => 'Expense'
        ));
    }

    /**
     * Finds and displays a expense entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="expense_show")
     * @Method("GET")
     */
    public function showAction(Expense $expense)
    {
        $deleteForm = $this->createDeleteForm($expense);

        return $this->render('expense/show.html.twig', array(
            'expense' => $expense,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing expense entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}/edit", name="expense_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Expense $expense)
    {
        $deleteForm = $this->createDeleteForm($expense);
        $editForm = $this->createForm('AppBundle\Form\ExpenseType', $expense);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $expense->setUserId($this->getUser()->getId());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('expense_edit', array('id' => $expense->getId()));
        }

        return $this->render('expense/edit.html.twig', array(
            'expense' => $expense,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a expense entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/{id}", name="expense_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Expense $expense)
    {
        $form = $this->createDeleteForm($expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($expense);
            $em->flush();
        }

        return $this->redirectToRoute('expense_index');
    }

    /**
     * Creates a form to delete a expense entity.
     *
     * @param Expense $expense The expense entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Expense $expense)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('expense_delete', array('id' => $expense->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Creates a new expense entity.
     * @Security("has_role('ROLE_USER')")
     * @Route("/income/new", name="income_new")
     * @Method({"GET", "POST"})
     */
    public function newIncomeAction(Request $request)
    {
        $expense = new Expense();
        $form = $this->createForm(
            'AppBundle\Form\IncomeType',
            $expense
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $expense->setUserId($this->getUser()->getId());
            $em->persist($expense);
            $em->flush();

            return $this->redirectToRoute('expense_show', array('id' => $expense->getId()));
        }

        return $this->render('expense/new.html.twig', array(
            'expense' => $expense,
            'form' => $form->createView(),
            'type' => 'Income'
        ));
    }
}
