<?php

namespace CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\DateType;


class CustomersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("lastName")
            ->add("firstName")
//            ->add("dateOfBirth")

            ->add('dateOfBirth', DateType::class, array(
                'attr' => ['placeholder' => 'yyyy/mm/dd'],
                'widget' => 'single_text',
                'format' => 'yyyy/MM/dd',
            ))

//            ->add("dateOfBirth", DateType::class, array(
//                'years' => range(date('Y') - 67, date('Y')),
//                'widget' => 'choice',
//            ))
            ->add("uuid")
            ->add("save", SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\Customers',
            'allow_extra_fields' => true,
        ));
    }

    public function getBlockPrefix()
    {
        return 'api_bundle_customers_type';
    }
}
