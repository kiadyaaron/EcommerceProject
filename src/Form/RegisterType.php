<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Firstname', TypeTextType::class, [
                'label'=> 'Prénom',
                'attr'=> [
                    'placeholder'=> 'Veullez saisir votre prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> 'Email',
                'attr'=> [
                    'placeholder'=> 'Veullez saisir votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent être identique',
                'required'=>true,
                'first_options'=>['label'=>'Mot de passe',
                'attr'=>[
                    'placeholder'=> 'Veullez saisir votre mot de passe'
                ]],
                'second_options'=>['label'=>'Confirmation du mot de passe',
                'attr'=>[
                    'placeholder'=> 'Confirmez votre mot de passe'
                ]]
            ])
            ->add('Submit', SubmitType::class, [
                'label'=> "S'inscrire"
                
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
