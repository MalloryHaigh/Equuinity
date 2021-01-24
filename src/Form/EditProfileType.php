<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('displayName')
            ->add('profile', TextareaType::class, ['required'=>false])
            ->add('signature', TextareaType::class, ['required'=>false])
            ->add('avatar', TextType::class, ['label'=>'Avatar URL','required'=>false])
            ->add('status', TextType::class, ['label'=>'Status','required'=>false])
            //->add('password',PasswordType::class,['label'=>"New Password",'required'=>false])
            ->add('save', SubmitType::class, ['label' => 'Save Changes'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
