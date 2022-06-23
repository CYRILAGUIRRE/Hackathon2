<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Players;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PlayersTypeEdit extends AbstractType
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
                'label' => 'Age',
	            'constraints' => [
					new Range([
						'min' => '16',
						'max' => '40',
						'notInRangeMessage' => 'Choisissez un age entre 16 et 40 ans',
					])
	            ]
            ])
            ->add('height', IntegerType::class, [
                'label' => 'Taille',
	            'constraints' => [
					new Range([
						'min' => 140,
						'max' => 200,
						'notInRangeMessage' => 'Choisissez une taille entre 140 et 200 cm',
					])
	            ]
            ])
            ->add('nationality', CountryType::class, [
                'label' => 'Nationalité',
	            'preferred_choices' => array('FR'),
	            'choice_translation_locale' => 'FR',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
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
            ])
            ->add('isTitular', CheckboxType::class,  [
				'label' => 'Titulaire',
	            'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Players::class,
        ]);
    }
}
