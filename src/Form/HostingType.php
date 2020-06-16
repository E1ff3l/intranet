<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Hosting;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class HostingType extends AbstractType
{
    private $transformer;

    public function __construct( FrenchToDateTimeTransformer $transformer ) {
        $this->transformer =            $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $client =                       $options[ 'client' ];
        
        $builder
            ->add('site', TextType::class, [
                'label' =>              'Site web',
                'attr' =>               [
                    'placeholder' =>    'URL du site'
                ],
                'required' =>           false
            ])
            ->add('prix', NumberType::class, [
                'label' =>              'Tarif',
                'attr' =>               [
                    'placeholder' =>    'Tarif de l\'hébergement'
                ],
                'required' =>           false
            ])
            ->add('debut', TextType::class, [
                'label' =>              'Fin du projet',
                'attr' =>               [
                    'placeholder' =>    'DD/MM/YYYY'
                ],
                'required' =>           false
            ])
            ->add('fin', TextType::class, [
                'label' =>              'Fin du projet',
                'attr' =>               [
                    'placeholder' =>    'DD/MM/YYYY'
                ],
                'required' =>           false
            ])
            ->add('client', EntityType::class, [
                // looks for choices from this entity
                'class' =>              Client::class,

                // uses the User.username property as the visible option string
                'choice_label' =>       'societe',

                // used to render a select box, check boxes or radios
                'multiple' =>           false,
                'expanded' =>           false,
                'query_builder' =>      function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.online = 1')
                        ->orderBy('c.nom', 'ASC')
                        ->groupBy('c.id');
                },
                'required' =>           false,
                'data' =>               $client
            ])
        ;

        $builder->get( 'debut' )->addModelTransformer( $this->transformer );
        $builder->get( 'fin' )->addModelTransformer( $this->transformer );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'client' =>     null,
            'data_class' => Hosting::class,
        ]);
    }
}
