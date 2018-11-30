<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class,$this->getConfiguration("Date d'arrivée", "Date à laquelle vous comptez arriver",
                ["widget" => 'single_text']
            ))
            ->add('endDate', DateType::class, $this->getConfiguration("Date de départ", "Date à laquelle vous comptez quitter les lieux",
                ["widget" => 'single_text']
            ))
            ->add('comment', TextareaType::class, $this->getConfiguration(false, "Si vous avez un commentaire n'hésitez pas à en faire part !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
