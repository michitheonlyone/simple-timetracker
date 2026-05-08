<?php

namespace App\Form;

use App\Entity\JournalEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalEntryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['show_timestamp']) {
            $builder->add('timestamp', DateType::class, [
                'label' => false,
                'widget' => 'single_text',
            ]);
        }

        $builder
            ->add('startingTime', TimeType::class, ['label' => false, 'minutes' => [0,15,30,45]])
            ->add('endingTime', TimeType::class, ['label' => false, 'minutes' => [0,15,30,45]])
            ->add('customer', TextType::class, ['label' => 'Kunde/ Projekt'])
            ->add('note', TextType::class, ['label' => 'Details'])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalEntry::class,
            'show_timestamp' => false,
        ]);
    }
}
