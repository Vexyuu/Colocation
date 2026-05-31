<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Appartement;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $landlord = $options['landlord'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'attr' => ['placeholder' => 'Ex: Magnifique chambre de 15m² proche métro'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez saisir un titre'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description détaillée',
                'attr' => ['rows' => 5, 'placeholder' => 'Décrivez la colocation, l\'ambiance, les colocataires en place...'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez saisir une description'),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'Loyer mensuel charges comprises (€)',
                'attr' => ['placeholder' => 'Ex: 550'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez saisir le prix'),
                ],
            ])
            ->add('surface', NumberType::class, [
                'label' => 'Surface de la chambre (m²)',
                'attr' => ['placeholder' => 'Ex: 15'],
                'constraints' => [
                    new NotBlank(null, 'Veuillez saisir la surface'),
                ],
            ])
            ->add('appartement', EntityType::class, [
                'class' => Appartement::class,
                'choice_label' => 'nom',
                'label' => 'Appartement de la colocation',
                'query_builder' => function (EntityRepository $er) use ($landlord) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.landlord = :landlord')
                        ->setParameter('landlord', $landlord);
                },
                'constraints' => [
                    new NotBlank(null, 'Veuillez sélectionner un appartement'),
                ],
            ])
            ->add('photoFile', FileType::class, [
                'label' => 'Photo de la chambre/colocation (JPEG, PNG, WebP)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        mimeTypesMessage: 'Veuillez uploader une image valide (JPEG, PNG ou WebP)'
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
            'landlord' => null,
        ]);
    }
}
