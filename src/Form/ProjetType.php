<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Projet;
use App\Form\AcompteType;
use App\Entity\ProjetEtat;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjetType extends AbstractType
{
    private $transformer;

    public function __construct( FrenchToDateTimeTransformer $transformer ) {
        $this->transformer =            $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $etat =                         $options[ 'etat' ];
        $client =                       $options[ 'client' ];

        $builder
            ->add('titre', TextType::class, [
                'label' =>              'Titre',
                'attr' =>               [
                    'placeholder' =>    'Titre du projet'
                ],
                'required' =>           false
            ])
            ->add('description', HiddenType::class, [
                'required' =>           false
            ])
            ->add('descriptionInterne', HiddenType::class, [
                'required' =>           false
            ])
            ->add('prix', NumberType::class, [
                'label' =>              'Tarif initial',
                'attr' =>               [
                    'placeholder' =>    'Tarif du projet'
                ],
                'required' =>           false
            ])
            ->add('dateFin', TextType::class, [
                'label' =>              'Fin du projet',
                'attr' =>               [
                    'placeholder' =>    'DD/MM/YYYY'
                ],
                'required' =>           false
            ])
            ->add('dateFacturation', TextType::class, [
                'label' =>              'Facturation',
                'attr' =>               [
                    'placeholder' =>    'DD/MM/YYYY'
                ],
                'required' =>           false
            ])
            ->add('datePaiement', TextType::class, [
                'label' =>              'Paiement',
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
                'data' =>               $client
            ])
            ->add('projetEtat', EntityType::class, [
                // looks for choices from this entity
                'class' =>              ProjetEtat::class,

                // uses the User.username property as the visible option string
                'choice_label' =>       'etat',

                // used to render a select box, check boxes or radios
                'multiple' =>           false,
                'expanded' =>           false,
                'query_builder' =>      function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.ordre', 'ASC');
                },
                'data' =>               $etat
            ])
            ->add('projetAcomptes', CollectionType::class, [
                'label' =>              false,
                'entry_type' =>         AcompteType::class,
                'allow_add' =>          true,
                'allow_delete' =>       true
            ])
        ;

        $builder->get( 'dateFin' )->addModelTransformer( $this->transformer );
        $builder->get( 'dateFacturation' )->addModelTransformer( $this->transformer );
        $builder->get( 'datePaiement' )->addModelTransformer( $this->transformer );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'etat' =>       null,
            'client' =>     null,
            'data_class' => Projet::class,
        ]);
    }
}
