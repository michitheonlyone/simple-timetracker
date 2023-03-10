<?php

namespace App\Form;

use App\Entity\Component;
use App\Entity\JournalEntry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalEntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startingTime', TimeType::class, ['label' => false, 'minutes' => [0,15,30,45]])
            ->add('endingTime', TimeType::class, ['label' => false, 'minutes' => [0,15,30,45]])
            ->add('component', EntityType::class, ['class' => Component::class, 'choice_label' => 'name'])
            ->add('note', TextType::class, ['label' => 'Was'])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalEntry::class,
        ]);
    }
}
