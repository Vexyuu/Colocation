<?php

namespace App\Form;

use App\Entity\Appartement;
use App\Entity\Facture;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $landlord = $options['landlord'];

        $builder
            ->add('typeOfCharge', ChoiceType::class, [
                'label' => 'Type de Charge',
                'choices' => [
                    'Électricité ⚡' => 'Électricité',
                    'Eau Chaude/Froide 💧' => 'Eau',
                    'Internet / Fibre 🌐' => 'Internet',
                    'Chauffage 🔥' => 'Chauffage',
                    'Ordures Ménagères 🗑️' => 'Ordures Ménagères',
                    'Autres Charges ⚙️' => 'Autres',
                ],
                'constraints' => [
                    new NotBlank(null, 'Veuillez sélectionner le type de charge'),
                ],
            ])
            ->add('totalAmount', NumberType::class, [
                'label' => 'Montant Total Global (€)',
                'attr' => ['placeholder' => 'Ex: 150.00'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez spécifier le montant total'),
                ],
            ])
            ->add('billDate', DateType::class, [
                'label' => 'Date de la facture',
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'constraints' => [
                    new NotBlank(null, 'Veuillez choisir la date'),
                ],
            ])
            ->add('appartment', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => 'nom',
                'label' => 'Appartement concerné',
                'query_builder' => function (EntityRepository $er) use ($landlord) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.landlord = :landlord')
                        ->setParameter('landlord', $landlord);
                },
                'constraints' => [
                    new NotBlank(null, 'Veuillez choisir un appartement'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
            'landlord' => null,
        ]);
    }
}
