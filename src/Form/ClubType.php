<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'Nom'
            ])
            ->add('stade', TextType::class, [
                'label'=>'Stade'
            ])
            ->add('logo',FileType::class,[
                'label'=>'Logo',
                'mapped'=>false,
                'required'=>false,
                'constraints'=> [
                    new NotBlank([
                        'message'=> 'Renseigner un logo svp'
                    ]),
                    new File([
                        'maxSize' => '3M',
                        'mimeTypesMessage' => 'Format invalide'
                    ])
                ]
            ])

            ->add('pelouse', ChoiceType::class,[
                'choices'=>[
                    'Naturelle'=>'Naturelle',
                    'Synthétique'=>'Synthétique',
                ]
            ])
            ->add('places', IntegerType::class,[
                'label'=>'PLaces'
            ])
            ->add('couverture',ChoiceType::class,[
                'choices'=>[
                    'couvert'=> 'Couvert',
                    'semi couvert'=> 'Semi couvert',
                    'non couvert'=> 'Non couvert',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
