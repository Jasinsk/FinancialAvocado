<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Expense;
use AppBundle\Model\Filter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ReportController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/tranche")
     */
    public function listResultsAction(Request $request)
    {
        $filter = new Filter();
        $form = $this->createForm('AppBundle\\Form\FilterFormType', $filter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenses = $this->getFilteredExpenses($filter);
            $shouldExport = $form->get('export')->isClicked();
        }

        $em = $this->getDoctrine()->getManager();

        $expenses = isset($expenses) ? $expenses : $em->getRepository('AppBundle:Expense')->findBy([
            'userId' => $this->getUser()->getId()
        ]);

        $catRepo = $this->getDoctrine()->getRepository('AppBundle:Category');
        foreach ($expenses as $expense) {
            $expense->getCategoryId();
            $category = $catRepo->find($expense->getCategoryId());
            $expense->setCategory($category);
        }

        if (isset($shouldExport) && $shouldExport) {
            return $this->export($expenses);
        }

        return $this->render('AppBundle:Report:list_results.html.twig', array(
            'form' => $form->createView(),
            'expenses' => $expenses
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @param $filter
     * @return Expense[]
     */
    private function getFilteredExpenses($filter)
    {
        $expenseRepo = $this->getDoctrine()->getRepository('AppBundle:Expense');
        $expenses = $expenseRepo->getFilteredExpenses($filter, $this->getUser());

        return $expenses;
    }

    /**
     * @param Expense[] $expenses
     * @Security("has_role('ROLE_USER')")
     * @return Response
     */
    private function export($expenses)
    {
        $rows = array();
        foreach ($expenses as $expense) {
            $data = [
                $expense->getTitle(),
                $expense->getAmount(),
                $expense->getCategory()->getName(),
                $expense->getPriority(),
                $expense->getDate()->format('Y-m-d H:i:s')
            ];

            $rows[] = implode(',', $data);
        }

        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=file.csv');

        return $response;
    }
}
