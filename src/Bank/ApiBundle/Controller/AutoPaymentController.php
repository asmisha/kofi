<?php

namespace Bank\ApiBundle\Controller;

use Bank\ApiBundle\Controller\BaseController;
use Bank\ApiBundle\Services\Api;
use Bank\MainBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Bank\MainBundle\Entity\AutoPayment;
use Bank\MainBundle\Form\AutoPaymentType;

/**
 * AutoPayment controller.
 *
 */
class AutoPaymentController extends BaseController
{

    /**
     * Lists all AutoPayment entities.
     *
     */
    public function listAction()
    {
		/** @var Account $account */
		$account = $this->get('api')->getAccount();

        $em = $this->getDoctrine()->getManager();

		$data = $em->getRepository('BankMainBundle:AutoPayment')->createQueryBuilder('ap')
			->where('ap.account = :account')
			->setParameter('account', $account)
			->getQuery()
			->getArrayResult()
		;

		foreach($data as $k=>$i){
			$data[$k]['startDate'] = $i['startDate'] ? $i['startDate']->getTimestamp() : null;
			$data[$k]['lastPayment'] = $i['lastPayment'] ? $i['lastPayment']->getTimestamp() : null;
		}

        return $this->view($data);
    }
    /**
     * Creates a new AutoPayment entity or updates existing one.
     *
     */
    public function updateAction(Request $request, $id)
    {
		if($id){
			$entity = $this->getDoctrine()->getRepository('BankMainBundle:AutoPayment')->find($id);

			if(!$entity){
				throw new \Exception(sprintf('AutoPayment with id %s not found', $id));
			}
		}else{
        	$entity = new AutoPayment();
		}

		/** @var Account $account */
		$account = $this->get('api')->getAccount();
		$entity->setAccount($account);

		$fields = $request->request->all();
		unset($fields[Api::CLIENT_ID_KEY]);
		unset($fields[Api::ACCOUNT_ID_KEY]);

        $form = $this->createCreateForm($entity);
        $form->submit($fields, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->view(array('success' => true, 'id' => $entity->getId()));
        }

        return $this->view(array(
			'success' => false,
			'errors' => $this->getFormErrors($form)
		));
    }

    /**
     * Creates a form to create a AutoPayment entity.
     *
     * @param AutoPayment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AutoPayment $entity)
    {
        $form = $this->createForm(new AutoPaymentType(), $entity, array(
            'action' => $this->generateUrl('autopayment_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
    * Creates a form to edit a AutoPayment entity.
    *
    * @param AutoPayment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AutoPayment $entity)
    {
        $form = $this->createForm(new AutoPaymentType(), $entity, array(
            'action' => $this->generateUrl('autopayment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('BankMainBundle:AutoPayment')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find AutoPayment entity.');
		}

		$em->remove($entity);
		$em->flush();

        return $this->view(array('success' => true));
    }
}
