<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('email',EmailType::class)
            ->add('passwd',PasswordType::class)
            ->add('confirm_password',PasswordType::class)
            ->add('country',CountryType::class, [
                ])

            ->add('role',ChoiceType::class,array(
                'choices'=>array('ROLE_USER'=>'ROLE_USER',
                    'ROLE_FOURNISSEUR'=>'ROLE_FOURNISSEUR',


                    )

            ))
            ->add('photo',FileType::class,
                array('label' => 'Photo (png, jpeg)'))
            ->add('numTel',NumberType::class)
            ->add('save',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
