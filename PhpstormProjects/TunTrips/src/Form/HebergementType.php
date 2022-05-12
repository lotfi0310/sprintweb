<?php

namespace App\Form;

use App\Entity\Hebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address')
            ->add('photo',FileType::class, array('data_class'  => null, 'required' => false,))
            ->add('type', ChoiceType::class, [
                'choices' => array(
                    'Apprtement' => 'Apprtement',
                    'Chateau' => 'Chateau',
                    'Résidence' => 'Résidence',
                    'Maison' => 'Maison',
                    'Hotel' => 'Hotel',
                ),
                'placeholder' => 'Select a Type',
                'required' => true,

            ])
            ->add('tarif', MoneyType::class)
            ->add('capacitechambre', ChoiceType::class, [
                'choices' => array(
                    '1 chambre' => '1',
                    '2 chambres' => '2',
                    '3 chambres' => '3',
                    '4 chambres' => '4',
                    '5 chambres' => '5',
                    '6 chambres' => '6',
                    '7 chambres' => '7',
                    '8 chambres' => '8',
                    '9+ chambres' => '9'
                ),
                'placeholder' => 'Nombres des chambres',
                'required' => true,

            ])
            ->add('disponibilite', ChoiceType::class, [
                'choices' => array(
                    'Disponiblie' => '1',
                    'Non Disponible' => '0',
                ),
                'placeholder' => 'Etat du hebergement',
                'required' => true,

            ])
            ->add('disponibilite_parking', ChoiceType::class, [
                'choices' => array(
                    'Avec Parking' => '1',
                    'Sans Parking' => '0',
                ),
                'placeholder' => 'Parking',
                'required' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
            'csrf_protection' => false
        ]);
    }
}
