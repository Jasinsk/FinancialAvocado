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

//todo zarejestrowac formy jako serwisy i przekazywac EM w konstruktorze
class IncomeType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * IncomeType constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
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
        $repo = $this->em->getRepository('AppBundle:Category');
        $cat = $repo->findBy([
            'name' => 'Zarobki'
        ])[0];

        $res = [
            $cat->getId() => $cat->getName()
        ];

        return $res;
    }
}
