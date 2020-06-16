<?php

namespace App\Form;

use App\Entity\Pays;
use App\Entity\Client;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pays =                         $options[ 'pays' ];

        $builder
            ->add('societe', TextType::class, [
                'label' =>              'Société',
                'attr' =>               [
                    'placeholder' =>    'Nom de la société'
                ],
                'required' =>           false
            ])
            ->add('url', TextType::class, [
                'label' =>              'URL',
                'attr' =>               [
                    'placeholder' =>    'Lien vers le site de la société'
                ],
                'required' =>           false
            ])
            ->add('nom', TextType::class, [
                'label' =>              'Nom',
                'attr' =>               [
                    'placeholder' =>    'Nom du responsable'
                ],
                'required' =>           false
            ])
            ->add('prenom', TextType::class, [
                'label' =>              'Prénom',
                'attr' =>               [
                    'placeholder' =>    'Prénom du responsable'
                ],
                'required' =>           false
            ])
            ->add('mail', TextType::class, [
                'label' =>              'E-mail',
                'attr' =>               [
                    'placeholder' =>    'E-mail du responsable'
                ],
                'required' =>           false
            ])
            ->add('tel', TextType::class, [
                'label' =>              'Fixe',
                'attr' =>               [
                    'placeholder' =>    'Téléphone fixe du responsable'
                ],
                'required' =>           false
            ])
            ->add('mobile', TextType::class, [
                'label' =>              'Portable',
                'attr' =>               [
                    'placeholder' =>    'Portable du responsable'
                ],
                'required' =>           false
            ])
            ->add('fax', TextType::class, [
                'label' =>              'Fax',
                'attr' =>               [
                    'placeholder' =>    'Fax du responsable'
                ],
                'required' =>           false
            ])
            ->add('adresse', TextType::class, [
                'label' =>              'Adresse',
                'attr' =>               [
                    'placeholder' =>    'Adresse postale'
                ],
                'required' =>           false
            ])
            ->add('adresse_suite', TextType::class, [
                'label' =>              'Adresse (suite)',
                'attr' =>               [
                    'placeholder' =>    'Adresse postale (suite)'
                ],
                'required' =>           false
            ])
            ->add('cp', TextType::class, [
                'label' =>              'Code postal',
                'required' =>           false
            ])
            ->add('ville', TextType::class, [
                'label' =>              'Ville',
                'attr' =>               [
                    'placeholder' =>    'Ville de la société'
                ],
                'required' =>           false
            ])
            ->add('online', CheckboxType::class, [
                'label' =>              'Actif',
                'required' =>           false
            ])
            ->add('pays', EntityType::class, [
                // looks for choices from this entity
                'class' =>              Pays::class,

                // uses the User.username property as the visible option string
                'choice_label' =>       'nom',

                // used to render a select box, check boxes or radios
                'multiple' =>           false,
                'expanded' =>           false,
                'query_builder' =>      function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nom', 'ASC');
                },
                'data' =>               $pays
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'pays' =>       null,
            'data_class' => Client::class,
        ]);
    }
}
