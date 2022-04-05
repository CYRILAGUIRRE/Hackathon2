<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Players;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlayersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age'
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Taille'
            ])
            ->add('nationality', TextType::class, [
                'label' => 'Nationalité'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ajouter une photo svp'
                    ]),
                    new File([
                        'maxSize' => '3M',
                        'mimeTypesMessage' => 'Format invalide'
                    ])
                ]
            ])
            ->add('prefered_role', ChoiceType::class, [
                'choices' => [
                    'Gardien' => 'Gardien',
                    'Défenseur' => 'Défenseur',
                    'Milieu' => 'Milieu',
                    'Attaquant' => 'Attaquant'
                ]
            ])
            ->add('team', EntityType::class, [
                'label' => "Equipe",
                'class' => Club::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('club')
                        ->orderBy('club.name', 'ASC');
                },
                'choice_label' => 'Name'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Players::class,
        ]);
    }
}
