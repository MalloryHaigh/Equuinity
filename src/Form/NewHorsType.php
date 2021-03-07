<?php

namespace App\Form;

use App\Entity\Breeds;
use App\Entity\Horses;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewHorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('breed', EntityType::class, [
                'class' => Breeds::class,
                'choice_value' => ChoiceList::value($this, 'name'),
                'choice_label' => 'Name'
            ])
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Stallion' => "Stallion",
                    'Mare' => "Mare",
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Horse']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Horses::class,
        ]);
    }
}
