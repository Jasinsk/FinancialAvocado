<?php

namespace AppBundle\Form;

use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ExpenseType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TokenStorage
     */
    private $ts;

    /**
     * ExpenseType constructor.
     * @param EntityManager $entityManager
     * @param TokenStorage $tokenStorage
     */
    public function __construct(EntityManager $entityManager, TokenStorage $tokenStorage)
    {
        $this->em = $entityManager;
        $this->ts = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('amount')
            ->add('priority')
            ->add('date', DateType::class)
            ->add('categoryId', ChoiceType::class,
                [
                    'choices' => $this->getCategories()
                ]
            );
        $builder
            ->get('amount')
            ->addModelTransformer(new CallbackTransformer(
                function ($positive) {
                    return $positive * -1;
                },
                function ($negative) {
                    return $negative * -1;
                }
            ));

        $builder->get('date')->addModelTransformer(new CallbackTransformer(
            function ($value) {
                if(!$value) {
                    return new \DateTime();
                }
                return $value;
            },
            function ($value) {
                return $value;
            }
        ));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Expense'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_expense';
    }

    /**
     * @param EntityManager
     * @return mixed
     */
    public function getCategories()
    {
        $user = $this->ts->getToken()->getUser();
        $repo = $this->em->getRepository('AppBundle:Category');
        $opts = $repo->getChoiceOptions($user);

        $res = [];
        foreach ($opts as $opt) {
            $res[$opt['id']] = $opt['name'];
        }

        return $res;
    }
}
