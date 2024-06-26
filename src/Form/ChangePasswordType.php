<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('Firstname', TextType::class, [
                'disabled'=>true,
                'label'=> 'Mon prénom'
            ])
            ->add('email', EmailType::class, [
                'disabled'=>true,
                'label'=> 'Mon adresse email'
            ])
            ->add('old_password', PasswordType::class, [
                'label'=> 'Mon mot de passe actuel',
                'mapped'=>false,
                'attr'=> [
                    'placeholder'=> 'Merci de saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'mapped'=>false,
                'invalid_message'=>'Le mot de passe et la confirmation doivent être identique',
                'required'=>true,
                'first_options'=>['label'=>'Nouveau mot de passe',
                'attr'=>[
                    'placeholder'=> 'Veullez saisir votre nouveau mot de passe'
                ]],
                'second_options'=>['label'=>'Confirmation du nouveau mot de passe',
                'attr'=>[
                    'placeholder'=> 'Confirmez votre nouveau mot de passe'
                ]]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Mettre à jour'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
