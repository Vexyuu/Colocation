<?php

namespace App\Form;

use App\Entity\Appartement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppartementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la colocation / appartement',
                'attr' => ['placeholder' => 'Ex: Coloc Éco-Marais'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez renseigner le nom de la colocation'),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse complète',
                'attr' => ['rows' => 3, 'placeholder' => 'Ex: 12 Rue des Vertus, 75003 Paris'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez saisir l\'adresse'),
                ],
            ])
            ->add('superficieTotale', NumberType::class, [
                'label' => 'Superficie Totale (m²)',
                'attr' => ['placeholder' => 'Ex: 85'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez spécifier la superficie totale'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appartement::class,
        ]);
    }
}
