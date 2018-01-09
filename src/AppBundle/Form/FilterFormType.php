<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

//todo zarejestrowac formy jako serwisy i przekazywac EM w konstruktorze
class FilterFormType extends AbstractType
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
     * FilterFormType constructor.
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
            ->add('name')
            ->add('dateFrom', DateType::class)
            ->add('dateTo', DateType::class)
            ->add('priority', IntegerType::class, [
                'required' => false
            ])
            ->add('categoryId', ChoiceType::class,
                [
                    'choices' => $this->getCategories()
                ]
            )
            ->add('sortBy', ChoiceType::class,
                [
                    'choices' => $this->getSortOptions()
                ])
        ->add('filter', SubmitType::class, array('label' => 'Filter'))
        ->add('export', SubmitType::class, array('label' => 'Export'));

        $builder->get('dateFrom')->addModelTransformer(new CallbackTransformer(
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

        $builder->get('dateTo')->addModelTransformer(new CallbackTransformer(
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
            'data_class' => 'AppBundle\Model\Filter'
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

        $res[''] = 'Choose';

        return $res;
    }

    /**
     * @return array
     */
    private function getSortOptions()
    {
        return [
            'title' => 'Title',
            'date' => 'Date',
            'category' => 'Category',
            'priority' => 'Priority',
            'amount' => 'Amount'
        ];
    }
}
