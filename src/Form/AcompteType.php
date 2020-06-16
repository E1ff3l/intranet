<?php

namespace App\Form;

use App\Entity\ProjetAcompte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class AcompteType extends AbstractType
{
    private $transformer;

    public function __construct( FrenchToDateTimeTransformer $transformer ) {
        $this->transformer =            $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant', TextType::class, [
                'label' =>              'Montant',
                'attr' =>               [
                    'placeholder' =>    'Montant de l\'acompte'
                ],
                'required' =>           false
            ])
            ->add('datePaiement', TextType::class, [
                'label' =>              'Le',
                'attr' =>               [
                    'placeholder' =>    'DD/MM/YYYY'
                ],
                'required' =>           false
            ])
        ;

        $builder->get('datePaiement')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjetAcompte::class,
        ]);
    }
}
